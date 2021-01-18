<?php

namespace App\Http\Requests\Sparepart;

use Illuminate\Foundation\Http\FormRequest;

class TambahSparepart extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'kode' => 'required',
            'nama' => 'required',
            'stok' => 'required',
            'warehouse_id' => 'required|exists:warehouse,id'
        ];
    }
}
