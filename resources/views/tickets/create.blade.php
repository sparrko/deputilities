@extends('layouts.default')

@section('title')Создание заявки АДС@endsection

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

        #dyn_address {
            color: black;
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

        @if(Auth::user()->staff != null)
            var s2_type_deled = false;
            var s2_tenant_deled = false;
            $(function () {
                $('#s2_type').on('select2:select', function (e) {
                    if (!s2_type_deled){
                        $('#s2_type option').eq(0).remove();
                        s2_type_deled = true;
                    }
                });

                $('#s2_tenant').on('select2:select', function (e) {
                    if (!s2_tenant_deled) {
                        $('#s2_tenant option').eq(0).remove();
                        s2_tenant_deled = true;
                    }
                    $.ajax({
                        url: '{{ route("get_address_by_tenant") }}',
                        type: 'get',
                        async: true,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": $(this).val()
                        },
                        success: function(data){
                            $('#dyn_address').css({'display' : 'block'});
                            $('#dyn_address span').text(data);
                        }
                    });
                });
            });
        @endif

    </script>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <a href="{{ route('tickets') }}">Заявки АДС</a>
            <span class="arrow">➜</span>
            <span>Создание</span>
        </p>
    </div>
    <div class="content">
        <h3>Создание заявки АДС</h3>

        <form id="form" class="form" action="{{ route('ticket_create') }}" method="post">
            @csrf

            <table class="edit_table">
                <tr>
                    <td colspan="2">
                        <div class="inline-block">
                            <p>Текст заявки:</p>
                            <div class="helper helper-replace">
                                <img>
                                <div>
                                    <p>Введите текст заявки (не более 255 символов). Он должен включать краткое описание проблемы и место ее нахождения.</p>
                                    <p>Например:</p>
                                    <p>- течет труба в туалете;</p>
                                    <p>- отсутствует отопление в спальне;</p>
                                    <p>- искрит розетка в зале.</p>
                                </div>
                            </div>
                        </div>
                        <textarea
                            name="desc"
                            maxlength="255"
                            class="input"
                            style="
                                resize: none;
                                height: 100px;
                            ">{{ old('desc') }}</textarea>
                    </td>
                </tr>
                @if(Auth::user()->staff != null)
                <tr>
                    <td>
                        <p>Квартиросъемщик:</p>
                        <select name="tenant" class="select2" style="margin: 0px" id="s2_tenant">
                            <option value="">Не выбран</option>
                            @foreach ($tenants as $tenant)
                                <option value="{{ $tenant->id }}"
                                    {{ (old('tenant') == $tenant->id) ? 'selected' : '' }}
                                    >{{ $tenant->subFullName }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <p>Тип заявки:</p>
                        <select name="type" class="select2" id="s2_type">
                            <option value="">Не выбран</option>
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

            @if(Auth::user()->staff != null)
                <center style="margin-bottom: 20px">
                    <p style="color: #98a7ba; display: none" id="dyn_address">Адрес: <span style="color: black;" id="dyn_address_span">-</span></p>
                </center>
            @endif

            @include('layouts.edit_validErrors', ['errors' => $errors])

            <button type="button" onclick="checkValidation()" class="btnGreen">Создать</button>
            </button>
            <a style="margin-left: 10px" class="link"
                href="{{ route('tickets') }}">
                Вернуться назад
            </a>
        </form>
    </div>


@endsection
