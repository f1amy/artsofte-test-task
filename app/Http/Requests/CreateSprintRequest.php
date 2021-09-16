<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSprintRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Year' => 'required|integer|digits:4|min:1970',
            'Week' => 'required|integer|min:1',
        ];
    }
}
