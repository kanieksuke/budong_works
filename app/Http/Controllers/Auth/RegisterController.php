<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
    $this->create($request->all());
    return back()->with('status', '登録が完了しました。');
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
            'password' => Hash::make(Str::random(30)) //ランダムな30文字のパスワードを生成する
        ]);

        // パスワードリセット用のトークンを生成
        $token = app('auth.password.broker')->createToken($user);
        // パスワードリセット用のメールを送信
        $user->sendPasswordResetNotification($token);
    }
}
