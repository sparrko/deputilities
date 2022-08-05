@extends('layouts.default')

@section('title')Главная@endsection

@section('styles')
    <style>
        .block a {
            margin-bottom: 5px;
            margin-left: 10px;
        }
    </style>
@endsection

@section('scripts')@endsection

@section('content')

<div class="linkNow">
    <p>Главная</p>
</div>
<div class="content">
    @if (!Auth::check())
        @include('main.include.nonReg')
    @elseif (Auth::user()->staff)
        <h2 style="text-align: center">
            @php
                $hour = Carbon\Carbon::now()->format('H');

                if ($hour < 6) echo "Добрая ночь, ";
                else if ($hour < 12) echo "Доброе утро, ";
                else if ($hour < 18) echo "Добрый день, ";
                else if ($hour < 24) echo "Добрый вечер, ";

                if (Auth::user()->staff != null)
                    echo Auth::user()->staff->name;
                else
                    echo Auth::user()->tenant->name;
            @endphp
        </h2>
        <h3>Доступный вам функционал:</h3>
        <div class="block">
            <a href="{{ route('profile') }}" class="link">Просмотр вашего профиля</a>
            @if (Auth::user()->staff != null)
                @if (Auth::user()->staff->role == 'admin')
                    <a href="{{ route('news') }}" class="link">Редактирование новостей</a>
                    <a href="{{ route('company_info') }}" class="link">Редактирование информации о компании</a>
                @elseif (Auth::user()->staff->role == 'staffOfficer')
                    <a href="{{ route('staff') }}" class="link">Редактирование сотрудников</a>
                    <a href="{{ route('news_list_for_all') }}" class="link">Просмотр новостей</a>
                @elseif (Auth::user()->staff->role == 'referenceOfficer')
                    <a href="{{ route('streets') }}" class="link">Редактирование улиц</a>
                    <a href="{{ route('houses') }}" class="link">Редактирование домов</a>
                    <a href="{{ route('tenants') }}" class="link">Редактирование квартиросъемщиков</a>
                    <a href="{{ route('news_list_for_all') }}" class="link">Просмотр новостей</a>
                @elseif (Auth::user()->staff->role == 'dispatcher')
                    <a href="{{ route('tickets') }}" class="link">АДС система</a>
                    <a href="{{ route('ticket_types') }}" class="link">Типы заявок</a>
                    <a href="{{ route('news_list_for_all') }}" class="link">Просмотр новостей</a>
                @elseif (Auth::user()->staff->role == 'executor')
                    <a href="{{ route('tickets') }}" class="link">АДС система</a>
                    <a href="{{ route('news_list_for_all') }}" class="link">Просмотр новостей</a>
                @endif


                @if (Auth::user()->staff->role != 'admin')
                    @php
                        $news = App\Models\News::where('type', 'staff')->take(2)->orderBy('created_at', 'DESC')->get();
                    @endphp

                    @if(count($news))
                    <h3>Новости
                        <a href="{{ route('news_list_for_all') }}" class="link" style="float: right; font-size: 16px">все новости</a>
                    </h3>
                    <div class="newsMini">
                        @foreach ($news as $item)
                        <a href="{{ route('news_view_tenant', $item->id) }}">
                        <div>
                            <div>
                                <img
                                    src="{{ ($item->icon == null) ? asset('images/newsNoImage.jpg') : $item->icon }}"
                                    >
                            </div>
                            <div>
                                <p>{{ $item->title }}</p>
                                <p style="font-size: 12px; color: gray;">{{ Carbon\Carbon::parse($item->created_at)->toDateString() }}</p>
                                <p>{{ $item->getMiniSrc() }}</p>
                            </div>
                        </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                @endif
            @endif
        </div>
    @elseif (Auth::user()->tenant)
        @include('main.include.tenant')
    @endif

</div>

@endsection
