@extends('index')

@section('title', 'Страница выбора категории')

@section('content')
    <div class="imageRegister" id="category">
        <div class="container">
            <div class="formCategories">
                @if(isset($post))
                    <h2 class="categoryText">Изменение категории: {{$post->name}}</h2>
                @else
                    <h2 class="categoryText">Выбор категории:</h2>
                @endif
                    <div class="categories">
                @foreach($categories as $category)
                        <a class="linkCategory" href="{{isset($post) ? : route('post.create', ['category' => $category->id])}}">
                            <img class="imageCategory" width="50px" height="50px" src="{{'/storage/categories/' . $category->photo}}" alt="{{$category->name}}">
                            <div class="nameCategory">{{$category->name}}</div>
                        </a>
                @endforeach
                    </div>
            </div>
        </div>
    </div>
@endsection

