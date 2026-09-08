<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota</title>
</head>
<body>
    <h1>Tambah Anggota Baru</h1>

    {{-- Alert jika terdapat error validasi --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('members.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 10px;">
            <label for="nim">NIM:</label><br>
            <input type="text" id="nim" name="nim" value="{{ old('nim') }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="nama">Nama Lengkap:</label><br>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="nomor_telepon">Nomor Telepon:</label><br>
            <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="alamat">Alamat:</label><br>
            <textarea id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="status">Status:</label><br>
            <select id="status" name="status" required>
                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <button type="submit">Simpan</button>
        <a href="{{ route('members.index') }}">Batal</a>
    </form>
</body>
</html>