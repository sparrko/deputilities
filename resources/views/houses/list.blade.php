@extends('layouts.default')

@section('title')Дома@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/elements/select2/select2.min.css') }}">
@endsection

@section('scripts')
    <script src={{ asset('js/pagination.js') }}></script>

    <script src="{{ asset('js/include/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/include/select2/i18n/ru.js') }}"></script>

    <script>
        function archiveAction($id) {
            $.ajax({
                url: '{{ route("houses_archive") }}',
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
            <span>Дома</span>
        </p>
    </div>
    <div class="content">
        <h3 style="margin-bottom: 40px">Дома {{ isset($streetSearch) ? 'на улице ' . $streetSearch->name : '' }}
            <span style="float: right;">
                <a href="
                    @if(isset($streetSearch))
                        {{ route('houses_create', ['idStreet' => $streetSearch->id]) }}
                    @else
                        {{ route('houses_create') }}
                    @endif
                    " target="_blank"><button class="btnGreen" type="button">Создать{{ isset($streetSearch) ? ' дом на ' . $streetSearch->name : '' }}</button></a>
            </span>
        </h3>
        <form class="filter" method="GET" action="{{ route('houses') }}" id="streetFilter">
            <table class="four-columns">
                <tr>
                    <td>Номер:</td>
                    <td>
                        <input name="number" class="input" value="{{ old('number') }}">
                    </td>
                    <td>По активности дома:</td>
                    <td>
                        <select name="status" class="input">
                            <option value="active">Активный</option>
                            <option value="any"
                                {{ (old('status') == 'any') ? 'selected' : '' }}
                                value="active"
                                >Любой</option>
                            <option value="archived"
                                {{ (old('status') == 'archived') ? 'selected' : '' }}
                                value="archived"
                                >Архивный</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Улица:</td>
                    <td>
                        <select name="street" class="select2">
                            <option value="any">Любая</option>
                            @foreach ($streets as $street)
                                <option value="{{ $street->id }}"
                                    @php
                                        if (old('street') == $street->id) {
                                            echo "selected";
                                        }
                                        else
                                        if (isset($streetSearch)) {
                                            if ($streetSearch->id == $street->id) {
                                                echo "selected";
                                            }
                                        }
                                    @endphp
                                     >{{ $street->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>По активности улицы:</td>
                    <td>
                        <select name="status_street" class="input">
                            <option value="any">Любая</option>
                            <option value="active"
                                {{ (old('status_street') == 'active') ? 'selected' : '' }}
                                >Активная</option>
                            <option value="archived"
                                {{ (old('status_street') == 'archived') ? 'selected' : '' }}
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
        @if(count($houses))
        <table class="dataTable">
            <tr>
                <th width="1px"></th>
                <th>Адрес</th>
                <th>Дата регистрации</th>
            </tr>
                @foreach ($houses as $item)
                <tr>
                    <td>
                        <div class="contextMenu">
                            <img>
                            <ul>
                                <a href="{{ route('houses_edit', $item->id) }}"><li>Редактировать</li></a>
                                <a href="{{ route('tenants', ['searchHouse' => $item->id]) }}"><li>Просмотр жителей</li></a>
                                <a onclick="archiveAction({{ $item->id }})"><li>{{ ($item->archived_at != null) ? "Разархировать" : "Архивировать" }}</li></a>
                            </ul>
                        </div>
                    </td>
                    <td>
                        {{ App\Models\Street::withArchived()->find($item->idStreet)->name }}
                        {{ $item->number }}
                        @if($item->archived_at != null)
                            <br><span style="color: red">(в архиве)</span>
                        @elseif(App\Models\Street::withArchived()->find($item->idStreet)->archived_at != null)
                            <br><span style="color: red">(улица в архиве)</span>
                        @endif
                    </td>
                    <td>
                        {{ Carbon\Carbon::parse($item->created_at)->toDateString() }}
                    </td>
                </tr>
                @endforeach
        </table>
        <hr>

        @include('layouts.pagination', ['countForPag' => count($houses), 'idForPagination' => 'streetFilter'])

        @else
            <p class="subText">Данные отсутсвуют</p>
        @endif
    </div>
@endsection
