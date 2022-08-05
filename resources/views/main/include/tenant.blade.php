<h3>Добро пожаловать!</h3>
<p>{{ App\Models\Company::first()->desc }}</p>

<h3>Доступный вам функционал:</h3>
<div class="block">
    <a href="{{ route('profile') }}" class="link">Просмотр вашего профиля</a>
    <a href="{{ route('news_list_for_all') }}" class="link">Просмотр новостей</a>
    <a href="{{ route('tickets') }}" class="link">Подача заявок в диспетчерскую</a>
    <a href="{{ route('aboutCompany') }}" class="link">Просмотр информации о компании</a>
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
            <p style="font-size: 12px; color: gray;">{{ $item->created_at }}</p>
            <p>{{ $item->getMiniSrc() }}</p>
        </div>
    </div>
    </a>
    @endforeach
</div>
@endif
