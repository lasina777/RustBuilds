<?php

namespace App\Http\Controllers;

use App\Http\Requests\Statuses\CreateStatusValidation;
use App\Models\Post;
use App\Models\status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::select('posts.*')
            ->whereHas('status', function ($query) {
                $query->where('status', '=', 'Ожидание')
                    ->whereRaw('created_at = (SELECT MAX(created_at) FROM statuses WHERE post_id = posts.id)');
                $query->where('status', '=', 'Ожидание');
            })
            ->paginate(9);
        return view('admin.applications.index', compact('posts'));
    }

    public function indexUser()
    {
        $query = Post::query();
        $query->select('posts.*')
            ->leftJoin('statuses', function ($join) {
                $join->on('statuses.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->where('posts.user_id', Auth::id())
            ->whereNotNull('statuses.post_id');
        $posts = $query->paginate(9);
        return view('users.applications.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStatusValidation $createStatusValidation, Post $post)
    {
        $request = $createStatusValidation->validated();
        $request['post_id'] = $post->id;
        $request['post_id'] = $post->id;
        $request['status'] = 'Отменен';
        $statusPost = status::create($request);
        return redirect()->route('admin.applications.index')->with(['cancel' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\status  $status
     * @return \Illuminate\Http\Response
     */
    public function show(status $status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\status  $status
     * @return \Illuminate\Http\Response
     */
    public function edit(status $status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, status $status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $deleteStatus = status::where('post_id', $post->id)->delete();
        return redirect()->route('admin.applications.index')->with(['accept' => true]);
    }
}
