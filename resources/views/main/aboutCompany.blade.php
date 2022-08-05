@extends('layouts.default')

@section('title')О компании@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <span>О компании</span>
        </p>
    </div>
    <div class="content">
        <div class="printable">
            <div class="center">
                <img src="{{ asset('images/logo.png') }}">
                <h3 style="margin-top: 5px;">УК "Богатырь"</h3>
            </div>

            <h3>Контакты</h3>
            <table class="tableInfoStatic">
                <tr>
                    <td>Наш адрес:</td>
                    <td>{{ $company->address }}</td>
                </tr>
                <tr>
                    <td>Номер диспетчерской:</td>
                    <td>{{ $company->phone }}</td>
                </tr>
                <tr>
                    <td>Почта:</td>
                    <td>{{ $company->email }}</td>
                </tr>
            </table>
            <h3>Реквизиты компании</h3>
            <table class="tableInfoStatic">
                <tr>
                    <td>ИНН:</td>
                    <td>{{ $company->inn }}</td>
                </tr>
                <tr>
                    <td>КПП</td>
                    <td>{{ $company->kpp }}</td>
                </tr>
                <tr>
                    <td>БИК</td>
                    <td>{{ $company->bik }}</td>
                </tr>
                <tr>
                    <td>Название банка</td>
                    <td>{{ $company->bank_name }}</td>
                </tr>
                <tr>
                    <td>Расчетный счет</td>
                    <td>{{ $company->bank_num }}</td>
                </tr>
                <tr>
                    <td>Корреспондентский счет</td>
                    <td>{{ $company->bank_cor }}</td>
                </tr>
            </table>
        </div>

        <button
            onclick="window.print()"
            style="margin-top: 30px;
            position: relative;
            left: 100%;
            transform: translateX(-100%)"
            type="button" class="btnGreen">Печать</button>

    </div>
@endsection
