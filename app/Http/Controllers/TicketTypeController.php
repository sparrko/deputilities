<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\TicketType;

class TicketTypeController extends Controller
{
    public $ticketTypes;

    public function __construct(Request $request) {
        $this->ticketTypes = TicketType::query();
    }

    public function list(Request $request) {
        if ($this->checkRole(['dispatcher']) == 'wrongRole')
            return view('main.block');


        $this->ticketTypes->orderBy('name', 'ASC');
        $this->filter($request);
        $this->pagination($request, $this->ticketTypes);

        return view('ticketTypes.list', [
            'ticketTypes' => $this->ticketTypes->get(),
            'pagination' => $this->paginationDataGet($request, $this->ticketTypes)
        ]);
    }

    public function filter(Request $request) {
        if (!isset($request->reset)) {
            foreach ($request->all() as $key => $value) {
                switch ($key) {
                    case 'status':
                        if ($value == 'any') {
                            $this->ticketTypes->withArchived();
                        } else if ($value == 'archived') {
                            $this->ticketTypes->onlyArchived();
                        }
                        break;
                    case 'name':
                        $this->ticketTypes->whereRaw(
                            "TRIM(name) like '%{$value}%'"
                        );
                        break;
                }
            }
            session()->flashInput($request->input());
        }
        else session()->flashInput([]);
    }

    public function create() {
        if ($this->checkRole(['dispatcher']) == 'wrongRole')
            return view('main.block');

        return view('ticketTypes.edit', [
        ]);
    }
    public function edit($id) {
        if ($this->checkRole(['dispatcher']) == 'wrongRole')
            return view('main.block');


        return view('ticketTypes.edit', [
            'ticketType' => TicketType::withArchived()->find($id),
        ]);
    }
    public function editPost(Request $request) {
        if ($this->checkRole(['dispatcher']) == 'wrongRole')
            return view('main.block');


        $dataValidation = [
            'name' => 'required|unique:tickettypes',
        ];

        $validator = Validator::make($request->all(), $dataValidation)->validate();


        if (isset($request->id)) {
            $ticketType = TicketType::withArchived()->find($request->id);
        }
        else{
            $ticketType = new TicketType;
        }

        $ticketType->name = $request->name;

        $ticketType->save();

        return redirect()->route('ticket_types');
    }
    public function archive(Request $request) {
        if ($this->checkRole(['dispatcher']) == 'wrongRole')
            return view('main.block');


        $id = $request->id;
        $item = TicketType::withArchived()->find($id);
        if ($item->archived_at == null)
            $item->archive();
        else
            $item->unArchive();

        return redirect()->route('ticket_types', ['ticketTypes' => $this->ticketTypes->get()]);
    }
}
