<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'body'  => ['sometimes', 'required', 'string'],
            'file'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'title' => ['description' => 'New title (optional).', 'example' => 'Updated title'],
            'body'  => ['description' => 'New body (optional).', 'example' => 'Updated body text.'],
            'file'  => ['description' => 'Replacement file (optional — replaces existing S3 file).'],
        ];
    }
}
