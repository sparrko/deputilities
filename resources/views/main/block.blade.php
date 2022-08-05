@extends('layouts.default')

@section('title')Не достаточно прав@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/modalMsg.css') }}" />
    <style>
    </style>
@endsection

@section('scripts')
    <script src="{{asset('/js/imgLoader.js')}}"></script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <span>Не достаточно прав</span>
        </p>
    </div>
    <div class="content">
        <h3>Доступ к странице или к данной функции запрещен</h3>
        <a href="{{ url()->previous() }}"><button class="btnGreen">Вернуться назад</button></a>
    </div>


@endsection
