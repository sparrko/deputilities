@extends('layouts.default')

@section('title')Редактирование улицы@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/modalMsg.css') }}" />
    <style>
        .form {
            padding: 10px;
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
            <a href="{{ route('streets') }}">Улицы</a>
            <span class="arrow">➜</span>
            <span>{{ isset($street) ? 'Редактирование' : 'Создание' }}</span>
        </p>
    </div>
    <div class="content">
        <h3>{{ isset($street) ? 'Редактирование улицы' : 'Создание улицы' }}</h3>

        <form id="form" class="form" action="{{ route('streets_create') }}" method="post">
            @csrf
            @if(isset($street))
                @include('layouts.edit_miniInfo', ['item' => $street])
                <input style="display: none" name='id' value="{{ $street->id }}">
            @endif

            <table class="edit_table">
                <tr>
                    <td>
                        <p>Название:</p>
                        <div class="inputCell">
                            <input class="input" name="name" maxlength="255"
                                value="{{ old('name') ? old('name') : (isset($street) ? $street->name : '') }}">
                        </div>
                    </td>
                </tr>
            </table>

            @include('layouts.edit_validErrors', ['errors' => $errors])

            <button type="button" onclick="checkValidation()" class="btnGreen">
                @if (isset($street))
                    Изменить
                @else
                    Создать
                @endif
            </button>
            <a style="margin-left: 10px" class="link"
                href="{{ route('streets') }}">
                Вернуться назад
            </a>
        </form>
    </div>


@endsection
