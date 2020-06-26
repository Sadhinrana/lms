<?php

namespace App\Http\Controllers;

use Carbon\Carbon, Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordRequest;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mail;

class ResetPasswordController extends Controller
{
    /**
     * Create token password reset.
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function sendMail(Request $request)
    {

        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'The email you entered cannot be found!'
            ], 500);
        }

        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => Str::random(60),
        ]);

        $Mail = null;
        if ($passwordReset) {
            //$user->notify(new ResetPasswordRequest($passwordReset->token));
//            $url = "https://celtenglish.com/#/reset_password/" . $passwordReset->token;
            $url = "http://celtenglish.mediusware.xyz/#/reset_password/" . $passwordReset->token;
            $data = array('email' => $passwordReset->email, 'url' => $url);

            \Illuminate\Support\Facades\Mail::send('emails.reset_password', ['url' => $url], function ($message) use ($data) {
                $message->to($data['email'])->subject('Reset Password');
                $message->from('info@mediusware.com', 'CELT English');
            });
            /*Mail::send([], [], function ($message) use ($data) {
                $message->from('no-reply@celtenglish.com', 'CELT English');
                $message->to($data['email']);
                $message->subject('Reset Password');
                $message->setBody('<html><h3>Reset Password</h3><p>You are receiving this email because we received a password reset request for your account.</p><br>Reset Password ' . $data["url"] . '<br><p>If you did not request a password reset, no further action is required.</p></html>', 'text/html');
            });*/

            if (Mail::failures()) {
                echo "not send";
            } else {
                echo "send";
            }
        }
        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);

    }


    public function resetPassword(Request $request)
    {
        $this->validate($request,
            [
                'password' => 'required|confirmed'
            ]
        );
        $passwordReset = PasswordReset::where('token', $request->token)->firstOrFail();
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => 'This password reset token is invalid.',
            ], 422);
        }

        $user = User::where('email', $passwordReset->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();
        $passwordReset->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function checkIfTokenValid(Request $request, $token)
    {
        $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => 'This password reset token is invalid.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }
}