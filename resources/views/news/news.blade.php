@extends('layouts.default')

@section('title')Новости@endsection

@section('scripts')
    <script src={{ asset('js/pagination.js') }}></script>
    <script>
        function archiveAction($id) {
            $.ajax({
                url: '{{ route("news_archive") }}',
                type: 'post',
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": $id
                },
                success: function(data){
                    $('#filterButton').click();
                }
            });
        }
    </script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            @if(Auth::user()->staff != null && Auth::user()->staff->role == App\Models\Staff::ROLE['admin'])
            <span>Новости</span>
            @endif
        </p>
    </div>
    <div class="content">
        <h3 style="margin-bottom: 40px">Новости
            <span style="float: right;">
                <a href="{{ route('news_create') }}" target="_blank"><button class="btnGreen" type="button">Создать</button></a>
            </span>
        </h3>
        <form class="filter" method="GET" action="{{ route('news') }}" id="newsFilter">
            <table class="filterDate">
                <tr><td></td><td>с</td><td>по</td></tr>
                <tr>
                    <td width="19%">Дата создания:</td>
                    <td><input name="dtStart" class="input" type="date" value="{{ old('dtStart') }}"></td>
                    <td><input name="dtEnd" class="input" type="date" value="{{ old('dtEnd') }}"></td>
                </tr>
            </table>
            <table class="four-columns">
                <tr>
                    <td>По активности:</td>
                    <td>
                        <select name="status" class="input">
                            <option value="active"
                                >Активные</option>
                            <option value="any"
                                {{ (old('status') == 'any') ? 'selected' : '' }}
                                >Любые</option>
                            <option value="archived"
                                {{ (old('status') == 'archived') ? 'selected' : '' }}
                                >Архивные</option>
                        </select>
                    </td>
                    <td>Для кого:</td>
                    <td>
                        <select name="type" class="input">
                            <option value="any"
                                {{ (old('type') == 'archived') ? 'selected' : '' }}
                                >Не важно</option>
                            <option value="staff"
                                {{ (old('type') == 'staff') ? 'selected' : '' }}
                                >Персоналу</option>
                            <option value="tenants"
                                {{ (old('type') == 'tenants') ? 'selected' : '' }}
                                >Квартиросъемщикам</option>
                        </select>
                    </td>
                </tr>
            </table>
            <center class="btns">
                <button class="btnTrans" type="submit" name="reset" value="1">Сбросить</button>
                <button class="btnGreen" type="submit" id="filterButton">Применить</button>
            </center>
        </form>
        <hr>
        @if(count($news))
        <table class="dataTable">
            <tr>
                <th width="1px">
                </th>
                <th>Заголовок</th>
                <th>Дата создания</th>
                <th>Тип</th>
            </tr>

                @foreach ($news as $item)
                <tr>
                    <td>
                        <div class="contextMenu">
                            <img>
                            <ul>
                                <a href="{{ route('news_view', $item->id) }}"><li>Просмотр</li></a>
                                <a href="{{ route('news_edit', $item->id) }}" target="_blank"><li>Редактировать</li></a>
                                <a onclick="archiveAction({{ $item->id }})"><li>{{ ($item->archived_at != null) ? "Разархировать" : "Архивировать" }}</li></a>
                            </ul>
                        </div>
                    </td>
                    <td>
                        {{ $item->title }}
                        @if($item->archived_at != null)
                        <span style="color: red">(в архиве)</span>
                        @endif
                    </td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->type == 'staff' ? 'Для персонала' : 'Для жителей'  }}</td>
                </tr>
                @endforeach
        </table>
        <hr>

        @include('layouts.pagination', ['countForPag' => count($news), 'idForPagination' => 'newsFilter'])

        @else
            <p class="subText">Данные отсутсвуют</p>
        @endif
    </div>
@endsection
