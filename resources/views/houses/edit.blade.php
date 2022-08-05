@extends('layouts.default')

@section('title')Редактирование дома@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/modalMsg.css') }}" />
    <style>
        .form {
            padding: 10px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/elements/select2/select2.min.css') }}">

@endsection

@section('scripts')
    <script src="{{asset('/js/imgLoader.js')}}"></script>

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
                placeholder: "Выберите город",
                maximumSelectionLength: 2,
                language: "ru"
            });
        });
    </script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <a href="{{ route('houses') }}">Дома</a>
            <span class="arrow">➜</span>
            <span>{{ isset($house) ? 'Редактирование' : 'Создание' }}</span>
        </p>
    </div>
    <div class="content">
        <h3>
            @if (isset($searchStreet))
                Создание дома на улице {{ $searchStreet->name }}
            @else
                {{ isset($house) ? 'Редактирование дома' : 'Создание дома' }}
            @endif
        </h3>

        <form id="form" class="form" action="{{ route('houses_create') }}" method="post">
            @csrf
            @if(isset($house))
                @include('layouts.edit_miniInfo', ['item' => $house])
                <input style="display: none" name='id' value="{{ $house->id }}">
            @endif

            <table class="edit_table">
                <tr>
                    <td>
                        <p>Номер:</p>
                        <div class="inputCell">
                            <input class="input" name="number" maxlength="255"
                                value="{{ old('number') ? old('number') : (isset($house) ? $house->number : '') }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Улица:</p>
                        <div style="padding: 15px 0px; width: 95%; position: relative; left: 50%; transform: translateX(-50%); margin-bottom: 30px">
                            @if(isset($searchStreet))
                                <select name="street" class="select2" disabled>
                                    <option value="{{ $searchStreet->id }}" selected>{{ $searchStreet->name }}</option>
                                </select>
                                <input style="display: none" name="street" value="{{ $searchStreet->id }}">
                            @else
                                <select name="street" class="select2">
                                    @foreach ($streets as $street)
                                        <option value="{{ $street->id }}"
                                            @php
                                                if (old('street') == $street->id) {
                                                    echo "selected";
                                                }
                                                else if (isset($house)) {
                                                    if ($house->street->id == $street->id) {
                                                        echo "selected";
                                                    }
                                                }
                                            @endphp
                                            >{{ $street->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            @include('layouts.edit_validErrors', ['errors' => $errors])

            <button type="button" onclick="checkValidation()" class="btnGreen">
                @if (isset($house))
                    Изменить
                @else
                    Создать
                @endif
            </button>
            <a style="margin-left: 10px" class="link"
                href="{{ route('houses') }}">
                Вернуться назад
            </a>
        </form>
    </div>


@endsection
