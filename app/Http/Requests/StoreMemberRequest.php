<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required',
            'nim' => 'required|unique:members',
            'email' => 'required|email|unique:members',
            'nomor_telepon' => 'required',
            'alamat' => 'required',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }
}
