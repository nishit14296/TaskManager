<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('task_details.index');
    }
    public function GetTaskList(Request $request)
    {
        $searchable = $request->get('search')['value'];
        $column=$request->order[0]['column'];
        $direction=$request->order[0]['dir'];
        $query=TaskDetail::where('user_id',Auth::user()->id);
        $start=$request->start;
        $length=$request->length;
        if($searchable){
            $query->where('task_title','LIKE','%'.$searchable.'%')
            ->orwhere('description','LIKE','%'.$searchable.'%')
            ->orwhere('date_time','LIKE','%'.$searchable.'%');
        }
        if ($column==0)
        {
            $query->orderBy('task_title',$direction);
        }
        elseif ($column==1)
        {
            $query->orderBy('description',$direction);
        }
        elseif ($column==2)
        {
            $query->orderBy('date_time',$direction);
        }
        elseif ($column==4)
        {
            $query->orderBy('priority',$direction);
        }
       
        $totalTasks=$query->get();
        $Tasks=$query->limit($length)->offset($start)->get();
        //dump($Products);
        return response()->json([
           'iTotalRecords'=>count($totalTasks),
            'iTotalDisplayRecords'=>count($totalTasks),
            'sEcho'=>0,
            'sColumns'=>"",
            'aaData'=>$Tasks->map(function ($Tasks){
                return [
                    'Task Title'=>$Tasks->task_title,
                    'Description'=>$Tasks->description,
                    'Date and Time'=>$Tasks->date_time,
                    'Priority'=>($Tasks->priority == TaskDetail::Low) ? '<span class="text-success">Low</span>' :(($Tasks->priority  == TaskDetail::Medium) ? '<span class="text-primary">Medium</span>':'<span class="text-danger">High</span>'),
                    'Action'=>
                    '<a href="'.route('edit_task',$Tasks->id).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit Task"><i class="fa fa-pencil-square-o"></i></i></a>'.
                    '<a href="javascript:void(0);" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete Task" onclick="deleteTask('.$Tasks->id.');"><i class="fa fa-times"></i></a>',
                ];
            })
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('task_details.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'task_title'=>'required|regex:/(^[A-Za-z ]+$)+/',
            'description'=>'required|max:255',
            'date_time'=>'required',
            'priority'=>'required'
        ],[
            'task_title.required'=>'Please enter Title.',
            'task_title.regex'=>'Invalid Title.',
            'description'=>'Please enter description.',
            'date_time'=>'Please enter date time.',
            'priority'=>'Please select priority.'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
        $task_details=new TaskDetail();
        $task_details->task_title = $request->task_title;
        $task_details->description = $request->description;
        $task_details->date_time = $request->date_time;
        $task_details->priority = $request->priority;
        $task_details->user_id = Auth::user()->id;
        if($task_details->save()){
            return 0;
        }
        return 1;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $task_detail = TaskDetail::find($id) ;
       return view('task_details.edit',compact('task_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'task_title'=>'required|regex:/(^[A-Za-z ]+$)+/',
            'description'=>'required|max:255',
            'date_time'=>'required',
            'priority'=>'required'
        ],[
            'task_title.required'=>'Please enter Title.',
            'task_title.regex'=>'Invalid Title.',
            'description'=>'Please enter description.',
            'date_time'=>'Please enter date time.',
            'priority'=>'Please select priority.'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
        $task_details=TaskDetail::find($request->task_id);
        $task_details->task_title = $request->task_title;
        $task_details->description = $request->description;
        $task_details->date_time = $request->date_time;
        $task_details->priority = $request->priority;
        $task_details->user_id = Auth::user()->id;
        if($task_details->save()){
            return 0;
        }
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $task = TaskDetail::find($request->TaskId);
        if($task)
        {
            $task->delete();
            return 0;
        }
    }
}
