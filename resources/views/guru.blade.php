<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Guru</h4>
                    <br>
                    <button type="button" class="btn btn-outline-primary kirim-surat" data-bs-toggle="modal" data-bs-target="#folderModal">
                        Buat Guru Baru
                    </button>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>Nama Guru</th>
                                <th>Mapel</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guru as $sm)
                            <tr>
                                <td>{{ $sm->nama_guru }}</td>
                                <td>{{ $sm->mapel_guru }}</td>
                                <td>
                                    <button type="button" class="btn btn-outline-secondary edit-barang"
                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id_guru="{{ $sm->id_guru }}"
                                        data-nama_guru="{{ $sm->nama_guru }}"
                                        data-mapel_guru="{{ $sm->mapel_guru }}">
                                        Edit Guru
                                    </button>

                                    <form action="{{ route('guru.destroy', $sm->id_guru) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Nama Guru</th>
                                <th>Mapel</th>
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
                <h5 class="modal-title" id="folderModalLabel">Buat Guru Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('buat_guru') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_folder" class="form-label">Nama Guru</label>
                        <input type="text" class="form-control" id="nama_guru" name="nama_guru" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_folder" class="form-label">Mapel Guru</label>
                        <input type="text" class="form-control" id="mapel_guru" name="mapel_guru" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Guru</button>
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
                <h5 class="modal-title" id="editModalLabel">Edit Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('GET')
                    <input type="hidden" name="id_guru" id="edit-id_guru">
                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama Guru</label>
                        <input type="text" class="form-control" id="edit-nama_guru" name="nama_guru" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-harga_paket" class="form-label">Mapel Guru</label>
                        <input type="text" class="form-control" id="edit-mapel_guru" name="mapel_guru" required>
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
        let id_guru = $(this).data('id_guru');
        let nama_guru = $(this).data('nama_guru');
        let mapel_guru = $(this).data('mapel_guru');

        // Set nilai form action untuk edit
        $('#editForm').attr('action', '{{ route("guru.update", ":id") }}'.replace(':id', id_guru));

        // Isi nilai input di modal edit
        $('#edit-nama_guru').val(nama_guru);
        $('#edit-mapel_guru').val(mapel_guru);
    });
</script>