<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadNoteFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'file' => ['description' => 'File to attach (jpg, png, pdf — max 5 MB). Replaces any existing attachment.'],
        ];
    }
}
