<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\PasswordMismatchException;

class UpdatePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(! \Hash::check(request('current_password'), auth()->user()->password)) {
            $message = empty(request('current_password')) ?
                        'The current password field is required.' :
                        'The current password did not match our records.';

            throw new PasswordMismatchException($message);
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => 'required',
            'new_password' => 'required|min:6|different:current_password|confirmed',
        ];
    }
}
