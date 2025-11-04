<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengabdianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'dosen';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2020|max:2030',
            'tim_pelaksana' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
            'proposal_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'dokumen_pendukung.*' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul pengabdian wajib diisi.',
            'judul.max' => 'Judul pengabdian maksimal 255 karakter.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2020.',
            'tahun.max' => 'Tahun maksimal 2030.',
            'tim_pelaksana.required' => 'Tim pelaksana wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'lokasi.max' => 'Lokasi maksimal 255 karakter.',
            'mitra.required' => 'Mitra wajib diisi.',
            'mitra.max' => 'Mitra maksimal 255 karakter.',
            'proposal_file.required' => 'File proposal wajib diupload.',
            'proposal_file.file' => 'File proposal harus berupa file.',
            'proposal_file.mimes' => 'File proposal harus berupa PDF, DOC, atau DOCX.',
            'proposal_file.max' => 'File proposal maksimal 10MB.',
            'dokumen_pendukung.*.file' => 'Dokumen pendukung harus berupa file.',
            'dokumen_pendukung.*.mimes' => 'Dokumen pendukung harus berupa PDF, DOC, atau DOCX.',
            'dokumen_pendukung.*.max' => 'Dokumen pendukung maksimal 10MB.',
        ];
    }
}
