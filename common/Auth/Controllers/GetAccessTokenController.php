<?php

namespace Common\Auth\Controllers;

use App\User;
use Common\Auth\Traits\GetsApiTokenForDevice;
use Common\Core\BaseController;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class GetAccessTokenController extends BaseController
{
    use AuthenticatesUsers, GetsApiTokenForDevice;

    /**
     * @var User
     */
    private $user;

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string|email_verified',
            'password' => 'required|string',
            'identifier' => 'required|string|min:3|max:50',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $this->user = User::where('email', $request->get('email'))->first();
        return $this->user && Hash::check($request->get('password'), $this->user->password);
    }

    protected function sendLoginResponse(Request $request)
    {
        return $this->success([
            'token' => $this->getApiToken($request->get('identifier'), $this->user),
            'user' => $this->user,
        ]);
    }
}
