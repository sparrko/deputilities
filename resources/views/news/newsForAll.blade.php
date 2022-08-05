@extends('layouts.default')

@section('title')Новости@endsection

@section('scripts')
    <script src={{ asset('js/pagination.js') }}></script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <span>Новости</span>
        </p>
    </div>
    <div class="content">
        <h3 style="margin-bottom: 40px">Новости</h3>
        <form class="filter" method="GET" action="{{ route('news_list_for_all') }}" id="newsFilter">
            <table class="filterDate">
                <tr><td></td><td>с</td><td>по</td></tr>
                <tr>
                    <td width="19%">Дата создания:</td>
                    <td><input name="dtStart" class="input" type="date" value="{{ old('dtStart') }}"></td>
                    <td><input name="dtEnd" class="input" type="date" value="{{ old('dtEnd') }}"></td>
                </tr>
            </table>
            <center class="btns">
                <button class="btnTrans" type="submit" name="reset" value="1">Сбросить</button>
                <button class="btnGreen" type="submit" id="filterButton">Применить</button>
            </center>
        </form>
        <hr>
        @if(count($news))
            <div class="newsMini">
                @foreach ($news as $item)
                <a href="{{ route('news_view_tenant', $item->id) }}">
                <div>
                    <div>
                        <img
                            src="{{ ($item == null) ? asset('images/newsNoImage.jpg') : $item->icon }}"
                            >
                    </div>
                    <div>
                        <p>{{ $item->title }}</p>
                        <p style="font-size: 12px; color: gray;">{{ \Carbon\Carbon::parse($item->created_at)->toDateString() }}</p>
                        <p>{{ $item->getMiniSrc() }}</p>
                    </div>
                </div>
                </a>
                @endforeach
            <div>
            <hr>

            @include('layouts.pagination', ['countForPag' => count($news), 'idForPagination' => 'newsFilter'])

        @else
            <p class="subText">Данные отсутсвуют</p>
        @endif
    </div>
@endsection
