<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where('user_id', \Auth::user()->id)->get();
 
        return view('tasks.index', [
                'tasks' => $tasks,
        ]);
        
        /**
            //dd('indexが呼ばれた');
            $tasks = Task::all();
        
        return view('tasks.index', [
                'tasks' => $tasks,
            ]);
            */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // dd('createが呼ばれた');
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',   
            'content' => 'required|max:255',
        ]);
        
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
        ]);
        
        /**
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        */

        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
         if (auth()->user()->id != $task->user_id) {
            return redirect(route('tasks.index'));
        }

        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        if (auth()->user()->id != $task->user_id) {
            return redirect(route('tasks.index'));
        }

        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|max:10',   
            'content' => 'required|max:255',
        ]);
        
        $task = Task::findOrFail($id);
        if (auth()->user()->id != $task->user_id) {
            return redirect(route('tasks.index'));
        }
        
        /**
        $tasks = Task::where('user_id', \Auth::user()->id)->get();
        
        //$task = \App\Models\Task::findOrFail($id);
        //$task->update($request->findOrFail());
        
    
        $request->user()->tasks()->update([
            'status' => $request->status,
            'content' => $request->content,
        ]);
        */
        
        
        //$task = Task::findOrFail($id);
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
    

        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = \App\Models\Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        /**
        $task = Task::findOrFail($id);
        $task->delete();
        */

        return redirect('/tasks');
    }
}
