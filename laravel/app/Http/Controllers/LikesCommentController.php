<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\likesComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikesCommentController extends Controller
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

    public function check(comment $comment)
    {
        $checkLike = DB::table('likes_comments')->where('user_id', Auth::id())->where('comment_id', $comment->id)->exists();

        return response()->json(['checkLike' => $checkLike]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, comment $comment)
    {
        $checkLike = DB::table('likes_comments')->where('user_id', Auth::id())->where('comment_id', $comment->id)->exists();
        if ($checkLike){
            $checkLike = DB::table('likes_comments')->where('user_id', Auth::id())->where('comment_id', $comment->id)->delete();
            return response()->json(['statusLike'=>'delete']);
        }
        else{
            $like = [];
            $like['user_id'] = Auth::id();
            $like['comment_id'] = $comment->id;
            likesComment::create($like);
            return response()->json(['statusLike'=>'create']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\likesComment  $likesComment
     * @return \Illuminate\Http\Response
     */
    public function show(likesComment $likesComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\likesComment  $likesComment
     * @return \Illuminate\Http\Response
     */
    public function edit(likesComment $likesComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\likesComment  $likesComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, likesComment $likesComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\likesComment  $likesComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(likesComment $likesComment)
    {
        //
    }
}
