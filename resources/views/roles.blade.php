@extends('layouts.app')

@section('title', 'Manajemen Role - InvenCheck')

@section('styles')
    <link rel="stylesheet" href="{{ asset('pengaturan.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div>
            <h1>Manajemen Role</h1>
            <p>Atur role dan hak akses pengguna sistem</p>
        </div>
        <button class="btn-primary" onclick="document.getElementById('modalTambahRole').classList.add('show')">+ Tambah Role</button>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="panel">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>Nama Role</th>
                    <th>Jumlah Izin</th>
                    <th>Jumlah Pengguna</th>
                    <th class="th-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>
                            {{ $role->name }}
                            @if(in_array($role->slug, ['admin', 'staff', 'user']))
                                <span class="tag tag-muted" style="margin-left: 6px;">Bawaan</span>
                            @endif
                        </td>
                        <td class="mono">{{ $role->permissions->count() }} izin</td>
                        <td class="mono">{{ $role->users_count }} pengguna</td>
                        <td class="th-center">
                            <div class="table-actions">
                                <button class="btn-icon" title="Edit" onclick='bukaModalEdit(@json($role->load("permissions")))'>✎</button>
                                @unless(in_array($role->slug, ['admin', 'staff', 'user']))
                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}" onsubmit="return confirm('Yakin hapus role {{ $role->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon danger" title="Hapus">🗑</button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL TAMBAH ROLE --}}
    <div class="modal-overlay" id="modalTambahRole">
        <div class="modal-box wide">
            <div class="modal-head">
                <h3>Tambah Role</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalTambahRole').classList.remove('show')">✕</button>
            </div>

            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="form-group" style="margin-bottom: 18px;">
                    <label>Nama Role</label>
                    <input type="text" name="name" required placeholder="Misal: Supervisor">
                </div>

                <label style="font-size: 12.5px; font-weight: 600; color: var(--ink); display: block; margin-bottom: 10px;">Hak Akses</label>

                <div class="permission-groups">
                    @foreach($permissions as $group => $items)
                        <div class="permission-group">
                            <div class="permission-group-title">{{ $group }}</div>
                            @foreach($items as $permission)
                                <label class="permission-item">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn-primary full">Simpan Role</button>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT ROLE --}}
    <div class="modal-overlay" id="modalEditRole">
        <div class="modal-box wide">
            <div class="modal-head">
                <h3>Edit Role</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalEditRole').classList.remove('show')">✕</button>
            </div>

            <form method="POST" id="formEditRole">
                @csrf
                @method('PUT')
                <div class="form-group" style="margin-bottom: 18px;">
                    <label>Nama Role</label>
                    <input type="text" name="name" id="edit_role_name" required>
                </div>

                <label style="font-size: 12.5px; font-weight: 600; color: var(--ink); display: block; margin-bottom: 10px;">Hak Akses</label>

                <div class="permission-groups">
                    @foreach($permissions as $group => $items)
                        <div class="permission-group">
                            <div class="permission-group-title">{{ $group }}</div>
                            @foreach($items as $permission)
                                <label class="permission-item">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="edit-permission-checkbox" data-id="{{ $permission->id }}">
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn-primary full">Update Role</button>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function bukaModalEdit(role) {
        document.getElementById('formEditRole').action = '/roles/' + role.id;
        document.getElementById('edit_role_name').value = role.name;

        const permissionIds = role.permissions.map(p => p.id);
        document.querySelectorAll('.edit-permission-checkbox').forEach(checkbox => {
            checkbox.checked = permissionIds.includes(parseInt(checkbox.dataset.id));
        });

        document.getElementById('modalEditRole').classList.add('show');
    }
</script>
@endsection