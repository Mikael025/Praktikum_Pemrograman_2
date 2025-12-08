<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInformasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:informasi,slug'],
            'image' => ['required', 'image', 'max:2048'],
            'content' => ['required', 'string'],
            'category' => ['required', 'in:penelitian,pengabdian,umum'],
            'visibility' => ['required', 'in:admin,dosen,semua'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}


