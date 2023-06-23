<?php

namespace App\Http\Controllers;


use App\Http\Requests\Posts\CreatePostValidation;
use App\Http\Requests\Posts\FiltrationPostValidation;
use App\Http\Requests\Posts\OrderingPostValidation;
use App\Http\Requests\Posts\SearchPostValidation;
use App\Http\Requests\Posts\UpdatePostValidation;
use App\Models\Category;
use App\Models\comment;
use App\Models\Hashtag;
use App\Models\like;
use App\Models\Post;
use App\Models\PostItem;
use App\Models\status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget(['ordering','filtration','search']);
        $query = Post::query();
        $query->select('posts.*')
            ->leftJoin('statuses', function ($join) {
                $join->on('statuses.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->whereNull('statuses.post_id');
        $posts = $query->paginate(9);
        $categories = Category::all();
        return view('users.posts.index', compact('posts', 'categories'));
    }

    public function choiceCategory()
    {
        $categories = Category::all();
        return view('users.posts.choiceOrUpdateCategory', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        return view('users.posts.createOrUpdate', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostValidation $createPostValidation, Category $category)
    {
        $request = $createPostValidation->validated();
        $requestPost = $createPostValidation->only(['name', 'photo', 'fortify']);
        if (isset($requestPost['fortify'])){
            unset($requestPost['fortify']);
            $fortify = $createPostValidation->file('fortify')->store('public/post/fortify');
            $requestPost['fortify'] = explode('/',$fortify)[3];
        }
        $requestPost['category_id'] = $category->id;
        $requestPost['user_id'] = Auth::id();
        unset($requestPost['photo']);
        # public/sdfsdfsdfsd.jpg
        $photo = $createPostValidation->file('photo')->store('public/post/images');
        # Explode => / => public/sdfsdfsdfsd.jpg => ['public', 'sdfsdfsdfsd.jpg']
        $requestPost['photo'] = explode('/',$photo)[3];
        $post = Post::create($requestPost);
        for ($i=0;$i<count($request['headers']);$i++){
            $requestPostItem = [];
            $requestPostItem['post_id'] = $post->id;
            $requestPostItem['header'] = $request['headers'][$i];
            $requestPostItem['photo'] = $request['photos'][$i];
            unset($requestPostItem['photo']);
            $photo = $createPostValidation['photos'][$i]->store('public/postItems/images');
            $requestPostItem['photo'] = explode('/',$photo)[3];
            $requestPostItem['information'] = $request['informations'][$i];
            $postItem = PostItem::create($requestPostItem);
        }
        if (isset($request['hashtags'])){
            for ($i=0;$i<count($request['hashtags']);$i++){
                $requestHashtags = [];
                $requestHashtags['post_id'] = $post->id;
                $requestHashtags['name'] = $request['hashtags'][$i];
                $postHashtags = Hashtag::create($requestHashtags);
            }
        }
        $requestStatus = [];
        $requestStatus['post_id'] = $post->id;
        $requestStatus['status'] = 'Ожидание';
        $statusPost = status::create($requestStatus);

        return redirect()->route('post.index')->with(['add' => true]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, Request $request)
    {
        if (empty($post->status->all())||$post->user->id == Auth::id()||Auth::user()->role->name == 'Администратор'){
            $postItems = PostItem::where('post_id', $post->id)->get();
            $query = comment::query();
            $query->where('post_id', $post->id);
            $query->selectRaw('comments.*, count(likes_comments.id) as likes_count')
                ->leftJoin('likes_comments', function ($join) {
                    $join->on('likes_comments.comment_id', '=', 'comments.id');
                })
                ->groupBy('comments.id')
                ->orderBy('likes_count', 'desc')
                ->orderBy('created_at', 'desc');
            $comments = $query->paginate(10);
            return view('users.posts.show', compact('post', 'postItems', 'comments'));
        }
        else{
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Post $post)
    {
        $request->session()->flashInput($post->toArray());
        return view('users.posts.createOrUpdate', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostValidation $updatePostValidation, Post $post)
    {
        $request = $updatePostValidation->validated();
        $requestPost = $updatePostValidation->only(['name', 'photo', 'fortify']);
        if (isset($requestPost['fortify'])){
            unset($requestPost['fortify']);
            $fortify = $updatePostValidation->file('fortify')->store('public/post/fortify');
            $requestPost['fortify'] = explode('/',$fortify)[3];
        }
        $requestPost['user_id'] = Auth::id();
        if (isset($requestPost['photo'])){
            unset($requestPost['photo']);
            # public/sdfsdfsdfsd.jpg
            $photo = $updatePostValidation->file('photo')->store('public/post/images');
            # Explode => / => public/sdfsdfsdfsd.jpg => ['public', 'sdfsdfsdfsd.jpg']
            $requestPost['photo'] = explode('/',$photo)[3];
        }
        $post->update($requestPost);
        $postItemsOld = PostItem::where('post_id', $post->id)->delete();
        for ($i=0;$i<count($request['headers']);$i++){
            $requestPostItem = [];
            $requestPostItem['post_id'] = $post->id;
            $requestPostItem['header'] = $request['headers'][$i];
            if (isset($request['photos'][$i])){
                $requestPostItem['photo'] = $request['photos'][$i];
                unset($requestPostItem['photo']);
                $photo = $updatePostValidation['photos'][$i]->store('public/postItems/images');
                $requestPostItem['photo'] = explode('/',$photo)[3];
            }
            else{
                if ($request['current_imagePost'][$i]){
                    $requestPostItem['photo'] = $request['current_imagePost'][$i];
                }
            }
            $requestPostItem['information'] = $request['informations'][$i];
            $postItem = PostItem::create($requestPostItem);
        }
        $postHashtagsOld = Hashtag::where('post_id', $post->id)->delete();
        if (isset($request['hashtags'])){
            for ($i=0;$i<count($request['hashtags']);$i++){
                $requestHashtags = [];
                $requestHashtags['post_id'] = $post->id;
                $requestHashtags['name'] = $request['hashtags'][$i];
                $postHashtags = Hashtag::create($requestHashtags);
            }
        }
        $requestStatus = [];
        $requestStatus['post_id'] = $post->id;
        $requestStatus['status'] = 'Ожидание';
        $statusPost = status::create($requestStatus);

        return redirect()->route('applications.index')->with(['updatePost' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('post.index')->with(['destroy' => true]);
    }

    public function filtration(FiltrationPostValidation $filtrationPostValidation, $filtration = null)
    {
        if (isset($filtration)){
            request()->session()->forget($filtration);
        }
        $request = $filtrationPostValidation->validated();
        foreach ($request as $key => $elem){
            if ($elem != null){
                $filtrationPostValidation->session()->put($key, $elem);
            }
        }
        $name = $filtrationPostValidation->session()->all();

        $query = Post::query();
        $categories = Category::all();
        $categoriesid = [];

        $query->select('posts.*')
            ->leftJoin('statuses', function ($join) {
                $join->on('statuses.post_id', '=', 'posts.id');
            })
            ->groupBy('posts.id')
            ->whereNull('statuses.post_id');

        foreach ($categories as $category){
            array_push($categoriesid ,$category->id);
        }

        if (isset($name['filtration'])){
            if (in_array($name['filtration'], $categoriesid)){
                $query->where('category_id', $name['filtration']);
            }
        }

        if (isset($name['ordering'])){
            if ($name['ordering'] == 'dataDescending'){
                $query->orderBy('created_at', 'desc');
            }
            elseif ($name['ordering'] == 'dataIncrease'){
                $query->orderBy('created_at', 'asc');
            }
            elseif ($name['ordering'] == 'likeDescending'){
                $query->select('posts.*')
                    ->leftJoin('likes', function ($join) {
                        $join->on('likes.post_id', '=', 'posts.id');
                    })
                    ->groupBy('posts.id')
                    ->orderByRaw('count(likes.id) desc');
            }
            elseif ($name['ordering'] == 'likeIncrease'){
                $query->select('posts.*')
                    ->leftJoin('likes', function ($join) {
                        $join->on('likes.post_id', '=', 'posts.id');
                    })
                    ->groupBy('posts.id')
                    ->orderByRaw('count(likes.id) asc');
            }
            elseif ($name['ordering'] == 'nameDescending'){
                $query->orderBy('name', 'desc');
            }
            elseif ($name['ordering'] == 'nameIncrease'){
                $query->orderBy('name', 'asc');
            }
        }
        if (isset($name['search'])){
            $query->select('posts.*')
                ->leftJoin('hashtags', function ($join) {
                    $join->on('hashtags.post_id', '=', 'posts.id');
                })
                ->groupBy('posts.id')
                ->where('posts.name','LIKE', "%{$name['search']}%")
                ->orWhere('hashtags.name','LIKE', "%{$name['search']}%");
        }
        $posts = $query->paginate(9)->appends(request()->query());
        return view('users.posts.index', compact('posts', 'categories'));
    }
}
