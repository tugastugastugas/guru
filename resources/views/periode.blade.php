<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Periode</h4>
                    <br>
                    <button type="button" class="btn btn-outline-primary kirim-surat" data-bs-toggle="modal" data-bs-target="#folderModal">
                        Buat Periode Baru
                    </button>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>Nama Periode</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Akhir</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($periode as $sm)
                            <tr>
                                <td>{{ $sm->nama_periode }}</td>
                                <td>{{ $sm->tgl_mulai }}</td>
                                <td>{{ $sm->tgl_akhir }}</td>
                                <td>{{ $sm->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-outline-secondary edit-barang"
                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id_periode="{{ $sm->id_periode }}"
                                        data-nama_periode="{{ $sm->nama_periode }}"
                                        data-tgl_mulai="{{ $sm->tgl_mulai }}"
                                        data-tgl_akhir="{{ $sm->tgl_akhir }}"
                                        data-status="{{ $sm->status }}">
                                        Edit Periode
                                    </button>

                                    <form action="{{ route('periode.destroy', $sm->id_periode) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Hapus</button>
                                    </form>
                                    @php
                                    $status = $sm->status;
                                    @endphp

                                    @if($status == "AKTIF")
                                    <form action="{{ route('periode.tidak_aktif', $sm->id_periode) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('GET')
                                        <button class="btn btn-danger btn-sm" type="submit">NON AKTIF</button>
                                    </form>
                                    @else
                                    <form action="{{ route('periode.aktif', $sm->id_periode) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('GET')
                                        <button class="btn btn-success btn-sm" type="submit">AKTIF</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Nama Periode</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Akhir</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="folderModal" tabindex="-1" aria-labelledby="folderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="folderModalLabel">Buat Periode Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('buat_periode') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_folder" class="form-label">Nama Periode</label>
                        <input type="text" class="form-control" id="nama_periode" name="nama_periode" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_folder" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_folder" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Periode</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('GET')
                    <input type="hidden" name="id_periode" id="edit-id_periode">
                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama Periode</label>
                        <input type="text" class="form-control" id="edit-nama_periode" name="nama_periode" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-harga_paket" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="edit-tgl_mulai" name="tgl_mulai" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-deskripsi_paket" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="edit-tgl_akhir" name="tgl_akhir" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Mengisi Data di Modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.edit-barang', function() {
        // Ambil data dari atribut tombol Edit
        let id_periode = $(this).data('id_periode');
        let nama_periode = $(this).data('nama_periode');
        let tgl_mulai = $(this).data('tgl_mulai');
        let tgl_akhir = $(this).data('tgl_akhir');

        // Set nilai form action untuk edit
        $('#editForm').attr('action', '{{ route("periode.update", ":id") }}'.replace(':id', id_periode));

        // Isi nilai input di modal edit
        $('#edit-nama_periode').val(nama_periode);
        $('#edit-tgl_mulai').val(tgl_mulai);
        $('#edit-tgl_akhir').val(tgl_akhir);
    });
</script>