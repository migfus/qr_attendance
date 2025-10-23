<?php

use App\Http\Requests\GeminiStoreRequest;

class GeminiStoreDTO {
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
}
