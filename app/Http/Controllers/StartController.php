<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Authorize;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Staff;
use App\Models\News;

class StartController extends Controller
{
    public function __construct()
    {

    }

    public function login() {
        if ($this->checkAuth() == null)
            return view('main.main');

        return view('auth.login');
    }
    public function loginPost(Request $request) {
        if ($this->checkAuth() == null)
            return view('main.block');

        $user = User::where('login', '=', $request->login)->first();

        if ($user == null) {
            return view('auth.login')->with(['error' => 'Данные не совпадают!']);
        }
        else if ($user->staff == null && $user->tenant == null) {
            return view('auth.login')->with(['error' => 'Ваша запись архивирована!']);
        }
        else if (!Hash::check($request->password, $user->password)) {
            return view('auth.login')->with(['error' => 'Данные не совпадают!']);
        }

        if (Auth::attempt(
            ['login' => $request->input('login'),
            'password' => $request->input('password')],
            $request->input('rememberMe') == 'on' ? true : false
        )){
            return redirect()->route('main');
        }

        return view('auth.login')->with(['error' => 'Данные не совпадают!']);
    }


    public function main() {
        return view('main.main');
    }

    public function viewNews($id) {
        return view('news.view', [
            'news' => News::find($id)
        ]);
    }

    public function logout(){
        if ($this->checkAuth() != null)
            return redirect()->route('login');

        Auth::logout();
        return redirect()->route('main');
    }

    public function profile() {
        if ($this->checkAuth() != null)
            return redirect()->route('login');

        return view('main.profile', ['user' => Auth::user()]);
    }

    public function profilePost(Request $request) {
        if ($this->checkAuth() != null)
            return redirect()->route('login');

        $dataValidation = [];
        if(Auth::user()->staff){
            $dataValidation['phone'] = 'required|numeric|digits_between:6,11';
        }
        $validator = Validator::make($request->all(), $dataValidation)->validate();


        if(isset(Auth::user()->staff)){
            $user = Auth::user()->staff;
            $user->icon = $request->image;
            $user->phone = $request->phone;
        }
        else{
            $user = Auth::user()->tenant;
            $user->phone = $request->phone;
        }

        $user->save();

        return view('main.profile', ['user' => Auth::user()]);
    }
}
