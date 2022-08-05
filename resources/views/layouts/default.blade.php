<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>УК "Богатырь" - @yield('title')</title>
        <style>
            * {
                padding: 0; margin: 0;
                font-family: ubuntu;
            }
        </style>
        @include('layouts.css')
        <link rel="stylesheet" href="{{ asset('css/default.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
        <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
        <link rel="stylesheet" href="{{ asset('css/print.css') }}">

        <link rel="stylesheet" href="{{ asset('css/elements/contextMenu.css') }}">
        <link rel="stylesheet" href="{{ asset('css/elements/filter.css') }}">
        <link rel="stylesheet" href="{{ asset('css/elements/pagination.css') }}">
        <link rel="stylesheet" href="{{ asset('css/elements/goUp.css') }}">
        @yield('styles')

        <script src={{ asset('js/include/jquery-3.6.0.js') }}></script>
        <script src={{ asset('js/include/jquery-ui.js') }}></script>
        @yield('scripts')
        <script src={{ asset('js/profileHeaderMenu.js') }}></script>
        <script src={{ asset('js/contextMenu.js') }}></script>
        <script src={{ asset('js/valid.js') }}></script>
        <script src={{ asset('js/goUp.js') }}></script>
    </head>
    <body>
        <header>
            <center>
                <div class="left toLeft">
                    <a href="{{ route('main') }}">
                        <img src="{{ asset('images/logo.png') }}">
                        <p>УК "Богатырь"</p>
                    </a>
                </div>
                @if (Auth::check())
                    <div class="right toRight">
                        <div>
                            <div id="profileHeaderImg">
                                <img
                                    @if(Auth::user()->staff != null && Auth::user()->staff->icon != null)
                                        src="{{ Auth::user()->staff->icon }}"
                                    @else
                                        src="{{ asset('images/userNoIcon.jpg') }}"
                                    @endif
                                    >
                            </div>
                            <ul id="profileHeaderMenu">
                                <a href="{{ route('profile') }}"><li>Мой профиль</li></a>
                                <a href="{{ route('logout') }}"><li>Выход</li></a>
                            </ul>
                        </div>
                        <p>
                            @if(Auth::user()->staff != null)
                                <b>{{ Auth::user()->staff->surnameName }}</b>
                                <br><span style="font-size: 12px">{{ __('user.' . Auth::user()->staff->role) }}</span>
                            @else
                                <b>{{ Auth::user()->tenant->surnameName }}</b>
                                <br>
                                @if (Auth::user()->tenant->house == null) <span style="font-size: 12px; color: red;">(ваш дом не актуален)</span>
                                @elseif (Auth::user()->tenant->house->street == null) <span style="font-size: 12px; color: red;">(ваша улица не актуальна)</span>
                                @else <span style="font-size: 12px">{{ Auth::user()->tenant->house->street->name }} {{ Auth::user()->tenant->house->number }}, кв {{ Auth::user()->tenant->room }}</span>@endif
                            @endif
                        </p>
                    </div>
                @else
                    <div class="right toRight">
                        <a href="{{ route('login') }}" class="link" style="line-height: 65px;">Авторизация</a>
                    </div>
                @endif

            </center>
        </header>
        <nav>
            <center>
                <ul>
                    <li><a href="{{ route('main') }}">Главная</a></li>
                    @if (isset(Auth::user()->staff))
                        @if (Auth::user()->staff->role == 'admin')
                            <li><a href="{{ route('news') }}">Новости</a></li>
                            <li><a href="{{ route('company_info') }}">Компания</a></li>
                        @elseif(Auth::user()->staff->role == 'dispatcher')
                            <li><a href="{{ route('tickets') }}">Диспетчерская</a></li>
                            <li><a href="{{ route('ticket_types') }}">Типы заявок</a></li>
                            <li><a href="{{ route('news_list_for_all') }}">Новости</a></li>
                        @elseif(Auth::user()->staff->role == 'executor')
                            <li><a href="{{ route('tickets') }}">Диспетчерская</a></li>
                            <li><a href="{{ route('news_list_for_all') }}">Новости</a></li>
                        @elseif(Auth::user()->staff->role == 'referenceOfficer')
                            <li><a href="{{ route('streets') }}">Улицы</a></li>
                            <li><a href="{{ route('houses') }}">Дома</a></li>
                            <li><a href="{{ route('tenants') }}">Квартиросъемщики</a></li>
                            <li><a href="{{ route('news_list_for_all') }}">Новости</a></li>
                        @elseif(Auth::user()->staff->role == 'staffOfficer')
                            <li><a href="{{ route('staff') }}">Сотрудники</a></li>
                            <li><a href="{{ route('news_list_for_all') }}">Новости</a></li>
                        @endif
                    @else
                        @if(isset(Auth::user()->tenant))
                            <li><a href="{{ route('tickets') }}">Диспетчерская</a></li>
                        @endif
                        <li><a href="{{ route('news_list_for_all') }}">Новости</a></li>
                        <li><a href="{{ route('aboutCompany') }}">О компании</a></li>
                    @endif
                </ul>
            </center>
        </nav>
        <main>
            <center>
                @yield('content')
            </center>
        </main>
        <footer>
            <center>
                <div class="left toLeft">
                    <p>ООО УК "Богатырь" {{ Carbon\Carbon::now()->format('Y') }}</p>
                </div>
                <div class="right toRight">
                    <div class="fonerius" title="Fonerius-Software - разработка web-проекта">
                        <a><b>fonerius</b></a>
                    </div>
                </div>
            </center>
        </footer>
        <button id="goUpButton" style="display: none">Наверх ⤴</button>
        @yield('post')
    </body>
</html>
