<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInformasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $slug = $this->route('informasi');
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('informasi', 'slug')->ignore($slug, 'slug'),
            ],
            'content' => ['required', 'string'],
            'category' => ['required', 'in:penelitian,pengabdian,umum'],
            'visibility' => ['required', 'in:admin,dosen,semua'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}


