<?php

namespace App\Http\Requests;

use App\Service\SprintService;
use Illuminate\Foundation\Http\FormRequest;

class SprintIdRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sprintId' => ['required', 'string', 'regex:' . SprintService::REGEX_ID],
        ];
    }
}
