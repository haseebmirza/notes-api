<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string'],
            'file'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'title' => ['description' => 'The note title.', 'example' => 'Meeting notes'],
            'body'  => ['description' => 'The note body.', 'example' => 'Discussed Q4 targets.'],
            'file'  => ['description' => 'Optional file attachment (jpg, png, pdf — max 5 MB).'],
        ];
    }
}
