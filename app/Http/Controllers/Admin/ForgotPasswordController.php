<?php

namespace App\Http\Controllers\Admin;

use Password;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('admin.passwords.email');
    }

    public function broker()
    {
        return Password::broker('admins');
    }
}
