<table width="100%">
    <tr>
        <td>
            <div style="width: 300px;">
                <table width="100%" style="border-spacing: 4px 4px;">
                    <tr>
                        <td><p>Страница: </p></td>
                        <td>
                            <select name="pPage" id="pPage" class="input" style="width: 120px;"  onchange="paginationSubmit('#{{ $idForPagination }}', true)">
                                @foreach ($pagination['pages'] as $page)
                                    <option
                                        value="{{ $page }}"
                                        {{ ($pagination['selectedPage'] == $page) ? 'selected' : '' }}
                                        >{{ $page }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><p>Выводить по: </p></td>
                        <td>
                            <select name="pCount" id="pCount" class="input" style="width: 120px;" onchange="paginationSubmit('#{{ $idForPagination }}', false)">
                                @foreach ($pagination['counts'] as $count)
                                    <option
                                        value="{{ $count }}"
                                        {{ ($pagination['selectedCount'] == $count) ? 'selected' : '' }}
                                        >{{ $count }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td>
            <div style="height: 100%; text-align: right;">
                <p>Всего: {{ $countForPag }}</p>
                <br><br><br><br>
            </div>
        </td>
    </tr>
</table>
