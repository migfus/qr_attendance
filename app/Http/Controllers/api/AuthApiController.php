<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthApiController extends Controller
{
    function login(Request $req) : JsonResponse {
        $req->validate([
            'email' => ['email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $req->email)->first();
        if(!$user || !Hash::check($req->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $token = $user->createToken($user->name.' - mobile')->plainTextToken;
        return response()->json([
            'token' => $token
        ]);
    }

    function logout(Request $req) : JsonResponse {
        $req->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'logout'
        ], 200);
    }

    function me(Request $req) : JsonResponse {
        $user = User::where('id', $req->user()->id)->with(['department', 'quick_response', 'roles'])->first();
        return response()->json($user, 200);
    }

    function forgot(Request $req) : JsonResponse {
        $req->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);

        // TODO: Sends Email for code
        // TODO: Redirect to recovery and expecting for [recovery_code]

        return response()->json(['succcess' => true]);
    }

    function recovery(Request $req) : JsonResponse {
        $req->validate([
            'recovery_code' => ['required'],
            'password' => ['required','string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $user = User::where('recovery_code', $req->recovery_code)->first();
        if(!$user)
            return response()->json(['error', 'Invalid recovery code.']);

        $user->update([
            'password' => Hash::make($req->password)
        ]);

        // TODO: Cient will redirect to login page
        return response()->json(['success' => true, 'email' => $user->email]);
    }

    function changePassword(Request $req) : JsonResponse {
        $req->validate([
            'password' => ['required', 'min:8'],
            'new_password' => ['required', 'confirmed', 'min:8'],
        ]);

        return response()->json(['Errror']);
        return response()->json(true);
    }
}
