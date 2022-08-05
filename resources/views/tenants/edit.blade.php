@extends('layouts.default')

@section('title')Редактирование квартиросъемщика@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/modalMsg.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/elements/select2/select2.min.css') }}">
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

        .select2 {
            margin-top: 15px;
            margin-bottom: 30px;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
            margin-left: 7px;
        }
    </style>

@endsection

@section('scripts')
    <script src="{{asset('/js/imgLoader.js')}}"></script>
    <script src="{{asset('/js/ruToEng.js')}}"></script>

    <script src="{{ asset('js/include/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/include/select2/i18n/ru.js') }}"></script>

    <script>
        function checkValidation() {
            var goSubmit = true;

            // if ($('#title').val() == "") { alert("Введите заголовок!"); goSubmit = false; }
            // else if (tinymce.activeEditor.getContent() == "") { alert("Введите текст!"); goSubmit = false; }

            if (goSubmit) $('#form').submit();
        }

        $(document).ready(function() {
            $('.select2').select2({
                maximumSelectionLength: 2,
                language: "ru"
            });
        });

        function randomUser() {
            var surname = $('#surname').val();

            var uni = (surname.length > 0) ? ruToEng(surname) + "_" : "";
            let today = new Date();
            let milliseconds = today.getTime();
            uni += milliseconds;

            $('#login').val(uni);
            $('#password').val(uni);
        }


    </script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <a href="{{ route('tenants') }}">Квартиросъемщики</a>
            <span class="arrow">➜</span>
            <span>{{ isset($tenant) ? 'Редактирование' : 'Создание' }}</span>
        </p>
    </div>
    <div class="content">
        <h3>{{ isset($tenant) ? 'Редактирование квартиросъемщика' : 'Создание квартиросъемщика' }}</h3>

        <form id="form" class="form" action="{{ route('tenants_create') }}" method="post">
            @csrf
            @if(isset($tenant))
                <input style="display: none" name='id' value="{{ $tenant->id }}">
                @include('layouts.edit_miniInfo', ['item' => $tenant])
            @endif

            <table class="edit_table">
                <tr>
                    <td>
                        <p>Фамилия:</p>
                        <div class="inputCell">
                            <input class="input" id="surname" name="surname" maxlength="255"
                                value="{{ old('surname') ? old('surname') : (isset($tenant) ? $tenant->surname : '') }}">
                        </div>
                    </td>
                    <td>
                        <p>Номер телефона:</p>
                        <div class="inputCell">
                            <input class="input phone" name="phone"
                                value="{{ old('phone') ? old('phone') : (isset($tenant) ? $tenant->phone : '') }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Имя:</p>
                        <div class="inputCell">
                            <input class="input" name="name" maxlength="255"
                                value="{{ old('name') ? old('name') : (isset($tenant) ? $tenant->name : '') }}">
                        </div>
                    </td>
                    <td>
                        <p>Дом:</p>
                        <div class="inputCell">
                            <div class="inputCell" style="padding-right: 15px">
                                <select name="address" class="select2">
                                    @foreach (App\Models\House::getAllOptions() as $address)
                                        <option
                                            {{ old('address') == $address->id ? 'selected' :
                                                (isset($tenant) && $tenant->idHouse == $address->id) ? 'selected' :
                                                (isset($searchHouse) && $searchHouse->id == $address->id) ? 'selected' : '' }}
                                            value="{{ $address->id }}"
                                            >{{ $address->text }}</option>
                                        >{{ $address->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Отчество:</p>
                        <div class="inputCell">
                            <input class="input" name="patr" maxlength="255"
                                value="{{ old('patr') ? old('patr') : (isset($tenant) ? $tenant->patr : '') }}">
                        </div>
                    </td>
                    <td>
                        <p>Квартира:</p>
                        <div class="inputCell">
                            <input class="input" name="room" maxlength="255"
                                value="{{ old('room') ? old('room') : (isset($tenant) ? $tenant->room : '') }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Логин:</p>
                        <div class="inputCell">
                            <input class="input" id="login" name="login" maxlength="255"
                                value="{{ old('login') ? old('login') : (isset($tenant) ? $tenant->user->login : '') }}">
                        </div>
                    </td>
                    <td>
                        <p>Пароль:
                            @if(isset($tenant))
                                <span style="color: gray">(введите чтобы изменить)</span>
                            @endif
                        </p>
                        <div class="inputCell">
                            <input class="input" id="password" name="password" maxlength="255">
                        </div>
                    </td>
                </tr>
            </table>


            @include('layouts.edit_validErrors', ['errors' => $errors])


            <button type="button" onclick="checkValidation()" class="btnGreen">
                @if (isset($tenant))
                    Изменить
                @else
                    Создать
                @endif
            </button>
            <button type="button" onclick="randomUser()" class="btnTrans">
                Сгенерировать логин и пароль
            </button>
            <a style="margin-left: 10px" class="link"
                href="{{ route('tenants') }}">
                Вернуться назад
            </a>
        </form>
    </div>


@endsection
