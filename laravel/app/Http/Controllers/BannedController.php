<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banneds\CreateBannedValidation;
use App\Models\banned;
use App\Models\User;
use Illuminate\Http\Request;

class BannedController extends Controller
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
    public function store( CreateBannedValidation $createBannedValidation, User $user)
    {
        $request = $createBannedValidation->validated();
        $request['user_id'] = $user->id;
        banned::create($request);
        return redirect()->route('profile.show', ['user' => $user->id])->with(['banned' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\banned  $banned
     * @return \Illuminate\Http\Response
     */
    public function show(banned $banned)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\banned  $banned
     * @return \Illuminate\Http\Response
     */
    public function edit(banned $banned)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\banned  $banned
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, banned $banned)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\banned  $banned
     * @return \Illuminate\Http\Response
     */
    public function destroy(banned $banned, User $user)
    {
        $banned->delete();
        return redirect()->route('profile.show', ['user' => $user->id])->with(['Unbanned' => true]);
    }
}
