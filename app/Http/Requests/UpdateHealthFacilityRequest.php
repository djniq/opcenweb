<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHealthFacilityRequest extends FormRequest
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
     * @return array
     * 
     */
    public function rules()
    {
        return [
            'name' => ['max:255'],
            'level' => [Rule::in(['primary', 'secondary', 'tertiary', 'specialized'])],
            'email' => ['email'],
            'contactNo' => ['string'],
            'address' => ['array'],
        ];
    }
}