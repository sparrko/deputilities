<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ticket;

class PrintController extends Controller
{
    public function ticket($id) {
        return view('print.ticket', [
            'ticket' => Ticket::withArchived()->find($id)
        ]);
    }

    public function outfitOrder($id) {
        return view('print.outfitOrder', [
            'ticket' => Ticket::withArchived()->find($id)
        ]);
    }
}
