<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\Street;
use App\Models\House;
use App\Models\Tenant;
use App\Models\User;

class ReferenceController extends Controller
{
    public $streets;
    public $houses;
    public $tenants;

    public function __construct(Request $request) {
        $this->streets = Street::query();
        $this->houses = House::query();
        $this->tenants = Tenant::query();
    }

    ////////////////////////////////////////////////
    // Streets
    public function streets(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        $this->streets->orderBy('name', 'DESC');
        $this->filterStreets($request);
        $this->pagination($request, $this->streets);

        return view('streets.list', [
            'streets' => $this->streets->get(),
            'pagination' => $this->paginationDataGet($request, $this->streets)
        ]);
    }
    public function filterStreets(Request $request) {
        if (!isset($request->reset)) {
            foreach ($request->all() as $key => $value) {
                switch ($key) {
                    case 'status':
                        if ($value == 'any') {
                            $this->streets->withArchived();
                        } else if ($value == 'archived') {
                            $this->streets->onlyArchived();
                        }
                        break;
                    case 'name':
                        $this->streets->whereRaw(
                            "TRIM(name) like '%{$value}%'"
                        );
                        break;
                }
            }
            session()->flashInput($request->input());
        }
        else session()->flashInput([]);
    }
    public function createStreet() {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        return view('streets.edit', [
        ]);
    }
    public function editStreet($id) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');


        return view('streets.edit', [
            'street' => Street::withArchived()->find($id),
        ]);
    }
    public function editPostStreet(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        $dataValidation = [
            'name' => 'required|max:255',
        ];
        $validator = Validator::make($request->all(), $dataValidation)->validate();

        // Check unique
        if (!isset($request->id))
            if (Street::whereRaw('LOWER(`name`) = "' . mb_strtolower($request->name) . '"')->count()) {
                return back()->with('error', 'Такая улица уже существует!')->withInput($request->input());
            }

        if (isset($request->id))
            $street = Street::withArchived()->find($request->id);
        else
            $street = new Street;

        $street->name = $request->name;
        $street->save();

        return redirect()->route('streets');
    }
    public function archiveStreet(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');


        $id = $request->id;
        $item = Street::withArchived()->find($id);
        if ($item->archived_at == null)
            $item->archive();
        else
            $item->unArchive();

        return redirect()->route('streets', ['streets' => $this->streets->get()]);
    }



    ////////////////////////////////////////////////
    // Houses
    public function houses(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        $this->houses->join('streets', 'streets.id', '=', 'houses.idStreet')->select('houses.*', 'streets.name');
        $this->houses->orderBy('streets.name');
        $this->houses->orderBy('houses.number');

        $this->filterHouses($request);
        $this->pagination($request, $this->houses);

        if ($request->idStreet != null) {
            $this->houses->where('idStreet', $request->idStreet);
        }

        return view('houses.list', [
            'houses' => $this->houses->get(),
            'streets' => Street::orderBy('name')->get(),
            'streetSearch' => isset($request->idStreet) ? Street::find($request->idStreet) : null,
            'pagination' => $this->paginationDataGet($request, $this->houses)
        ]);
    }
    public function filterHouses(Request $request) {
        if (!isset($request->reset)) {
            $street_filter = false;
            foreach ($request->all() as $key => $value) {
                switch ($key) {
                    case 'number':
                        $this->houses->whereRaw(
                            "TRIM(number) like '%{$value}%'"
                        );
                        break;
                    case 'status':
                        if ($value == 'any') {
                            $this->houses->withArchived();
                        } else if ($value == 'archived') {
                            $this->houses->onlyArchived();
                        }
                        break;
                    case 'street':
                        if ($value != 'any') {
                            $this->houses->where('idStreet', $value);
                        }
                    case 'status_street':
                        if ($value == 'active') {
                            $this->houses
                                ->whereNull('streets.archived_at');
                            $street_filter = true;
                        } else if ($value == 'archived') {
                            $this->houses
                                ->whereNotNull('streets.archived_at');
                            $street_filter = true;
                        }
                        break;
                }
            }
            session()->flashInput($request->input());
        }
        else session()->flashInput([]);
    }
    public function createHouse(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        if (isset($request->idStreet)){
            return view('houses.edit', [
                'streets' => Street::orderBy('name')->get(),
                'searchStreet' => Street::find($request->idStreet)
            ]);
        }
        else {
            return view('houses.edit', [
                'streets' => Street::orderBy('name')->get(),
            ]);
        }
    }
    public function editHouse($id) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');


        return view('houses.edit', [
            'house' => House::withArchived()->find($id),
            'streets' => Street::orderBy('name')->get(),
        ]);
    }
    public function editPostHouse(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        $dataValidation = [
            'number' => 'required|max:255',
            'street' => 'required',
        ];
        $validator = Validator::make($request->all(), $dataValidation)->validate();

        // Check unique
        if (!isset($request->id))
            if (House::whereRaw('idStreet = ' . $request->street . ' and LOWER(`number`) = "' . mb_strtolower($request->number) . '"')->count()) {
                return back()->with('error', 'Такой дом уже существует!')->withInput($request->input());
            }

        if (isset($request->id))
            $house = House::withArchived()->find($request->id);
        else
            $house = new House;

        $house->number = $request->number;
        $house->idStreet = $request->street;
        $house->save();

        return redirect()->route('houses');
    }
    public function archiveHouse(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        $id = $request->id;

        $item = House::withArchived()->find($id);
        if ($item->archived_at == null)
            $item->archive();
        else
            $item->unArchive();

        return redirect()->route('houses', [
            'houses' => $this->houses->get(),
            'streets' => Street::orderBy('name')->get(),
        ]);
    }


    ////////////////////////////////////////////////
    // Tenants
    public function tenants(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        $this->tenants->orderBy("id", "desc");
        $this->filterTenants($request);
        $this->pagination($request, $this->tenants);
        if (isset($request->searchHouse)) {
            $this->tenants->where('idHouse', $request->searchHouse);
        }

        return view('tenants.list', [
            'tenants' => $this->tenants->get(),
            'searchHouse' => House::withArchived()->find($request->searchHouse),
            'pagination' => $this->paginationDataGet($request, $this->tenants)
        ]);
    }
    public function filterTenants(Request $request) {
        if (!isset($request->reset)) {
            foreach ($request->all() as $key => $value) {
                switch ($key) {
                    case 'status':
                        if ($value == 'any') {
                            $this->tenants->withArchived();
                        } else if ($value == 'archived') {
                            $this->tenants->onlyArchived();
                        }
                        break;
                    case 'fullName':
                        $this->tenants->whereRaw(
                            "TRIM(CONCAT(surname, ' ', name, ' ', patr)) like '%{$value}%'"
                        );
                        break;
                    case 'address':
                        if ($value != 'any') {
                            $this->tenants->where('idHouse', $value);
                        }
                        break;
                }
            }
            session()->flashInput($request->input());
        }
        else session()->flashInput([]);
    }
    public function createTenant(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        return view('tenants.edit', [
            'searchHouse' => House::withArchived()->find($request->searchHouse),
        ]);
    }
    public function editTenant(Request $request, $id) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        return view('tenants.edit', [
            'tenant' => Tenant::withArchived()->find($id),
        ]);
    }
    public function editPostTenant(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');


        $dataValidation = [
            'surname' => 'required|max:255',
            'name' => 'required|max:255',
            'patr' => 'required|max:255',
            'phone' => 'required|numeric|digits_between:6,11',
            'address' => 'required',
            'room' => 'required|max:255',
            'login' => 'required|max:255|min:6',
        ];

        if (!isset($request->id)) {
            $dataValidation['password'] = 'required|min:6|max:255';
            $dataValidation['login'] = 'required|unique:users|max:255|min:6';
        }

        $validator = Validator::make($request->all(), $dataValidation)->validate();


        if (isset($request->id)) {
            $tenant = Tenant::withArchived()->find($request->id);

            $tenant->user->login = $request->login;
            if($request->password != null)
                $tenant->user->password = Hash::make($request->password);

            $tenant->user->save();
        }
        else{
            $tenant = new Tenant;
            $user = new User;

            $user->login = $request->login;
            $user->password = Hash::make($request->password);
            $user->save();
            $tenant->idUser = $user->id;
        }

        $tenant->surname = $request->surname;
        $tenant->name = $request->name;
        $tenant->patr = $request->patr;
        $tenant->phone = $request->phone;
        $tenant->idHouse = $request->address;
        $tenant->room = $request->room;

        $tenant->save();

        return redirect()->route('tenants');
    }
    public function archiveTenant(Request $request) {
        if ($this->checkRole(['referenceOfficer']) == 'wrongRole')
            return view('main.block');

        $id = $request->id;

        $item = Tenant::withArchived()->find($id);
        if ($item->archived_at == null)
            $item->archive();
        else
            $item->unArchive();

        return redirect()->route('tenants', [
            'tenants' => $this->tenants->get(),
        ]);
    }
}
