@if(session('notification'))
<div class="alert alert-info">
    {{ session('notification') }}
</div>
@endif

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Ulasan</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>Nama Guru</th>
                                <th>Mapel</th>
                                <th>Ulasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guru as $sm)
                            <tr>
                                <td>{{ $sm->nama_guru }}</td>
                                <td>{{ $sm->mapel_guru }}</td>
                                <td>
                                    @if($sm->id_ulasan)
                                        <span class="badge bg-success">Sudah Dinilai</span>
                                        <button type="button" class="btn btn-outline-primary edit-ulasan"
                                            data-bs-toggle="modal" data-bs-target="#editUlasanModal"
                                            data-id_ulasan="{{ $sm->id_ulasan }}"
                                            data-kritikan="{{ $sm->kritikan }}"
                                            data-pujian="{{ $sm->pujian }}">
                                            Edit Ulasan
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary edit-barang"
                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id_guru="{{ $sm->id_guru }}"
                                            data-nama_guru="{{ $sm->nama_guru }}"
                                            data-mapel_guru="{{ $sm->mapel_guru }}">
                                            Nilai Guru
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Nama Guru</th>
                                <th>Mapel</th>
                                <th>Ulasan</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Nilai Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('GET')
                    <input type="hidden" name="id_guru" id="edit-id_guru">
                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama Guru</label>
                        <input type="text" class="form-control" id="edit-nama_guru" name="nama_guru" disabled required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-harga_paket" class="form-label">Mapel Guru</label>
                        <input type="text" class="form-control" id="edit-mapel_guru" name="mapel_guru" disabled required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-harga_paket" class="form-label">Kritikan</label>
                        <input type="text" class="form-control" id="edit-kritikan" name="kritikan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-harga_paket" class="form-label">Pujian</label>
                        <input type="text" class="form-control" id="edit-pujian" name="pujian" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUlasanModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Ulasan Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('ganti_ulasan') }}" method="POST" id="editForm">
                    @csrf
                    @method('GET')
                    <input type="hidden" name="id_ulasan" id="edit-id_ulasan">
                    <input type="hidden" name="id_guru" id="edit-id_guru">
                    <div class="mb-3">
                        <label for="edit-kritikan" class="form-label">Kritikan</label>
                        <input type="text" class="form-control" id="edit-kritikan" name="kritikan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-pujian" class="form-label">Pujian</label>
                        <input type="text" class="form-control" id="edit-pujian" name="pujian" required>
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
        $('#editForm').attr('action', '{{ route("buat_ulasan") }}');

        // Isi nilai input di modal edit
        $('#edit-id_guru').val(id_guru);
        $('#edit-nama_guru').val(nama_guru);
        $('#edit-mapel_guru').val(mapel_guru);
    });
</script>

<script>
    $(document).on('click', '.edit-ulasan', function() {
    let id_ulasan = $(this).data('id_ulasan');
    let kritikan = $(this).data('kritikan');
    let pujian = $(this).data('pujian');


    // Isi nilai input di modal "Edit Ulasan"
    $('#editUlasanModal #edit-id_ulasan').val(id_ulasan);
    $('#editUlasanModal #edit-kritikan').val(kritikan);
    $('#editUlasanModal #edit-pujian').val(pujian);

    // Tampilkan modal
    $('#editUlasanModal').modal('show');
});


</script>