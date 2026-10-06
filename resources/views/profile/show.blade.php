@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
    <h1>Profil Saya</h1>
    
    <div style="background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 30px;">
        <p><strong>Nama:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
    </div>

    <h2>Ganti Password</h2>
    
    <form action="{{ route('profile.password.update') }}" method="POST" style="max-width: 400px;">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 15px;">
            <label for="current_password" style="display:block; font-weight:bold; margin-bottom: 5px;">Password Lama</label>
            <input type="password" name="current_password" id="current_password" style="width: 100%; padding: 8px; box-sizing: border-box;">
            @error('current_password')
                <div style="color: #b91c1c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="new_password" style="display:block; font-weight:bold; margin-bottom: 5px;">Password Baru</label>
            <input type="password" name="new_password" id="new_password" style="width: 100%; padding: 8px; box-sizing: border-box;">
            @error('new_password')
                <div style="color: #b91c1c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="new_password_confirmation" style="display:block; font-weight:bold; margin-bottom: 5px;">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <button type="submit" class="btn">Simpan Password</button>
    </form>
@endsection
