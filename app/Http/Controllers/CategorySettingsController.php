<?php

namespace App\Http\Controllers;

use App\CategorySettings;
use App\CategoryTournament;
use App\Http\Requests;
use App\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Response;

class CategorySettingsController extends Controller
{

    protected $currentModelName, $defaultSettings;

    public function __construct()
    {
        // Fetch the Site Settings object
//        $this->middleware('auth');
        $this->currentModelName = trans_choice('crud.categorySettings', 2);
        View::share('currentModelName', $this->currentModelName);
        $this->defaultSettings = CategorySettings::getDefaultSettings();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function create($tournamentId, $categoryId)
//    {
////        $defaultSettings = $this->defaultSettings;
//        return view("categories.create", compact('tournamentId', 'categoryId')); //, 'defaultSettings'
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $tournamentSlug, $categoryId)
    {
        $tournament = Tournament::findBySlug($tournamentSlug);

        $categoryTournament = CategoryTournament::where('tournament_id', $tournament->id)
            ->where('category_id', $categoryId)->first();

        $request->request->add(['category_tournament_id' => $categoryTournament->id]);
        if ($setting = CategorySettings::create($request->all())) {
            return Response::json(['settingId' =>$setting->id, 'msg' => 'Configuration created', 'status' => 'success']);
        } else {
            return Response::json(['msg' => 'Error configuring Category', 'status' => 'error']);
        }
//        flash()->success(Lang::get('core.operation_successful'));
//        return redirect("tournaments/$tournamentId/edit");

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
//    public function edit($tournamentId, $categoryId, $settingId)
//    {
//
//        $categorySetting = CategorySettings::findOrFail($settingId);
//
////        dd($categorySetting);
//        return view("categories.edit", compact('tournamentId', 'categoryId', 'categorySetting')); //, 'defaultSettings'
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tournamentId, $categoryId, $categorySettingsId)
    {
        if (CategorySettings::findOrFail($categorySettingsId)->update($request->all())) {
            return Response::json(['msg' => 'Category updated', 'status' => 'success']);
        } else {
            return Response::json(['msg' => 'Error updating category', 'status' => 'error']);
        }
//        flash()->success(trans('core.operation_successful'));
//        return redirect("tournaments/$tournamentId/edit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
