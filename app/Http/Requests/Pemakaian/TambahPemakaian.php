<?php

namespace App\Http\Requests\Pemakaian;

use Illuminate\Foundation\Http\FormRequest;

class TambahPemakaian extends FormRequest
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
            'sparepart_id' => 'required|exists:sparepart,id',
            'kapal_id' => 'required|exists:kapal,id',
            'jumlah' => 'required',
            'tanggal_pemakaian' => 'required'
        ];
    }
}
