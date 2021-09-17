<?php

namespace App\Http\Requests;

use App\Service\TaskService;
use Illuminate\Foundation\Http\FormRequest;

class TaskIdRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'taskId' => ['required', 'string', 'regex:' . TaskService::REGEX_ID],
        ];
    }
}
