<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project, Sheet $sheet)
    {
        return view('manager/process', ['project' => $project, 'sheet' => $sheet]);
    }
}
