@extends('layouts.default')

@section('title')Авторизация@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <span>Авторизация</span>
        </p>
    </div>
    <div class="content">

        <h3>Авторизация</h3>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <table class="table_login">
                <tr>
                    <td>Логин</td>
                    <td><input name="login" class="input" maxlength="255"></td>
                </tr>
                <tr>
                    <td>Пароль</td>
                    <td><input name="password" class="input" type="password" maxlength="255"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <p class="pForCheckbox">
                            <input name="rememberMe" id="rememberMe" class="checkbox" type="checkbox">
                            <span>Запомнить меня</span>
                        </p>
                    </td>
                </tr>
            </table>

            @if (isset($error))
                <center style="margin-top: 20px">
                    <p style="margin: 0 10px; color: red;">{{ $error }}</p>
                </center>
            @endif
            <center style="margin-top: 20px">
                <button class="btnGreen" style="margin-right: 10px;">Войти</button>
                <a class="link" style="line-height: 37px;" href="{{ route('main') }}">На главную</a>
            </center>
        </form>
    </div>


@endsection
