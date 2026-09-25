@extends('layouts.app')

@section('title', 'Kategori - InvenCheck')

@section('styles')
    <link rel="stylesheet" href="{{ asset('kategori.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div>
            <h1>Kategori Barang</h1>
            <p>Kelompok barang berdasarkan jenisnya</p>
        </div>
        @if(auth()->user()->hasPermission('manage_categories'))
        <button class="btn-primary" onclick="document.getElementById('modalTambah').classList.add('show')">+ Tambah Kategori</button>
        @endif
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="category-grid">

        @foreach($categories as $category)
            <div class="category-card">
                <div class="category-icon">{{ $category->icon ?? '📦' }}</div>
                <div class="category-body">
                    <h3>{{ $category->name }}</h3>
                    <p class="category-count">{{ $category->items_count }} jenis barang</p>
                </div>
                <div class="category-stats">
                    <div class="category-stat">
                        <span class="stat-num">{{ number_format($category->total_stock, 0, ',', '.') }}</span>
                        <span class="stat-lbl">Total Stok</span>
                    </div>
                    @if($category->habis_count > 0)
                        <div class="category-stat danger">
                            <span class="stat-num">{{ $category->habis_count }}</span>
                            <span class="stat-lbl">Habis</span>
                        </div>
                    @else
                        <div class="category-stat {{ $category->menipis_count > 0 ? 'warn' : 'ok' }}">
                            <span class="stat-num">{{ $category->menipis_count }}</span>
                            <span class="stat-lbl">Menipis</span>
                        </div>
                    @endif
                </div>
                @if(auth()->user()->hasPermission('manage_categories'))
                <div class="category-actions">
                    <button class="btn-icon" title="Edit" onclick='bukaModalEdit(@json($category))'>✎</button>
                    <form method="POST" action="{{ route('kategori.destroy', $category->id) }}" onsubmit="return confirm('Yakin hapus kategori {{ $category->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon danger" title="Hapus">🗑</button>
                    </form>
                </div>
                @endif
            </div>
        @endforeach

        <!-- <button class="category-card add-card" onclick="document.getElementById('modalTambah').classList.add('show')" type="button">
            <span class="add-icon">+</span>
            <span>Tambah Kategori Baru</span>
        </button> -->

    </div>

    {{-- MODAL TAMBAH --}}
    <div class="modal-overlay" id="modalTambah">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Tambah Kategori</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalTambah').classList.remove('show')">✕</button>
            </div>

            <form method="POST" action="{{ route('kategori.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" required placeholder="Misal: Elektronik">
                    </div>
                    <div class="form-group">
                        <label>Icon (emoji, opsional)</label>
                        <input type="text" name="icon" placeholder="⚡ 🧱 🦺 dll" maxlength="10">
                    </div>
                </div>
                <button type="submit" class="btn-primary full">Simpan Kategori</button>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal-overlay" id="modalEdit">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Edit Kategori</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalEdit').classList.remove('show')">✕</button>
            </div>

            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" id="edit_name" required>
                    </div>
                    <div class="form-group">
                        <label>Icon (emoji, opsional)</label>
                        <input type="text" name="icon" id="edit_icon" maxlength="10">
                    </div>
                </div>
                <button type="submit" class="btn-primary full">Update Kategori</button>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function bukaModalEdit(category) {
        document.getElementById('formEdit').action = '/kategori/' + category.id;
        document.getElementById('edit_name').value = category.name;
        document.getElementById('edit_icon').value = category.icon ?? '';
        document.getElementById('modalEdit').classList.add('show');
    }
</script>
@endsection