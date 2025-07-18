<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\IncomingItem;
use App\Models\OutgoingItem;
use App\Models\Producer;
use App\Models\User;
use App\Models\Category; // Added this import
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mengambil data barang masuk dari database
        $incomingItems = IncomingItem::orderBy('tanggal_masuk_barang', 'desc')->get();

        // Mengambil data barang keluar dari database
        $outgoingItems = OutgoingItem::orderBy('tanggal_keluar_barang', 'desc')->get();

        // --- Data untuk Status Stok (Banyak, Sedikit, Habis) ---
        // Hitungan ini relevan untuk dashboard manajer juga
        $plentyStockCount = 0;
        $lowStockCount = 0;
        $outOfStockCount = 0;

        foreach ($incomingItems as $item) {
            if ($item->jumlah_barang > 50) { // Asumsi: Banyak jika > 50 unit
                $plentyStockCount++;
            } elseif ($item->jumlah_barang > 0 && $item->jumlah_barang <= 50) { // Asumsi: Sedikit jika 1-50 unit
                $lowStockCount++;
            } else { // Asumsi: Habis jika 0 unit
                $outOfStockCount++;
            }
        }
        // --- Akhir Data untuk Status Stok ---

        // Memeriksa role user dan memuat tampilan yang sesuai
        if ($user->role === 'manager') {
            return view('home', [
                'dashboardView' => 'dashboard.manager_dashboard',
                'incomingItems' => $incomingItems, // Tetap diperlukan untuk ringkasan total
                'outgoingItems' => $outgoingItems, // Tetap diperlukan untuk ringkasan total
                'plentyStockCount' => $plentyStockCount,
                'lowStockCount' => $lowStockCount,
                'outOfStockCount' => $outOfStockCount,
            ]);
        } elseif ($user->role === 'admin') { // Diubah dari 'staff_admin' menjadi 'admin'
            return view('home', [
                'dashboardView' => 'dashboard.staff_admin_dashboard', // Tetap menggunakan tampilan staff_admin_dashboard
                'incomingItems' => $incomingItems,
                'outgoingItems' => $outgoingItems,
            ]);
        }
        
        // Jika role tidak dikenali, arahkan ke dashboard manager sebagai fallback
        // Atau Anda bisa mengarahkan ke halaman login dengan pesan error
        return view('home', [
            'dashboardView' => 'dashboard.manager_dashboard', // Fallback ke dashboard manager
            'incomingItems' => $incomingItems,
            'outgoingItems' => $outgoingItems,
            'plentyStockCount' => $plentyStockCount,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
        ])->with('error', 'Role pengguna tidak dikenali atau tidak memiliki dashboard khusus. Anda diarahkan ke dashboard manager.');
    }

    /**
     * Show the stock report page.
     */
    public function showStockReport()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mengambil data barang masuk dari database
        $incomingItems = IncomingItem::orderBy('tanggal_masuk_barang', 'desc')->get();

        // Mengambil data barang keluar dari database
        $outgoingItems = OutgoingItem::orderBy('tanggal_keluar_barang', 'desc')->get();

        // Get producers data
        $producers = Producer::orderBy('nama_produsen_supplier')->get();

        // Get categories data
        $categories = Category::orderBy('nama_kategori')->get();

        // --- Data untuk Grafik Tren Penjualan/Pembelian ---
        // Anda dapat menyesuaikan rentang tanggal ini sesuai kebutuhan
        $startDate = Carbon::now()->subDays(6)->startOfDay(); // 7 hari terakhir
        $endDate = Carbon::now()->endOfDay();

        $daysOfWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $daysOfWeek[] = $startDate->copy()->addDays($i)->isoFormat('dddd'); // Nama hari dalam bahasa Indonesia
        }

        $purchaseData = array_fill(0, 7, 0);
        $salesData = array_fill(0, 7, 0);

        $incomingItemsForChart = IncomingItem::whereBetween('tanggal_masuk_barang', [$startDate, $endDate])
                                            ->get();
        foreach ($incomingItemsForChart as $item) {
            $dayOfWeek = $item->tanggal_masuk_barang->dayOfWeekIso; // 1 (Senin) sampai 7 (Minggu)
            $purchaseData[$dayOfWeek - 1] += $item->jumlah_barang;
        }

        $outgoingItemsForChart = OutgoingItem::whereBetween('tanggal_keluar_barang', [$startDate, $endDate])
                                            ->get();
        foreach ($outgoingItemsForChart as $item) {
            $dayOfWeek = $item->tanggal_keluar_barang->dayOfWeekIso;
            $salesData[$dayOfWeek - 1] += $item->jumlah_barang;
        }
        // --- Akhir Data untuk Grafik ---

        // --- Data untuk Status Stok (Banyak, Sedikit, Habis) ---
        // Data ini tidak lagi diperlukan di sini karena sudah dipindahkan ke index()
        $plentyStockCount = 0;
        $lowStockCount = 0;
        $outOfStockCount = 0;

        foreach ($incomingItems as $item) {
            if ($item->jumlah_barang > 50) { // Asumsi: Banyak jika > 50 unit
                $plentyStockCount++;
            } elseif ($item->jumlah_barang > 0 && $item->jumlah_barang <= 50) { // Asumsi: Sedikit jika 1-50 unit
                $lowStockCount++;
            } else { // Asumsi: Habis jika 0 unit
                $outOfStockCount++;
            }
        }
        // --- Akhir Data untuk Status Stok ---

        return view('dashboard.report_stock', [
            'incomingItems' => $incomingItems,
            'outgoingItems' => $outgoingItems,
            'producers' => $producers,
            'categories' => $categories,
            'chartLabels' => $daysOfWeek,
            'purchaseTrendData' => $purchaseData,
            'salesTrendData' => $salesData,
            'chartPeriod' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
            'plentyStockCount' => $plentyStockCount,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
        ]);
    }

    /**
     * Show the order items page (Daftar Mitra Produsen).
     */
    public function showOrderItems()
    {
        // Mengambil data produsen dari database
        $producers = Producer::orderBy('nama_produsen_supplier')->get();

        return view('dashboard.order_items', [
            'producers' => $producers,
        ]);
    }

    /**
     * Show the employee accounts management page.
     */
    public function showEmployeeAccounts()
    {
        // Mengambil semua user dengan role 'admin' dari database
        $employeeAccounts = User::where('role', 'admin')->get();

        // Anda juga bisa meneruskan daftar peran yang tersedia jika ingin dinamis
        $roles = ['admin', 'manager']; // Contoh peran yang tersedia (hanya admin dan manager)

        return view('dashboard.employee_accounts', [
            'employeeAccounts' => $employeeAccounts,
            'roles' => $roles, // Teruskan peran ke view
        ]);
    }

    /**
     * Store a new employee account.
     */
    public function storeEmployeeAccount(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencari password_confirmation
            'role' => 'required|in:admin,manager', // Hanya izinkan role 'admin' atau 'manager'
            'phone_number' => 'nullable|string|max:20',
        ], [
            'full_name.required' => 'Nama Lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata Sandi wajib diisi.',
            'password.min' => 'Kata Sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi Kata Sandi tidak cocok.',
            'role.required' => 'Peran Pegawai wajib dipilih.',
            'role.in' => 'Peran Pegawai yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buat user baru
        User::create([
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Akan selalu 'admin' atau 'manager' karena validasi
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('employee.accounts')->with('success', 'Akun pegawai berhasil ditambahkan!');
    }
}
