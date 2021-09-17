<?php

namespace App\Http\Requests;

use App\Service\TaskService;
use Illuminate\Foundation\Http\FormRequest;

class EstimateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'string', 'regex:' . TaskService::REGEX_ID],
            'estimation' => ['required', 'string', 'regex:' . TaskService::REGEX_ESTIMATION],
        ];
    }
}
