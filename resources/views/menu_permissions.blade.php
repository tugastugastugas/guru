<div class="container-fluid py-4" style="margin-top: 60px;">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4" style="padding: 20px;">

                <div class="container">
                    <h1>Set Menu Permissions for {{ $userLevel }}</h1>
                    <form action="{{ route('save.permissions') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_level" value="{{ $userLevel }}">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="menus[]" value="Setting"
                                    {{ isset($permissions['Setting']) && $permissions['Setting'] ? 'checked' : '' }}>
                                Setting
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="menus[]" value="Data"
                                    {{ isset($permissions['Data']) && $permissions['Data'] ? 'checked' : '' }}>
                                Data
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="menus[]" value="UlasanGuru"
                                    {{ isset($permissions['UlasanGuru']) && $permissions['UlasanGuru'] ? 'checked' : '' }}>
                                UlasanGuru
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="menus[]" value="Ulasan"
                                    {{ isset($permissions['Ulasan']) && $permissions['Ulasan'] ? 'checked' : '' }}>
                                Ulasan
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="menus[]" value="UlasanSaya"
                                    {{ isset($permissions['UlasanSaya']) && $permissions['UlasanSaya'] ? 'checked' : '' }}>
                                UlasanSaya
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Permissions</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>