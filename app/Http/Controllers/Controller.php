<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Staff;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function pagination(Request $request, $query) {
        $count = isset($request->pCount) ? $request->pCount : 10;
        $page = isset($request->pPage) ? $request->pPage : 1;

        $query->skip($count * ($page - 1))->take($count);
    }

    public function paginationDataGet(Request $request, $queryMain) {
        $query = $queryMain;
        $query->skip(0)->take(10);

        $counts = array(10, 25, 50, 100);
        $pages = array();

        $bufCount = 10;
        if (isset($request->pCount)) $bufCount = $request->pCount;
        for ($i = 1; $i < $query->count() / $bufCount + 1; $i++) {
            array_push($pages, $i);
        }

        $ret = array(
            'counts' => $counts,
            'selectedCount' => isset($request->pCount) ? $request->pCount : 10,
            'pages' => $pages,
            'selectedPage' => isset($request->pPage) ? $request->pPage : 1,
        );

        return $ret;
    }

    public const CHECKERROR = [
        'nonAuth' => 'nonAuth',
        'wrongRole' => 'wrongRole'
    ];
    public function checkStaff() {
        if (!Auth::check()) {
            return 'nonAuth';
        }
        else if (Auth::user()->staff == null) {
            return 'wrongRole';
        }
    }
    public function checkTenant() {
        if (!Auth::check()) {
            return 'nonAuth';
        }
        else if (Auth::user()->tenant == null) {
            return 'wrongRole';
        }
    }
    public function checkAuth() {
        if (!Auth::check()) {
            return 'nonAuth';
        }
    }
    public function checkRole($roleNames) {
        $next = false;

        if (Auth::check()) {
            foreach ($roleNames as $roleName) {
                if (
                    $roleName == 'admin' ||
                    $roleName == 'staffOfficer' ||
                    $roleName == 'referenceOfficer' ||
                    $roleName == 'executor' ||
                    $roleName == 'dispatcher'
                    )
                {
                    if (Auth::user()->staff != null) {
                        if (Auth::user()->staff->role == Staff::ROLE[$roleName]) {
                            $next = true;
                        }
                    }
                }
                else if ($roleName == 'tenant') {
                    if (Auth::user()->tenant != null) {
                        $next = true;
                    }
                }
                else if ($roleName == 'staff') {
                    if (Auth::user()->staff != null) {
                        $next = true;
                    }
                }
            }
        }
        else {
            return 'nonAuth';
        }

        if ($next == false) {
            return 'wrongRole';
        }

    }
}
