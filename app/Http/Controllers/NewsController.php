<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Staff;
use App\Models\News;


class NewsController extends Controller
{
    public $news;

    public function __construct(Request $request) {
        $this->news = News::query();
    }


    public function list(Request $request) {
        if ($this->checkRole(['admin']) == 'wrongRole')
            return view('main.block');


        $this->news->orderBy('created_at', 'DESC');
        $this->filter($request);
        $this->pagination($request, $this->news);

        return view('news.news', [
            'news' => $this->news->get(),
            'pagination' => $this->paginationDataGet($request, $this->news)
        ]);
    }
    public function listForAll(Request $request) {
        $this->news->withoutArchived();


        if ($this->checkStaff() == null)
            $this->news->where('type', 'staff');
        else
            $this->news->where('type', 'tenants');

        $this->news->orderBy('created_at', 'DESC');
        $this->filter($request);
        $this->pagination($request, $this->news);

        return view('news.newsForAll', [
            'news' => $this->news->get(),
            'pagination' => $this->paginationDataGet($request, $this->news)
        ]);
    }
    public function filter(Request $request) {
        if (!isset($request->reset)) {
            foreach ($request->all() as $key => $value) {
                switch ($key) {
                    case 'status':
                        if ($value == 'any') {
                            $this->news->withArchived();
                        } else if ($value == 'archived') {
                            $this->news->onlyArchived();
                        }
                        break;
                    case 'type':
                        if ($value == 'staff') {
                            $this->news->where('type', News::TYPE['staff']);
                        } else if ($value == 'tenants') {
                            $this->news->where('type', News::TYPE['tenants']);
                        }
                        break;
                    case 'dtStart':
                        if ($value != null)
                            $this->news->where('created_at', '>=', Carbon::parse($value));
                        break;
                    case 'dtEnd':
                        if ($value != null)
                            $this->news->where('created_at', '<', Carbon::parse($value)->addDays(1));
                        break;
                }
            }
            session()->flashInput($request->input());
        }
        else session()->flashInput([]);
    }
    public function create() {
        if ($this->checkRole(['admin']) == 'wrongRole')
            return view('main.block');


        return view('news.edit');
    }
    public function editPost(Request $request) {
        if ($this->checkRole(['admin']) == 'wrongRole')
            return view('main.block');


        $dataValidation = [
            'title' => 'required|max:255',
            'message' => 'required|max:4294967295',
        ];
        Validator::make($request->all(), $dataValidation)->validate();

        if (isset($request->id)) {
            $news = News::withArchived()->find($request->id);
        }
        else{
            $news = new News;
        }
        $news->title = $request->title;
        $news->icon = $request->image;
        $news->src = $request->message;
        $news->type = $request->type;
        $news->save();

        return redirect()->route('news');
    }
    public function edit($id) {
        if ($this->checkRole(['admin']) == 'wrongRole')
            return view('main.block');


        return view('news.edit', [
            'news' => News::withArchived()->find($id)
        ]);
    }

    public function view($id) {
        if ($this->checkRole(['admin']) == 'wrongRole')
            return view('main.block');


        return view('news.view', [
            'news' => News::withArchived()->find($id)
        ]);
    }


    public function archive(Request $request) {
        if ($this->checkRole(['admin']) == 'wrongRole')
            return view('main.block');


        $id = $request->id;
        $item = News::withArchived()->find($id);
        if ($item->archived_at == null)
            $item->archive();
        else
            $item->unArchive();

        return redirect()->route('news', ['news' => $this->news->get()]);
    }
}
