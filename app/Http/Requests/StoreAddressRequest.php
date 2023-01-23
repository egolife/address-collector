<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'address'   => 'string|required',
            'zip'       => 'string|required|digits:5',
            'apartment' => 'string|nullable',
            'city'      => 'string|required',
            'state'     => 'string|required|min:2',
        ];
    }
}
