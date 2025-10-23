<?php

namespace App\Http\DataTransferObjects\dashboard\AccountSettings\store;

use App\Http\Requests\GeminiStoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StatusMessagesDTO extends FormRequest {
    public function __construct(
        public string $prompt,
        public string $request_status_type,
    ) {
    }

    public static function fromRequest(GeminiStoreRequest $req): self {
        $data = $req->validated();

        return new self(
            $data['prompt'],
            $data['request_status_type']
        );
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
