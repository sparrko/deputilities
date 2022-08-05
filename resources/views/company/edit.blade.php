@extends('layouts.default')

@section('title')Редактирование компании@endsection

@section('styles')
<style>
    .edit_companyInfo {
        border-spacing: 10px 10px;
        width: 90%;
        position: relative;
        left: 50%;
        transform: translateX(-50%);
    }
    .edit_companyInfo h3 {
        margin-left: -10px;
        margin-bottom: 0px;
        padding-top: 20px;
    }
    .company_desc_edit {
        width: 87%;
        position: relative;
        left: 50%;
        transform: translateX(-50%);
    }
    .company_desc_edit h3 {
        margin-left: -10px;
        padding-top: 20px;
    }
</style>
@endsection

@section('content')

    <div class="linkNow">
        <p>
            <a href="{{ route('main') }}">Главная</a>
            <span class="arrow">➜</span>
            <span>Редактирование информации о компании</span>
        </p>
    </div>
    <div class="content">

        <h3>Редактирование информации о компании</h3>

        @include('layouts.edit_miniInfo', ['item' => $company])

        <form action="{{ route('company_info') }}" method="post">
            @csrf
            <table class="edit_companyInfo">
                <tr>
                    <td><h3>Контакты:</h3></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Адрес:</td>
                    <td><input name="address" value="{{ $company->address }}" class="input" maxlength="255"></td>
                </tr>
                <tr>
                    <td>Номер диспетчерской:</td>
                    <td><input name="phone" value="{{ $company->phone }}" class="input" maxlength="255"></td>
                </tr>
                <tr>
                    <td>Почта:</td>
                    <td><input name="email" value="{{ $company->email }}" class="input" maxlength="255"></td>
                </tr>

                <tr>
                    <td><h3>Реквизиты компании:</h3></td>
                    <td></td>
                </tr>
                <tr>
                    <td>ИНН:</td>
                    <td><input name="inn" value="{{ $company->inn }}" class="input" maxlength="255"></td>
                </tr>
                <tr>
                    <td>КПП:</td>
                    <td><input name="kpp" value="{{ $company->kpp }}" class="input" maxlength="255"></td>
                </tr>
                <tr>
                    <td>БИК:</td>
                    <td><input name="bik" value="{{ $company->bik }}" class="input" maxlength="255"></td>
                </tr>
                <tr>
                    <td>Название банка:</td>
                    <td><input name="bank_name" value="{{ $company->bank_name }}" class="input" maxlength="255"></td>
                </tr>
                <tr>
                    <td>Расчетный счет:</td>
                    <td><input name="bank_num" value="{{ $company->bank_num }}" class="input" maxlength="255"></td>
                </tr>
                <tr>
                    <td>Корреспондентский счет:</td>
                    <td><input name="bank_cor" value="{{ $company->bank_cor }}" class="input" maxlength="255"></td>
                </tr>
            </table>

            <div class="company_desc_edit">
                <h3>Описание компании на главной странице:</h3>
                <textarea style="height: 150px; resize: none;" name="desc" class="input" maxlength="65535"> {{ $company->desc }}</textarea>
            </div>

            @include('layouts.edit_validErrors', ['errors' => $errors])

            <button
                style="margin-top: 30px;
                position: relative;
                left: 100%;
                transform: translateX(-100%)"
                type="submit" class="btnGreen">Сохранить</button>
        </form>

    </div>
@endsection
