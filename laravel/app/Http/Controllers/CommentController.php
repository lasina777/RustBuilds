<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comments\CreateComment;
use App\Http\Requests\Comments\FiltrationComment;
use App\Http\Requests\Comments\UpdateComment;
use App\Models\comment;
use App\Models\Post;
use App\Models\PostItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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
    public function store(CreateComment $createComment, Post $post)
    {
        $request = $createComment->validated();
        if (isset($request['photo'])){
            unset($request['photo']);
            $photo = $createComment->file('photo')->store('public/comment/images');
            $request['photo'] = explode('/',$photo)[3];
        }
        $request['user_id'] = Auth::id();
        $request['post_id'] = $post->id;
        comment::create($request);
        return redirect()->route('post.show', ['post' => $post->id])->with(['create' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComment $updateComment, comment $comment, Post $post)
    {
        $request = $updateComment->validated();
        if (isset($request['current_image'])||isset($request['photo'])){
            if (isset($request['photo'])){
                unset($request['photo']);
                $photo = $updateComment->file('photo')->store('public/comment/images');
                $request['photo'] = explode('/',$photo)[3];
            }
            if (isset($request['current_image'])){
                $request['photo'] = $request['current_image'];
                unset($request['current_image']);
            }
        }
        else{
            $request['photo'] = NULL;
        }
        $comment->update($request);
        return redirect()->route('post.show', ['post' => $post->id])->with(['update' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(comment $comment, Post $post)
    {
        $comment->delete();
        return redirect()->route('post.show', ['post' => $post->id])->with(['destroy' => true]);
    }

    public function filtration(FiltrationComment $filtrationComment , Post $post)
    {
        $request = $filtrationComment->validated();
        $query = comment::query();
        $query->where('post_id', $post->id);
        if ($request['filtration'] == 'all'){
            $query->selectRaw('comments.*, count(likes_comments.id) as likes_count')
                ->leftJoin('likes_comments', function ($join) {
                    $join->on('likes_comments.comment_id', '=', 'comments.id');
                })
                ->groupBy('comments.id')
                ->orderBy('likes_count', 'desc')
                ->orderBy('created_at', 'desc');
        }
        if ($request['filtration'] == 'dataIncrease'){
            $query->orderBy('created_at', 'asc');
        }
        if ($request['filtration'] == 'dataDescending'){
            $query->orderBy('created_at', 'desc');
        }
        if ($request['filtration'] == 'likeDescending'){
            $query->selectRaw('comments.*, count(likes_comments.id) as likes_count')
                ->leftJoin('likes_comments', function ($join) {
                    $join->on('likes_comments.comment_id', '=', 'comments.id');
                })
                ->groupBy('comments.id')
                ->orderBy('likes_count', 'desc')
                ->orderBy('created_at', 'desc');
        }
        if ($request['filtration'] == 'likeIncrease'){
            $query->selectRaw('comments.*, count(likes_comments.id) as likes_count')
                ->leftJoin('likes_comments', function ($join) {
                    $join->on('likes_comments.comment_id', '=', 'comments.id');
                })
                ->groupBy('comments.id')
                ->orderBy('likes_count', 'asc')
                ->orderBy('created_at', 'desc');
        }
        $comments = $query->paginate(10)->appends(request()->query());
        $postItems = PostItem::where('post_id', $post->id)->get();
        return view('users.posts.show', compact('comments' , 'post' , 'postItems'));
    }
}
