<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginReq;
use App\Http\Requests\RegisterReq;
use App\Http\Requests\ChangePasswordReq;
use App\Models\User;
use App\DTO\UserDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MyController extends Controller
{
//     Регистрация нового пользователя.
//     Возвращает DTO зарегистрированного пользователя
    public function register(RegisterReq $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'birthday' => $validated['birthday'],
        ]);

        return response()->json(UserDTO::fromModel($user)->toArray(), 201);
    }

//     Авторизация пользователя.
//     При успехе возвращается токен, при этом сохраняются только 5 последних.
    public function login(LoginReq $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Неверные учетные данные.'], 401);
        }

        $user = Auth::user();

        // Генерируем новый токен
        $newToken = $user->createToken('access_token')->plainTextToken;

        // Оставляем только 5 последних токенов
        $tokens = $user->tokens()->latest()->get();
        if ($tokens->count() > 5) {
            $tokens->slice(5)->each->delete();
        }

        return response()->json(['token' => $newToken], 200);
    }


//     Получение данных авторизованного пользователя
    public function me(Request $request)
    {
        return response()->json(UserDTO::fromModel($request->user())->toArray());
    }


//     Разлогин — отзываем текущий токен.

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Вы успешно вышли из системы.']);
    }


//    Разлогин со всех устройств — удаляем все токены пользователя.

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Все сессии завершены.']);
    }


// Получение списка активных токенов текущего пользователя.

    public function tokens(Request $request)
    {
        return response()->json($request->user()->tokens()->get());
    }


// Смена пароля авторизованным пользователем.
// Проверка по токену, а не по логину.

    public function changePassword(ChangePasswordReq $request)
    {
        $user = $request->user(); // Получаем пользователя по текущему токену

        // Проверка текущего пароля
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['message' => 'Неверный текущий пароль.'], 400);
        }

        // Обновляем пароль
        $user->password = bcrypt($request->input('new_password'));
        $user->save();

        return response()->json(['message' => 'Пароль успешно изменён.']);
    }
}
