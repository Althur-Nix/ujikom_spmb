<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class GelombangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'         => ['required','string','max:50'],
            'tahun'        => ['required','integer'],
            'tgl_mulai'    => ['required','date'],
            'tgl_selesai'  => ['required','date','after_or_equal:tgl_mulai'],
            'biaya_daftar' => ['required','numeric'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        return Arr::only($data, ['nama','tahun','tgl_mulai','tgl_selesai','biaya_daftar']);
    }
}