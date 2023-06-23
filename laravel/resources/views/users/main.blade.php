@extends('index')

@section('title', 'Страница вывода всех пользователей')

@section('content')
    <div class="video__block">
        <video class="video_media" autoplay muted loop>
            <source src="/assets/image/videoback.webm">
        </video>
    </div>
    <div class="container Column">
        @if(!empty($posts->all()))
            <div class="slider">
                <div class="slider_line">
                    @foreach($posts as $post)
                        <a href="{{route('post.show', ['post' => $post->id])}}" class="headerPostSlider">
                            <img class="headerImagePostSlider" src="/storage/post/images/{{$post->photo}}" alt="...">
                            <div class="headerInfoSlider">
                                <div class="headerDetailsSlider">
                                    <div class="namePostSlider">{{$post->name}}</div>
                                    <div class="categoryPostSlider">Категория: {{$post->category->name}}</div>
                                    <div class="dateCreatePostSlider">{{ $post->created_at->format('d m Y') }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <a class="prev" onclick="minusSlide()">&#10094;</a>
                <a class="next" onclick="plusSlide()">&#10095;</a>
            </div>
        @endif
        <div class="forumHeader">
            <div class="bigTextMain">RUSTBUILD</div>
            <div class="MediumtextMain">Форум необходим для того чтобы можно было
                воспользоваться постройкой других пользователей в собственном проекте, а
                не придумывать ее самому. И наоборот, если пользователь, который считает,
                что его постройка, по сравнению с остальными более креативная и
                модернизированная, может выложить ее на форум, для того чтобы о ней
                узнало больше людей.
            </div>
            <a class="LinkToPosts" href="{{route('post.index')}}">К постам</a>
        </div>
    </div>
    <script>
        let offset = 0;
        const sliderLine= document.querySelector('.slider_line');

        document.querySelector('.next').addEventListener('click', function(){
            offset = offset + 1000;
            if(offset > {{(count($posts)-1)*1000}}){
                offset = 0;
            }
            sliderLine.style.left = -offset + 'px';
        });

        document.querySelector('.prev').addEventListener('click', function(){
            offset = offset - 1000;
            if(offset < 0){
                offset = {{(count($posts)-1)*1000}};
            }
            sliderLine.style.left = -offset + 'px';
        });
    </script>
@endsection
