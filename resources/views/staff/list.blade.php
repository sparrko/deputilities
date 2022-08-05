@extends('layouts.default')

@section('title')Сотрудники@endsection

@section('scripts')
    <script src={{ asset('js/pagination.js') }}></script>
    <script>
        function archiveAction($id) {
            $.ajax({
                url: '{{ route("staff_archive") }}',
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
            <span>Сотрудники</span>
        </p>
    </div>
    <div class="content">
        <h3 style="margin-bottom: 40px">Сотрудники
            <span style="float: right;">
                <a href="{{ route('staff_create') }}"><button class="btnGreen" type="button">Создать</button></a>
            </span>
        </h3>
        <form class="filter" method="GET" action="{{ route('staff') }}" id="staffFilter">
            <table class="four-columns">
                <tr>
                    <td>Роль:</td>
                    <td>
                        <select name="role" class="input">
                            <option
                                value="any"
                                {{ (old('role') == 'any') ? 'selected' : '' }}
                                >Любая</option>
                            @foreach ($roles as $role)
                                <option
                                    {{ (old('role') == $role) ? 'selected' : '' }}
                                    value="{{ $role }}"
                                    >{{ __('user.' . $role) }}</option>
                            @endforeach
                        </select>
                    </td>
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
                </tr>
            </table>
            <table>
                <tr>
                    <td>ФИО:</td>
                    <td>
                        <input name="fullName" id="filter_fullName" class="input" value="{{ old('fullName') }}">
                    </td>
                </tr>
            </table>

            <center class="btns">
                <button class="btnTrans" type="submit" name="reset" value="1">Сбросить</button>
                <button class="btnGreen" type="submit" id="filterButton">Применить</button>
            </center>
        </form>
        <hr>
        @if(count($staff))
        <table class="dataTable">
            <tr>
                <th width="1px"></th>
                <th>Таб-ном</th>
                <th>ФИО</th>
                <th>Роль</th>
                <th>Телефон</th>
                <th>Дата рождения</th>
            </tr>

                @foreach ($staff as $item)
                <tr>
                    <td>
                        <div class="contextMenu">
                            <img>
                            <ul>
                                @if ($item->id != Auth::user()->staff->id)
                                    <a href="{{ route('staff_edit', $item->id) }}" target="_blank"><li>Редактировать</li></a>
                                    <a onclick="archiveAction({{ $item->id }})"><li>{{ ($item->archived_at != null) ? "Разархировать" : "Архивировать" }}</li></a>
                                @else
                                    <a><li style="color: red; cursor: default;">Себя нельзя редактировать!</li></a>
                                @endif
                            </ul>
                        </div>
                    </td>
                    <td>
                        {{ $item->id }}
                    </td>
                    <td>
                        {{ $item->surname }}<br>{{ $item->name }} {{ $item->patr }}
                        @if($item->archived_at != null)
                        <br><span style="color: red">(в архиве)</span>
                        @endif
                    </td>
                    <td>
                        {{ __('user.' . $item->role) }}
                    </td>
                    <td>
                        {{ $item->phone }}
                    </td>
                    <td>
                        {{ Carbon\Carbon::parse($item->dateBorn)->format('d.m.Y') }}
                    </td>
                </tr>
                @endforeach
        </table>
        <hr>

        @include('layouts.pagination', ['countForPag' => count($staff), 'idForPagination' => 'staffFilter'])

        @else
            <p class="subText">Данные отсутсвуют</p>
        @endif
    </div>
@endsection
