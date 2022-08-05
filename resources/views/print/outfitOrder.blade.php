<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <title>Печать "Наряд-заказ"</title>
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
      padding: 2px;
      margin: 0;
    }
    .tableBorder {
      border-collapse:collapse;
    }
    </style>
  </head>
  <body>
    <div class="card">
      <center style="font-size: 20px"><b>Наряд заказ №{{ $ticket->id }}</b></center>
      <center style="font-size: 12px">От {{ Carbon\Carbon::now()->toDateString() }}</center>

      <table style="margin-top: 10px" width="100%">
        <tr>
          <td><b>Заказчик:</b></td>
          <td>
              {{ App\Models\Tenant::withArchived()->find($ticket->idTenant)->subFullName }}
          </td>
        </tr>
        <tr>
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
        <tr>
          <td><b>Дата создания заявки:</b></td>
          <td>
              {{ $ticket->created_at }}
          </td>
        </tr>
      </table>

      <p><b>Заявка: </b></p>
      <p style="text-align: justify">{{ $ticket->desc }}</p>

      <table class="tableBorder" style="margin-top: 10px" width="100%">
        <tr>
          <td>Выполненые работы</td>
          <td>Расходный материал</td>
          <td>Ед. изм.</td>
          <td>Кол-во</td>
        </tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
      </table>

      <center style="font-size: 12px; margin-top: 10px">По объему и качеству оказанных услуг лицо, направившее заявку, претензий не имеет.</center>

      <table style="margin-top: 30px" width="100%">
        <tr>
          <td>Исполнитель:</td>
          <td>______________{{ App\Models\Staff::withArchived()->find($ticket->idExecutor)->subFullName }}<span style="color: red">{{ $ticket->executor == null ? '(в архиве)' : '' }}</span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Время прибытия:</td>
          <td>Время окончания работ:</td>
        </tr>
        <tr>
          <td>_____________</td>
          <td>_____________</b></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Заказчик:</td>
          <td>______________{{ App\Models\Tenant::withArchived()->find($ticket->idTenant)->subFullName }}</td>
        </tr>
      </table>

      <p>Комментарий лица, направившего заявку:</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
    <p class='more'></p>
  </body>
  <script>
    window.print();
</script>
</html>
