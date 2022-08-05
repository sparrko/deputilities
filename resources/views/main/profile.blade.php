@extends('layouts.default')

@section('title')Профиль@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
@endsection

@section('scripts')
    <script src="{{asset('/js/imgLoader.js')}}"></script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <span>Профиль</span>
        </p>
    </div>
    <div class="content">
        <h3>Ваш профиль</h3>
        <form action="{{ route('profile_post') }}" method="post">
            @csrf

            @if($user->staff != null)
                <p style="text-align: center">Фото:</p>
                <center>
                    <div class="centerForImageLoader" style="height: 280px">
                        <img-loader src="{{ $user->staff->icon }}" id="image" name="image" mwidth="250px" mweight="16777215"></img-loader>
                    </div>
                </center>
            @endif

            @include('layouts.edit_miniInfo', ['item' => $user->staff != null ? $user->staff : $user->tenant])

            <table class="tableInfoStatic">
                <tr>
                    <td>ФИО:</td>
                    <td>{{ $user->staff != null ? $user->staff->fullName : $user->tenant->fullName }}</td>
                </tr>
                <tr>
                    @if($user->staff != null)
                        <td>Роль:</td>
                        <td>{{ __('user.' . Auth::user()->staff->role) }}</td>
                    @else
                        <td>Адрес:</td>
                        <td>{{ $user->tenant->address }}</td>
                    @endif
                </tr>
            </table>
            <table class="edit_table" style="margin-top: 20px">
                <tr>
                    <td>
                        <p>Номер телефона:</p>
                        <div class="inputCell">
                            <input class="input phone" name="phone" value="{{ $user->staff != null ? $user->staff->phone : $user->tenant->phone }}">
                        </div>
                    </td>
                </tr>
            </table>

            @include('layouts.edit_validErrors', ['errors' => $errors])

            <button type="submit" class="btnGreen">Сохранить изменения</button>
        </form>
    </div>


@endsection
