<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Sheet;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreSheetRequest;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\UpdateSheetRequest;

class SheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreSheetRequest $request)
    {
        try{
            $sheet = new Sheet();
            $sheet->sheet_name = $request->get('name');
            $sheet->project_id = $request->get('project_id');
            $sheet->save();
            return Response::json(array('success' => true, 'id' => $sheet->id), 200);
        }catch(Throwable $e){
            Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while Creating Page!!'
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSheetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSheetRequest $request)
    {
        try{
            $path = str_replace(" ", "-", $request->get('sheet_name'));
            Storage::disk('local')->put($path, $request->get('form'));
            $data = $request->except('_method','_token','submit','form');
            $sheet = Sheet::find($request->get('id'));
            $sheet->path = $path;
            $sheet->update($data);
        }catch(Throwable $e){
            Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong Storing form!!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sheet  $sheet
     * @return \Illuminate\Http\Response
     */
    public function show(Sheet $sheet)
    {
        try{
            return Storage::disk('local')->get($sheet->path);
        }catch(Throwable $e){
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSheetRequest  $request
     * @param  \App\Models\Sheet  $sheet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSheetRequest $request, Sheet $sheet)
    {
        try{
            $data = $request->except('_method','_token','submit');
            $sheet = Sheet::find($request->id);
            $sheet->update($data);
        }catch(Throwable $e){
            Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while Renaming Page!!'
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sheet  $sheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(UpdateSheetRequest $request)
    {
        try{
            $sheet = Sheet::destroy($request->id);
        }catch(Throwable $e){
            Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while Deleting Page!!'
            ],500);
        }
    }
}
