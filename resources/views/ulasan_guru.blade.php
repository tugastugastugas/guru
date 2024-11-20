<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">History</h4>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="periodeTabs" role="tablist">
                    <!-- Dynamically generate tabs for each periode -->
                    @foreach($ulasan->groupBy('nama_periode') as $periode => $items)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ Str::slug($periode) }}-tab" data-bs-toggle="tab" href="#tab-{{ Str::slug($periode) }}" role="tab" aria-controls="tab-{{ Str::slug($periode) }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $periode }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="periodeTabsContent">
                    <!-- Dynamically generate tab content for each periode -->
                    @foreach($ulasan->groupBy('nama_periode') as $periode => $items)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ Str::slug($periode) }}" role="tabpanel" aria-labelledby="tab-{{ Str::slug($periode) }}-tab">
                        <table id="datatable" class="table table-striped" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Nama Guru</th>
                                    <th>Mapel</th>
                                    <th>Kritikan</th>
                                    <th>Pujian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $sm)
                                <tr>
                                    <td>{{ $sm->username }}</td>
                                    <td>{{ $sm->nama_guru }}</td>
                                    <td>{{ $sm->mapel_guru }}</td>
                                    <td>{{ $sm->kritikan }}</td>
                                    <td>{{ $sm->pujian }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>