<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <title>Печать "Заявка"</title>
    <style>
    @media print { .more { page-break-after: always; }  }
    @page { margin: 1cm; } @page :first { margin: 2cm; }
    @media print{
      textarea::-webkit-input-placeholder{visibility: hidden;}
      textarea::-moz-placeholder  {visibility: hidden;}
      textarea:-moz-placeholder  {visibility: hidden;}
      textarea:-ms-input-placeholder  {visibility: hidden;}
    }
    .card {
      border: 1px solid black;
      padding: 10pt;
    }
    .tableBorder td {
      border: 1px solid black;
      margin: 0;
    }
    .tableBorder {
      border-collapse:collapse;
    }
    .newPunct * {
        padding-top: 10px;
    }
    </style>
  </head>
  <body>
    <div class="card">
        <center style="font-size: 20px"><b>Заявка №{{ $ticket->id }}</b></center>
        <hr>
        <table style="margin-top: 20px" width="100%">
            <tr>
                <td width="50%"><b>От:</b></td>
                <td>{{ $ticket->created_at }}</td>
            </tr>
            <tr>
                <td><b>Дата завершения:</b></td>
                <td>{{ isset($ticket->completed_at) ? $ticket->completed_at : '' }}</td>
            </tr>
            @if($ticket->idDispatcher)
                <tr class="newPunct">
                    <td><b>Диспетчер:</b></td>
                    <td>{{ App\Models\Staff::withArchived()->find($ticket->idDispatcher)->subFullName }}<span style="color: red">{{ $ticket->dispatcher == null ? '(в архиве)' : '' }}</span></td>
                </tr>
            @endif
            @if($ticket->idExecutor)
                <tr>
                    <td><b>Исполнитель:</b></td>
                    <td>{{ App\Models\Staff::withArchived()->find($ticket->idExecutor)->subFullName }}<span style="color: red">{{ $ticket->executor == null ? '(в архиве)' : '' }}</span></td>
                </tr>
            @endif
            <tr>
                <td><b>Квартиросъемщик:</b></td>
                <td>{{ App\Models\Tenant::withArchived()->find($ticket->idTenant)->subFullName }}</td>
            </tr>
            <tr class="newPunct">
                <td><b>Адрес:</b></td>
                <td>
                    @php
                        $tenant = App\Models\Tenant::withArchived()->find($ticket->idTenant);
                        $house = App\Models\House::withArchived()->find($tenant->idHouse);
                        $number = $house->number;
                        $street = App\Models\Street::withArchived()->find($house->idStreet)->name;
                        $room = $tenant->room;

                        echo $street . " " . $number . ", " . $room;
                    @endphp
                </td>
            </tr>
            <tr class="newPunct">
                <td><b>Тип заявки:</b></td>
                <td>
                    @if($ticket->idTicketType != null)
                        {{ App\Models\TicketType::withArchived()->find($ticket->idTicketType)->name }}
                    @else
                        <span style="font-weight: bold">Тип не определен</span>
                    @endif
                </td>
            </tr>
        </table>
        <p style="margin-left: 2px"><b>Описание:</b></p>
        <p style="margin-left: 2px">{{ $ticket->desc }}</p>

        @if($ticket->archiveDesc != null)
            <p style="margin-left: 2px"><b>Причина отмены:</b></p>
            <p style="margin-left: 2px">{{ $ticket->archiveDesc }}</p>
        @endif

        <hr>
        <p>Распечатано {{ Carbon\Carbon::now()->toDateString() }} {{ Auth::user()->staff != null ? Auth::user()->staff->subFullName : Auth::user()->tenant->subFullName }} </p>
    </div>
    <p class='more'></p>
  </body>
    <script>
        window.print();
    </script>
</html>
