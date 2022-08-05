@extends('layouts.default')

@section('title')Редактирование сотрудника@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/modalMsg.css') }}" />
    <style>
        .inputCell input, .inputCell select {
            margin-top: 15px;
            margin-bottom: 30px;
            position: relative;
            width: 95%;
            left: 50%;
            transform: translateX(-50%);
        }

        .form {
            padding: 10px;
        }

        .edit_table {
            /* border-spacing: 10px 10px; */
            width: 90%;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
@endsection

@section('scripts')
    <script src="{{asset('/js/imgLoader.js')}}"></script>
    <script src="{{asset('/js/include/tinymce.js')}}"></script>
    <script>
        function checkValidation() {
            var goSubmit = true;

            // if ($('#title').val() == "") { alert("Введите заголовок!"); goSubmit = false; }
            // else if (tinymce.activeEditor.getContent() == "") { alert("Введите текст!"); goSubmit = false; }

            if (goSubmit) $('#form').submit();
        }
    </script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <a href="{{ route('staff') }}">Сотрудники</a>
            <span class="arrow">➜</span>
            <span>{{ isset($staff) ? 'Редактирование' : 'Создание' }}</span>
        </p>
    </div>
    <div class="content">
        <h3>{{ isset($staff) ? 'Редактирование сотрудника' : 'Создание сотрудника' }}</h3>

        <form id="form" class="form" action="{{ route('staff_create') }}" method="post">
            @csrf
            @if(isset($staff))
                <input style="display: none" name='id' value="{{ $staff->id }}">
                @include('layouts.edit_miniInfo', ['item' => $staff])
            @endif

            <table class="edit_table">
                <tr>
                    <td rowspan="3">
                        <p>Фотография:</p>
                        <div class="centerForImageLoader" style="height: 280px">
                            <img-loader id="image" name="image" mwidth="250px" mweight="512000"
                                src="{{ old('image') ? old('image') : (isset($staff) ? $staff->icon : '') }}"></img-loader>
                        </div>
                    </td>
                    <td>
                        <p>Фамилия:</p>
                        <div class="inputCell">
                            <input class="input" name="surname" maxlength="255"
                                value="{{ old('surname') ? old('surname') : (isset($staff) ? $staff->surname : '') }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Имя:</p>
                        <div class="inputCell">
                            <input class="input" name="name" maxlength="255"
                                value="{{ old('name') ? old('name') : (isset($staff) ? $staff->name : '') }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Отчество:</p>
                        <div class="inputCell">
                            <input class="input" name="patr" maxlength="255"
                                value="{{ old('patr') ? old('patr') : (isset($staff) ? $staff->patr : '') }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Номер телефона:</p>
                        <div class="inputCell">
                            <input class="input phone" name="phone"
                                value="{{ old('phone') ? old('phone') : (isset($staff) ? $staff->phone : '') }}">
                        </div>
                    </td>
                    <td>
                        <p>Дата рождения</p>
                        <div class="inputCell">
                            <input type="date" class="input" name="dateborn"
                                value="{{ old('dateborn') ? old('dateborn') : (isset($staff) ? $staff->dateBorn : '') }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>Роль:</p>
                        <div class="inputCell">
                            <select name="role" class="input">
                                @foreach ($roles as $role)
                                    <option
                                        {{ old('role') == $role ? 'selected' : (isset($staff) && $staff->role == $role) ? 'selected' : '' }}
                                        value="{{ $role }}"
                                        >{{ __('user.' . $role) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Логин:</p>
                        <div class="inputCell">
                            <input class="input" name="login" maxlength="255"
                                value="{{ old('login') ? old('login') : (isset($staff) ? $staff->user->login : '') }}">
                        </div>
                    </td>
                    <td>
                        <p>Пароль:
                            @if(isset($staff))
                                <span style="color: gray">(введите чтобы изменить)</span>
                            @endif
                        </p>
                        <div class="inputCell">
                            <input class="input" name="password" maxlength="255">
                        </div>
                    </td>
                </tr>
            </table>


            @include('layouts.edit_validErrors', ['errors' => $errors])


            <button type="button" onclick="checkValidation()" class="btnGreen">
                @if (isset($staff))
                    Изменить
                @else
                    Создать
                @endif
            </button>
            <a style="margin-left: 10px" class="link"
                href="{{ route('staff') }}">
                Вернуться назад
            </a>
        </form>
    </div>


@endsection
