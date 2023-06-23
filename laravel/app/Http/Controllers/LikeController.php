<?php

namespace App\Http\Controllers;

use App\Models\like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
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

    public function check(Post $post)
    {
        $checkLike = DB::table('likes')->where('user_id', Auth::id())->where('post_id', $post->id)->exists();

        return response()->json(['checkLike' => $checkLike]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $checkLike = DB::table('likes')->where('user_id', Auth::id())->where('post_id', $post->id)->exists();
        if ($checkLike){
            $checkLike = DB::table('likes')->where('user_id', Auth::id())->where('post_id', $post->id)->delete();
            return response()->json(['statusLike'=>'delete']);
        }
        else{
            $like = [];
            $like['user_id'] = Auth::id();
            $like['post_id'] = $post->id;
            like::create($like);
            return response()->json(['statusLike'=>'create']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\like  $like
     * @return \Illuminate\Http\Response
     */
    public function show(like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\like  $like
     * @return \Illuminate\Http\Response
     */
    public function edit(like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy(like $like)
    {
        //
    }
}
