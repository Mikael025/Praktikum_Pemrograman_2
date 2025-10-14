<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPenelitianRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && auth()->user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'judul' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 5),
            'tim_peneliti' => 'required|string',
            'sumber_dana' => 'required|string|max:255',
            'status' => 'required|in:draft,menunggu_verifikasi,terverifikasi,ditolak,berjalan,selesai',
            'catatan_verifikasi' => 'nullable|string|max:500'
        ];
    }

    public function messages()
    {
        return [
            'judul.required' => 'Judul penelitian wajib diisi.',
            'judul.max' => 'Judul penelitian maksimal 255 karakter.',
            'tahun.required' => 'Tahun penelitian wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2020.',
            'tahun.max' => 'Tahun maksimal ' . (date('Y') + 5) . '.',
            'tim_peneliti.required' => 'Tim peneliti wajib diisi.',
            'sumber_dana.required' => 'Sumber dana wajib diisi.',
            'sumber_dana.max' => 'Sumber dana maksimal 255 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'catatan_verifikasi.max' => 'Catatan verifikasi maksimal 500 karakter.'
        ];
    }
}
