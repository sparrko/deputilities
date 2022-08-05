@extends('layouts.default')

@section('title')Заявки АДС@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/elements/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sub/tickets.css') }}">
@endsection

@section('scripts')
    <script src={{ asset('js/pagination.js') }}></script>

    <script src="{{ asset('js/include/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/include/select2/i18n/ru.js') }}"></script>

    <script>

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
            <span>Заявки АДС</span>
        </p>
    </div>
    <div class="content">
        <h3 style="margin-bottom: 40px">Заявки АДС
            <span style="float: right;">
                <a href="{{ route('ticket_create') }}"><button class="btnGreen" type="button">Создать {{ (isset($searchHouse)) ? 'на ' . $searchHouse->street->name . " " . $searchHouse->number : ''  }}</button></a>
            </span>
        </h3>
        <form class="filter" method="GET" action="{{ route('tickets') }}" id="ticketsFilter">
            <table class="filterDate">
                <tr><td></td><td>с</td><td>по</td></tr>
                <tr>
                    <td width="19%">Дата создания:</td>
                    <td><input name="created_at_start" class="input" type="date" value="{{ old('created_at_start') }}"></td>
                    <td><input name="created_at_end" class="input" type="date" value="{{ old('created_at_end') }}"></td>
                </tr>
                <tr>
                    <td width="19%">Дата завершения:</td>
                    <td><input name="completed_at_start" class="input" type="date" value="{{ old('completed_at_start') }}"></td>
                    <td><input name="completed_at_end" class="input" type="date" value="{{ old('completed_at_end') }}"></td>
                </tr>
            </table>
            <table class="four-columns">
                @if(Auth::user()->staff != null)
                    <tr>
                        <td>Статус:</td>
                        <td>
                            <select name="status" class="input">
                                <option value="any"
                                    >Любой</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ (old('status') == $status) ? 'selected' : '' }}
                                        >{{ __('ticket.' . $status) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>Диспетчер:</td>
                        <td>
                            <select name="dispatcher" class="select2">
                                <option value="any"
                                    >Все</option>
                                <option value="without"
                                    {{ (old('dispatcher') == 'without') ? 'selected' : '' }}
                                    >Без диспечера</option>
                                @foreach ($dispatchers as $dispatcher)
                                    <option value="{{ $dispatcher->id }}"
                                        {{ (old('dispatcher') == $dispatcher->id) ? 'selected' : '' }}
                                        >{{ $dispatcher->subFullName }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Тип:</td>
                        <td>
                            <select name="type" class="select2">
                                <option value="any"
                                    >Любой</option>
                                <option value="nothing"
                                    {{ (old('type') == "nothing") ? 'selected' : '' }}
                                    >Не определен</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ (old('type') == $type->id) ? 'selected' : '' }}
                                        >{{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        @if (Auth::user()->staff->role == 'dispatcher')
                        <td>Исполнитель:</td>
                        <td>
                            <select name="executor" class="select2">
                                <option value="any"
                                    >Все</option>
                                <option value="without"
                                    {{ (old('executor') == 'without') ? 'selected' : '' }}
                                    >Без исполнителя</option>
                                @foreach ($executors as $executor)
                                    <option value="{{ $executor->id }}"
                                        {{ (old('executor') == $executor->id) ? 'selected' : '' }}
                                        >{{ $executor->subFullName }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        @endif
                    </tr>
                @else
                    <tr>
                        <td>Статус:</td>
                        <td>
                            <select name="status" class="input">
                                <option value="any"
                                    >Любой</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ (old('status') == $status) ? 'selected' : '' }}
                                        >{{ __('ticket.' . $status) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>Тип:</td>
                        <td>
                            <select name="type" class="select2">
                                <option value="any"
                                    >Любой</option>
                                <option value="nothing"
                                    {{ (old('type') == "nothing") ? 'selected' : '' }}
                                    >Не определен</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ (old('type') == $type->id) ? 'selected' : '' }}
                                        >{{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endif
            </table>
            <center class="btns">
                <button class="btnTrans" type="submit" name="reset" value="1">Сбросить</button>
                <button class="btnGreen" type="submit" id="filterButton">Применить</button>
            </center>
        </form>
        <hr>
        @if(count($tickets))
            <table class="tickets">
                @foreach ($tickets as $ticket)
                    <tr>
                        <td class="status_td">
                            <a class="link" style="font-size: 24px;
                            @if($ticket->archived_at != null)
                                color: red
                            @endif"
                            target="_blank"
                            href="{{ route('ticket_edit', $ticket->id) }}">Заявка №{{ $ticket->id }}</a>
                            <br>
                            <p>
                            @if($ticket->idTicketType != null)
                                <span style="
                                    {{ $ticket->type == null ? 'color: red' : '' }}
                                ">
                                    {{ App\Models\TicketType::withArchived()->find($ticket->idTicketType)->name }}
                                </span>
                            @else
                                <span style="font-weight: bold">Тип не определен</span>
                            @endif
                            </p>

                            <div class="status">
                                <img style="width: 30px" src="{{ $ticket->iconStatus() }}">
                                <span>{{ __('ticket.' . $ticket->status()) }}</span>
                            </div>
                        </td>
                        <td>
                            <b>От:</b>
                            <p>{{ Carbon\Carbon::parse($ticket->created_at)->toDateString() }}</p>
                            @if(Auth::user()->staff != null)
                                @if($ticket->completed_at != null)
                                    <b>Дата выполнения:</b>
                                    <p>{{ Carbon\Carbon::parse($ticket->completed_at)->toDateString() }}</p>
                                @endif
                                @if($ticket->archived_at != null)
                                    <b>Дата отмены:</b>
                                    <p>{{ Carbon\Carbon::parse($ticket->archived_at)->toDateString() }}</p>
                                @endif
                                @if($ticket->idDispatcher != null)
                                    <b>Диспетчер:</b>
                                    <p>{{ App\Models\Staff::withArchived()->find($ticket->idDispatcher)->subFullName }}<span style="color: red">{{ $ticket->dispatcher == null ? '(в архиве)' : '' }}</span></p>
                                @endif
                            @endif
                        </td>
                            @if(Auth::user()->staff != null)
                                <td>
                                    <b>Квартиросъемщик:</b>
                                    <p>{{ App\Models\Tenant::withArchived()->find($ticket->idTenant)->subFullName }}</p>
                                    <b>Адрес:</b>
                                    <p>
                                        @php
                                            $tenant = App\Models\Tenant::withArchived()->find($ticket->idTenant);
                                            $house = App\Models\House::withArchived()->find($tenant->idHouse);
                                            $number = $house->number;
                                            $street = App\Models\Street::withArchived()->find($house->idStreet)->name;
                                            $room = $tenant->room;

                                            echo $street . " " . $number . ", " . $room;
                                        @endphp
                                    @if($ticket->idExecutor != null)
                                        <b>Исполнитель:</b>
                                        <p>{{ App\Models\Staff::withArchived()->find($ticket->idExecutor)->subFullName }}<span style="color: red">{{ $ticket->executor == null ? '(в архиве)' : '' }}</span></p>
                                    @endif
                                </td>
                            @else
                                <td style="width: 0%"></td>
                            @endif
                        <td>
                            @if($ticket->archiveDesc != null)
                                <b>Отменил:</b>
                                <p>
                                    @php
                                        $user = App\Models\User::find($ticket->idUserArchive);
                                        if (App\Models\Staff::withArchived()->where('idUser', $user->id)->first() != null) {
                                            $staff = App\Models\Staff::withArchived()->where('idUser', $user->id)->first();
                                            $role = $staff->role;
                                            switch ($role) {
                                                case 'dispatcher': echo 'Диспетчер'; break;
                                                case 'executor': echo 'Исполнитель'; break;
                                            }
                                        }
                                        else {
                                            echo 'Квартиросъемщик';
                                        }
                                    @endphp
                                </p>
                                <b>Причина отмены:</b>
                                <p class="ticket_comment" style="-webkit-line-clamp: 4;">{{ $ticket->archiveDesc }}</p>
                            @else
                                <b>Комментарий:</b>
                                <p class="ticket_comment">{{ $ticket->desc }}</p>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            <hr>

            @include('layouts.pagination', ['countForPag' => count($tickets), 'idForPagination' => 'ticketsFilter'])

        @else
            <p class="subText">Заявки отсутсвуют</p>
        @endif
    </div>
@endsection

