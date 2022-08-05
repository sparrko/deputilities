@extends('layouts.default')

@section('title')Отмена заявки АДС (№{{ $ticket->id }})@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/modalMsg.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/helper.css') }}" />
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
            border-spacing: 10px 10px;
            width: 90%;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 20px;
        }
        .edit_table p {
            margin-bottom: 5px;
            color: #98a7ba;
        }

        .select2 {
            position: relative;
            left: 50%;
            transform: translateX(-50%);
        }

        .inline-block * {
            display: inline-block;
        }

        .helper-replace {
            position: relative;
            top: 5px;
        }

        .tableInfoStatic {
            margin-bottom: 10px;
        }
    </style>

@endsection

@section('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script src="{{asset('/js/imgLoader.js')}}"></script>
    <script src="{{asset('/js/helper.js')}}"></script>
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

    </script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <a href="{{ route('tickets') }}">Заявки АДС</a>
            <span class="arrow">➜</span>
            <span>Отмена заявки</span>
        </p>
    </div>
    <div class="content">
        <h3>Отмена заявки АДС (№{{ $ticket->id }})</h3>

        <form id="form" class="form" action="{{ route('ticket_archive_post', $ticket->id) }}" method="post">
            @csrf

            <table class="edit_table">
                <tr>
                    <td colspan="2">
                        <div class="inline-block">
                            <p>Причина отмены:</p>
                            <div class="helper helper-replace">
                                <img>
                                <div>
                                    <p>Укажите причину отмены (не более 255 символов).</p>
                                </div>
                            </div>
                        </div>
                        <textarea
                            name="archiveDesc"
                            maxlength="255"
                            class="input"
                            style="
                                resize: none;
                                height: 100px;
                            ">{{ old('desc') }}</textarea>
                    </td>
                </tr>
            </table>

            @include('layouts.edit_validErrors', ['errors' => $errors])

            <button type="button" onclick="checkValidation()" class="btnGreen">Продолжить</button>
            <a style="margin-left: 10px" class="link"
                href="{{ URL::previous() }}">
                Вернуться назад
            </a>
        </form>
    </div>


@endsection
