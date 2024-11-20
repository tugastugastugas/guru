<div class="row" style="padding: 20px;">
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h5>Filter Laporan</h5>
                </div>
                <form method="GET" action="{{ route('printLaporan') }}">
                    <div class="mt-3">
                        <label for="id_periode">
                            <h4>Pilih Periode:</h4>
                        </label>
                        <select id="id_periode" name="id_periode" class="form-control" required>
                            <option value="">-- Pilih Periode --</option>
                            @foreach($periodes as $periode)
                            <option value="{{ $periode->id_periode }}" {{ request('id_periode') == $periode->id_periode ? 'selected' : '' }}>
                                {{ $periode->nama_periode }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>