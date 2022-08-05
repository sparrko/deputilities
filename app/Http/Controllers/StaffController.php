<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\Staff;
use App\Models\User;

class StaffController extends Controller
{
    public $staff;

    public function __construct(Request $request) {
        $this->staff = Staff::query();
    }

    public function list(Request $request) {
        if ($this->checkRole(['staffOfficer']) == 'wrongRole')
            return view('main.block');


        $this->staff->orderBy('created_at', 'DESC');
        $this->filter($request);
        $this->pagination($request, $this->staff);

        return view('staff.list', [
            'staff' => $this->staff->get(),
            'roles' => Staff::ROLE,
            'pagination' => $this->paginationDataGet($request, $this->staff)
        ]);
    }

    public function filter(Request $request) {
        if (!isset($request->reset)) {
            foreach ($request->all() as $key => $value) {
                switch ($key) {
                    case 'status':
                        if ($value == 'any') {
                            $this->staff->withArchived();
                        } else if ($value == 'archived') {
                            $this->staff->onlyArchived();
                        }
                        break;
                    case 'role':
                        if ($value != 'any') {
                            $this->staff->where('role', $value);
                        }
                        break;
                    case 'fullName':
                        $this->staff->whereRaw(
                            "TRIM(CONCAT(surname, ' ', name, ' ', patr)) like '%{$value}%'"
                        );
                        break;
                }
            }
            session()->flashInput($request->input());
        }
        else session()->flashInput([]);
    }

    public function create() {
        if ($this->checkRole(['staffOfficer']) == 'wrongRole')
            return view('main.block');

        return view('staff.edit', [
            'roles' => Staff::ROLE,
        ]);
    }
    public function edit($id) {
        if ($this->checkRole(['staffOfficer']) == 'wrongRole')
            return view('main.block');


        return view('staff.edit', [
            'staff' => Staff::withArchived()->find($id),
            'roles' => Staff::ROLE,
        ]);
    }
    public function editPost(Request $request) {
        if ($this->checkRole(['staffOfficer']) == 'wrongRole')
            return view('main.block');


        $dataValidation = [
            'surname' => 'required|max:255',
            'name' => 'required|max:255',
            'patr' => 'required|max:255',
            'phone' => 'required|numeric|digits_between:6,11',
            'role' => 'required',
            'login' => 'required|max:255|min:6',
            'dateborn' => 'required',
        ];

        if (!isset($request->id)) {
            $dataValidation['password'] = 'required|max:255|min:6';
            $dataValidation['login'] = 'required|unique:users|max:255|min:6';
        }

        $validator = Validator::make($request->all(), $dataValidation)->validate();


        if (isset($request->id)) {
            $staff = Staff::withArchived()->find($request->id);

            $staff->user->login = $request->login;
            if($request->password != null)
                $staff->user->password = Hash::make($request->password);

            $staff->user->save();
        }
        else{
            $staff = new Staff;
            $user = new User;

            $user->login = $request->login;
            $user->password = Hash::make($request->password);
            $user->save();
            $staff->idUser = $user->id;
        }

        $staff->icon = $request->image;
        $staff->surname = $request->surname;
        $staff->name = $request->name;
        $staff->patr = $request->patr;
        $staff->phone = $request->phone;
        $staff->dateBorn = $request->dateborn;
        $staff->role = $request->role;

        $staff->save();

        return redirect()->route('staff');
    }
    public function archive(Request $request) {
        if ($this->checkRole(['staffOfficer']) == 'wrongRole')
            return view('main.block');


        $id = $request->id;
        $item = Staff::withArchived()->find($id);
        if ($item->archived_at == null)
            $item->archive();
        else
            $item->unArchive();

        return redirect()->route('staff', ['staff' => $this->staff->get()]);
    }
}
