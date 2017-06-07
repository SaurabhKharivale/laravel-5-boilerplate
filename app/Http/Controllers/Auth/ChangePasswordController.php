<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePassword;

class ChangePasswordController extends Controller
{
    public function change(UpdatePassword $request)
    {
        $user = auth()->user();
        $user->password = bcrypt(request('new_password'));
        $user->save();

        return redirect()->back()->with([
            'notification.type' => 'success',
            'notification.persist' => true,
            'notification.message' => 'Your password has been updated.',
        ]);
    }
}
