<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Requests\GeminiStoreRequest;
use GeminiStoreDTO;
use Illuminate\Support\Facades\Http;

class GeminiController extends Controller {

    public function index() {
        $chat = session()->get('gemini_chat', []);
        return response()->json([
            'chat' => $chat,
        ]);
    }

    public function destroy() {
        session()->forget('gemini_chat');
        return response()->json(['success' => true]);
    }

    public function store(GeminiStoreRequest $req) {
        $req = GeminiStoreDTO::fromRequest($req);

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
        $apiKey = env('GEMINI_API', '');
        $chat = session()->get('gemini_chat', []);

        if (empty($chat)) {
            $chat[] = [
                'role' => 'user',
                'parts' => [
                    'text' => 'You are an assistant for the OHRM QR Attendance website at CMU. ' .
                        'This site is for the Office of Human Resource Management Quick Response Attendance, by helping our staff for "ID Request" reply back (not email). ' .
                        'The expecting reply should be the message for our ID request status which are "Unverified", "Processed", "Completed", "Claimed", "Rejected" & "Removed". ' .
                        'The "Unverified" is for pending request like it\'s not checked yet and it will takes time because of the long queue. ' .
                        'The "Processed" means it has been checked and it\'s undergoes editing or printing. ' .
                        'The "Completed" means it is ready to be claimed and printed. ' .
                        'The "Claimed" means has been claimed by the employee' .
                        'The "Rejected" means, there\'s an error for their input like misspelled so it has been rejected.' .
                        'Lastly for "Removed" means the data that they encoded and picture that has been uploaded will be deleted after 30 days.' .
                        'The email of our staff is "obm@cmu.edu.ph"'
                ]
            ];
        }

        $chat[] = [
            'role' => 'user',
            'parts' => [
                ['text' => '[' . $req->request_status_type . "] \r\n" . $req->prompt],
            ],
        ];

        $payload = [
            'contents' => $chat,
        ];


        $response = Http::withHeaders([
            'X-Goog-Api-Key' => $apiKey,
            'Content-Type'   => 'application/json',
        ])->post($endpoint, $payload);

        if ($response->successful()) {
            $data = $response->json();

            // Add model reply to chat history
            $chat[] = [
                'role' => 'model',
                'parts' => [
                    ['text' => $data['candidates'][0]['content']['parts'][0]['text']],
                ],
            ];

            // Store updated chat history in session
            session(['gemini_chat' => $chat]);

            return response()->json([
                'reply' => $data['candidates'][0]['content']['parts'][0]['text'],
                'chat' => $chat,
            ]);
        }

        logger()->error('Gemini API error', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        abort(502, 'Gemini API request failed.');
    }
}
