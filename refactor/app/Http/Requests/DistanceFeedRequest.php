<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistanceFeedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'jobid' => 'required',
            'distance' => 'nullable|string',
            'time' => 'nullable|string',
            'session_time' => 'nullable|string',
            'flagged' => 'required|in:true,false',
            'manually_handled' => 'required|in:true,false',
            'by_admin' => 'required|in:true,false',
            'admincomment' => 'nullable|string',
        ];
    }
}
