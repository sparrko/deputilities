<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Company;

class CompanyInfoController extends Controller
{
    public function aboutCompany() {
        return view('main.aboutCompany', ['company' => Company::first()]);
    }

    public function companyInfo() {
        if ($this->checkRole(['admin']) == 'wrongRole')
            return view('main.block');

        return view('company.edit', ['company' => Company::first()]);
    }
    public function saveCompanyInfo(Request $request) {
        if ($this->checkRole(['admin']) == 'wrongRole')
            return view('main.block');


        $dataValidation = [
            'address' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|max:255',
            'inn' => 'required|max:255',
            'kpp' => 'required|max:255',
            'bik' => 'required|max:255',
            'bank_name' => 'required|max:255',
            'bank_num' => 'required|max:255',
            'bank_cor' => 'required|max:255',
            'desc' => 'required|max:65535',
        ];
        Validator::make($request->all(), $dataValidation)->validate();

        $company = Company::first();
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->inn = $request->inn;
        $company->kpp = $request->kpp;
        $company->bik = $request->bik;
        $company->bank_name = $request->bank_name;
        $company->bank_num = $request->bank_num;
        $company->bank_cor = $request->bank_cor;
        $company->desc = $request->desc;
        $company->save();

        return view('main.aboutCompany', ['company' => Company::first()]);
    }
}
