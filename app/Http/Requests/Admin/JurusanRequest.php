<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class JurusanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:10|unique:jurusan,kode,' . $this->jurusan?->id,
            'nama' => 'required|string|max:100',
            'kuota' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'aktif' => 'boolean'
        ];
    }
}