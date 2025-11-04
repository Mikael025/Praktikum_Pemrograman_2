<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenelitianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $penelitian = $this->route('penelitian');
        return auth()->check() && 
               auth()->user()->role === 'dosen' && 
               $penelitian->user_id === auth()->id() &&
               $penelitian->canBeEditedByDosen();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2020|max:2030',
            'tim_peneliti' => 'required|string',
            'sumber_dana' => 'required|string|max:255',
        ];

        // Add file validation based on status and existing documents
        $penelitian = $this->route('penelitian');
        
        if ($penelitian->requiresProposal() && !$penelitian->documents()->where('jenis_dokumen', 'proposal')->exists()) {
            $rules['proposal_file'] = 'required|file|mimes:pdf,doc,docx|max:10240';
        } else {
            $rules['proposal_file'] = 'nullable|file|mimes:pdf,doc,docx|max:10240';
        }

        if ($penelitian->requiresFinalDocuments()) {
            if (!$penelitian->documents()->where('jenis_dokumen', 'laporan_akhir')->exists()) {
                $rules['laporan_akhir_file'] = 'required|file|mimes:pdf,doc,docx|max:10240';
            }
            if (!$penelitian->documents()->where('jenis_dokumen', 'sertifikat')->exists()) {
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
            'judul.required' => 'Judul penelitian wajib diisi.',
            'judul.max' => 'Judul penelitian maksimal 255 karakter.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2020.',
            'tahun.max' => 'Tahun maksimal 2030.',
            'tim_peneliti.required' => 'Tim peneliti wajib diisi.',
            'sumber_dana.required' => 'Sumber dana wajib diisi.',
            'sumber_dana.max' => 'Sumber dana maksimal 255 karakter.',
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
