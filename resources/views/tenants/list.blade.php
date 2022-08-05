@extends('layouts.default')

@section('title')Квартиросъемщики@endsection

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
                url: '{{ route("tenants_archive") }}',
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
            <span>Квартиросъемщики {{ (isset($searchHouse)) ? 'дома ' . $searchHouse->street->name . " " . $searchHouse->number : ''  }}</span>
        </p>
    </div>
    <div class="content">
        <h3 style="margin-bottom: 40px">Квартиросъемщики {{ (isset($searchHouse)) ? 'дома ' . $searchHouse->street->name . " " . $searchHouse->number : ''  }}
            <span style="float: right;">
                <a href="{{ (isset($searchHouse)) ? route('tenants_create', ['searchHouse' => $searchHouse->id]) : route('tenants_create') }}"><button class="btnGreen" type="button">Создать {{ (isset($searchHouse)) ? 'на ' . $searchHouse->street->name . " " . $searchHouse->number : ''  }}</button></a>
            </span>
        </h3>
        <form class="filter" method="GET" action="{{ route('tenants') }}" id="tenantsFilter">
            <table class="four-columns">
                <tr>
                    <td>По активности:</td>
                    <td>
                        <select name="status" class="input">
                            <option value="active"
                                >Активный</option>
                            <option value="any"
                                {{ (old('status') == 'any') ? 'selected' : '' }}
                                >Любой</option>
                            <option value="archived"
                                {{ (old('status') == 'archived') ? 'selected' : '' }}
                                >Архивный</option>
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
            <table>
                <tr>
                    <td>По адресу:</td>
                    <td>
                        <select name="address" class="select2">
                            <option value="any">Любой</option>
                            @foreach (App\Models\House::getAllOptions() as $address)
                                <option value="{{ $address->id }}"
                                    @php
                                        if (old('address') == $address->id) {
                                            echo "selected";
                                        }
                                        else
                                        if (isset($searchHouse)) {
                                            if ($searchHouse->id == $address->id) {
                                                echo "selected";
                                            }
                                        }
                                    @endphp
                                     >{{ $address->text }}</option>
                            @endforeach
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
        @if(count($tenants))
        <table class="dataTable">
            <tr>
                <th width="1px"></th>
                <th>Код</th>
                <th>ФИО</th>
                <th>Телефон</th>
                <th>Адрес</th>
            </tr>

                @foreach ($tenants as $item)
                <tr>
                    <td>
                        <div class="contextMenu">
                            <img>
                            <ul>
                                <a href="{{ route('tenants_edit', $item->id) }}" target="_blank"><li>Редактировать</li></a>
                                <a onclick="archiveAction({{ $item->id }})"><li>{{ ($item->archived_at != null) ? "Разархировать" : "Архивировать" }}</li></a>
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
                        @if(isset($item->phone))
                            {{ $item->phone }}
                        @else
                            -
                        @endif
                    </td>
                    <td>

                        {{
                            App\Models\Street::withArchived()->find(
                                App\Models\House::withArchived()->find(
                                    $item->idHouse
                                )->idStreet
                            )->name
                        }}
                        {{ App\Models\House::withArchived()->find($item->idHouse)->number }}, кв
                        {{ $item->room }}
                        @if($item->house == null)
                            <span style="color: red">(архивный дом)</span>
                        @elseif(App\Models\Street::find($item->house->idStreet) == null)
                            <span style="color: red">(архивная улица)</span>
                        @endif
                    </td>
                </tr>
                @endforeach
        </table>
        <hr>

        @include('layouts.pagination', ['countForPag' => count($tenants), 'idForPagination' => 'tenantsFilter'])

        @else
            <p class="subText">Данные отсутсвуют</p>
        @endif
    </div>
@endsection
