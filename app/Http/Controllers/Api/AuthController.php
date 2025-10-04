<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginSanctumRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\ForgotPasswordMail;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{

    /**
     * Get authenticated user
     */
    public function user(): JsonResponse
    {
        return ApiResponse::success(
            "",
            User::with("purchasedPlaces")
                ->find(Auth::id())
                ->makeVisible(['email', 'name', 'birth_date'])
        );
    }

    /**
     * Register user
     * 
     * @unauthenticated
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $input = $request->all();

        $input['password'] = bcrypt($input['password']);
        $user              = User::create($input);
        $user["api_token"] = $user->createToken('GG-LAN')->plainTextToken;

        Mail::to($user)->send(new VerificationMail($user));

        return ApiResponse::created("", $user);
    }

    /**
     * Login user
     */
    public function login(LoginSanctumRequest $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // We delete all current tokens of the user to avoid having multiple tokens for one user.
            $user->tokens()->delete();

            return ApiResponse::success("", [
                "api_token" => $user->createToken('GG-LAN')->plainTextToken,
            ]);
        } else {
            return ApiResponse::unauthorized(__("responses.register.wrong_match"), []);
        }
    }

    /**
     * Logout user
     *
     * @unauthenticated
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return ApiResponse::success(__("responses.register.logout"), []);
    }

    /**
     * Email verification of the user
     * 
     * @unauthenticated
     */
    public function verify(Request $request, $id): JsonResponse
    {
        if (! $request->hasValidSignature()) {
            return ApiResponse::unauthorized(__("responses.register.url_invalid"), []);
        }

        $user = User::findOrFail($id);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            return ApiResponse::success(__("responses.register.email.verified"), []);
        }

        return ApiResponse::badRequest(__("responses.went_wrong"), []);
    }

    /**
     * Resend of the verification email
     */
    public function resend(): JsonResponse
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return ApiResponse::badRequest(__("responses.register.email.already_verified"), []);
        }

        // $user->sendEmailVerificationNotification();
        Mail::to($user)->send(new VerificationMail($user));

        return ApiResponse::success(__("responses.register.email.sent"), []);
    }

    /**
     * Generic response when trying to do actions with unverified user
     * 
     * @unauthenticated
     */
    public function notVerified(): JsonResponse
    {
        return ApiResponse::unauthorized(__("responses.register.account_unverified"), []);
    }

    /**
     * Send an email to reset the password & create a reset token
     * 
     * @unauthenticated
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where("email", $request->email)->first();

        // If no user found, generic response
        if (! $user) {
            return ApiResponse::success(__("responses.register.email.forgotP_sent"), []);
        }

        // Create password reset token in DB
        $resetToken = Password::createToken($user);

        Mail::To($user)->send(new ForgotPasswordMail($user, $resetToken));

        return ApiResponse::success(__("responses.register.email.forgotP_sent"), []);
    }

    /**
     * Resetting password & changing it
     * 
     * @unauthenticated
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::where("email", $request->email)->first();

        if (! $user) {
            return ApiResponse::badRequest(__("responses.went_wrong"), []);
        }

        // Si on retrouve bien le token
        if (Password::tokenExists($user, $request->token)) {
            $user->password = bcrypt($request->password);
            $user->save();

            // Remove reset token
            Password::deleteToken($user);

            // Generate new api token
            $apiToken = $user->createToken('GG-LAN')->plainTextToken;

            return ApiResponse::success(__("responses.register.password_updated"), [
                "api_token" => $apiToken,
            ]);
        } else {
            return ApiResponse::badRequest(__("responses.went_wrong"), []);
        }
    }
}
