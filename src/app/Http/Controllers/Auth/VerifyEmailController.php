<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Constants\LoginConstant;
use App\Constants\UserRole;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    private static function getEmailReturnedView($authUser){
        if($authUser){
            if($authUser->is_filled_with_profile === true){
                $stringReturnedView = 'index';
            }else{
                $stringReturnedView = 'mypage.profile';
            }
        }else{//$authUser
            $stringReturnedView = 'index';
        }//$authUser

        return($stringReturnedView);
    }

    public function verifyEmail() {
        $authUser = Auth::user();
        // 認証状態を更新
        $authUser->markEmailAsVerified();
        $stringReturnedView = self::getEmailReturnedView($authUser);

        return redirect()
            ->route($stringReturnedView)
            ->with('status', 'メール認証が完了しました！');
    }

    public function emailVerifyIdHash(EmailVerificationRequest $request){
        $request->fulfill(); // メール認証完了
        $authUser = Auth::user();
        $stringReturnedView = self::getEmailReturnedView($authUser);
        return redirect()->route($stringReturnedView); // 認証後のリダイレクト先
    }

    public function resendEmail(Request $request){
        $user = $request->user();
        $user->sendEmailVerificationNotification();
        return redirect()->route('verification.notice')->with('status', 'verification-link-sent');
    }
}
