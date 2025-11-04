<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminPengabdianRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'judul' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 5),
            'tim_pelaksana' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
            'status' => 'required|in:diusulkan,tidak_lolos,lolos_perlu_revisi,lolos,revisi_pra_final,selesai',
            'catatan_verifikasi' => 'nullable|string|max:500'
        ];
    }

    public function messages()
    {
        return [
            'judul.required' => 'Judul pengabdian wajib diisi.',
            'judul.max' => 'Judul pengabdian maksimal 255 karakter.',
            'tahun.required' => 'Tahun pengabdian wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2020.',
            'tahun.max' => 'Tahun maksimal ' . (date('Y') + 5) . '.',
            'tim_pelaksana.required' => 'Tim pelaksana wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'lokasi.max' => 'Lokasi maksimal 255 karakter.',
            'mitra.required' => 'Mitra wajib diisi.',
            'mitra.max' => 'Mitra maksimal 255 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'catatan_verifikasi.max' => 'Catatan verifikasi maksimal 500 karakter.'
        ];
    }
}
