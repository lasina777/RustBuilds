@extends('index')

@section('title', 'Страница всех ролей')

@section('content')
    @if(session()->has('add'))
        <div class="message" id="green">Вы успешно добавили пост</div>
    @endif
    @if(session()->has('update'))
        <div class="message" id="yellow">Вы успешно изменили пост</div>
    @endif
    @if(session()->has('destroy'))
        <div class="message" id="red">Вы успешно удалили пост</div>
    @endif
    <div class="imagePostCards"></div>
    <div class="container functionsContainer">
        <div class="functionsPost">
            <div class="filtrations">
                <form action="{{route('post.filtration')}}" method="GET">
                    <select name="ordering" class="ordering select" onchange="this.form.submit()">
                        <option disabled selected value>Выберите вариант</option>
                        <option value="all">По умолчанию</option>
                        <option value="dataDescending">По убыванию, дата</option>
                        <option value="dataIncrease">По возрастанию, дата</option>
                        <option value="likeDescending">По убыванию, лайки</option>
                        <option value="likeIncrease">По возрастанию, лайки</option>
                        <option value="nameDescending">По убыванию, наименование</option>
                        <option value="nameIncrease">По возрастанию, наименование</option>
                    </select>
                </form>
                <form action="{{route('post.filtration')}}" method="GET">
                    <select name="filtration" class="filtration select" onchange="this.form.submit()">
                        <option disabled selected value>Выберите вариант</option>
                        <option value="all">По умолчанию</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="search">
                <form class="search" action="{{route('post.filtration')}}" method="GET">
                    <input placeholder="Поиск..." class="searchInput" type="text" name="search" value="{{old('search')}}">
                    <button class="searchButton" type="submit">Поиск</button>
                </form>
            </div>
        </div>
        <div class="filterPost">
            <?php
            $filter = request()->session()->all();
            if (isset($filter['filtration'])){
            ?> <a href="{{route('post.filtration', ['filtration' => 'filtration'])}}" class="deleteFilter" data-filter = "filtration">Отменить фильтрацию</a><?php
            }
            if (isset($filter['ordering'])){
            ?> <a href="{{route('post.filtration', ['filtration' => 'ordering'])}}" class="deleteFilter" data-filter = "ordering"> Отменить упорядочивание</a><?php
            }
            if (isset($filter['search'])){
            ?> <a href="{{route('post.filtration', ['filtration' => 'search'])}}" class="deleteFilter" data-filter = "search">Отменить поиск: '{{$filter['search']}}'</a><?php
            }
            ?>
        </div>
    </div>
    <div class="container" id="cards-column">
        @foreach($posts as $post)
            <div class="cardHover">
                <a href="{{route('post.show', ['post' => $post->id])}}" class="card">
                    <svg class="favorite" data-postid="{{$post->id}}" viewBox="0 0 12 12" enable-background="new 0 0 12 12" id="Слой_1" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <polygon class="polygonFavorite" fill="#A7A8A8FF" points="1.5,0 1.5,12 6,9.8181763 10.5,12 10.5,0 "></polygon>
                        </g>
                    </svg>
                    <img class="imagePostCard" src="/storage/post/images/{{$post->photo}}" alt="...">
                    <div class="infoCard">
                        <div class="headerCard">{{$post->name}}</div>
                        <div class="footerCard">
                            <div class="author" data-authorid="{{$post->user->id}}">
                                {{$post->user->login}}
                            </div>
                            <div class="functionsCard">
                                <div class="countLike">
                                    {{count($post->like)}}
                                </div>
                                <svg class="heart" data-postid="{{$post->id}}" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.064"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M26.996 12.898c-.064-2.207-1.084-4.021-2.527-5.13-1.856-1.428-4.415-1.69-6.542-.132-.702.516-1.359 1.23-1.927 2.168-.568-.938-1.224-1.652-1.927-2.167-2.127-1.559-4.685-1.297-6.542.132-1.444 1.109-2.463 2.923-2.527 5.13-.035 1.172.145 2.48.788 3.803 1.01 2.077 5.755 6.695 10.171 10.683l.035.038.002-.002.002.002.036-.038c4.415-3.987 9.159-8.605 10.17-10.683.644-1.323.822-2.632.788-3.804z">

                                        </path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <div class="hashtagsPost">
                            @foreach($post->hashtag as $hashtag)
                                <div class="tagPost">
                                    #{{$hashtag->name}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
            <?php
            if($posts->currentPage()+1 <= $posts->lastPage() || $posts->currentPage()-1 >= 1){
            ?>
            <div class="paginateBlock">
                <a class="paginateElem" href="{{$posts->url(1)}}">Первая</a>
                <?php
                if ($posts->currentPage()-1 >= 1) {
                ?>
                <a class="paginateElem" href="{{$posts->previousPageUrl()}}">Предыдущая</a>
                <?php
                }
                ?>
                <?php
                if ($posts->currentPage()-2 >= 1) {
                    ?>
                    <a class="paginateElem" href="{{$posts->url($posts->currentPage()-2)}}">{{$posts->currentPage()-2}}</a>
                    <?php
                }
                if ($posts->currentPage()-1 >= 1) {
                    ?>
                    <a class="paginateElem" href="{{$posts->url($posts->currentPage()-1)}}">{{$posts->currentPage()-1}}</a>
                    <?php
                }
                ?>
                    <a class="paginateElem  activeElem" href="#">{{ $posts->currentPage() }}</a>
                <?php
                if ($posts->currentPage()+1 <= $posts->lastPage()) {
                    ?>
                    <a class="paginateElem" href="{{$posts->url($posts->currentPage()+1)}}">{{$posts->currentPage()+1}}</a>
                    <?php
                }
                if ($posts->currentPage()+2 <= $posts->lastPage()) {
                    ?>
                    <a class="paginateElem" href="{{$posts->url($posts->currentPage()+2)}}">{{$posts->currentPage()+2}}</a>
                    <?php
                }
                ?>
                <?php
                if ($posts->currentPage()+1 <= $posts->lastPage()) {
                    ?>
                    <a class="paginateElem" href="{{$posts->nextPageUrl()}}">Следующая</a>
                    <?php
                }
                ?>
                    <a class="paginateElem" href="{{$posts->url($posts->lastPage())}}">Последняя</a>
            </div
            <?php
            }
            ?>
    </div>
    <script>
        let likes = document.querySelectorAll('.heart')
        likes.forEach((item)=>{
            const token = '{{ csrf_token() }}';
            let postId = item.dataset.postid
            fetch(`/like/check/${postId}`, {method: 'POST', mode: "cors", headers: {
                    "X-CSRF-Token": token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
                .then((response) => {
                    return response.json();
                })
                .then((checkLike) => {
                    console.log(checkLike.checkLike);
                    if (checkLike.checkLike){
                        item.dataset.statuslike = 'created'
                    }
                    else{
                        item.dataset.statuslike = 'notСreated'
                    }
                })
                .catch((error) => {
                    console.log(error);
                });

            item.addEventListener('click', (event)=>{
                event.preventDefault();
                const token = '{{ csrf_token() }}';
                fetch(`/like/store/${postId}`, {method: 'POST', mode: "cors",headers: {
                        "X-CSRF-Token": token
                    },})
                    .then((response) => {
                        return response.json();

                    })
                    .then((statusLike) => {
                        console.log(statusLike);
                        if (statusLike.statusLike == 'create'){
                            item.dataset.statuslike = 'created'
                            $parrentItem = item.parentNode
                            $countLikes = $parrentItem.querySelector('.countLike')
                            $asd = Number($countLikes.textContent)
                            $countLikes.textContent = $asd+1;
                        }
                        else{
                            item.dataset.statuslike = 'notСreated'
                            $parrentItem = item.parentNode
                            $countLikes = $parrentItem.querySelector('.countLike')
                            $asd = Number($countLikes.textContent)
                            $countLikes.textContent = $asd-1;
                        }

                    })
                    .catch((error) => {
                        console.log(error);
                    });
            })
        })

        let favorites = document.querySelectorAll('.favorite')
        favorites.forEach((item)=>{
            const token = '{{ csrf_token() }}';
            let postId = item.dataset.postid
            fetch(`/favorite/check/${postId}`, {method: 'POST', mode: "cors", headers: {
                    "X-CSRF-Token": token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
                .then((response) => {
                    return response.json();
                })
                .then((checkFavorite) => {
                    console.log(checkFavorite.checkFavorite);
                    if (checkFavorite.checkFavorite){
                        item.dataset.statusfavorite = 'created'
                    }
                    else{
                        item.dataset.statusfavorite = 'notСreated'
                    }
                })
                .catch((error) => {
                    console.log(error);
                });

            item.addEventListener('click', (event)=>{
                event.preventDefault();
                const token = '{{ csrf_token() }}';
                fetch(`/favorite/store/${postId}`, {method: 'POST', mode: "cors",headers: {
                        "X-CSRF-Token": token
                    },})
                    .then((response) => {
                        return response.json();

                    })
                    .then((statusFavorite) => {
                        console.log(statusFavorite);
                        if (statusFavorite.statusFavorite == 'create'){
                            item.dataset.statusfavorite = 'created'
                        }
                        else{
                            item.dataset.statusfavorite = 'notСreated'
                        }

                    })
                    .catch((error) => {
                        console.log(error);
                    });
            })
        })
        let authors = document.querySelectorAll('.author')
        authors.forEach(item=>{
            item.addEventListener('click', (event)=>{
                event.preventDefault();
                let authorid = item.dataset.authorid
                window.location.href = `/profile/show/${authorid}`;
            })
        })
    </script>
@endsection
