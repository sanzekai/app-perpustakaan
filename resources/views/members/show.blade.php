{{-- File: resources/views/members/show.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Anggota</title>
    <style>
        body { font-family: sans-serif; margin: 40px; max-width: 600px; }
        .detail-group { margin-bottom: 16px; }
        .detail-label { font-weight: bold; margin-bottom: 4px; display: block; }
        .detail-value { padding: 8px; background: #f3f4f6; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Detail Anggota</h1>
    <p><a href="{{ route('members.index') }}">&larr; Kembali ke daftar anggota</a></p>

    <div class="detail-group">
        <span class="detail-label">ID</span>
        <div class="detail-value">{{ $member->id }}</div>
    </div>

    <div class="detail-group">
        <span class="detail-label">Nama</span>
        <div class="detail-value">{{ $member->nama }}</div>
    </div>

    <div class="detail-group">
        <span class="detail-label">NIM</span>
        <div class="detail-value">{{ $member->nim }}</div>
    </div>

    <div class="detail-group">
        <span class="detail-label">Email</span>
        <div class="detail-value">{{ $member->email }}</div>
    </div>

    <div class="detail-group">
        <span class="detail-label">Nomor Telepon</span>
        <div class="detail-value">{{ $member->nomor_telepon }}</div>
    </div>

    <div class="detail-group">
        <span class="detail-label">Alamat</span>
        <div class="detail-value">{{ $member->alamat }}</div>
    </div>

    <div class="detail-group">
        <span class="detail-label">Status</span>
        <div class="detail-value">{{ ucfirst($member->status) }}</div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('members.edit', $member->id) }}" style="padding: 8px 16px; background: #eab308; color: #fff; text-decoration: none; border-radius: 4px;">Edit</a>
    </div>
</body>
</html>
