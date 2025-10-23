<?php

namespace App\Http\Requests\dashboard\AccountSettings\store;

use Illuminate\Foundation\Http\FormRequest;

class StatusMessagesRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'request_status_messages' => ['required', 'array'],
            'request_status_messages.Unverified' => ['required', 'string'],
            'request_status_messages.Processing' => ['required', 'string'],
            'request_status_messages.Completed' => ['required', 'string'],
            'request_status_messages.Claimed' => ['required', 'string'],
            'request_status_messages.Rejected' => ['required', 'string'],
            'request_status_messages.Removed' => ['required', 'string'],
        ];
    }
}
