<?php

namespace App\Http\Controllers;

use App\Models\favorite;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
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
        $checkFavorite = DB::table('favorites')->where('user_id', Auth::id())->where('post_id', $post->id)->exists();

        return response()->json(['checkFavorite' => $checkFavorite]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $checkFavorite = DB::table('favorites')->where('user_id', Auth::id())->where('post_id', $post->id)->exists();
        if ($checkFavorite){
            $checkFavorite = DB::table('favorites')->where('user_id', Auth::id())->where('post_id', $post->id)->delete();
            return response()->json(['statusFavorite'=>'delete']);
        }
        else{
            $favorite = [];
            $favorite['user_id'] = Auth::id();
            $favorite['post_id'] = $post->id;
            favorite::create($favorite);
            return response()->json(['statusFavorite'=>'create']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function show(favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function edit(favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function destroy(favorite $favorite)
    {
        //
    }
}
