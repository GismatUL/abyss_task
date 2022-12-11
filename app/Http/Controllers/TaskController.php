<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Task::select('name','description','type')->paginate(10);

        return response()->json($data,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $taskRequest)
    {
        $data = $taskRequest->validated();
        $data['file'] = str_replace("tasks/", "", $taskRequest->file('file')->store('tasks'));
        $record = Task::create($data);

        return response()->json([
            'name'=>$record->name,
            'description'=>$record->description,
            'type'=>$record->type,
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Task::where(['id'=>$id])->select('name','description','file','type')->firstOrfail();
        $url = Storage::disk('local')->temporaryUrl($data->file, Carbon::now()->addMinutes(10));

        return response()->json([
            'name'=>$data->name,
            'description'=>$data->description,
            'temp_url'=>$url,
            'type'=>$data->type,
        ],200);
    }

    public function downloadImage(Request $request, $path)
    {
        if (! $request->hasValidSignature()) abort(419);
        return \Illuminate\Support\Facades\Storage::disk('local')->download('tasks/'.$path);
    }
}
