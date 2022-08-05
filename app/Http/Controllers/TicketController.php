<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Staff;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketType;

class TicketController extends Controller
{
    public $tickets;

    public function __construct(Request $request) {
        $this->tickets = Ticket::query()->withArchived();
    }

    public function tickets(Request $request) {
        if (!($this->checkRole(['dispatcher']) != 'wrongRole' || $this->checkRole(['executor']) != 'wrongRole' || $this->checkTenant() != 'wrongRole'))
            return view('main.block');


        // Get dispatcher
        $dispatchers = Staff::withoutArchived()->where('role', 'dispatcher')->orderBy('surname', 'ASC')->get();

        // Get executors
        $executors = Staff::withoutArchived()->where('role', 'executor')->orderBy('surname', 'ASC')->get();

        // Get types of ticket
        $types = TicketType::withoutArchived()->orderBy('name', 'ASC')->get();


        // For tenant?
        if (Auth::user()->staff == null){
            $this->tickets->where('tickets.idTenant', Auth::user()->tenant->id);
        } // For executor?
        else if (Auth::user()->staff->role == 'executor') {
            $this->tickets->where('tickets.idExecutor', Auth::user()->staff->id);
        }

        // Order list
        $this->tickets->orderBy('created_at', 'DESC');

        // Filter
        if (!isset($request->reset)) {
            foreach ($request->all() as $key => $value) {
                switch ($key) {
                    case 'status':
                        if ($value != 'any') {
                            if ($value == 'new')
                                $this->tickets
                                    ->whereNull('tickets.completed_at')
                                    ->whereNull('tickets.archived_at')
                                    ->whereNull('tickets.idExecutor');
                            else if ($value == 'in_work')
                                $this->tickets
                                        ->whereNull('tickets.completed_at')
                                        ->whereNull('tickets.archived_at')
                                        ->whereNotNull('tickets.idExecutor');
                            else if ($value == 'archived')
                                $this->tickets
                                        ->whereNotNull('tickets.archived_at');
                            else if ($value == 'completed')
                                $this->tickets
                                        ->whereNotNull('tickets.completed_at');
                        }
                        break;
                    case 'type':
                        if ($value != 'any') {
                            if ($value == 'nothing') {
                                $this->tickets
                                    ->whereNull('idTicketType');
                            } else {
                                $this->tickets
                                    ->where('idTicketType', $value);
                            }
                        }
                        break;
                    case 'dispatcher':
                        if ($value != 'any') {
                            if ($value == 'without') {
                                $this->tickets
                                    ->whereNull('tickets.idDispatcher');
                            }
                            else {
                                $this->tickets
                                    ->where('tickets.idDispatcher', $value);
                            }
                        }
                        break;
                    case 'executor':
                        if ($value != 'any') {
                            if ($value == 'without') {
                                $this->tickets
                                    ->whereNull('tickets.idExecutor');
                            }
                            else {
                                $this->tickets
                                    ->where('tickets.idExecutor', $value);
                            }
                        }
                        break;
                    case 'created_at_start':
                        if ($value != null)
                            $this->tickets->where('tickets.created_at', '>=', Carbon::parse($value));
                        break;
                    case 'created_at_end':
                        if ($value != null)
                            $this->tickets->where('tickets.created_at', '<', Carbon::parse($value)->addDays(1));
                        break;
                    case 'completed_at_start':
                        if ($value != null)
                            $this->tickets->where('tickets.completed_at', '>=', Carbon::parse($value))
                                        ->orWhere('tickets.archived_at', '>=', Carbon::parse($value));
                        break;
                    case 'completed_at_end':
                        if ($value != null)
                            $this->tickets->where('tickets.completed_at', '<', Carbon::parse($value)->addDays(1))
                                        ->orWhere('tickets.archived_at', '<', Carbon::parse($value)->addDays(1));
                        break;
                }
            }
            session()->flashInput($request->input());
        }
        else session()->flashInput([]);

        // Soft pagination
        $this->pagination($request, $this->tickets);


        return view('tickets.list', [
            'tickets' => $this->tickets->get(),
            'statuses' => Ticket::STATUS,
            'dispatchers' => $dispatchers,
            'executors' => $executors,
            'types' => $types,
            'pagination' => $this->paginationDataGet($request, $this->tickets)
        ]);
    }

    public function createTicket() {
        if (!($this->checkRole(['dispatcher']) != 'wrongRole' || $this->checkTenant() != 'wrongRole'))
            return view('main.block');

        // Get tenants
        $tenants = Tenant::withoutArchived()->orderBy('surname', 'ASC')->get();

        // Get types of ticket
        $types = TicketType::withoutArchived()->orderBy('name', 'ASC')->get();

        return view('tickets.create', [
            'tenants' => $tenants,
            'types' => $types,
        ]);
    }
    public function createPostTicket(Request $request){
        if (!($this->checkRole(['dispatcher']) != 'wrongRole' || $this->checkTenant() != 'wrongRole'))
            return view('main.block');


        $dataValidation = ['desc' => 'required'];
        if ($this->checkStaff() != 'wrongRole'){
            $dataValidation['tenant'] = 'required';
            $dataValidation['type'] = 'required';
        }

        $validator = Validator::make($request->all(), $dataValidation)->validate();

        $ticket = new Ticket;
        $ticket->desc = $request->desc;
        if ($this->checkStaff() == 'wrongRole'){
            $ticket->idTenant = Auth::user()->tenant->id;
        }
        else{
            $ticket->idDispatcher = Auth::user()->staff->id;
            $ticket->idTenant = $request->tenant;
            $ticket->idTicketType = $request->type;
        }
        $ticket->save();

        return redirect()->route('tickets');
    }

    public function editTicket($id) {
        if (!($this->checkRole(['dispatcher']) != 'wrongRole' || $this->checkRole(['executor']) != 'wrongRole' || $this->checkTenant() != 'wrongRole'))
            return view('main.block');

        // Get executors
        $executors = Staff::withoutArchived()->where('role', 'executor')->get();

        // Get types of ticket
        $types = TicketType::withoutArchived()->get();

        return view('tickets.edit', [
            'executors' => $executors,
            'types' => $types,
            'ticket' => Ticket::withArchived()->find($id)
        ]);
    }
    public function editPostTicket($id, Request $request) {
        if (!($this->checkRole(['dispatcher']) != 'wrongRole'))
            return view('main.block');

        $ticket = Ticket::withoutArchived()->find($id);

        $ticket->idDispatcher = Auth::user()->staff->id;

        if ($request->executor != null && $request->executor != "") {
            $ticket->idExecutor = $request->executor;
        }

        if ($request->type != null && $request->type != "") {
            $ticket->idTicketType = $request->type;
        }

        $ticket->save();

        return redirect()->back();
    }
    public function completePostTicket($id, Request $request) {
        if (!($this->checkRole(['executor']) != 'wrongRole'))
            return view('main.block');

        $ticket = Ticket::withoutArchived()->find($id);
        $ticket->completed_at = Carbon::now();
        $ticket->save();

        return redirect()->route('tickets');
    }
    public function archiveTicket($id) {
        // if (!($this->checkRole(['dispatcher']) != 'wrongRole' || $this->checkTenant() != 'wrongRole'))
        //     return view('main.block');

        return view('tickets.archive', [
            'ticket' => Ticket::withArchived()->find($id)
        ]);
    }
    public function archivePostTicket($id, Request $request) {
        // if (!($this->checkRole(['dispatcher']) != 'wrongRole' || $this->checkTenant() != 'wrongRole'))
        //     return view('main.block');

        $ticket = Ticket::withoutArchived()->find($id);
        $ticket->archive();
        $ticket->archiveDesc = $request->archiveDesc;
        $ticket->idUserArchive = Auth::user()->id;
        $ticket->save();

        return redirect()->route('tickets');
    }
}
