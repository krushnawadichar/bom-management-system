<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BomUploadRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->can('upload-boms');
    }

    public function rules()
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'bom_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
            'revision' => 'nullable|string|max:10',
            'original_bom_number' => 'nullable|string|max:50'
        ];
    }

    public function messages()
    {
        return [
            'project_id.required' => 'Please select a project',
            'project_id.exists' => 'Selected project does not exist',
            'bom_file.required' => 'Please select a BOM file',
            'bom_file.mimes' => 'File must be Excel (.xlsx, .xls) or CSV format',
            'bom_file.max' => 'File size must not exceed 10MB'
        ];
    }
}