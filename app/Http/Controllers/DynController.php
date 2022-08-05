<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tenant;
use App\Models\House;
use App\Models\Street;


class DynController extends Controller
{
    public function getAddressByTenant(Request $request) {
        $tenant = Tenant::withoutArchived()->find($request->id);
        $house = House::withArchived()->find($tenant->idHouse);
        $street = Street::withArchived()->find($house->idStreet);

        $ret = "ул ";
        $ret .= $street->name;
        $ret .= " " . $house->number;
        $ret .= ", " . $tenant->room;

        return $ret;
    }
}
