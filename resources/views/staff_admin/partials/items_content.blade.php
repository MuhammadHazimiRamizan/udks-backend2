{{-- items_content.blade.php - Partial content untuk pengelolaan barang staff admin --}}

{{-- Menampilkan pesan sesi (tetap dipertahankan untuk pesan dari controller) --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <div>{{ session('warning') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle"></i>
        <div>{{ session('info')}}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Card Data Barang (Tabbed Interface) --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <ul class="nav nav-tabs card-header-tabs" id="itemTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="incoming-items-tab" data-bs-toggle="tab" data-bs-target="#incoming-items" type="button" role="tab">
                    <i class="fas fa-arrow-down"></i> Barang Masuk
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="outgoing-items-tab" data-bs-toggle="tab" data-bs-target="#outgoing-items" type="button" role="tab">
                    <i class="fas fa-arrow-up"></i> Barang Keluar
                </button>
            </li>
        </ul>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary btn-sm" onclick="window.addNewIncomingItem()">
                <i class="fas fa-plus"></i> Tambah Barang Masuk Baru
            </button>
            <button class="btn btn-primary btn-sm" onclick="window.refreshData()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="tab-content" id="itemTabsContent">
            {{-- Tab Barang Masuk --}}
            <div class="tab-pane fade show active" id="incoming-items" role="tabpanel">
                {{-- Form Verifikasi Barang Masuk --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> Verifikasi Barang Masuk</h5>
                    </div>
                    <div class="card-body">
                        <form id="verificationForm">
                            @csrf {{-- Tambahkan CSRF token untuk form ini --}}
                            <input type="hidden" id="verify_item_id" name="item_id"> {{-- Input tersembunyi untuk menyimpan ID barang --}}
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group mb-3">
                                <label for="verify_producer_id" class="form-label">Nama Produsen</label>
                                <select class="form-select" id="verify_producer_id" name="producer_id" required>
                                    <option value="">Pilih Produsen</option>
                                    @foreach($producers as $producer)
                                        <option value="{{ $producer->id }}">{{ $producer->nama_produsen_supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                                    <div class="mb-3">
                                        <label for="verify_nama_barang" class="form-label">Nama Barang</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="verify_nama_barang" name="nama_barang" placeholder="Pilih Barang untuk Verifikasi" required readonly>
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#selectItemModal">Pilih Barang</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="verify_jumlah_barang" class="form-label">Jumlah Barang</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="verify_jumlah_barang" name="jumlah_barang" min="1" step="1" placeholder="0" required readonly>
                                            <select class="form-select" id="verify_satuan_barang" name="satuan_barang" required disabled>
                                                <option value="">Pilih Satuan Barang</option>
                                                <option value="Unit">Unit</option>
                                                <option value="Pcs">Pcs</option>
                                                <option value="Dus">Dus</option>
                                                <option value="Kg">Kg</option>
                                                <option value="Liter">Liter</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kondisi_fisik" class="form-label">Kondisi Fisik *</label>
                                        <select class="form-select" id="kondisi_fisik" name="kondisi_fisik" required>
                                            <option value="">Pilih Kondisi Fisik</option>
                                            <option value="Baik">Baik</option>
                                            <option value="Rusak Ringan">Rusak Ringan</option>
                                            <option value="Tidak Sesuai">Tidak Sesuai</option>
                                            <option value="Kadaluarsa">Kadaluarsa</option>
                                        </select>
                                        <div class="form-text">Pilih kondisi fisik barang setelah diperiksa.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="catatan_verifikasi" class="form-label">Catatan Verifikasi</label>
                                        <textarea class="form-control" id="catatan_verifikasi" name="catatan_verifikasi" rows="3" 
                                            placeholder="Tambahkan catatan verifikasi jika diperlukan"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-outline-info w-100 mb-2" onclick="window.ajukanPergantianBarang()">
                                            Ajukan Pergantian Barang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Lanjut >></button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Filter dan Pencarian --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Pilih Kategori Barang</option>
                            @if(isset($incomingItems))
                                @foreach($incomingItems->pluck('kategori_barang')->unique()->filter() as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="dateIncomingFilter" placeholder="Filter Tanggal">
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Mencari barang..." id="searchIncomingInput">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi Bulk --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex gap-2 align-items-center">
                            <input type="checkbox" id="selectAllIncoming" class="form-check-input">
                            <label for="selectAllIncoming" class="form-check-label me-3">Pilih Semua</label>
                            <button class="btn btn-sm btn-warning" onclick="window.bulkEditLocation()" disabled id="bulkEditBtn">
                                <i class="fas fa-edit"></i> Edit Lokasi Terpilih
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="window.bulkDelete()" disabled id="bulkDeleteBtn">
                                <i class="fas fa-trash"></i> Hapus Terpilih
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tabel Barang Masuk --}}
                <div class="table-responsive">
                    <table class="table table-hover" id="incomingTable">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input" id="selectAllIncomingHeader">
                                </th>
                                <th>No.</th>
                                <th>Foto Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Tanggal Masuk</th>
                                <th>Lokasi Rak</th>
                                <th>Nama Produsen</th>
                                <th>Metode Bayar</th>
                                <th>Pembayaran Transaksi</th>
                                <th>Nota Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($incomingItems) && $incomingItems->count() > 0)
                                @foreach ($incomingItems as $item)
                                    <tr data-id="{{ $item->id }}"
                                        data-category="{{ $item->kategori_barang }}" 
                                        data-date="{{ $item->tanggal_masuk_barang->format('Y-m-d') }}">
                                        <td>
                                            <input type="checkbox" class="form-check-input item-checkbox" 
                                                   value="{{ $item->id }}" onchange="window.updateBulkActions()">
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($item->foto_barang)
                                                <img src="{{ asset('storage/' . $item->foto_barang) }}" alt="Foto Barang" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            @else
                                                <img src="https://placehold.co/50x50/e0e0e0/ffffff?text=No+Image" alt="No Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="item-icon me-2">
                                                    <i class="fas fa-box text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $item->nama_barang }}</strong>
                                                    <small class="text-muted d-block">ID: #{{ $item->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->kategori_barang }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $item->jumlah_barang }}</span> unit
                                        </td>
                                        <td>{{ $item->tanggal_masuk_barang->format('d M Y') }}</td>
                                        <td>
                                            @if($item->lokasi_rak_barang)
                                                <span class="badge bg-info">{{ $item->lokasi_rak_barang }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->producer)
                                                {{ $item->producer->nama_produsen_supplier }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->metode_bayar ?? '-' }}</td>
                                        <td>
                                            @if($item->pembayaran_transaksi)
                                                <a href="{{ asset('storage/' . $item->pembayaran_transaksi) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file"></i> Lihat
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->nota_transaksi)
                                                <a href="{{ asset('storage/' . $item->nota_transaksi) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-receipt"></i> Lihat
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-info" onclick="window.viewItemDetails({{ $item->id }})" 
                                                        title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="window.editIncomingItem({{ $item->id }})" 
                                                        title="Edit Item">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success" onclick="window.moveToOutgoing({{ $item->id }})" 
                                                        title="Pindah ke Barang Keluar">
                                                    <i class="fas fa-arrow-right"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="window.deleteIncomingItem({{ $item->id }})" 
                                                        title="Hapus Item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12" class="text-center py-4"> {{-- Updated colspan --}}
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada data barang masuk.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Menampilkan {{ isset($incomingItems) ? $incomingItems->count() : 0 }} item dari total {{ isset($incomingItems) ? $incomingItems->count() : 0 }} item
                    </small>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" disabled>Sebelumnya</button>
                        <button class="btn btn-sm btn-outline-secondary" disabled>Berikutnya</button>
                    </div>
                </div>

                {{-- Summary Stats --}}
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-center border-0 bg-light">
                            <div class="card-body">
                                <i class="fas fa-box-open fa-2x text-primary mb-2"></i>
                                <h4 class="text-primary">{{ isset($incomingItems) ? $incomingItems->count() : 0 }}</h4>
                                <small class="text-muted">Total Item</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-0 bg-light">
                            <div class="card-body">
                                <i class="fas fa-cubes fa-2x text-success mb-2"></i>
                                <h4 class="text-success">{{ isset($incomingItems) ? $incomingItems->sum('jumlah_barang') : 0 }}</h4>
                                <small class="text-muted">Total Unit</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-0 bg-light">
                            <div class="card-body">
                                <i class="fas fa-warehouse fa-2x text-info mb-2"></i>
                                <h4 class="text-info">{{ isset($incomingItems) ? $incomingItems->whereNotNull('lokasi_rak_barang')->count() : 0 }}</h4>
                                <small class="text-muted">Sudah Ditempatkan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-0 bg-light">
                            <div class="card-body">
                                <i class="fas fa-question-circle fa-2x text-warning mb-2"></i>
                                <h4 class="text-warning">{{ isset($incomingItems) ? $incomingItems->whereNull('lokasi_rak_barang')->count() : 0 }}</h4>
                                <small class="text-muted">Belum Ditempatkan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Barang Keluar --}}
            <div class="tab-pane fade" id="outgoing-items" role="tabpanel">
                {{-- Filter dan Pencarian untuk Barang Keluar --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="outgoingCategoryFilter">
                            <option value="">Pilih Kategori Barang</option>
                            @if(isset($outgoingItems))
                                @foreach($outgoingItems->pluck('kategori_barang')->unique()->filter() as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="destinationFilter">
                            <option value="">Pilih Tujuan</option>
                            @if(isset($outgoingItems))
                                @foreach($outgoingItems->pluck('tujuan_distribusi')->unique()->filter() as $destination)
                                    <option value="{{ $destination }}">{{ $destination }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="dateOutgoingFilter" placeholder="Filter Tanggal">
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Mencari barang..." id="searchOutgoingInput">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tabel Barang Keluar --}}
                <div class="table-responsive">
                    <table class="table table-hover" id="outgoingTable">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <!-- <th>Foto Barang</th> -->
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Tanggal Keluar</th>
                                <th>Lokasi Rak Asal</th>
                                <th>Nama Produsen</th>
                                <th>Pembayaran Transaksi</th>
                                <th>Nota Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($outgoingItems) && $outgoingItems->count() > 0)
                                @foreach ($outgoingItems as $item)
                                    <tr data-id="{{ $item->id }}"
                                        data-category="{{ $item->kategori_barang }}" 
                                        data-date="{{ $item->tanggal_keluar_barang->format('Y-m-d') }}"
                                        data-destination="{{ $item->tujuan_distribusi }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($item->foto_barang)
                                                <img src="{{ asset('storage/' . $item->foto_barang) }}" alt="Foto Barang" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            @else
                                                <img src="https://placehold.co/50x50/e0e0e0/ffffff?text=No+Image" alt="No Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="item-icon me-2">
                                                    <i class="fas fa-shipping-fast text-danger"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $item->nama_barang }}</strong>
                                                    <small class="text-muted d-block">ID: #{{ $item->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->kategori_barang }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-danger">{{ $item->jumlah_barang }}</span> unit
                                        </td>
                                        <td>{{ $item->tanggal_keluar_barang->format('d M Y') }}</td>
                                        <td>
                                            @if($item->lokasi_rak_barang)
                                                <span class="badge bg-info">{{ $item->lokasi_rak_barang }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->producer)
                                                {{ $item->producer->nama_produsen_supplier }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->pembayaran_transaksi)
                                                <a href="{{ asset('storage/' . $item->pembayaran_transaksi) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file"></i> Lihat
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->nota_transaksi)
                                                <a href="{{ asset('storage/' . $item->nota_transaksi) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-receipt"></i> Lihat
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-info" onclick="window.viewItemDetails({{ $item->id }}, 'outgoing')" 
                                                        title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="window.editOutgoingItem({{ $item->id }})" 
                                                        title="Edit Item Keluar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" onclick="window.printDeliveryNote({{ $item->id }})" 
                                                        title="Cetak Surat Jalan">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="window.deleteOutgoingItem({{ $item->id }})" 
                                                        title="Hapus Item Keluar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center py-4"> {{-- Updated colspan --}}
                                        <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada data barang keluar.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Summary Stats untuk Barang Keluar --}}
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center border-0 bg-light">
                            <div class="card-body">
                                <i class="fas fa-shipping-fast fa-2x text-primary mb-2"></i>
                                <h4 class="text-primary">{{ isset($outgoingItems) ? $outgoingItems->count() : 0 }}</h4>
                                <small class="text-muted">Total Pengiriman</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center border-0 bg-light">
                            <div class="card-body">
                                <i class="fas fa-box-open fa-2x text-danger mb-2"></i>
                                <h4 class="text-danger">{{ isset($outgoingItems) ? $outgoingItems->sum('jumlah_barang') : 0 }}</h4>
                                <small class="text-muted">Total Unit Keluar</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center border-0 bg-light">
                            <div class="card-body">
                                <i class="fas fa-map-marker-alt fa-2x text-info mb-2"></i>
                                <h4 class="text-info">{{ isset($outgoingItems) ? $outgoingItems->pluck('tujuan_distribusi')->unique()->count() : 0 }}</h4>
                                <small class="text-muted">Tujuan Unik</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="selectItemModal" tabindex="-1" aria-labelledby="selectItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectItemModalLabel">Pilih Barang untuk Verifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Produsen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pendingItemsTableBody">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk Detail Item --}}
<div class="modal fade" id="itemDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="itemDetailContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.printItemDetails()">
                    <i class="fas fa-print"></i> Cetak Detail
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk Tambah/Edit Barang (Generic CRUD Modal) --}}
<div class="modal fade" id="itemCrudModal" tabindex="-1" aria-labelledby="itemCrudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemCrudModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="itemCrudForm">
                    @csrf
                    {{-- Hidden field for method spoofing for PUT requests --}}
                    <input type="hidden" name="_method" value="POST" id="formMethod">
                    {{-- Hidden field for item ID in case of editing --}}
                    <input type="hidden" name="id" id="crudItemId">

                    <div id="itemCrudFormContent">
                        {{-- Form fields will be injected here by JavaScript --}}
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="itemCrudSubmitBtn">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk Import CSV --}}
<div class="modal fade" id="importCSVModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data dari CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="csvImportForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="csvFile" class="form-label">File CSV</label>
                        <input type="file" class="form-control" id="csvFile" name="csv_file" accept=".csv" required>
                        <small class="form-text text-muted">
                            Format: nama_barang, kategori_barang, jumlah_barang, tanggal_masuk_barang, lokasi_rak_barang, nama_produsen, metode_bayar, pembayaran_transaksi, nota_transaksi, foto_barang
                        </small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hasHeader" checked>
                            <label class="form-check-label" for="hasHeader">
                                File memiliki header
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Template CSV:</strong>
                        <a href="#" onclick="window.downloadCSVTemplate()" class="alert-link">Download template CSV</a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="window.processCSVImport()">
                    <i class="fas fa-upload"></i> Import Data
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Kustom --}}
<div class="modal fade" id="customConfirmModal" tabindex="-1" aria-labelledby="customConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="customConfirmModalLabel"><i class="fas fa-question-circle"></i> Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="customConfirmMessage">
                <!-- Message will be injected here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="customConfirmActionBtn">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>


<style>
/* Additional CSS for enhanced functionality */
.warehouse-selector-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    max-height: 400px;
    overflow-y: auto;
}

.warehouse-rack-selector {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    background: #f8f9fa;
}

.rack-selector-header {
    text-align: center;
    margin-bottom: 0.5rem;
    padding: 0.25rem;
    background: #6c757d;
    color: white;
    border-radius: 4px;
    font-weight: bold;
}

.rack-selector-grid {
    display: grid;
    gap: 2px;
}

.rack-selector-row {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 2px;
}

.rack-selector-cell {
    aspect-ratio: 1;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    min-height: 30px;
    background: #ffffff;
}

.rack-selector-cell.occupied {
    background: #dc3545;
    color: white;
    cursor: not-allowed;
}

.rack-selector-cell.selected {
    background: #28a745;
    color: white;
    transform: scale(1.1);
    z-index: 10;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);
}

.rack-selector-cell:hover:not(.occupied) {
    background: #e9ecef;
    transform: scale(1.05);
}

.item-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(13, 110, 253, 0.1);
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.table-responsive {
    border-radius: 8px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.5em 0.75em;
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .warehouse-selector-grid {
        grid-template-columns: 1fr;
    }
    
    .rack-selector-row {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
    }
}
</style>
@push('scripts')
<script>
// Functions that will be called from HTML (onclick) must be in the global scope
// or accessed via window.functionName. For simplicity, we will make them global.

/**
 * Initializes page functionality after the DOM is loaded.
 */
window.initializePage = function() {
    // Setup filter functionality
    window.setupFilters();
    
    // Setup bulk actions
    window.setupBulkActions();
    
    // Setup outgoing item selector
    window.setupOutgoingItemSelector();
}

/**
 * Sets up filter and search functionality for item tables.
 */
window.setupFilters = function() {
    // Incoming items filters
    const searchIncomingInput = document.getElementById('searchIncomingInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const dateIncomingFilter = document.getElementById('dateIncomingFilter');

    function filterIncomingTable() {
        const searchText = searchIncomingInput ? searchIncomingInput.value.toLowerCase() : '';
        const selectedCategory = categoryFilter ? categoryFilter.value : '';
        const selectedDate = dateIncomingFilter ? dateIncomingFilter.value : '';
        
        const rows = document.querySelectorAll('#incomingTable tbody tr');

        rows.forEach(row => {
            if (row.cells.length < 2) return; // Skip empty rows
            
            const nameCell = row.cells[3]; // Nama Barang column (index 3 after No. and Foto Barang)
            const category = row.dataset.category || '';
            const date = row.dataset.date || '';

            if (nameCell) {
                const nameText = nameCell.textContent.toLowerCase();
                const matchesSearch = nameText.includes(searchText);
                const matchesCategory = !selectedCategory || category === selectedCategory;
                const matchesDate = !selectedDate || date === selectedDate;

                if (matchesSearch && matchesCategory && matchesDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }

    if (searchIncomingInput) searchIncomingInput.addEventListener('keyup', filterIncomingTable);
    if (categoryFilter) categoryFilter.addEventListener('change', filterIncomingTable);
    if (dateIncomingFilter) dateIncomingFilter.addEventListener('change', filterIncomingTable);

    // Outgoing items filters
    const searchOutgoingInput = document.getElementById('searchOutgoingInput');
    const outgoingCategoryFilter = document.getElementById('outgoingCategoryFilter');
    const destinationFilter = document.getElementById('destinationFilter');
    const dateOutgoingFilter = document.getElementById('dateOutgoingFilter');

    function filterOutgoingTable() {
        const searchText = searchOutgoingInput ? searchOutgoingInput.value.toLowerCase() : '';
        const selectedCategory = outgoingCategoryFilter ? outgoingCategoryFilter.value : '';
        const selectedDestination = destinationFilter ? destinationFilter.value : '';
        const selectedDate = dateOutgoingFilter ? dateOutgoingFilter.value : '';
        
        const rows = document.querySelectorAll('#outgoingTable tbody tr');

        rows.forEach(row => {
            if (row.cells.length < 2) return; // Skip empty rows
            
            const nameCell = row.cells[2]; // Nama Barang column (index 2 after No. and Foto Barang)
            const category = row.dataset.category || '';
            const destination = row.dataset.destination || '';
            const date = row.dataset.date || '';

            if (nameCell) {
                const nameText = nameCell.textContent.toLowerCase();
                const matchesSearch = nameText.includes(searchText);
                const matchesCategory = !selectedCategory || category === selectedCategory;
                const matchesDestination = !selectedDestination || destination === selectedDestination;
                const matchesDate = !selectedDate || date === selectedDate;

                if (matchesSearch && matchesCategory && matchesDestination && matchesDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }

    if (searchOutgoingInput) searchOutgoingInput.addEventListener('keyup', filterOutgoingTable);
    if (outgoingCategoryFilter) outgoingCategoryFilter.addEventListener('change', filterOutgoingTable);
    if (destinationFilter) destinationFilter.addEventListener('change', filterOutgoingTable);
    if (dateOutgoingFilter) dateOutgoingFilter.addEventListener('change', filterOutgoingTable);

    // Event listener for tab change to trigger filter on the active tab
    const itemTabs = document.getElementById('itemTabs');
    if (itemTabs) {
        itemTabs.addEventListener('shown.bs.tab', function (event) {
            const activeTabId = event.target.id;
            if (activeTabId === 'incoming-items-tab') {
                filterIncomingTable();
            } else if (activeTabId === 'outgoing-items-tab') {
                filterOutgoingTable();
            }
        });
    }
}

/**
 * Sets up bulk action functionality (select all, edit/delete selected).
 */
window.setupBulkActions = function() {
    const selectAllIncoming = document.getElementById('selectAllIncoming');
    const selectAllIncomingHeader = document.getElementById('selectAllIncomingHeader');
    
    if (selectAllIncoming) {
        selectAllIncoming.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            window.updateBulkActions();
        });
    }
    
    if (selectAllIncomingHeader) {
        selectAllIncomingHeader.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            window.updateBulkActions();
        });
    }
}

/**
 * Sets up outgoing item selector functionality (auto-fills category and location).
 */
window.setupOutgoingItemSelector = function() {
    // This function is now less critical as the forms are in the modal,
    // but the logic for updating fields based on selected item is still relevant
    // for the modal's dynamic form content.
}

/**
 * Updates the status of bulk action buttons based on selected items.
 */
window.updateBulkActions = function() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    const bulkEditBtn = document.getElementById('bulkEditBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    const hasSelection = checkedBoxes.length > 0;
    
    if (bulkEditBtn) bulkEditBtn.disabled = !hasSelection;
    if (bulkDeleteBtn) bulkDeleteBtn.disabled = !hasSelection;
}

/**
 * Helper function to determine if a file path is a PDF.
 * @param {string} filePath - The path to the file.
 * @returns {boolean} True if the file is a PDF, false otherwise.
 */
window.isPdf = function(filePath) {
    return filePath && filePath.toLowerCase().endsWith('.pdf');
};

/**
 * Displays item detail modal (incoming or outgoing).
 * @param {number} itemId - Item ID.
 * @param {string} itemType - 'incoming' or 'outgoing'.
 */
window.viewItemDetails = async function(itemId, itemType = 'incoming') {
    let itemsData = [];
    if (itemType === 'incoming') {
        itemsData = @json($incomingItems ?? []);
    } else {
        itemsData = @json($outgoingItems ?? []);
    }

    const item = itemsData.find(i => i.id == itemId);

    if (item) {
        const modalContent = document.getElementById('itemDetailContent');
        let htmlContent = '';

        const fotoBarangHtml = item.foto_barang
            ? `<img src="{{ asset('storage') }}/${item.foto_barang}" alt="Foto Barang" class="img-fluid rounded mb-3" style="max-width: 200px; height: auto;">`
            : `<img src="https://placehold.co/200x200/e0e0e0/ffffff?text=No+Image" alt="No Image" class="img-fluid rounded mb-3">`;

        const pembayaranTransaksiHtml = item.pembayaran_transaksi
            ? `<a href="{{ asset('storage') }}/${item.pembayaran_transaksi}" target="_blank" class="btn btn-sm btn-outline-primary mt-2" title="Lihat Bukti Pembayaran">` +
              (window.isPdf(item.pembayaran_transaksi) ? `<i class="fas fa-file-pdf"></i> PDF` : `<i class="fas fa-image"></i> Gambar`) +
              `</a>`
            : `-`;

        const notaTransaksiHtml = item.nota_transaksi
            ? `<a href="{{ asset('storage') }}/${item.nota_transaksi}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2" title="Lihat Nota Transaksi">` +
              (window.isPdf(item.nota_transaksi) ? `<i class="fas fa-file-pdf"></i> PDF` : `<i class="fas fa-image"></i> Gambar`) +
              `</a>`
            : `-`;


        if (itemType === 'incoming') {
            htmlContent = `
                <div class="row">
                    <div class="col-md-4 text-center">
                        ${fotoBarangHtml}
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Barang</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr><td width="30%"><strong>ID Barang:</strong></td><td>#${item.id}</td></tr>
                                    <tr><td><strong>Nama Barang:</strong></td><td>${item.nama_barang}</td></tr>
                                    <tr><td><strong>Kategori:</strong></td><td><span class="badge bg-secondary">${item.kategori_barang}</span></td></tr>
                                    <tr><td><strong>Jumlah:</strong></td><td><span class="fw-bold">${item.jumlah_barang}</span> unit</td></tr>
                                    <tr><td><strong>Tanggal Masuk:</strong></td><td>${new Date(item.tanggal_masuk_barang).toLocaleDateString('id-ID', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}</td></tr>
                                    <tr><td><strong>Lokasi Rak:</strong></td><td>${item.lokasi_rak_barang ? '<span class="badge bg-info">' + item.lokasi_rak_barang + '</span>' : '<span class="badge bg-secondary">Belum ditempatkan</span>'}</td></tr>
                                    <tr><td><strong>Nama Produsen:</strong></td><td>${item.nama_produsen ?? '-'}</td></tr>
                                    <tr><td><strong>Metode Bayar:</strong></td><td>${item.metode_bayar ?? '-'}</td></tr>
                                    <tr><td><strong>Pembayaran Transaksi:</strong></td><td>${pembayaranTransaksiHtml}</td></tr>
                                    <tr><td><strong>Nota Transaksi:</strong></td><td>${notaTransaksiHtml}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-cog"></i> Aksi Cepat & Riwayat</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-grid">
                                            <button class="btn btn-warning btn-sm" onclick="window.editIncomingItem(${item.id})">
                                                <i class="fas fa-edit"></i> Edit Barang
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <div class="mb-2">
                                                <i class="fas fa-plus text-success"></i>
                                                Dibuat ${new Date(item.created_at || item.tanggal_masuk_barang).toLocaleDateString('id-ID')}
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-check text-success"></i>
                                                Status: <span class="badge bg-success">Aktif</span>
                                            </div>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else { // itemType === 'outgoing'
            htmlContent = `
                <div class="row">
                    <div class="col-md-4 text-center">
                        ${fotoBarangHtml}
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0"><i class="fas fa-shipping-fast"></i> Detail Pengiriman</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr><td width="30%"><strong>ID Pengiriman:</strong></td><td>#${item.id}</td></tr>
                                    <tr><td><strong>Nama Barang:</strong></td><td>${item.nama_barang}</td></tr>
                                    <tr><td><strong>Kategori:</strong></td><td><span class="badge bg-secondary">${item.kategori_barang}</span></td></tr>
                                    <tr><td><strong>Jumlah:</strong></td><td><span class="fw-bold text-danger">${item.jumlah_barang}</span> unit</td></tr>
                                    <tr><td><strong>Tanggal Keluar:</strong></td><td>${new Date(item.tanggal_keluar_barang).toLocaleDateString('id-ID', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}</td></tr>
                                    <tr><td><strong>Lokasi Rak Asal:</strong></td><td>${item.lokasi_rak_barang ? '<span class="badge bg-info">' + item.lokasi_rak_barang + '</span>' : '<span class="badge bg-secondary">-</span>'}</td></tr>
                                    <tr><td><strong>Nama Produsen:</strong></td><td>${item.nama_produsen ?? '-'}</td></tr>
                                    <tr><td><strong>Metode Bayar:</strong></td><td>${item.metode_bayar ?? '-'}</td></tr>
                                    <tr><td><strong>Pembayaran Transaksi:</strong></td><td>${pembayaranTransaksiHtml}</td></tr>
                                    <tr><td><strong>Nota Transaksi:</strong></td><td>${notaTransaksiHtml}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-tools"></i> Aksi & Timeline</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary btn-sm" onclick="window.printDeliveryNote(${item.id})">
                                                <i class="fas fa-print"></i> Cetak Surat Jalan
                                            </button>
                                            <button class="btn btn-success btn-sm" onclick="window.trackDelivery(${item.id})">
                                                <i class="fas fa-truck"></i> Lacak Pengiriman
                                            </button>
                                            <button class="btn btn-info btn-sm" onclick="window.updateDeliveryStatus(${item.id})">
                                                <i class="fas fa-edit"></i> Update Status
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <div class="mb-2">
                                                <i class="fas fa-plus text-success"></i>
                                                Dibuat ${new Date(item.created_at || item.tanggal_keluar_barang).toLocaleDateString('id-ID')}
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-shipping-fast text-primary"></i>
                                                Dikirim ${new Date(item.tanggal_keluar_barang).toLocaleDateString('id-ID')}
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-check text-success"></i>
                                                Status: <span class="badge bg-success">Selesai</span>
                                            </div>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        modalContent.innerHTML = htmlContent;
        const modal = new bootstrap.Modal(document.getElementById('itemDetailModal'));
        modal.show();
    } else {
        window.showAlert('error', 'Data barang tidak ditemukan.');
    }
}

/**
 * Displays the CRUD form modal for adding a new incoming item.
 */
window.addNewIncomingItem = function() {
    window.renderItemCrudForm('incoming', 'add');
}

/**
 * Displays the CRUD form modal for editing an incoming item.
 * @param {number} itemId - The ID of the incoming item to edit.
 */
window.editIncomingItem = async function(itemId) {
    try {
        const response = await fetch(`/staff/incoming-items/${itemId}`);
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Server response for editIncomingItem was not OK:', errorText);
            window.showAlert('error', `Gagal memuat data barang untuk diedit. Status: ${response.status}. Detail di konsol.`);
            return;
        }
        const result = await response.json();

        if (result.success) {
            window.renderItemCrudForm('incoming', 'edit', result.data);
        } else {
            window.showAlert('error', result.message || 'Gagal memuat data barang.');
        }
    }
    catch (error) {
        console.error('Error fetching incoming item for edit:', error);
        window.showAlert('error', 'Terjadi kesalahan saat memuat data barang.');
    }
}

/**
 * Displays the CRUD form modal for editing an outgoing item.
 * @param {number} itemId - The ID of the outgoing item to edit.
 */
window.editOutgoingItem = async function(itemId) {
    try {
        const response = await fetch(`/staff/outgoing-items/${itemId}`);
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Server response for editOutgoingItem was not OK:', errorText);
            window.showAlert('error', `Gagal memuat data barang keluar untuk diedit. Status: ${response.status}. Detail di konsol.`);
            return;
        }
        const result = await response.json();

        if (result.success) {
            window.renderItemCrudForm('outgoing', 'edit', result.data);
        } else {
            window.showAlert('error', result.message || 'Gagal memuat data barang keluar.');
        }
    }
    catch (error) {
        console.error('Error fetching outgoing item for edit:', error);
        window.showAlert('error', 'Terjadi kesalahan saat memuat data barang keluar.');
    }
}

/**
 * Populates and displays the generic CRUD form modal dynamically.
 * @param {string} itemType - 'incoming' or 'outgoing'
 * @param {string} mode - 'add' or 'edit'
 * @param {object} itemData - Item data if in 'edit' or 'moveToOutgoing' mode
 */
window.renderItemCrudForm = function(itemType, mode, itemData = null) {
    const modalLabel = document.getElementById('itemCrudModalLabel');
    const formContentDiv = document.getElementById('itemCrudFormContent');
    const crudItemIdField = document.getElementById('crudItemId');
    const formMethodField = document.getElementById('formMethod');
    const itemCrudForm = document.getElementById('itemCrudForm');
    const submitBtn = document.getElementById('itemCrudSubmitBtn');

    // Set the form's onsubmit handler to the generic handler
    itemCrudForm.onsubmit = (event) => window.handleItemCrudSubmit(event, itemType, mode);

    let formHtml = '';
    itemCrudForm.reset(); // Reset form fields

    if (mode === 'add') {
        crudItemIdField.value = '';
        formMethodField.value = 'POST';
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan';
        submitBtn.classList.remove('btn-warning', 'btn-danger'); // Ensure correct class
        submitBtn.classList.add('btn-primary');

        if (itemType === 'incoming') {
            modalLabel.textContent = 'Tambah Barang Masuk Baru';
            formHtml = `
                <div class="mb-3">
                    <label for="crud_nama_barang" class="form-label">Nama Barang *</label>
                    <input type="text" class="form-control" id="crud_nama_barang" name="nama_barang" required placeholder="Masukkan nama barang">
                </div>
                <div class="mb-3">
                    <label for="crud_kategori_barang" class="form-label">Kategori Barang *</label>
                    <select class="form-select" id="crud_kategori_barang" name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="crud_producer_id" class="form-label">Produsen *</label>
                    <select class="form-select" id="crud_producer_id" name="producer_id" required>
                        <option value="">Pilih Produsen</option>
                        @foreach($producers as $producer)
                            <option value="{{ $producer->id }}">{{ $producer->nama_produsen_supplier }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="crud_jumlah_barang" class="form-label">Jumlah Barang *</label>
                    <input type="number" class="form-control" id="crud_jumlah_barang" name="jumlah_barang" required min="1">
                </div>
                <div class="mb-3">
                    <label for="crud_harga_jual" class="form-label">Harga Jual</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="crud_harga_jual" name="harga_jual" min="0" step="0.01" placeholder="0.00">
                    </div>
                    <small class="text-muted">Masukkan harga jual per unit</small>
                </div>
                <div class="mb-3">
                    <label for="crud_tanggal_masuk" class="form-label">Tanggal Masuk *</label>
                    <input type="date" class="form-control" id="crud_tanggal_masuk" name="tanggal_masuk_barang" required>
                </div>
                <div class="mb-3">
                    <label for="crud_lokasi_rak" class="form-label">Lokasi Rak</label>
                    <input type="text" class="form-control" id="crud_lokasi_rak" name="lokasi_rak_barang" placeholder="Format: R1-1-1">
                    <small class="text-muted">Format: R[1-8]-[1-4]-[1-6], contoh: R1-1-1</small>
                </div>
                <div class="mb-3">
                    <label for="crud_metode_bayar" class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="crud_metode_bayar" name="metode_bayar">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="Cash">Cash</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Kredit">Kredit</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="crud_pembayaran_transaksi" class="form-label">Bukti Pembayaran</label>
                    <input type="file" class="form-control" id="crud_pembayaran_transaksi" name="pembayaran_transaksi" accept="image/*,application/pdf">
                    <small class="form-text text-muted">Format yang diizinkan: JPG, PNG, GIF, SVG, PDF. Maksimal 2MB.</small>
                </div>
                <div class="mb-3">
                    <label for="crud_nota_transaksi" class="form-label">Nota Transaksi</label>
                    <input type="file" class="form-control" id="crud_nota_transaksi" name="nota_transaksi" accept="image/*,application/pdf">
                    <small class="form-text text-muted">Format yang diizinkan: JPG, PNG, GIF, SVG, PDF. Maksimal 2MB.</small>
                </div>
                <div class="mb-3">
                    <label for="crud_foto_barang" class="form-label">Foto Barang</label>
                    <input type="file" class="form-control" id="crud_foto_barang" name="foto_barang" accept="image/*">
                    <small class="form-text text-muted">Format yang diizinkan: JPG, PNG, GIF, SVG. Maksimal 2MB.</small>
                </div>
            `;
        } else { // outgoing - add mode (e.g., from "Pindah ke Barang Keluar" or "Proses Barang Keluar")
            modalLabel.textContent = 'Proses Barang Keluar Baru';
            submitBtn.innerHTML = '<i class="fas fa-arrow-up"></i> Proses Barang Keluar';
            submitBtn.classList.remove('btn-primary', 'btn-warning');
            submitBtn.classList.add('btn-danger');

            let availableStockText = '';
            let defaultLocation = '';
            let defaultCategory = '';
            let defaultFoto = '';
            let defaultPembayaran = '';
            let defaultNota = '';

            if (itemData) {
                availableStockText = `Stok tersedia: ${itemData.jumlah_barang} unit`;
                defaultLocation = itemData.lokasi_rak_barang || '';
                defaultCategory = itemData.kategori_barang || '';
                defaultFoto = itemData.foto_barang || '';
                defaultPembayaran = itemData.pembayaran_transaksi || '';
                defaultNota = itemData.nota_transaksi || '';
            }

            formHtml = `
                <div class="mb-3">
                    <label for="crud_nama_barang" class="form-label">Nama Barang *</label>
                    <select class="form-select" id="crud_nama_barang" name="nama_barang" required onchange="window.updateOutgoingFormFields(this)">
                        <option value="">Pilih dari stok tersedia</option>
                        @if(isset($incomingItems))
                            @foreach($incomingItems->where('jumlah_barang', '>', 0) as $incItem)
                                <option value="{{ $incItem->nama_barang }}" 
                                        data-category="{{ $incItem->kategori_barang }}" 
                                        data-available="{{ $incItem->jumlah_barang }}" 
                                        data-location="{{ $incItem->lokasi_rak_barang }}"
                                        data-foto="{{ $incItem->foto_barang }}"
                                        data-pembayaran="{{ $incItem->pembayaran_transaksi }}"
                                        data-nota="{{ $incItem->nota_transaksi }}"
                                        ${itemData && itemData.nama_barang === '{{ $incItem->nama_barang }}' ? 'selected' : ''}>
                                    {{ $incItem->nama_barang }} ({{ $incItem->jumlah_barang }} unit tersedia)
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label for="crud_kategori_barang" class="form-label">Kategori Barang</label>
                    <input type="text" class="form-control" id="crud_kategori_barang" name="kategori_barang" readonly placeholder="Otomatis terisi" value="${defaultCategory}">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="crud_jumlah_barang" class="form-label">Jumlah Keluar *</label>
                            <input type="number" class="form-control" id="crud_jumlah_barang" name="jumlah_barang" required min="1" placeholder="0">
                            <small class="form-text text-muted" id="crud_availableStock">${availableStockText}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="crud_tanggal_keluar" class="form-label">Tanggal Keluar *</label>
                            <input type="date" class="form-control" id="crud_tanggal_keluar" name="tanggal_keluar_barang" required value="${new Date().toISOString().split('T')[0]}">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="crud_lokasi_asal" class="form-label">Lokasi Rak Asal</label>
                    <input type="text" class="form-control" id="crud_lokasi_asal" name="lokasi_rak_barang" readonly placeholder="Otomatis dari stok" value="${defaultLocation}">
                </div>
                <div class="mb-3">
                    <label for="crud_nama_produsen_outgoing" class="form-label">Nama Produsen *</label>
                    <input type="text" class="form-control" id="crud_nama_produsen_outgoing" name="nama_produsen" required placeholder="Nama Produsen">
                </div>
                <div class="mb-3">
                    <label for="crud_metode_bayar_outgoing" class="form-label">Metode Bayar</label>
                    <select class="form-select" id="crud_metode_bayar_outgoing" name="metode_bayar">
                        <option value="">Pilih Metode</option>
                        <option value="Cash">Cash</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Kartu Kredit">Kartu Kredit</option>
                        <option value="Debit">Debit</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="crud_pembayaran_transaksi_outgoing" class="form-label">Bukti Pembayaran Transaksi</label>
                    <input type="file" class="form-control" id="crud_pembayaran_transaksi_outgoing" name="pembayaran_transaksi" accept="image/*,application/pdf">
                    <small class="form-text text-muted">Unggah gambar atau PDF bukti pembayaran (opsional).</small>
                    <div id="current_pembayaran_transaksi_outgoing" class="mt-2">
                        ${defaultPembayaran ? (window.isPdf(defaultPembayaran) ? `<a href="{{ asset('storage') }}/${defaultPembayaran}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-file-pdf"></i> Lihat PDF</a>` : `<img src="{{ asset('storage') }}/${defaultPembayaran}" alt="Current Payment" style="width: 100px; height: auto; border-radius: 5px;">`) : ''}
                    </div>
                </div>
                <div class="mb-3">
                    <label for="crud_nota_transaksi_outgoing" class="form-label">Nota Transaksi</label>
                    <input type="file" class="form-control" id="crud_nota_transaksi_outgoing" name="nota_transaksi" accept="image/*,application/pdf">
                    <small class="form-text text-muted">Unggah gambar atau PDF nota transaksi (opsional).</small>
                    <div id="current_nota_transaksi_outgoing" class="mt-2">
                        ${defaultNota ? (window.isPdf(defaultNota) ? `<a href="{{ asset('storage') }}/${defaultNota}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-file-pdf"></i> Lihat PDF</a>` : `<img src="{{ asset('storage') }}/${defaultNota}" alt="Current Nota" style="width: 100px; height: auto; border-radius: 5px;">`) : ''}
                    </div>
                </div>
                <div class="mb-3">
                    <label for="crud_foto_barang_outgoing" class="form-label">Foto Barang</label>
                    <input type="file" class="form-control" id="crud_foto_barang_outgoing" name="foto_barang" accept="image/*">
                    <small class="form-text text-muted">Unggah gambar barang (opsional).</small>
                    <div id="current_foto_barang_outgoing" class="mt-2">
                        ${defaultFoto ? `<img src="{{ asset('storage') }}/${defaultFoto}" alt="Current Photo" style="width: 100px; height: auto; border-radius: 5px;">` : ''}
                    </div>
                </div>
            `;
        }
    } else { // edit mode
        crudItemIdField.value = itemData.id;
        formMethodField.value = 'PUT';
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
        submitBtn.classList.remove('btn-primary', 'btn-danger');
        submitBtn.classList.add('btn-warning');

        if (itemType === 'incoming') {
            modalLabel.textContent = `Edit Barang Masuk (ID: #${itemData.id})`;
            formHtml = `
                <div class="mb-3">
                    <label for="crud_nama_barang" class="form-label">Nama Barang *</label>
                    <input type="text" class="form-control" id="crud_nama_barang" name="nama_barang" value="${itemData.nama_barang}" required>
                </div>
                <div class="mb-3">
                    <label for="crud_kategori_barang" class="form-label">Kategori Barang *</label>
                    <select class="form-select" id="crud_kategori_barang" name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="crud_producer_id" class="form-label">Produsen *</label>
                    <select class="form-select" id="crud_producer_id" name="producer_id" required>
                        <option value="">Pilih Produsen</option>
                        @foreach($producers as $producer)
                            <option value="{{ $producer->id }}">{{ $producer->nama_produsen_supplier }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="crud_jumlah_barang" class="form-label">Jumlah Barang *</label>
                    <input type="number" class="form-control" id="crud_jumlah_barang" name="jumlah_barang" value="${itemData.jumlah_barang}" required min="0">
                </div>
                <div class="mb-3">
                    <label for="crud_harga_jual" class="form-label">Harga Jual</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="crud_harga_jual" name="harga_jual" min="0" step="0.01" value="${itemData.harga_jual || ''}" placeholder="0.00">
                    </div>
                    <small class="text-muted">Masukkan harga jual per unit</small>
                </div>
                <div class="mb-3">
                    <label for="crud_tanggal_masuk" class="form-label">Tanggal Masuk *</label>
                    <input type="date" class="form-control" id="crud_tanggal_masuk" name="tanggal_masuk_barang" value="${new Date(itemData.tanggal_masuk_barang).toISOString().split('T')[0]}" required>
                </div>
                <div class="mb-3">
                    <label for="crud_lokasi_rak" class="form-label">Lokasi Rak</label>
                    <input type="text" class="form-control" id="crud_lokasi_rak" name="lokasi_rak_barang" value="${itemData.lokasi_rak_barang || ''}" pattern="R[1-8]-[1-4]-[1-6]" placeholder="R1-1-1 (opsional)">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.showRackSelector('crud_lokasi_rak')">
                        <i class="fas fa-map"></i> Pilih
                    </button>
                    <small class="form-text text-muted">Format: R[1-8]-[1-4]-[1-6]</small>
                </div>
                <div class="mb-3">
                    <label for="crud_metode_bayar" class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="crud_metode_bayar" name="metode_bayar">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="Cash" ${itemData.metode_bayar === 'Cash' ? 'selected' : ''}>Cash</option>
                        <option value="Transfer Bank" ${itemData.metode_bayar === 'Transfer Bank' ? 'selected' : ''}>Transfer Bank</option>
                        <option value="Kredit" ${itemData.metode_bayar === 'Kredit' ? 'selected' : ''}>Kredit</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="crud_pembayaran_transaksi" class="form-label">Bukti Pembayaran</label>
                    <input type="file" class="form-control" id="crud_pembayaran_transaksi" name="pembayaran_transaksi" accept="image/*,application/pdf">
                    <small class="form-text text-muted">Unggah gambar atau PDF bukti pembayaran (biarkan kosong untuk tidak mengubah).</small>
                    <div id="current_pembayaran_transaksi" class="mt-2">
                        ${itemData.pembayaran_transaksi ? (window.isPdf(itemData.pembayaran_transaksi) ? `<a href="{{ asset('storage') }}/${itemData.pembayaran_transaksi}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-file-pdf"></i> Lihat PDF</a>` : `<img src="{{ asset('storage') }}/${itemData.pembayaran_transaksi}" alt="Current Payment" style="width: 100px; height: auto; border-radius: 5px;">`) : ''}
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="remove_pembayaran_transaksi" name="pembayaran_transaksi_removed" value="true">
                        <label class="form-check-label" for="remove_pembayaran_transaksi">Hapus bukti pembayaran yang ada</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="crud_nota_transaksi" class="form-label">Nota Transaksi</label>
                    <input type="file" class="form-control" id="crud_nota_transaksi" name="nota_transaksi" accept="image/*,application/pdf">
                    <small class="form-text text-muted">Unggah gambar atau PDF nota transaksi (biarkan kosong untuk tidak mengubah).</small>
                    <div id="current_nota_transaksi" class="mt-2">
                        ${itemData.nota_transaksi ? (window.isPdf(itemData.nota_transaksi) ? `<a href="{{ asset('storage') }}/${itemData.nota_transaksi}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-file-pdf"></i> Lihat PDF</a>` : `<img src="{{ asset('storage') }}/${itemData.nota_transaksi}" alt="Current Nota" style="width: 100px; height: auto; border-radius: 5px;">`) : ''}
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="remove_nota_transaksi" name="nota_transaksi_removed" value="true">
                        <label class="form-check-label" for="remove_nota_transaksi">Hapus nota transaksi yang ada</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="crud_foto_barang" class="form-label">Foto Barang</label>
                    <input type="file" class="form-control" id="crud_foto_barang" name="foto_barang" accept="image/*">
                    <small class="form-text text-muted">Unggah gambar barang (biarkan kosong untuk tidak mengubah).</small>
                    <div id="current_foto_barang" class="mt-2">
                        ${itemData.foto_barang ? `<img src="{{ asset('storage') }}/${itemData.foto_barang}" alt="Current Photo" style="width: 100px; height: auto; border-radius: 5px;">` : ''}
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="remove_foto_barang" name="foto_barang_removed" value="true">
                        <label class="form-check-label" for="remove_foto_barang">Hapus foto barang yang ada</label>
                    </div>
                </div>
            `;
        } else { // outgoing - edit mode
            modalLabel.textContent = `Edit Barang Keluar (ID: #${itemData.id})`;
            formHtml = `
                <div class="mb-3">
                    <label for="crud_nama_barang" class="form-label">Nama Barang *</label>
                    <input type="text" class="form-control" id="crud_nama_barang" name="nama_barang" value="${itemData.nama_barang}" required>
                </div>
                <div class="mb-3">
                    <label for="crud_kategori_barang" class="form-label">Kategori Barang *</label>
                    <input type="text" class="form-control" id="crud_kategori_barang" name="kategori_barang" value="${itemData.kategori_barang}" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="crud_jumlah_barang" class="form-label">Jumlah Keluar *</label>
                            <input type="number" class="form-control" id="crud_jumlah_barang" name="jumlah_barang" value="${itemData.jumlah_barang}" required min="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="crud_tanggal_keluar" class="form-label">Tanggal Keluar *</label>
                            <input type="date" class="form-control" id="crud_tanggal_keluar" name="tanggal_keluar_barang" value="${new Date(itemData.tanggal_keluar_barang).toISOString().split('T')[0]}" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="crud_tujuan_distribusi" class="form-label">Tujuan Distribusi</label>
                    <input type="text" class="form-control" id="crud_tujuan_distribusi" name="tujuan_distribusi" value="${itemData.tujuan_distribusi || ''}" placeholder="Tujuan Distribusi (opsional)">
                </div>
                <div class="mb-3">
                    <label for="crud_lokasi_rak_asal" class="form-label">Lokasi Rak Asal</label>
                    <input type="text" class="form-control" id="crud_lokasi_rak_asal" name="lokasi_rak_barang" value="${itemData.lokasi_rak_barang || ''}" pattern="R[1-8]-[1-4]-[1-6]" placeholder="R1-1-1 (opsional)">
                    <small class="form-text text-muted">Format: R[1-8]-[1-4]-[1-6]</small>
                </div>
                <div class="mb-3">
                    <label for="crud_nama_produsen_outgoing" class="form-label">Nama Produsen</label>
                    <input type="text" class="form-control" id="crud_nama_produsen_outgoing" name="nama_produsen" value="${itemData.nama_produsen || ''}" placeholder="Nama Produsen (opsional)">
                </div>
                <div class="mb-3">
                    <label for="crud_metode_bayar_outgoing" class="form-label">Metode Bayar</label>
                    <select class="form-select" id="crud_metode_bayar_outgoing" name="metode_bayar">
                        <option value="">Pilih Metode</option>
                        <option value="Cash" ${itemData.metode_bayar === 'Cash' ? 'selected' : ''}>Cash</option>
                        <option value="Transfer Bank" ${itemData.metode_bayar === 'Transfer Bank' ? 'selected' : ''}>Transfer Bank</option>
                        <option value="Kartu Kredit" ${itemData.metode_bayar === 'Kartu Kredit' ? 'selected' : ''}>Kartu Kredit</option>
                        <option value="Debit" ${itemData.metode_bayar === 'Debit' ? 'selected' : ''}>Debit</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="crud_pembayaran_transaksi_outgoing" class="form-label">Bukti Pembayaran Transaksi</label>
                    <input type="file" class="form-control" id="crud_pembayaran_transaksi_outgoing" name="pembayaran_transaksi" accept="image/*,application/pdf">
                    <small class="form-text text-muted">Unggah gambar atau PDF bukti pembayaran (biarkan kosong untuk tidak mengubah).</small>
                    <div id="current_pembayaran_transaksi_outgoing" class="mt-2">
                        ${itemData.pembayaran_transaksi ? (window.isPdf(itemData.pembayaran_transaksi) ? `<a href="{{ asset('storage') }}/${itemData.pembayaran_transaksi}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-file-pdf"></i> Lihat PDF</a>` : `<img src="{{ asset('storage') }}/${itemData.pembayaran_transaksi}" alt="Current Payment" style="width: 100px; height: auto; border-radius: 5px;">`) : ''}
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="remove_pembayaran_transaksi_outgoing" name="pembayaran_transaksi_removed" value="true">
                        <label class="form-check-label" for="remove_pembayaran_transaksi_outgoing">Hapus bukti pembayaran yang ada</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="crud_nota_transaksi_outgoing" class="form-label">Nota Transaksi</label>
                    <input type="file" class="form-control" id="crud_nota_transaksi_outgoing" name="nota_transaksi" accept="image/*,application/pdf">
                    <small class="form-text text-muted">Unggah gambar atau PDF nota transaksi (biarkan kosong untuk tidak mengubah).</small>
                    <div id="current_nota_transaksi_outgoing" class="mt-2">
                        ${itemData.nota_transaksi ? (window.isPdf(itemData.nota_transaksi) ? `<a href="{{ asset('storage') }}/${itemData.nota_transaksi}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-file-pdf"></i> Lihat PDF</a>` : `<img src="{{ asset('storage') }}/${itemData.nota_transaksi}" alt="Current Nota" style="width: 100px; height: auto; border-radius: 5px;">`) : ''}
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="remove_nota_transaksi_outgoing" name="nota_transaksi_removed" value="true">
                        <label class="form-check-label" for="remove_nota_transaksi_outgoing">Hapus nota transaksi yang ada</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="crud_foto_barang_outgoing" class="form-label">Foto Barang</label>
                    <input type="file" class="form-control" id="crud_foto_barang_outgoing" name="foto_barang" accept="image/*">
                    <small class="form-text text-muted">Unggah gambar barang (biarkan kosong untuk tidak mengubah).</small>
                    <div id="current_foto_barang_outgoing" class="mt-2">
                        ${itemData.foto_barang ? `<img src="{{ asset('storage') }}/${itemData.foto_barang}" alt="Current Photo" style="width: 100px; height: auto; border-radius: 5px;">` : ''}
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="remove_foto_barang_outgoing" name="foto_barang_removed" value="true">
                        <label class="form-check-label" for="remove_foto_barang_outgoing">Hapus foto barang yang ada</label>
                    </div>
                </div>
            `;
        }
    }

    formContentDiv.innerHTML = formHtml;
    const itemCrudModal = new bootstrap.Modal(document.getElementById('itemCrudModal'));
    itemCrudModal.show();

    // Auto-fill form values for edit mode
    if (mode === 'edit' && itemData) {
        if (itemType === 'incoming') {
            // Set category dropdown value
            const categorySelect = document.getElementById('crud_kategori_barang');
            if (categorySelect && itemData.category_id) {
                categorySelect.value = itemData.category_id;
            }
            
            // Set producer dropdown value
            const producerSelect = document.getElementById('crud_producer_id');
            if (producerSelect && itemData.producer_id) {
                producerSelect.value = itemData.producer_id;
            }
        } else if (itemType === 'outgoing') {
            // For outgoing items, producer is stored as name in producer field
            const producerField = document.getElementById('crud_nama_produsen_outgoing');
            if (producerField && itemData.nama_produsen) {
                producerField.value = itemData.nama_produsen;
            }
            
            // If producer_id exists and relates to producer table, get the name
            if (itemData.producer_id && itemData.producer) {
                const producerField = document.getElementById('crud_nama_produsen_outgoing');
                if (producerField && itemData.producer.nama_produsen_supplier) {
                    producerField.value = itemData.producer.nama_produsen_supplier;
                }
            }
        }
    }

    // Re-attach event listener for outgoing_nama_barang if it exists in the dynamically loaded form
    if (itemType === 'outgoing' && mode === 'add') {
        const crudNamaBarangSelect = document.getElementById('crud_nama_barang');
        if (crudNamaBarangSelect) {
            crudNamaBarangSelect.addEventListener('change', function() {
                window.updateOutgoingFormFields(this);
            });
            // Trigger change event if an item was pre-selected (e.g., from moveToOutgoing)
            if (itemData && itemData.nama_barang) {
                window.updateOutgoingFormFields(crudNamaBarangSelect);
            }
        }
    }
}

/**
 * Handles the submission of the CRUD form (add/edit incoming or outgoing item).
 */
window.handleItemCrudSubmit = async function(event, itemType, mode) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const itemId = document.getElementById('crudItemId') ? document.getElementById('crudItemId').value : null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const submitBtn = document.getElementById('itemCrudSubmitBtn');
    const originalBtnHtml = submitBtn.innerHTML;

    let url = '';
    let method = 'POST'; // Default for Laravel with _method spoofing

    if (itemType === 'incoming') {
        if (mode === 'add') {
            url = '{{ route("staff.incoming_items.store") }}';
        } else { // mode === 'edit'
            url = `/staff/incoming-items/${itemId}`;
            formData.append('_method', 'PUT'); // Spoof PUT method
        }
    } else { // itemType === 'outgoing'
        // Validate stock for outgoing items before sending
        if (mode === 'add') {
            const selectedItemName = document.getElementById('crud_nama_barang').value;
            const requestedQuantity = parseInt(document.getElementById('crud_jumlah_barang').value);
            const selectedOption = document.querySelector(`#crud_nama_barang option[value="${selectedItemName}"]`);
            const availableStock = parseInt(selectedOption ? selectedOption.dataset.available : '0');

            if (requestedQuantity > availableStock) {
                window.showAlert('error', `Jumlah yang diminta (${requestedQuantity}) melebihi stok yang tersedia (${availableStock}).`);
                return;
            }
            url = '{{ route("staff.outgoing_items.store") }}';
        } else { // mode === 'edit'
            url = `/staff/outgoing-items/${itemId}`;
            formData.append('_method', 'PUT'); // Spoof PUT method
        }
    }

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<div class="loading-spinner"></div> Processing...';

    try {
        // Get category name from category_id
        const categorySelect = document.getElementById('crud_kategori_barang');
        if (categorySelect) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            if (selectedOption) {
                formData.append('kategori_barang', selectedOption.text);
            }
        }

        // Handle file inputs
        const fileInputs = ['pembayaran_transaksi', 'nota_transaksi', 'foto_barang'];
        fileInputs.forEach(fieldName => {
            const fileInput = document.querySelector(`input[name="${fieldName}"]`);
            if (fileInput && fileInput.files.length === 0) {
                formData.delete(fieldName); // Remove empty file fields
            }
        });

        const response = await fetch(url, {
            method: 'POST', // Always POST for Laravel with _method spoofing
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        // Enhanced error handling for fetch responses
        if (!response.ok) {
            let errorText = `HTTP error! status: ${response.status}`;
            try {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const errorData = await response.json();
                    errorText = errorData.message || JSON.stringify(errorData);
                    if (errorData.errors) {
                        let errorMessage = 'Validation errors:';
                        for (const key in errorData.errors) {
                            errorMessage += `\n- ${errorData.errors[key][0]}`;
                        }
                        window.showAlert('error', errorMessage);
                        return;
                    }
                } else {
                    errorText = await response.text();
                }
            }
            catch (parseError) {
                console.error('Error parsing response for non-OK status:', parseError);
                errorText += ` (Failed to parse response: ${parseError.message})`;
            }
            console.error('Server response was not OK:', errorText);
            window.showAlert('error', `Server error: ${errorText.substring(0, 150)}... (See console for details)`);
            return;
        }

        const data = await response.json();

        if (data.success) {
            window.showAlert('success', data.message);
            const itemCrudModalElement = document.getElementById('itemCrudModal');
            if (itemCrudModalElement) {
                const itemCrudModal = bootstrap.Modal.getInstance(itemCrudModalElement);
                if (itemCrudModal) itemCrudModal.hide();
            }
            location.reload(); // Reload page to reflect changes
        } else {
            let errorMessage = data.message || 'An error occurred.';
            if (data.errors) {
                for (const key in data.errors) {
                    errorMessage += `\n- ${data.errors[key][0]}`;
                }
            }
            window.showAlert('error', errorMessage);
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        window.showAlert('error', 'Network or server error: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHtml;
    }
}

/**
 * Handles the deletion of an incoming item.
 * @param {number} itemId - The ID of the incoming item to delete.
 */
window.deleteIncomingItem = async function(itemId) {
    window.showCustomConfirm('Are you sure you want to delete this incoming item? This action cannot be undone.', async () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        window.showAlert('info', 'Deleting incoming item...');

        try {
            const response = await fetch(`/staff/incoming-items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            // Enhanced error handling for fetch responses
            if (!response.ok) {
                let errorText = `HTTP error! status: ${response.status}`;
                try {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const errorData = await response.json();
                        errorText = errorData.message || JSON.stringify(errorData);
                    } else {
                        errorText = await response.text();
                    }
                }
                catch (parseError) {
                    console.error('Error parsing response for non-OK status:', parseError);
                    errorText += ` (Failed to parse response: ${parseError.message})`;
                }
                console.error('Server response for deleteIncomingItem was not OK:', errorText);
                window.showAlert('error', `Failed to delete incoming item. Status: ${response.status}. Details in console.`);
                return;
            }

            const data = await response.json();

            if (data.success) {
                window.showAlert('success', data.message);
                location.reload();
            } else {
                window.showAlert('error', data.message || 'Failed to delete incoming item.');
            }
        } catch (error) {
            console.error('Delete Error:', error);
            window.showAlert('error', 'Network error while deleting incoming item.');
        }
    });
}

/**
 * Handles the deletion of an outgoing item.
 * @param {number} itemId - The ID of the outgoing item to delete.
 */
window.deleteOutgoingItem = async function(itemId) {
    window.showCustomConfirm('Are you sure you want to delete this outgoing item? This action cannot be undone.', async () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        window.showAlert('info', 'Deleting outgoing item...');

        try {
            const response = await fetch(`/staff/outgoing-items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            // Enhanced error handling for fetch responses
            if (!response.ok) {
                let errorText = `HTTP error! status: ${response.status}`;
                try {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const errorData = await response.json();
                        errorText = errorData.message || JSON.stringify(errorData);
                    } else {
                        errorText = await response.text();
                    }
                }
                catch (parseError) {
                    console.error('Error parsing response for non-OK status:', parseError);
                    errorText += ` (Failed to parse response: ${parseError.message})`;
                }
                console.error('Server response for deleteOutgoingItem was not OK:', errorText);
                window.showAlert('error', `Failed to delete outgoing item. Status: ${response.status}. Details in console.`);
                return;
            }

            const data = await response.json();

            if (data.success) {
                window.showAlert('success', data.message);
                location.reload();
            } else {
                window.showAlert('error', data.message || 'Failed to delete outgoing item.');
            }
        } catch (error) {
            console.error('Delete Error:', error);
            window.showAlert('error', 'Network error while deleting outgoing item.');
        }
    });
}

/**
 * Updates outgoing item form fields when an item name is selected.
 * @param {HTMLSelectElement} selectElement - The item name select element.
 */
window.updateOutgoingFormFields = function(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const category = selectedOption.dataset.category || '';
    const available = selectedOption.dataset.available || '0';
    const location = selectedOption.dataset.location || '';
    const foto = selectedOption.dataset.foto || ''; // Get foto_barang
    const pembayaran = selectedOption.dataset.pembayaran || ''; // Get pembayaran_transaksi
    const nota = selectedOption.dataset.nota || ''; // Get nota_transaksi

    document.getElementById('crud_kategori_barang').value = category;
    document.getElementById('crud_lokasi_asal').value = location;
    document.getElementById('crud_availableStock').textContent = `Stok tersedia: ${available} unit`;
    document.getElementById('crud_jumlah_barang').max = available;
    document.getElementById('crud_jumlah_barang').placeholder = `Max: ${available}`;

    // Display current photo in the form
    const currentFotoDiv = document.getElementById('current_foto_barang_outgoing');
    if (currentFotoDiv) {
        if (foto) {
            currentFotoDiv.innerHTML = `<img src="{{ asset('storage') }}/${foto}" alt="Current Photo" style="width: 100px; height: auto; border-radius: 5px;">`;
        } else {
            currentFotoDiv.innerHTML = '';
        }
    }

    // Display current pembayaran_transaksi in the form
    const currentPembayaranDiv = document.getElementById('current_pembayaran_transaksi_outgoing');
    if (currentPembayaranDiv) {
        if (pembayaran) {
            currentPembayaranDiv.innerHTML = window.isPdf(pembayaran) 
                ? `<a href="{{ asset('storage') }}/${pembayaran}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-file-pdf"></i> Lihat PDF</a>` 
                : `<img src="{{ asset('storage') }}/${pembayaran}" alt="Current Payment" style="width: 100px; height: auto; border-radius: 5px;">`;
        } else {
            currentPembayaranDiv.innerHTML = '';
        }
    }

    // Display current nota_transaksi in the form
    const currentNotaDiv = document.getElementById('current_nota_transaksi_outgoing');
    if (currentNotaDiv) {
        if (nota) {
            currentNotaDiv.innerHTML = window.isPdf(nota) 
                ? `<a href="{{ asset('storage') }}/${nota}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-file-pdf"></i> Lihat PDF</a>` 
                : `<img src="{{ asset('storage') }}/${nota}" alt="Current Nota" style="width: 100px; height: auto; border-radius: 5px;">`;
        } else {
            currentNotaDiv.innerHTML = '';
        }
    }
}

/**
 * Displays the warehouse rack selector modal.
 * @param {string} targetInputId - The ID of the input to fill with the selected rack location.
 * @param {number|null} itemId - Item ID if moving an existing item.
 */
window.showRackSelector = function(targetInputId, itemId = null) {
    window.currentTargetInput = targetInputId;
    window.currentItemIdForRack = itemId; // Store current item ID if moving an existing item
    window.generateWarehouseSelector();
    const modal = new bootstrap.Modal(document.getElementById('rackSelectorModal'));
    modal.show();
}

/**
 * Generates the warehouse grid in the rack selection modal.
 */
window.generateWarehouseSelector = async function() {
    const warehouseSelector = document.getElementById('warehouseSelector');
    if (!warehouseSelector) return;
    
    window.showAlert('info', 'Memuat data lokasi gudang...');

    try {
        const response = await fetch('{{ route("staff.locations.available") }}', {
            headers: { 'Accept': 'application/json' }
        });
        // Enhanced error handling for fetch responses
        if (!response.ok) {
            let errorText = `HTTP error! status: ${response.status}`;
            try {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const errorData = await response.json();
                    errorText = errorData.message || JSON.stringify(errorData);
                } else {
                    errorText = await response.text();
                }
            }
            catch (parseError) {
                console.error('Error parsing response for non-OK status:', parseError);
                errorText += ` (Failed to parse response: ${parseError.message})`;
            }
            console.error('Server response for generateWarehouseSelector was not OK:', errorText);
            window.showAlert('error', `Gagal memuat data lokasi gudang. Status: ${response.status}. Detail di konsol.`);
            return;
        }

        const result = await response.json();

        if (result.success) {
            const allLocations = result.data;
            let html = '';
            for (let rak = 1; rak <= 8; rak++) {
                html += `
                    <div class="warehouse-rack-selector">
                        <div class="rack-selector-header">Rak ${rak}</div>
                        <div class="rack-selector-grid">
                `;
                
                for (let row = 1; row <= 4; row++) {
                    html += '<div class="rack-selector-row">';
                    for (let col = 1; col <= 6; col++) {
                        const position = `R${rak}-${row}-${col}`;
                        const locationData = allLocations.find(loc => loc.location === position);
                        const isOccupied = locationData ? !locationData.available : true; // If not found, assume occupied or invalid
                        const currentItemLocation = document.getElementById(window.currentTargetInput)?.value;
                        const isCurrentItemLocation = currentItemLocation === position;

                        let cellClass = 'rack-selector-cell';
                        let cellContent = position.split('-').slice(1).join('-'); // Default content

                        if (isOccupied && !isCurrentItemLocation) {
                            cellClass += ' occupied';
                            cellContent = '<i class="fas fa-times"></i>'; // Mark as occupied
                        } else if (isCurrentItemLocation) {
                            cellClass += ' selected'; // Highlight current item's location
                            cellContent = '<i class="fas fa-check"></i>'; // Mark as current
                        }
                        
                        html += `
                            <div class="${cellClass}" data-position="${position}" 
                                 onclick="window.selectRackPosition('${position}', ${isOccupied && !isCurrentItemLocation})">
                                ${cellContent}
                            </div>
                        `;
                    }
                    html += '</div>';
                }
                
                html += '</div></div>';
            }
            warehouseSelector.innerHTML = html;
            window.showAlert('success', 'Data lokasi gudang berhasil dimuat.');
        } else {
            window.showAlert('error', result.message || 'Gagal memuat data lokasi gudang.');
        }
    } catch (error) {
        console.error('Error fetching available locations:', error);
        window.showAlert('error', 'Kesalahan jaringan saat memuat lokasi gudang.');
    }
}

/**
 * Selects a rack position in the selector modal.
 * @param {string} position - The selected rack position.
 * @param {boolean} isOccupied - Whether the rack position is occupied.
 */
window.selectRackPosition = function(position, isOccupied) {
    if (isOccupied) {
        window.showAlert('warning', 'Lokasi ini sudah ditempati oleh barang lain.');
        return;
    }
    
    // Remove previous selection
    document.querySelectorAll('.rack-selector-cell.selected').forEach(cell => {
        // Only remove 'selected' if it's not the current item's original location
        const currentItemLocation = document.getElementById(window.currentTargetInput)?.value;
        if (cell.dataset.position !== currentItemLocation) {
            cell.classList.remove('selected');
            cell.innerHTML = cell.dataset.position.split('-').slice(1).join('-'); // Reset content
        }
    });
    
    // Add selection to clicked cell
    const cell = document.querySelector(`[data-position="${position}"]`);
    if (cell) {
        cell.classList.add('selected');
        cell.innerHTML = '<i class="fas fa-check"></i>'; // Mark as selected
        window.selectedRackPosition = position;
        
        // Enable confirm button
        const confirmBtn = document.getElementById('confirmRackBtn');
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.textContent = `Pilih ${position}`;
        }
    }
}

/**
 * Confirms rack selection and populates the target input.
 */
window.confirmRackSelection = async function() {
    if (window.selectedRackPosition && window.currentTargetInput) {
        const targetInput = document.getElementById(window.currentTargetInput);
        if (targetInput) {
            targetInput.value = window.selectedRackPosition;

            // If moving an existing item, send an update request
            if (window.currentItemIdForRack) {
                const itemId = window.currentItemIdForRack;
                const newLocation = window.selectedRackPosition;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                window.showAlert('info', `Memperbarui lokasi item #${itemId} ke ${newLocation}...`);

                try {
                    const itemResponse = await fetch(`/staff/incoming-items/${itemId}`);
                    if (!itemResponse.ok) {
                        const errorText = await itemResponse.text();
                        console.error('Server response for updateItemLocationToClickedRack (fetch existing) was not OK:', errorText);
                        window.showAlert('error', `Gagal memuat data item untuk pembaruan lokasi. Status: ${itemResponse.status}. Detail di konsol.`);
                        return;
                    }
                    const existingItemData = await itemResponse.json();

                    if (!existingItemData.success) {
                        window.showAlert('error', existingItemData.message || 'Gagal memuat data item untuk pembaruan lokasi.');
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('nama_barang', existingItemData.data.nama_barang);
                    formData.append('kategori_barang', existingItemData.data.kategori_barang);
                    formData.append('jumlah_barang', existingItemData.data.jumlah_barang);
                    formData.append('tanggal_masuk_barang', existingItemData.data.tanggal_masuk_barang);
                    formData.append('lokasi_rak_barang', newLocation); // Update only location
                    formData.append('nama_produsen', existingItemData.data.nama_produsen || '');
                    formData.append('metode_bayar', existingItemData.data.metode_bayar || '');
                    // For file fields, if not explicitly changed, retain existing path
                    formData.append('pembayaran_transaksi_existing', existingItemData.data.pembayaran_transaksi || '');
                    formData.append('nota_transaksi_existing', existingItemData.data.nota_transaksi || '');
                    formData.append('foto_barang_existing', existingItemData.data.foto_barang || '');


                    const response = await fetch(`/staff/incoming-items/${itemId}`, {
                        method: 'POST', // Laravel will interpret PUT via _method
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    // Enhanced error handling for fetch responses
                    if (!response.ok) {
                        let errorText = `HTTP error! status: ${response.status}`;
                        try {
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                const errorData = await response.json();
                                errorText = errorData.message || JSON.stringify(errorData);
                            } else {
                                errorText = await response.text();
                            }
                        }
                        catch (parseError) {
                            console.error('Error parsing response for non-OK status:', parseError);
                            errorText += ` (Failed to parse response: ${parseError.message})`;
                        }
                        console.error('Server response for updateItemLocationToClickedRack (update) was not OK:', errorText);
                        window.showAlert('error', `Gagal memperbarui lokasi item. Status: ${response.status}. Detail di konsol.`);
                        return;
                    }

                    const data = await response.json();

                    if (data.success) {
                        window.showAlert('success', data.message);
                        location.reload(); // Reload to reflect changes
                    } else {
                        window.showAlert('error', data.message || 'Gagal memperbarui lokasi item.');
                    }
                } catch (error) {
                    console.error('Error updating item location:', error);
                    window.showAlert('error', 'Kesalahan jaringan saat memperbarui lokasi item.');
                }
            }
        }
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('rackSelectorModal'));
        if (modal) modal.hide();
        
        // Reset selections
        window.selectedRackPosition = null;
        window.currentItemIdForRack = null;
    }
}

/**
 * Displays rack location on the item management page.
 * @param {string} rackPosition - The rack position to display.
 */
window.showRackLocation = function(rackPosition) {
    @if(Route::has('staff.item.management'))
        window.location.href = `{{ route('staff.item.management') }}?highlight=${rackPosition}`;
    @else
        window.showAlert('info', `Lokasi item: ${rackPosition}. Halaman manajemen gudang tidak tersedia.`);
    @endif
}

/**
 * Quickly assigns a rack location for an incoming item.
 * @param {number} itemId - Incoming item ID.
 */
window.quickAssignLocation = function(itemId) {
    window.showCustomConfirm('Apakah Anda yakin ingin secara otomatis menetapkan lokasi rak untuk item ini?', async () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        window.showAlert('info', 'Menetapkan lokasi secara otomatis...');

        try {
            const itemResponse = await fetch(`/staff/incoming-items/${itemId}`);
            if (!itemResponse.ok) {
                const errorText = await itemResponse.text();
                console.error('Server response for quickAssignLocation (fetch existing) was not OK:', errorText);
                window.showAlert('error', `Gagal memuat data item untuk penetapan lokasi otomatis. Status: ${itemResponse.status}. Detail di konsol.`);
                return;
            }
            const itemResult = await itemResponse.json();

            if (!itemResult.success) {
                window.showAlert('error', itemResult.message || 'Gagal memuat data item untuk penetapan lokasi otomatis.');
                return;
            }
            const existingItemData = itemResult.data;

            // Call auto-assign endpoint for this specific item
            const response = await fetch('{{ route("staff.items.auto_assign_locations") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    items: [{ id: itemId }], // Send the specific item to be auto-assigned
                    // No need to send 'location' here, backend will find it
                })
            });

            // Enhanced error handling for fetch responses
            if (!response.ok) {
                let errorText = `HTTP error! status: ${response.status}`;
                try {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const errorData = await response.json();
                        errorText = errorData.message || JSON.stringify(errorData);
                    } else {
                        errorText = await response.text();
                    }
                }
                catch (parseError) {
                    console.error('Error parsing response for non-OK status:', parseError);
                    errorText += ` (Failed to parse response: ${parseError.message})`;
                }
                console.error('Server response for auto_assign_locations was not OK:', errorText);
                window.showAlert('error', `Gagal menetapkan lokasi secara otomatis. Status: ${response.status}. Detail di konsol.`);
                return;
            }

            const data = await response.json();

            if (data.success) {
                window.showAlert('success', data.message);
                location.reload();
            } else {
                window.showAlert('error', data.message || 'Gagal menetapkan lokasi secara otomatis.');
            }
        } catch (error) {
            console.error('Quick assign error:', error);
            window.showAlert('error', 'Kesalahan jaringan saat menetapkan lokasi.');
        }
    });
}

/**
 * Moves an incoming item to the outgoing items list.
 * @param {number} itemId - Incoming item ID.
 */
window.moveToOutgoing = function(itemId) {
    window.showCustomConfirm('Apakah Anda yakin ingin memindahkan item ini ke daftar barang keluar? Ini akan mengurangi stok barang masuk dan menambahkannya ke barang keluar.', async () => {
        try {
            const response = await fetch(`/staff/incoming-items/${itemId}`);
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server response for moveToOutgoing (fetch incoming) was not OK:', errorText);
                window.showAlert('error', `Gagal memuat data item untuk dipindahkan. Status: ${response.status}. Detail di konsol.`);
                return;
            }
            const result = await response.json();

            if (result.success) {
                const itemData = result.data;
                // Switch to add-items tab
                const addTabButton = document.getElementById('add-items-tab');
                if (addTabButton) {
                    addTabButton.click();
                }
                
                // Render the outgoing form in 'add' mode with incoming item data
                window.renderItemCrudForm('outgoing', 'add', itemData);
                // Pre-select the item in the dropdown
                const crudNamaBarangSelect = document.getElementById('crud_nama_barang');
                if (crudNamaBarangSelect) {
                    // Find the option by value (nama_barang) and set it as selected
                    for (let i = 0; i < crudNamaBarangSelect.options.length; i++) {
                        if (crudNamaBarangSelect.options[i].value === itemData.nama_barang) {
                            crudNamaBarangSelect.selectedIndex = i;
                            crudNamaBarangSelect.dispatchEvent(new Event('change')); // Trigger change to populate other fields
                            break;
                        }
                    }
                }
                window.showAlert('info', `Harap lengkapi detail untuk memproses barang keluar "${itemData.nama_barang}".`);
            } else {
                window.showAlert('error', result.message || 'Gagal memuat data item untuk dipindahkan.');
            }
        } catch (error) {
            console.error('Error fetching item for move to outgoing:', error);
            window.showAlert('error', 'Kesalahan jaringan saat memuat data item.');
        }
    });
}

/**
 * Performs bulk actions (edit location or delete) on incoming items.
 */
window.bulkEditLocation = function() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    const itemIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (itemIds.length === 0) {
        window.showAlert('warning', 'Harap pilih setidaknya satu item untuk diedit.');
        return;
    }
    
    window.showCustomConfirm(`Apakah Anda yakin ingin mengedit lokasi ${itemIds.length} item yang dipilih?`, () => {
        const newLocation = prompt('Masukkan lokasi rak baru (Format: R[1-8]-[1-4]-[1-6]):');
        if (newLocation) {
            const pattern = /^R[1-8]-[1-4]-[1-6]$/;
            if (pattern.test(newLocation)) {
                window.sendBulkAction(itemIds, 'update_location', { location: newLocation });
            } else {
                window.showAlert('error', 'Format lokasi tidak valid! Gunakan format: R[1-8]-[1-4]-[1-6]');
            }
        }
    });
}

window.bulkDelete = function() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    const itemIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (itemIds.length === 0) {
        window.showAlert('warning', 'Harap pilih setidaknya satu item untuk dihapus.');
        return;
    }
    
    window.showCustomConfirm(`Apakah Anda yakin ingin menghapus ${itemIds.length} item yang dipilih? Tindakan ini tidak dapat dibatalkan.`, () => {
        window.sendBulkAction(itemIds, 'delete');
    });
}

/**
 * Sends a bulk action request to the backend.
 * @param {Array<number>} itemIds - Array of item IDs.
 * @param {string} action - Type of action ('update_category', 'update_location', 'delete').
 * @param {object} payload - Additional data for the action (e.g., { category: 'New Category' }).
 */
window.sendBulkAction = async function(itemIds, action, payload = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    window.showAlert('info', `Memproses aksi massal (${action})...`);

    try {
        const response = await fetch('{{ route("staff.items.bulk_update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                items: itemIds.map(id => ({ id: id })),
                action: action,
                ...payload
            })
        });

        // Enhanced error handling for fetch responses
        if (!response.ok) {
            let errorText = `HTTP error! status: ${response.status}`;
            try {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const errorData = await response.json();
                    errorText = errorData.message || JSON.stringify(errorData);
                } else {
                    errorText = await response.text();
                }
            }
            catch (parseError) {
                console.error('Error parsing response for non-OK status:', parseError);
                errorText += ` (Failed to parse response: ${parseError.message})`;
            }
            console.error('Server response for bulk_update was not OK:', errorText);
            window.showAlert('error', `Gagal melakukan aksi massal. Status: ${response.status}. Detail di konsol.`);
            return;
        }

        const data = await response.json();

        if (data.success) {
            window.showAlert('success', data.message);
            location.reload();
        } else {
            let errorMessage = data.message || 'Gagal melakukan aksi massal.';
            if (data.errors) {
                for (const key in data.errors) {
                    errorMessage += `\n- ${data.errors[key][0]}`;
                }
            }
            window.showAlert('error', errorMessage);
        }
    } catch (error) {
        console.error('Bulk action error:', error);
        window.showAlert('error', 'Kesalahan jaringan saat melakukan aksi massal.');
    }
}

// Placeholder functions for other actions
window.printDeliveryNote = function(itemId) {
    window.showAlert('info', 'Menyiapkan surat jalan untuk dicetak...');
    // Implement actual print logic or API call here
    setTimeout(() => {
        window.showAlert('success', 'Surat jalan berhasil dicetak!');
    }, 1500);
}

window.trackDelivery = function(itemId) {
    window.showAlert('info', 'Fitur pelacakan pengiriman segera hadir!');
}

window.updateDeliveryStatus = function(itemId) {
    window.showCustomConfirm('Apakah Anda ingin memperbarui status pengiriman?', () => {
        const newStatus = prompt('Masukkan status baru (Pending, In Transit, Completed):');
        if (newStatus) {
            window.showAlert('success', `Status pengiriman berhasil diubah menjadi: ${newStatus}`);
            // Implement AJAX call to update outgoing item status
            // location.reload();
        }
    });
}

window.exportData = function(format) {
    const activeTab = document.querySelector('.nav-link.active').textContent.trim();
    window.showAlert('info', `Mengekspor data ${activeTab} ke ${format.toUpperCase()}...`);
    
    // Implement actual export logic or redirect to export route
    // Example: window.location.href = `{{ route('staff.items.export_csv') }}?type=incoming&format=${format}`;
    
    setTimeout(() => {
        window.showAlert('success', `Data berhasil diekspor ke ${format.toUpperCase()}!`);
    }, 2000);
}

window.refreshData = function() {
    window.showAlert('info', 'Memuat ulang data...');
    setTimeout(() => {
        location.reload();
    }, 1000);
}

window.importFromCSV = function() {
    const modal = new bootstrap.Modal(document.getElementById('importCSVModal'));
    modal.show();
}

window.processCSVImport = async function() {
    const csvFile = document.getElementById('csvFile');
    if (!csvFile.files[0]) {
        window.showAlert('error', 'Harap pilih file CSV terlebih dahulu.');
        return;
    }
    
    const formData = new FormData();
    formData.append('csv_file', csvFile.files[0]);
    formData.append('has_header', document.getElementById('hasHeader').checked ? '1' : '0');
    // Determine type based on active tab or a new input in the modal
    const activeTabId = document.querySelector('#itemTabs .nav-link.active').id;
    let importType = 'incoming';
    if (activeTabId === 'outgoing-items-tab') {
        importType = 'outgoing';
    }
    formData.append('type', importType);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    window.showAlert('info', 'Memproses impor data...');
    
    try {
        const response = await fetch('{{ route("staff.items.import_csv") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
            // No 'Content-Type' header when sending FormData, browser sets it
        });
        // Enhanced error handling for fetch responses
        if (!response.ok) {
            let errorText = `HTTP error! status: ${response.status}`;
            try {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const errorData = await response.json();
                    errorText = errorData.message || JSON.stringify(errorData);
                } else {
                    errorText = await response.text();
                }
            }
            catch (parseError) {
                console.error('Error parsing response for non-OK status:', parseError);
                errorText += ` (Failed to parse response: ${parseError.message})`;
            }
            console.error('Server response for import_csv was not OK:', errorText);
            window.showAlert('error', `Gagal mengimpor data. Status: ${response.status}. Detail di konsol.`);
            return;
        }

        const data = await response.json();

        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('importCSVModal'));
            if (modal) modal.hide();
            window.showAlert('success', data.message + (data.errors.length > 0 ? ` Beberapa kesalahan terjadi: ${data.errors.join(', ')}` : ''));
            location.reload();
        } else {
            let errorMessage = data.message || 'Gagal mengimpor data.';
            if (data.errors) {
                errorMessage += `\n- ${data.errors.join('\n- ')}`;
            }
            window.showAlert('error', errorMessage);
        }
    } catch (error) {
        console.error('Import CSV Error:', error);
        window.showAlert('error', 'Kesalahan jaringan saat mengimpor CSV.');
    }
}

window.downloadCSVTemplate = function() {
    // Updated template to reflect new columns
    const csvContent = "nama_barang,kategori_barang,jumlah_barang,tanggal_masuk_barang,lokasi_rak_barang,nama_produsen,metode_bayar,pembayaran_transaksi,nota_transaksi,foto_barang\n" +
                      "Contoh Barang,Makanan,100,2024-01-01,R1-1-1,Toko ABC,Cash,path/to/payment.jpg,path/to/invoice.pdf,images/chitato.jpg\n" +
                      "Contoh Barang 2,Minuman,50,2024-01-01,R1-1-2,Supplier XYZ,Transfer Bank,,path/to/invoice2.png,"; // Example with no payment photo
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'template_barang_masuk.csv';
    a.click();
    window.URL.revokeObjectURL(url);
    window.showAlert('info', 'Template CSV berhasil diunduh.');
}

window.generateBarcode = function() {
    window.showAlert('info', 'Fitur pembuatan Barcode segera hadir!');
}

window.stockOpname = function() {
    window.showAlert('info', 'Fitur stock opname segera hadir!');
}

window.viewWarehouse = function() {
    @if(Route::has('staff.warehouse_monitor'))
        window.open('{{ route("staff.warehouse_monitor") }}', '_blank');
    @else
        window.showAlert('info', 'Halaman monitor gudang tidak tersedia.');
    @endif
}

/**
 * Helper function to format numbers as currency.
 * @param {number} amount - The number to format.
 * @param {number} decimals - Number of decimal places.
 * @param {string} decPoint - Decimal point character.
 * @param {string} thousandsSep - Thousands separator character.
 * @returns {string} Formatted number.
 */
window.number_format = function(amount, decimals, decPoint, thousandsSep) {
    amount = (amount + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+amount) ? 0 : +amount,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousandsSep === 'undefined') ? '.' : thousandsSep,
        dec = (typeof decPoint === 'undefined') ? ',' : decPoint,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };

    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}


/**
 * Displays a custom alert message.
 * @param {string} type - Alert type ('success', 'error', 'warning', 'info').
 * @param {string} message - The message to display.
 */
window.showAlert = function(type, message) {
    // Remove any existing custom alerts to prevent stacking
    const existingAlert = document.querySelector('.custom-fixed-alert');
    if (existingAlert) {
        existingAlert.remove();
    }

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show custom-fixed-alert`;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.style.maxWidth = '400px';
    alertDiv.style.opacity = '0'; // Start invisible for animation
    alertDiv.style.transform = 'translateY(-20px)'; // Start slightly above for animation
    alertDiv.style.transition = 'all 0.5s ease'; // Smooth transition

    const iconMap = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    };
    
    alertDiv.innerHTML = `
        <i class="${iconMap[type] || 'fas fa-info-circle'}"></i>
        <div>${message.replace(/\n/g, '<br>')}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Trigger fade-in animation
    setTimeout(() => {
        alertDiv.style.opacity = '1';
        alertDiv.style.transform = 'translateY(0)';
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.style.opacity = '0';
            alertDiv.style.transform = 'translateY(-20px)';
            setTimeout(() => alertDiv.remove(), 500); // Remove after transition
        }
    }, 5000);
}

/**
 * Displays a custom confirmation modal.
 * @param {string} message - The confirmation message.
 * @param {Function} onConfirm - Callback when the user confirms.
 */
window.showCustomConfirm = function(message, onConfirm) {
    const confirmModal = new bootstrap.Modal(document.getElementById('customConfirmModal'));
    const confirmMessage = document.getElementById('customConfirmMessage');
    const confirmBtn = document.getElementById('customConfirmActionBtn');

    confirmMessage.textContent = message;
    
    // Clear previous event listener
    confirmBtn.onclick = null; 
    
    // Set new event listener
    confirmBtn.onclick = () => {
        onConfirm();
        confirmModal.hide();
    };

    confirmModal.show();
}

window.printItemDetails = function() {
    window.print();
}

window.duplicateItem = async function(itemId) {
    window.showCustomConfirm('Apakah Anda yakin ingin menduplikasi item ini?', async () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        window.showAlert('info', 'Menduplikasi item...');

        try {
            const response = await fetch(`/staff/items/${itemId}/duplicate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            // Enhanced error handling for fetch responses
            if (!response.ok) {
                let errorText = `HTTP error! status: ${response.status}`;
                try {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const errorData = await response.json();
                        errorText = errorData.message || JSON.stringify(errorData);
                    } else {
                        errorText = await response.text();
                    }
                }
                catch (parseError) {
                    console.error('Error parsing response for non-OK status:', parseError);
                    errorText += ` (Failed to parse response: ${parseError.message})`;
                }
                console.error('Server response for duplicateItem was not OK:', errorText);
                window.showAlert('error', `Gagal menduplikasi item. Status: ${response.status}. Detail di konsol.`);
                return;
            }

            const data = await response.json();

            if (data.success) {
                window.showAlert('success', data.message);
                location.reload();
            } else {
                window.showAlert('error', data.message || 'Gagal menduplikasi item.');
            }
        } catch (error) {
            console.error('Duplicate item error:', error);
            window.showAlert('error', 'Kesalahan jaringan saat menduplikasi item.');
        }
    });
}

window.generateQR = function(itemId) {
    window.showAlert('info', 'Membuat Kode QR...');
    // Implement actual QR code generation logic or API call here
    setTimeout(() => {
        window.showAlert('success', 'Kode QR berhasil dibuat!');
    }, 1500);
}

/**
 * Placeholder for "Ajukan Pergantian Barang" action.
 */
window.ajukanPergantianBarang = function() {
    window.showAlert('info', 'Fitur "Permintaan Penggantian Barang" segera hadir!');
    // Implement logic for "Ajukan Pergantian Barang" here
};

// --- NEW VERIFICATION LOGIC ---

// Fungsi untuk mengambil data barang yang belum diverifikasi untuk modal
function fetchPendingVerificationItemsForModal() {
    const tableBody = document.getElementById('pendingItemsTableBody');
    if (!tableBody) {
        console.error('Table body element not found!');
        return;
    }

    // Show loading state
    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Loading...</td></tr>';

    fetch('/staff/items/pending-verification')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada barang yang perlu diverifikasi</td></tr>';
                    return;
                }

                tableBody.innerHTML = '';
                data.data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.nama_barang}</td>
                        <td>${item.kategori_barang || '-'}</td>
                        <td>${item.jumlah_barang}</td>
                        <td>${item.nama_produsen || '-'}</td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="selectItemForVerification(${item.id}, '${item.nama_barang}', '${item.producer_id || ''}', ${item.jumlah_barang})">
                                Pilih
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                throw new Error(data.message || 'Failed to fetch data');
            }
        })
        .catch(error => {
            console.error('Error fetching pending verification items:', error);
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error: ${error.message}</td></tr>`;
        });
}

// Add event listener to modal show
document.getElementById('selectItemModal').addEventListener('show.bs.modal', function (event) {
    fetchPendingVerificationItemsForModal();
});

// Handle form submission for verification
const verificationForm = document.getElementById('verificationForm');
if (verificationForm) {
    verificationForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const itemId = document.getElementById('verify_item_id').value;
        if (!itemId) {
            window.showAlert('error', 'Silakan pilih barang yang akan diverifikasi terlebih dahulu.');
            return;
        }

        const formData = new FormData(this);
        
        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

        fetch(`/verify-incoming-item/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close verification modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('selectItemModal'));
                if (modal) modal.hide();

                // Show appropriate message based on status
                if (data.status === 'deleted') {
                    window.showAlert('warning', data.message);
                } else {
                    window.showAlert('success', data.message);
                }

                // Reset form
                this.reset();
                
                // Refresh the page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat verifikasi barang.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.showAlert('error', error.message || 'Terjadi kesalahan saat verifikasi barang.');
        })
        .finally(() => {
            // Restore button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    });
}

function selectItemForVerification(id, namaBarang, producerId, jumlahBarang) {
    // Fill the verification form
    document.getElementById('verify_item_id').value = id;
    document.getElementById('verify_producer_id').value = producerId || '';
    document.getElementById('verify_nama_barang').value = namaBarang;
    document.getElementById('verify_jumlah_barang').value = jumlahBarang;
    
    // Enable satuan_barang select
    document.getElementById('verify_satuan_barang').disabled = false;
    
    // Close the modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('selectItemModal'));
    modal.hide();
}

function submitIncomingItemForm(formData) {
    const submitButton = document.querySelector('#incomingItemForm button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

    fetch('/staff/incoming-items', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            window.showAlert('success', data.message);
            
            // Reset form
            document.getElementById('incomingItemForm').reset();
            
            // Clear any previous error messages
            clearFormErrors();
            
            // Refresh the page after a short delay to show the updated list
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan saat menyimpan data.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        if (error.errors) {
            // Show validation errors
            showFormErrors(error.errors);
        } else {
            // Show general error message
            window.showAlert('error', error.message || 'Terjadi kesalahan saat menyimpan data.');
        }
    })
    .finally(() => {
        // Restore button state
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

function showFormErrors(errors) {
    clearFormErrors();
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = errors[field][0];
            input.parentNode.appendChild(feedback);
        }
    });
}

function clearFormErrors() {
    document.querySelectorAll('.is-invalid').forEach(input => {
        input.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(feedback => {
        feedback.remove();
    });
}
</script>
@endpush
