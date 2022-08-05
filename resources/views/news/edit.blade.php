@extends('layouts.default')

@section('title'){{ isset($news) ? 'Редактирование новости' : 'Создание новости' }}@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/modalMsg.css') }}" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&display=swap');

        body {
            font-family: 'Open Sans', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
        font-family: 'Roboto', sans-serif;
        }

        .tox-notifications-container {
            display: none;
        }
        .tox-statusbar__branding{
            display: none;
        }

        .forTinymce > .tox-tinymce {
            margin: 20px;
        }

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
    </style>
@endsection

@section('scripts')
    <script src="{{asset('/js/imgLoader.js')}}"></script>
    <script src="{{asset('/js/include/tinymce.js')}}"></script>
    <script>
        tinymce.init({
            selector:'textarea',
            language: 'ru',
            plugins: 'image, table, link, fullscreen, lists',
        });

        function checkValidation() {
            var goSubmit = true;

            if ($('#title').val() == "") { alert("Введите заголовок!"); goSubmit = false; }
            else if (tinymce.activeEditor.getContent() == "") { alert("Введите текст!"); goSubmit = false; }

            if (goSubmit) $('#form').submit();
        }
    </script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <a href="{{ route('news') }}">Новости</a>
            <span class="arrow">➜</span>
            <span>{{ isset($news) ? 'Редактирование' : 'Создание' }}</span>
        </p>
    </div>
    <div class="content">
        <h3>{{ isset($news) ? 'Редактирование новости' : 'Создание новости' }}</h3>

        <form id="form" class="form" action="{{ route('news_create') }}" method="post">
            @csrf
            @if(isset($news))
                <input style="display: none" name='id' value="{{ $news->id }}">
                @include('layouts.edit_miniInfo', ['item' => $news])
            @endif

            <p>Заголовок:</p>
            <div class="inputCell">
                <input id="title" class="input" name="title" value="{{ isset($news) ? $news->title : '' }}" maxlength="255">
            </div>

            <p>Изображение:</p>
            <div class="centerForImageLoader" style="height: 280px">
                <img-loader src="{{ isset($news) ? $news->icon : '' }}" id="image" name="image" mwidth="250px" mweight="16777215"></img-loader>
            </div>

            <p>Текст:</p>
            <div class="forTinymce">
                <textarea name="message" id="user_input">
                    {!! isset($news) ? $news->src : '' !!}
                </textarea>
            </div>

            <p>Кому адресовано:</p>
            <div class="inputCell">
                <select name="type" class="input">
                    <option value="staff"
                        {{ (isset($news) && $news->type == 'staff') ? 'selected' : '' }}
                        >Персоналу</option>
                    <option value="tenants"
                        {{ (isset($news) && $news->type == 'tenants') ? 'selected' : '' }}
                        >Квартиросъемщикам</option>
                </select>
            </div>

            @include('layouts.edit_validErrors', ['errors' => $errors])

            <button type="button" onclick="checkValidation()" class="btnGreen">
                @if (isset($news))
                    Изменить
                @else
                    Создать
                @endif
            </button>
        </form>
    </div>


@endsection
