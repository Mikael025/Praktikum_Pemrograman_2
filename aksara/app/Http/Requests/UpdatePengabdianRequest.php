<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengabdianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $pengabdian = $this->route('pengabdian');
        return auth()->check() && 
               auth()->user()->role === 'dosen' && 
               $pengabdian->user_id === auth()->id() &&
               $pengabdian->canBeEditedByDosen();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2020|max:2030',
            'tim_pelaksana' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
        ];

        // Add file validation based on status and existing documents
        $pengabdian = $this->route('pengabdian');
        
        if ($pengabdian->requiresProposal() && !$pengabdian->documents()->where('jenis_dokumen', 'proposal')->exists()) {
            $rules['proposal_file'] = 'required|file|mimes:pdf,doc,docx|max:10240';
        } else {
            $rules['proposal_file'] = 'nullable|file|mimes:pdf,doc,docx|max:10240';
        }

        if ($pengabdian->requiresFinalDocuments()) {
            if (!$pengabdian->documents()->where('jenis_dokumen', 'laporan_akhir')->exists()) {
                $rules['laporan_akhir_file'] = 'required|file|mimes:pdf,doc,docx|max:10240';
            }
            if (!$pengabdian->documents()->where('jenis_dokumen', 'sertifikat')->exists()) {
                $rules['sertifikat_file'] = 'required|file|mimes:pdf,doc,docx|max:10240';
            }
        }

        $rules['dokumen_pendukung.*'] = 'nullable|file|mimes:pdf,doc,docx|max:10240';

        return $rules;
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
            'laporan_akhir_file.required' => 'File laporan akhir wajib diupload.',
            'laporan_akhir_file.file' => 'File laporan akhir harus berupa file.',
            'laporan_akhir_file.mimes' => 'File laporan akhir harus berupa PDF, DOC, atau DOCX.',
            'laporan_akhir_file.max' => 'File laporan akhir maksimal 10MB.',
            'sertifikat_file.required' => 'File sertifikat wajib diupload.',
            'sertifikat_file.file' => 'File sertifikat harus berupa file.',
            'sertifikat_file.mimes' => 'File sertifikat harus berupa PDF, DOC, atau DOCX.',
            'sertifikat_file.max' => 'File sertifikat maksimal 10MB.',
            'dokumen_pendukung.*.file' => 'Dokumen pendukung harus berupa file.',
            'dokumen_pendukung.*.mimes' => 'Dokumen pendukung harus berupa PDF, DOC, atau DOCX.',
            'dokumen_pendukung.*.max' => 'Dokumen pendukung maksimal 10MB.',
        ];
    }
}
