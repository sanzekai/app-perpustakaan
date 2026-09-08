<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota</title>
</head>
<body>
    <h1>Daftar Anggota Perpustakaan</h1>

    {{-- Flash message sukses --}}
    @if(session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <p>
        <a href="{{ route('members.create') }}">+ Tambah Anggota Baru</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Nomor Telepon</th>
                <th>Alamat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $index => $member)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $member['nim'] }}</td>
                    <td>{{ $member['nama'] }}</td>
                    <td>{{ $member['email'] }}</td>
                    <td>{{ $member['nomor_telepon'] }}</td>
                    <td>{{ $member['alamat'] }}</td>
                    <td>{{ ucfirst($member['status']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data anggota.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>