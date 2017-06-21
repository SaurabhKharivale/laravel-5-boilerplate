<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\ActivationEmailRequested;
use App\Repositories\ActivationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActivationController extends Controller
{
    public function resend()
    {
        $path = \Auth::check() ? 'home' : 'login';
        $message = \Auth::check() ?
                    'You have already verified your email.' :
                    'Please login first inorder to receive your activation email.';

        if(\Auth::guest() || \Auth::user()->verified) {
            return redirect($path)->with('notification', [
                'message' => $message,
                'type' => 'info'
            ]);
        }

        event(new ActivationEmailRequested(\Auth::user()));

        return redirect('home')->with('notification', [
            'message' => 'We have resent activation link to your email address.',
            'type' => 'success'
        ]);
    }

    public function activate($token, ActivationRepository $activationRepo)
    {
        $path = \Auth::check() ? 'home' : 'login';

        try {
            $activationRepo->activateUserAccount($token);
        } catch (ModelNotFoundException $e) {
            return redirect($path)->with('notification', [
                'message' => 'Sorry! Your activation link is not valid. Please check your email and try again.',
                'type' => 'error'
            ]);
        }

        return redirect($path)->with('notification', [
            'message' => 'Thank you for verifing your email.',
            'type' => 'success'
        ]);
    }
}
