<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\LoginValidation;
use App\Http\Requests\Users\RegisterValidation;
use App\Http\Requests\Users\SearchUsersValidation;
use App\Http\Requests\Users\SearchValidation;
use App\Http\Requests\Users\UpdateAvatarValidation;
use App\Http\Requests\Users\UpdatePasswordValidation;
use App\Http\Requests\Users\UpdateValidation;
use App\Models\banned;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(){
        return view('users.register');
    }

    public function registerPost(RegisterValidation $registerValidation){
        $request = $registerValidation->validated();
        $request['password'] = Hash::make($request['password']);
        User::create($request);
        return redirect()->route('login')->with(['register' => true]);
    }

    public function login(){
        return view('users.login');
    }

    public function loginPost(LoginValidation $loginValidation){
        if (Auth::attempt($loginValidation->validated())){
            $banned = banned::where('user_id', Auth::id())->latest()->first();
            if (!empty($banned)){
                if ($banned['period'] == 'Навсегда'){
                    $period = 'Навсегда';
                    Auth::logout();
                    $loginValidation->session()->regenerate();
                    return back()->withErrors(['errorBanned' => $period]);
                }
                else{
                    $dateOutBanned = $banned['created_at']->addDays($banned['period']);
                    if (Carbon::now()>$dateOutBanned){
                        $loginValidation->session()->regenerate();
                        return redirect()->route('main');
                    }
                    else{

                        $period = Carbon::parse($dateOutBanned)->diff(Carbon::now())->format('%d дней %h часов %i минут');
                        Auth::logout();
                        $loginValidation->session()->regenerate();
                        return back()->withErrors(['errorBanned' => $period]);
                    }
                }
            }
            else{
                $loginValidation->session()->regenerate();
                return redirect()->route('main');
            }
        }
        return back()->withErrors(['errorAuth' => true]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->regenerate();
        return redirect()->route('login');
    }
    public function index(Request $request)
    {
        $users = User::paginate(21);
        return view('users.profile.index', compact('users'));
    }

    public function search(SearchUsersValidation $searchUsersValidation)
    {
        $request = $searchUsersValidation->validated();
        $users = [];
        if (!empty($request['login'])){
            $users = User::where('login','LIKE', "%{$request['login']}%")->paginate(21)->appends(request()->query());
        }
        else{
            $users = User::paginate(21);
        }
        return view('users.profile.index', compact('users'));
    }

    public function update(Request $request,User $user){
        $request->session()->flashInput($user->toArray());
        return view('users.profile.update', compact('user'));

    }

    public function updateAvatar(UpdateAvatarValidation $avatarValidation, User $user){
        $request = $avatarValidation->validated();
        unset($request['photo']);
        # public/sdfsdfsdfsd.jpg
        $photo = $avatarValidation->file('photo')->store('public');
        # Explode => / => public/sdfsdfsdfsd.jpg => ['public', 'sdfsdfsdfsd.jpg']
        $request['photo'] = explode('/',$photo)[1];
        $user->update($request);
        return back()->with(['updateAvatar' => true]);
    }

    public function updateAccountMain(UpdateValidation $updateValidation, User $user){
        $request = $updateValidation->validated();
        if (Hash::check($request['passwordReal'], $user->password)){
            unset($request['passwordReal']);
            $user->update($request);
            return back();
        }
        return back()->withErrors(['passwordReal' => 'Неверный пароль']);
    }

    public function updateAccountPassword(UpdatePasswordValidation $passwordValidation, User $user){
        $request = $passwordValidation->validated();
        if (Hash::check($request['oldPassword'], $user->password)){
            unset($request['oldPassword']);
            $request['password'] = Hash::make($request['password']);
            $user->update($request);
            return back();
        }
        return back()->withErrors(['oldPassword' => 'Неверный пароль']);
    }

    public function show(User $user){
        $posts = Post::where('user_id', $user->id)
            ->select('posts.*')
            ->leftJoin('statuses', function ($join) {
                $join->on('statuses.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->whereNull('statuses.post_id')
            ->paginate(9, ['*'], 'posts-page');
        $likes = Post::select('posts.*')
            ->leftJoin('likes', function ($join) {
                $join->on('likes.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->where('likes.user_id', $user->id)
            ->select('posts.*')
            ->leftJoin('statuses', function ($join) {
                $join->on('statuses.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->whereNull('statuses.post_id')
            ->paginate(9, ['*'], 'likes-page');
        $favorites = Post::selectRaw('posts.*')
            ->leftJoin('favorites', function ($join) {
                $join->on('favorites.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->where('favorites.user_id', $user->id)
            ->select('posts.*')
            ->leftJoin('statuses', function ($join) {
                $join->on('statuses.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->whereNull('statuses.post_id')
            ->paginate(9, ['*'], 'favorites-page');

        $banned = banned::where('user_id', $user->id)->latest()->first();
        $bannedBool = '';
        if (!empty($banned)){
            if ($banned['period'] == 'Навсегда'){
                $bannedBool = true;
            }
            else{
                $dateOutBanned = $banned['created_at']->addDays($banned['period']);
                if (Carbon::now()>$dateOutBanned){
                    $bannedBool = false;
                }
                else{
                    $bannedBool = true;
                }
            }
        }
        else{
            $bannedBool = false;
        }
        return view('users.profile.show', compact('user', 'posts', 'likes', 'favorites', 'bannedBool'));
    }

    public function main(){
        $query = Post::query();
        $query->where('posts.created_at', '>=', now()->subDays(30))->get();
        $query->selectRaw('posts.*, count(likes.id) as likes_count')
            ->leftJoin('likes', function ($join) {
                $join->on('likes.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->orderByRaw('count(likes.id) desc');
        $query->select('posts.*')
            ->leftJoin('statuses', function ($join) {
                $join->on('statuses.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->whereNull('statuses.post_id');
        $posts = $query->take(5)->get();
        return view('users.main', ['posts' => $posts]);
    }
}
