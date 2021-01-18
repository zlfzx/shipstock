<?php

namespace App\Http\Requests\Kapal;

use Illuminate\Foundation\Http\FormRequest;

class TambahKapal extends FormRequest
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
            'nama' => 'required',
            'kapten' => 'required'
        ];
    }
}
