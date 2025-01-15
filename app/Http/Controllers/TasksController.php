<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザーを取得
            $user = \Auth::user();
            // ユーザーとフォロー中ユーザーの投稿の一覧を作成日時の降順で取得
            $tasks = $user->tasks()->orderBy('id', 'asc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // dashboardビューでそれらを表示
        return view('dashboard', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
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

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [
                'task' => $task,
            ]);
        }

        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::findOrFail($id);

        // メッセージ編集ビューでそれを表示
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
        
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            $task->update($validated);
            return redirect('/')
                ->with('success','Update Successful');
        }

        return redirect('/')
            ->with('Update Failed');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/')
                ->with('success','Delete Successful');
        }

        return redirect('/')
            ->with('Delete Failed');
    }
}
