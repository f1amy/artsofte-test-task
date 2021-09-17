<?php

namespace App\Http\Requests;

use App\Models\Sprint;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class AddSprintTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sprintId' => ['required', 'string', 'regex:' . Sprint::REGEX_ID],
            'taskId' => ['required', 'string', 'regex:' . Task::REGEX_ID],
        ];
    }
}
