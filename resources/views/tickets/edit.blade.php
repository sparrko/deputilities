@extends('layouts.default')

@section('title')Просмотр заявки АДС (№{{ $ticket->id }})@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/imgLoader.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/modalMsg.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/elements/helper.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/elements/select2/select2.min.css') }}">
    <style>
        .inputCell input, .inputCell select {
            margin-top: 15px;
            margin-bottom: 30px;
            position: relative;
            width: 95%;
            left: 50%;
            transform: translateX(-50%);
        }

        .form {
            padding: 10px;
        }

        .edit_table {
            border-spacing: 10px 10px;
            width: 90%;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 20px;
        }
        .edit_table p {
            margin-bottom: 5px;
            color: #98a7ba;
        }

        .select2 {
            position: relative;
            left: 50%;
            transform: translateX(-50%);
        }

        .inline-block * {
            display: inline-block;
        }

        .helper-replace {
            position: relative;
            top: 5px;
        }

        .tableInfoStatic {
            margin-bottom: 10px;
        }
    </style>

@endsection

@section('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script src="{{asset('/js/imgLoader.js')}}"></script>
    <script src="{{asset('/js/helper.js')}}"></script>
    <script src="{{asset('/js/ruToEng.js')}}"></script>

    <script src="{{ asset('js/include/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/include/select2/i18n/ru.js') }}"></script>

    <script>
        function checkValidation() {
            var goSubmit = true;

            // if ($('#title').val() == "") { alert("Введите заголовок!"); goSubmit = false; }
            // else if (tinymce.activeEditor.getContent() == "") { alert("Введите текст!"); goSubmit = false; }

            if (goSubmit) $('#form').submit();
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
            <a href="{{ route('tickets') }}">Заявки АДС</a>
            <span class="arrow">➜</span>
            <span>Просмотр</span>
        </p>
    </div>
    <div class="content">
        <h3>Просмотр заявки АДС (№{{ $ticket->id }})</h3>

        <form id="form" class="form" action="{{ route('ticket_edit', $ticket->id) }}" method="post">
            @csrf

            <table class="tableInfoStatic">
                <tr>
                    <td>От:</td>
                    <td><span style="color: grey">({{ Carbon\Carbon::parse($ticket->created_at)->diffForHumans() }})</span> {{ Carbon\Carbon::parse($ticket->created_at)->toDateString() }}</td>
                </tr>
                @if($ticket->archived_at)
                    <tr>
                        <td>Дата отмены:</td>
                        <td><span style="color: grey">({{ Carbon\Carbon::parse($ticket->archived_at)->diffForHumans() }})</span> {{ Carbon\Carbon::parse($ticket->archived_at)->toDateString() }}</td>
                    </tr>
                @elseif($ticket->completed_at)
                    <tr>
                        <td>Дата исполнения:</td>
                        <td><span style="color: grey">({{ Carbon\Carbon::parse($ticket->completed_at)->diffForHumans() }})</span> {{ Carbon\Carbon::parse($ticket->completed_at)->toDateString() }}</td>
                    </tr>
                @endif
                @if($ticket->updated_at && $ticket->updated_at != $ticket->created_at)
                    <tr>
                        <td>Дата последнего изменения:</td>
                        <td><span style="color: grey">({{ Carbon\Carbon::parse($ticket->updated_at)->diffForHumans() }})</span> {{ Carbon\Carbon::parse($ticket->updated_at)->toDateString() }}</td>
                    </tr>
                @endif
            </table>
            <table class="tableInfoStatic">
                <tr>
                    <td>Статус:</td>
                    <td>
                        <img style="width: 13px" src="{{ $ticket->iconStatus() }}">
                        <span>{{ __('ticket.' . $ticket->status()) }}</span>
                    </td>
                </tr>
                @if($ticket->idTicketType != null)
                    <tr>
                        <td>Тип:</td>
                        <td>
                            {{ App\Models\TicketType::withArchived()->find($ticket->idTicketType)->name }}
                            <span style="color: red">{{ $ticket->type == null ? '(в архиве)' : '' }}</span>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>Тип:</td>
                        <td>Тип не определен</td>
                    </tr>
                @endif
            </table>

            @php
                $tenant = App\Models\Tenant::withArchived()->find($ticket->idTenant);
            @endphp

            <table class="tableInfoStatic">
                <tr>
                    <td>Квартиросъемщик:</td>
                    <td>
                        {{ $tenant->fullName }}
                    </td>
                </tr>
                <tr>
                    <td>Адрес:</td>
                    <td>
                        {{ $tenant->address }}
                    </td>
                </tr>
                @if($tenant->phone != null)
                    <tr>
                        <td>Номер телефона квартиросъемщика:</td>
                        <td>
                            {{ $tenant->phone }}
                        </td>
                    </tr>
                @endif
            </table>

            <table class="tableInfoStatic">
                @if($ticket->idDispatcher != null)
                    <tr>
                        <td>Диспетчер:</td>
                        <td>
                            {{ App\Models\Staff::withArchived()->find($ticket->idDispatcher)->fullName }}
                            <span style="color: red">{{ $ticket->dispatcher == null ? '(в архиве)' : '' }}</span>
                        </td>
                    </tr>
                @endif
                @if($ticket->idExecutor != null)
                    <tr>
                        <td>Исполнитель:</td>
                        <td>
                            {{ App\Models\Staff::withArchived()->find($ticket->idExecutor)->fullName }}
                            <span style="color: red">{{ $ticket->executor == null ? '(в архиве)' : '' }}</span>
                        </td>
                    </tr>
                @endif
            </table>

            <table class="tableInfoStatic">
                <tr>
                    <td>Описание:</td>
                    <td>
                        {{ $ticket->desc }}
                    </td>
                </tr>
                @if($ticket->archiveDesc != null)
                    <tr>
                        <td>Отменена:</td>
                        <td>
                        @php
                            $user = App\Models\User::find($ticket->idUserArchive);
                            if (App\Models\Staff::withArchived()->where('idUser', $user->id)->first() != null) {
                                $staff = App\Models\Staff::withArchived()->where('idUser', $user->id)->first();
                                $role = $staff->role;
                                switch ($role) {
                                    case 'dispatcher': echo 'Диспетчером'; break;
                                    case 'executor': echo 'Исполнителем'; break;
                                }
                            }
                            else {
                                echo 'Квартиросъемщиком';
                            }
                        @endphp
                        </td>
                    </tr>
                    <tr>
                        <td>Причина отмены:</td>
                        <td>
                            {{ $ticket->archiveDesc }}
                        </td>
                    </tr>
                @endif
            </table>

            @if($ticket->completed_at == null &&
                $ticket->archived_at == null &&
                Auth::user()->staff != null &&
                (Auth::user()->staff->role == 'dispatcher' ||
                Auth::user()->staff->role == 'executor')
            )
            <h3>Редактирование</h3>
                @if(Auth::user()->staff->role != 'executor')
                <table class="edit_table">
                    <tr>
                        <td>
                            <p>Исполнитель:</p>
                            <div class="inputCell">
                                <select name="executor" class="select2">
                                    @if($ticket->idExecutor == null)
                                        <option value=""
                                            >Не выбран</option>
                                    @elseif($ticket->executor == null)
                                        @php
                                            $archEx = App\Models\Staff::withArchived()->find($ticket->idExecutor);
                                        @endphp
                                        <option value="{{ $archEx->id }}"
                                                selected
                                                >{{ $archEx->subFullName }} (в архиве)</option>
                                    @endif
                                    @foreach ($executors as $executor)
                                        <option value="{{ $executor->id }}"
                                            {{ ($ticket->idExecutor == $executor->id) ? 'selected' : '' }}
                                            >{{ $executor->subFullName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <p>Тип заявки:</p>
                            <div class="inputCell">
                                <select name="type" class="select2">
                                    @if($ticket->idTicketType == null)
                                        <option value=""
                                            >Не выбран</option>
                                    @elseif($ticket->type == null)
                                        @php
                                            $archType = App\Models\TicketType::withArchived()->find($ticket->idTicketType);
                                        @endphp
                                        <option value="{{ $archType->id }}"
                                                selected
                                                >{{ $archType->name }} (в архиве)</option>
                                    @endif
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}"
                                            {{ ($ticket->idTicketType == $type->id) ? 'selected' : '' }}
                                            >{{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                @endif
            @endif

            <div style="margin-top: 40px">
                @if($ticket->completed_at == null && $ticket->archived_at == null &&
                    Auth::user()->staff != null &&
                    Auth::user()->staff->role == 'dispatcher'
                )
                    @include('layouts.edit_validErrors', ['errors' => $errors])
                    <button type="button" onclick="checkValidation()" class="btnGreen">Сохранить</button>
                @endif
                <a style="margin-left: 10px" class="link"
                        href="{{ route('tickets') }}">
                        Вернуться назад
                    </a>
            </div>
        </form>
        <div class="inline-block" style="float: right; margin-top: -48px">
            @if($ticket->completed_at == null && $ticket->archived_at == null)
                @if(!(isset(Auth::user()->tenant) && $ticket->idExecutor != null))
                    <a href="{{ route('ticket_archive', $ticket->id) }}"><button type="button" class="btnTrans">Отменить</button></a>
                @endif
                @if ($ticket->idExecutor != null && Auth::user()->staff != null && Auth::user()->staff->role == 'executor')
                    <form action="{{ route('ticket_complete', $ticket->id) }}" method="post">
                        @csrf<button type="submit" class="btnGreen">Завершить</button>
                    </form>
                @endif
            @endif
        </div>

        @if(isset(Auth::user()->staff))
            <h3>Печать</h3>
            <div class="inline-block" style="margin-left: 10px">
                <a target="_blank" class="link" style="margin-right: 30px"
                    href="{{ route('print_ticket', $ticket->id) }}">
                    Документ "Заявка"
                </a>
                @if($ticket->idExecutor)
                    <a target="_blank" class="link"
                        href="{{ route('print_outfit_order', $ticket->id) }}">
                        Документ "Наряд-заказ"
                    </a>
                @endif
            </div>
        @endif

    </div>


@endsection
