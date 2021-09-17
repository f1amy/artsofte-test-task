<?php

namespace App\Http\Requests;

use App\Models\Task;
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
            'id' => ['required', 'string', 'regex:' . Task::REGEX_ID],
            'estimation' => ['required', 'string', 'regex:' . Task::REGEX_ESTIMATION],
        ];
    }
}
