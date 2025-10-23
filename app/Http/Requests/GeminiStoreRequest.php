<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeminiStoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'prompt' => ['required', 'string'],
            'request_status_type' => ['required', 'string']
        ];
    }
}
