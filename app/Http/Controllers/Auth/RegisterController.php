<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | このコントローラーは、新しいユーザーの登録と、その検証と作成を処理します。
    |
    */

    use RegistersUsers;

    /**
     * 登録後にユーザーをリダイレクトする場所。
     *
     * @var string
     */
    // protected $redirectTo = '/register'

    /**
     * 新しいコントローラー インスタンスを作成します。
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    protected function register(Request $request)
    {
    $this->validator($request->all())->validate();
    $user = $this->create($request->all());
    event(new Registered($user));
    // return redirect($this->redirectPath())->with('status', '登録が完了しました。');
    return redirect('/register')->with('status', '登録が完了しました。');
    }

    protected function registered(Request $request, $user)
    {
    $this->guard()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    event(new Registered($user));
    }

    /**
     * 新規登録時のバリデーターを取得します。
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'employee_code' => ['required', 'regex:/^[0-9]{4}$/', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * 有効な登録後に新しいユーザー インスタンスを作成します。
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'employee_code' => $data['employee_code'],
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // パスワードリセット用のトークンを生成
        $token = app('auth.password.broker')->createToken($user);
        // パスワードリセット用のメールを送信
        $user->sendPasswordResetNotification($token);
    }
}
