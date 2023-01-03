<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Template;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;


class ProjectController extends Controller
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
    public function create(StoreProjectRequest $request)
    {
        $project = new Project();
        $project->project_name = $request->get('project_name');
        $project->description = $request->get('description');
        $project->save();
        return response()->json([
            'message'=>'Project Updated Successfully!!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function templates(Project $project)
    {
        $templates = Template::all();
        return view('projects/template', ['template' => $project->template_id, 'id' => $project->id, 'templates' => $templates]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $templates = Template::all();
        return view('projects/process', ['template' => $project->template_id, 'id' => $project->id, 'templates' => $templates]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function sheets(UpdateProjectRequest $request, Project $project)
    {
        $sheets = DB::table('sheets')->select('id AS value','sheet_name AS label')->where('project_id',$project->id)->get();
        return response()->json($sheets);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function template(UpdateProjectRequest $request, Project $project)
    {
        // Update
        DB::table('projects')->where('id',$project->id)->update(['template_id' => $request->get('template_id')]);
        return redirect()->route('Project.list');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function getTemplate(UpdateProjectRequest $request, Project $project)
    {
        return response()->json([
            $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $base64 = NULL;
        if($request->hasFile('logo')){
            $path = $request->file('logo')->getRealPath();
            $logo = file_get_contents($path);
            $base64 = base64_encode($logo);
            $mime = $request->file('logo')->getClientMimeType();
            $filename = $request->file('logo')->getClientOriginalName();
        }
        // Update
        DB::table('projects')->where('id',$request->get('id'))->update(['project_name' => $request->get('project_name'), 'description' => $request->get('description'), 'logo' => $base64, 'logo_type' => $mime, 'logo_name' => $filename]);
        return response()->json([
            'message'=>'Project Updated Successfully!!'
        ]);
    }

    public function download(Project $project)
    {
        $contents = $project->logo;
        return response("data:image/" . $project->logo_type . ";base64,".$contents)
                         ->header('Cache-Control', 'no-cache private')
                         ->header('Content-Description', 'File Transfer')
                         ->header('Content-length', strlen($contents))
                         ->header('Content-Disposition', 'attachment; filename='.$project->logo_name)
                         ->header('Content-Transfer-Encoding', 'binary');
    }
}
