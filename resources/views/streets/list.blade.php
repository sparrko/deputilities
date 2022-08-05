@extends('layouts.default')

@section('title')Улицы@endsection

@section('scripts')
    <script src={{ asset('js/pagination.js') }}></script>
    <script>
        function archiveAction($id) {
            $.ajax({
                url: '{{ route("streets_archive") }}',
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
            <span>Улицы</span>
        </p>
    </div>
    <div class="content">
        <h3 style="margin-bottom: 40px">Улицы
            <span style="float: right;">
                <a href="{{ route('streets_create') }}"><button class="btnGreen" type="button">Создать</button></a>
            </span>
        </h3>
        <form class="filter" method="GET" action="{{ route('streets') }}" id="streetFilter">
            <table class="four-columns">
                <tr>
                    <td>По названию:</td>
                    <td>
                        <input name="name" class="input" value="{{ old('name') }}">
                    </td>
                    <td>По активности:</td>
                    <td>
                        <select name="status" class="input">
                            <option value="active"
                                >Активная</option>
                            <option value="any"
                                {{ (old('status') == 'any') ? 'selected' : '' }}
                                >Любая</option>
                            <option value="archived"
                                {{ (old('status') == 'archived') ? 'selected' : '' }}
                                >Архивная</option>
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
        @if(count($streets))
        <table class="dataTable">
            <tr>
                <th width="1px"></th>
                <th>Название</th>
                <th>Кол-во домов</th>
                <th>Дата регистрации</th>
            </tr>

                @foreach ($streets as $item)
                <tr>
                    <td>
                        <div class="contextMenu">
                            <img>
                            <ul>
                                <a href="{{ route('streets_edit', $item->id) }}" target="_blank"><li>Редактировать</li></a>
                                <a href="{{ route('houses', ['idStreet' => $item->id]) }}" target="_blank"><li>Просмотр домов</li></a>
                                <a onclick="archiveAction({{ $item->id }})"><li>{{ ($item->archived_at != null) ? "Разархировать" : "Архивировать" }}</li></a>
                            </ul>
                        </div>
                    </td>
                    <td>
                        {{ $item->name }}
                        @if($item->archived_at != null)
                            <br><span style="color: red">(в архиве)</span>
                        @endif
                    </td>
                    <td>
                        {{ $item->houses->count() }}
                    </td>
                    <td>
                        {{ Carbon\Carbon::parse($item->created_at)->toDateString() }}
                    </td>
                </tr>
                @endforeach
        </table>
        <hr>

        @include('layouts.pagination', ['countForPag' => count($streets), 'idForPagination' => 'streetFilter'])

        @else
            <p class="subText">Данные отсутсвуют</p>
        @endif
    </div>
@endsection
