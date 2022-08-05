<h3>Добро пожаловать!</h3>
<p>{{ App\Models\Company::first()->desc }}</p>

<div class="pride">
    <div>
        <div>
            <div>
                @php
                    $cHouses = App\Models\House::query();
                    $cHouses->join('streets', 'streets.id', '=', 'houses.idStreet')->select('houses.*', 'streets.name');
                    $cHouses->whereNull('streets.archived_at');
                    echo $cHouses->count();
                @endphp
            </div>
            <div>домов</div>
            <p>в управлении нашей компании </p>
        </div>
        <div>
            <div>{{ App\Models\Staff::count() }}</div>
            <div>сотрудников</div>
            <p>трудятся в нашем коллективе</p>
        </div>
        <div>
            <div>{{ \Carbon\Carbon::now()->diffInYears(\Carbon\Carbon::parse('2020-04-03')) }}</div>
            <div>года</div>
            <p>действует организация</p>
        </div>
    </div>
</div>

<h3>Контакты</h3>
<table class="tableInfoStatic">
    <tr>
        <td>Наш адрес:</td>
        <td>{{ App\Models\Company::first()->address }}</td>
    </tr>
    <tr>
        <td>Номер диспетчерской:</td>
        <td>{{ App\Models\Company::first()->phone }}</td>
    </tr>
    <tr>
        <td>Почта:</td>
        <td>{{ App\Models\Company::first()->email }}</td>
    </tr>
</table>

@php
    $news = App\Models\News::where('type', 'tenants')->take(2)->orderBy('created_at', 'DESC')->get();
@endphp
@if(count($news))
<h3>Новости
    <a href="{{ route('news_list_for_all') }}" class="link" style="float: right; font-size: 16px">все новости</a>
</h3>
<div class="newsMini">
    @foreach ($news as $item)
    <a href="{{ route('news_view_tenant', $item->id) }}">
    <div>
        <div>
            <img
                src="{{ ($item == null) ? asset('images/newsNoImage.jpg') : $item->icon }}"
                >
        </div>
        <div>
            <p>{{ $item->title }}</p>
            <p style="font-size: 12px; color: gray;">{{ \Carbon\Carbon::parse($item->created_at)->toDateString() }}</p>
            <p>{{ $item->getMiniSrc() }}</p>
        </div>
    </div>
    </a>
    @endforeach
</div>
@endif
