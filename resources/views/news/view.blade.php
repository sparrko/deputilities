@extends('layouts.default')

@section('title')Просмотр новости@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/modalMsg.css') }}" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&display=swap');

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
            <a href="{{ route('news') }}">Новости</a>
            <span class="arrow">➜</span>
            <span>Просмотр</span>
        </p>
    </div>
    <div class="content">
        <h3>{{ $news->title }}</h3>
        <p style="font-size: 16px; font-weight: normal">{{ $news->created_at }}</p>
        @if ($news->icon != null)
            <br>
            <image style="position: relative; left: 50%; transform: translateX(-50%);" src="{{ $news->icon }}">
        @endif
        <hr>
        <div class="newsView">{!! $news->src !!}</div>
        <hr>
        <a href="{{ url()->previous() }}">
            <button class="btnGreen">
            Вернуться назад
            </button>
        </a>
    </div>


@endsection
