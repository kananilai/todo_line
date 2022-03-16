<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;


class TaskController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $check = Task::where('user_id',$user_id)->where('status',0)->first();
        $done_check = Task::where('user_id',$user_id)->where('status',1)->first();
        if($check != null && $done_check != null){
            $items = Task::where('user_id',$user_id)->where('status',0)->orderBy('deadline', 'asc')->get();
            $done_items = Task::where('user_id',$user_id)->where('status',1)->orderBy('deadline', 'asc')->get();
            $today = new Carbon('tomorrow');
            return view('main',['items'=>$items,'today'=> $today,'done_items'=>$done_items]);
        }
        else if($check == null && $done_check != null){
            $done_items = Task::where('user_id',$user_id)->where('status',1)->orderBy('deadline', 'asc')->get();
            return view('main',['done_items'=>$done_items]);
        }
        else if($check != null && $done_check == null){
            $items = Task::where('user_id',$user_id)->where('status',0)->orderBy('deadline', 'asc')->get();
            $today = new Carbon('tomorrow');
            return view('main',['items'=>$items,'today'=> $today]);
        }
        else{
            return view('main');
        }
    }



    public function create(Request $request)
    {
        $deadline = date('Y-m-d H:i',strtotime($request->deadline));
        $user_id = Auth::id();
        Task::create([
            'user_id'=>$user_id,
            'task' =>$request->task,
            'deadline' =>$deadline
        ]);
        return redirect('/main');
    }

    public function update(Request $request)
    {
        Task::where('id', $request->id)->update(['task' => $request->task]);
        return redirect('/main');
    }

    public function delete(Request $request)
    {
        Task::where('id',$request->id)->delete();
        return redirect('/main');
    }

    public function done(Request $request)
    {
        Task::where('id',$request->id)->update(['status' => 1]);
        return redirect('/main');
    }
}
