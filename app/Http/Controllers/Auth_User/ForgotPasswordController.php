<?php
namespace App\Http\Controllers\Auth_User;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForgotPassword(){
        return view('');
    }

    public function sendResetLinkEmail(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $token = Str::random(64);
        DB::table('password_resets_tokens')->UpdateOrInsert(
            ['email' => $request->email],
            [
                'token' => hash('sha256', $token),
                'created_at' => now(),
            ]
        );
        Mail::send('emails_reset_password', ['token' => $token], function ($mail) use ($request) {
            $mail->to($request->email);
            $mail->subject('Reset Password');
        });
        return route('');
    }
    public function showresetform(String $token){
        return Route('');
    }

    public function resetpassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);
        $record = DB::table('password_resets_tokens')
            ->where('email', $request->email)
            ->first();

            if (!$record || $record->token !== hash('sha256', $request->token)) {
            return back()->withErrors(['token' => 'link is invalid or expired']);
        }
            if (now()->diffInMinutes($record->created_at) > 60) {
            return back()->withErrors(['token' => 'link has expired']);
        }

        User::where('email', $request->email)->update([
            'password' => $request->password
        ]);

        DB::table('password_resets_tokens')->where('email', $request->email)->delete();

        return redirect()->route('');
    }
}
