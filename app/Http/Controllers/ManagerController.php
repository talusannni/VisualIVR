<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Project;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return view('manager/process', ['template' => $project->template_id, 'id' => $project->id]);
    }
}
