@extends('index')

@section('title', 'Страница показа поста')

@section('content')
    @if(session()->has('create'))
        <div class="message" id="green">Вы успешно добавили комментарий</div>
    @endif
    @if(session()->has('update'))
        <div class="message" id="yellow">Вы успешно изменили комментарий</div>
    @endif
    @if(session()->has('destroy'))
        <div class="message" id="red">Вы успешно удалили комментарий</div>
    @endif
    <div class="imagePostShow"></div>
    <div class="container postShowContainer" >
        <div class="headerPost">
            <img class="headerImagePost" src="/storage/post/images/{{$post->photo}}" width="100%" height="100%" alt="...">
            <div class="headerInfo">
                <div class="headerDetails">
                    <div class="namePost">{{$post->name}}</div>
                    <div class="categoryPost">Категория: {{$post->category->name}}</div>
                    <div class="dateCreatePost">{{ $post->created_at->format('d m Y') }}</div>
                </div>
            </div>
        </div>
        <div class="section">
            <div class="postItemsIndex">
                @foreach($postItems as $postItem)
                    <div class="postItem">
                        <div class="headerPostItem" name="header">{{$postItem->header}}</div>
                        <img class="imagePostItem" name="photo" src="/storage/postItems/images/{{$postItem->photo}}" alt="...">
                        <div class="infoPostItem" name="information">{{$postItem->information}}</div>
                    </div>
                @endforeach
                @auth()
                    <div class="commentsPost">
                        <div class="commentsName">Отправить комментарий:</div>
                        <form class="formCommentStore" action="{{route('post.comment.store', ['post' => $post->id])}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <textarea type="text" name="text" class="textCommentStore @error('text') is-invalid @enderror" aria-describedby="textHelp"></textarea>
                            @error('text')<div id="textHelp" class="form-error">{{$message}}</div>@enderror
                            <div class="commentImage">
                                <label for="exampleInputPhoto" class="labelPhotoComment"></label>
                                <input accept="image/*" type="file" name="photo" data-method="create" class="photoCommentStore @error('photo') is-invalid @enderror" id="exampleInputPhoto" aria-describedby="photoHelp">
                            </div>
                            @error('photo')<div id="photoHelp" class="form-error">{{$message}}</div>@enderror
                            <button class="commentStoreButton" type="submit">ОТПРАВИТЬ</button>
                        </form>
                    </div>
                @endauth
                    @if(!empty($comments->all()))
                    <div class="comments">
                        <div class="indexComments">
                            <div class="indexCommentsName">
                                <div class="commentsName indexName">Комментарии: </div>
                                <form action="{{route('post.comment.filtration', ['post' => $post->id])}}" method="GET">
                                    <select name="filtration" class="filtrationComments" onchange="this.form.submit()">
                                        @if(request()->filtration)
                                            @if(request()->filtration == 'all')
                                                <option value="all" selected>По умолчанию</option>
                                            @else
                                                <option value="all">По умолчанию</option>
                                            @endif
                                            @if(request()->filtration == 'dataDescending')
                                                <option value="dataDescending" selected>По убываннию, дата</option>
                                                @else
                                                <option value="dataDescending">По убываннию, дата</option>
                                            @endif
                                            @if(request()->filtration == 'dataIncrease')
                                                <option value="dataIncrease" selected>По возрастанию, дата</option>
                                            @else
                                                <option value="dataIncrease">По возрастанию, дата</option>
                                            @endif
                                            @if(request()->filtration == 'likeDescending')
                                                <option value="likeDescending" selected>По убыванию, лайки</option>
                                            @else
                                                <option value="likeDescending">По убыванию, лайки</option>
                                            @endif
                                            @if(request()->filtration == 'likeIncrease')
                                                <option value="likeIncrease" selected>По возрастанию, лайки</option>
                                            @else
                                                <option value="likeIncrease">По возрастанию, лайки</option>
                                            @endif
                                        @else
                                            <option value="all">По умолчанию</option>
                                            <option value="dataDescending">По убываннию, дата</option>
                                            <option value="dataIncrease">По возрастанию, дата</option>
                                            <option value="likeDescending">По убыванию, лайки</option>
                                            <option value="likeIncrease">По возрастанию, лайки</option>
                                        @endif
                                    </select>
                                </form>
                            </div>
                            @foreach($comments as $comment)
                                <div class="comment">
                                    <div class="userComment">
                                        <a href="{{route('profile.show', ['user' => $comment->user_id])}}">
                                            <img class="userPhotoComment" src="/storage/{{$comment->user->photo}}">
                                        </a>
                                        <div class="commentBody">
                                            <a href="{{route('profile.show', ['user' => $comment->user_id])}}" class="loginUserComment">
                                                {{$comment->user->login}}
                                            </a>
                                            @if($comment->text)
                                                <div class="textCommentIndex">
                                                    <div class="textComment">{{$comment->text}}</div>
                                                </div>
                                            @endif
                                            @if($comment->photo)
                                                <img class="photoComment" src="/storage/comment/images/{{$comment->photo}}">
                                            @endif
                                            <div class="functionComment">
                                                <div class="dataComment">
                                                    <div class="dateComment">
                                                        <?php
                                                        $firstDate = $comment->created_at;
                                                        $currentDateTime = \Carbon\Carbon::now();
                                                        $differenceInMinutes = $currentDateTime->diffInMinutes($firstDate);
                                                        if ($differenceInMinutes<60){
                                                            if ($differenceInMinutes == 1 || ($differenceInMinutes > 20 && $differenceInMinutes % 10 == 1)) {
                                                                echo $differenceInMinutes . ' минуту назад';
                                                            } elseif (
                                                                $differenceInMinutes >= 2 && $differenceInMinutes <= 4 ||
                                                                ($differenceInMinutes > 20 && ($differenceInMinutes % 10 >= 2 && $differenceInMinutes % 10 <= 4))
                                                            ) {
                                                                echo $differenceInMinutes . ' минуты назад';
                                                            } else {
                                                                echo $differenceInMinutes . ' минут назад';
                                                            }
                                                        }
                                                        else if ($differenceInMinutes>=60 && $differenceInMinutes<1440){
                                                            $differenceInHours = $currentDateTime->diffInHours($firstDate);
                                                            if ($differenceInHours < 1) {
                                                                echo 'меньше часа назад';
                                                            } else if ($differenceInHours == 1) {
                                                                echo '1 час назад';
                                                            } elseif ($differenceInHours % 10 >= 2 && $differenceInHours % 10 <= 4 && ($differenceInHours < 12 || $differenceInHours > 14)) {
                                                                echo $differenceInHours . ' часа назад';
                                                            } else {
                                                                echo $differenceInHours . ' часов назад';
                                                            }
                                                        }
                                                        else if ($differenceInMinutes>=1440 && $differenceInMinutes<43200){
                                                            $differenceInDays = $currentDateTime->diffInDays($firstDate);
                                                            if ($differenceInDays < 1) {
                                                                echo 'меньше часа назад';
                                                            }
                                                            else if ($differenceInDays == 1) {
                                                                echo '1 день назад';
                                                            } elseif ($differenceInDays % 10 >= 2 && $differenceInDays % 10 <= 4 && ($differenceInDays < 12 || $differenceInDays > 14)) {
                                                                echo $differenceInDays . ' дня назад';
                                                            } else {
                                                                echo $differenceInDays . ' дней назад';
                                                            }
                                                        }
                                                        else{
                                                            echo 'больше месяца назад';
                                                        }
                                                        ?>
                                                    </div>
                                                    @if($comment->user_id == \Illuminate\Support\Facades\Auth::id())
                                                        <div class="updateComment" data-commentid = "{{$comment->id}}" data-text = "{{$comment->text}}" data-photo = "{{$comment->photo}}">
                                                            Редактировать
                                                        </div>
                                                        @if($comment->user_id == \Illuminate\Support\Facades\Auth::id() || \Illuminate\Support\Facades\Auth::user()->role->name == 'Администратор')
                                                            <div class="deleteComment" data-commentid = "{{$comment->id}}" data-userlogin = "{{$comment->user->login}}">
                                                                Удалить
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="likesComment">
                                                    <div class="countLikeComment">
                                                        {{count($comment->like)}}
                                                    </div>
                                                    <svg class="heartComment" data-commentid="{{$comment->id}}" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.064"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <path d="M26.996 12.898c-.064-2.207-1.084-4.021-2.527-5.13-1.856-1.428-4.415-1.69-6.542-.132-.702.516-1.359 1.23-1.927 2.168-.568-.938-1.224-1.652-1.927-2.167-2.127-1.559-4.685-1.297-6.542.132-1.444 1.109-2.463 2.923-2.527 5.13-.035 1.172.145 2.48.788 3.803 1.01 2.077 5.755 6.695 10.171 10.683l.035.038.002-.002.002.002.036-.038c4.415-3.987 9.159-8.605 10.17-10.683.644-1.323.822-2.632.788-3.804z">

                                                            </path>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <?php
                            if($comments->currentPage()+1 <= $comments->lastPage() || $comments->currentPage()-1 >= 1){
                            ?>
                            <div class="paginateBlock commentsPaginate">
                                <a class="paginateElem" href="{{$comments->url(1)}}">Первая</a>
                                <?php
                                if ($comments->currentPage()-1 >= 1) {
                                ?>
                                <a class="paginateElem" href="{{$comments->previousPageUrl()}}">Предыдущая</a>
                                <?php
                                }
                                ?>
                                <?php
                                if ($comments->currentPage()-2 >= 1) {
                                ?>
                                <a class="paginateElem" href="{{$comments->url($comments->currentPage()-2)}}">{{$comments->currentPage()-2}}</a>
                                <?php
                                }
                                if ($comments->currentPage()-1 >= 1) {
                                ?>
                                <a class="paginateElem" href="{{$comments->url($comments->currentPage()-1)}}">{{$comments->currentPage()-1}}</a>
                                <?php
                                }
                                ?>
                                <a class="paginateElem  activeElem" href="#">{{ $comments->currentPage() }}</a>
                                <?php
                                if ($comments->currentPage()+1 <= $comments->lastPage()) {
                                ?>
                                <a class="paginateElem" href="{{$comments->url($comments->currentPage()+1)}}">{{$comments->currentPage()+1}}</a>
                                <?php
                                }
                                if ($comments->currentPage()+2 <= $comments->lastPage()) {
                                ?>
                                <a class="paginateElem" href="{{$comments->url($comments->currentPage()+2)}}">{{$comments->currentPage()+2}}</a>
                                <?php
                                }
                                ?>
                                <?php
                                if ($comments->currentPage()+1 <= $comments->lastPage()) {
                                ?>
                                <a class="paginateElem" href="{{$comments->nextPageUrl()}}">Следующая</a>
                                <?php
                                }
                                ?>
                                <a class="paginateElem" href="{{$comments->url($comments->lastPage())}}">Последняя</a>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    @endif
            </div>
            <div class="detailsPost">
                <div class="detailPost">
                    <a href="{{route('profile.show', ['user' => $post->user->id])}}" class="authorPost">
                        <img class="imageProfileAuthor" src="/storage/{{$post->user->photo}}">
                        <div class="loginProfileAuthor">{{$post->user->login}}</div>
                    </a>
                    <div class="tagsPost">
                        <div class="headerTags">Теги:</div>
                        @foreach($post->hashtag as $tag)
                            <div class="tagPostShow">#{{$tag->name}}</div>
                        @endforeach
                    </div>
                    <div class="functionsPostShow">
                        <div class="likes">
                            <svg class="heart" data-postid="{{$post->id}}" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.064"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M26.996 12.898c-.064-2.207-1.084-4.021-2.527-5.13-1.856-1.428-4.415-1.69-6.542-.132-.702.516-1.359 1.23-1.927 2.168-.568-.938-1.224-1.652-1.927-2.167-2.127-1.559-4.685-1.297-6.542.132-1.444 1.109-2.463 2.923-2.527 5.13-.035 1.172.145 2.48.788 3.803 1.01 2.077 5.755 6.695 10.171 10.683l.035.038.002-.002.002.002.036-.038c4.415-3.987 9.159-8.605 10.17-10.683.644-1.323.822-2.632.788-3.804z">

                                    </path>
                                </g>
                            </svg>
                            <div class="countLike countLikeIndex">
                                {{count($post->like)}}
                            </div>
                        </div>
                        <div class="favorites">
                            <svg class="favorite favoriteShow" data-postid="{{$post->id}}" viewBox="0 0 12 12" enable-background="new 0 0 12 12" id="Слой_1" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <polygon class="polygonFavorite" fill="#A7A8A8FF" points="1.5,0 1.5,12 6,9.8181763 10.5,12 10.5,0 "></polygon>
                                </g>
                            </svg>
                            <div class="countFavorite">
                                {{count($post->favorite)}}
                            </div>
                        </div>
                        <div class="commentsShow">
                            <svg class="commentShow" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#A7A8A8FF"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#A7A8A8FF" d="M736 504a56 56 0 1 1 0-112 56 56 0 0 1 0 112zm-224 0a56 56 0 1 1 0-112 56 56 0 0 1 0 112zm-224 0a56 56 0 1 1 0-112 56 56 0 0 1 0 112zM128 128v640h192v160l224-160h352V128H128z"></path></g></svg>
                            <div class="countComments">
                                {{count($post->comment)}}
                            </div>
                        </div>
                    </div>
                    @if($post->fortify)
                        <?php dd($post) ?>
                        <a class="downloadFortifyPostShow" href="/storage/post/fortify/{{$post->fortify}}" download>Скачать файл Fortify</a>
                    @endif
                    @auth
                        @if($post->user_id == \Illuminate\Support\Facades\Auth::id() || \Illuminate\Support\Facades\Auth::user()->role->name == 'Администратор')
                            <button class="btn-deletePost" data-postid = "{{$post->id}}" data-postname = "{{$post->name}}">Удалить</button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
    <script>
        document.body.style.overflow = 'hidden';

        window.addEventListener("DOMContentLoaded", function () {
            window.scrollTo(0, 0);
        });

        window.onload = function() {
            document.body.style.overflow = 'auto';

            let like = document.querySelector('.heart')
            const token = '{{ csrf_token() }}';
            let postId = like.dataset.postid
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
                        like.dataset.statuslike = 'created'
                    }
                    else{
                        like.dataset.statuslike = 'notСreated'
                    }
                })
                .catch((error) => {
                    console.log(error);
                });

            like.addEventListener('click', (event)=>{
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
                            like.dataset.statuslike = 'created'
                            $parrentItem = like.parentNode
                            $countLikes = $parrentItem.querySelector('.countLike')
                            $asd = Number($countLikes.textContent)
                            $countLikes.textContent = $asd+1;
                        }
                        else{
                            like.dataset.statuslike = 'notСreated'
                            $parrentItem = like.parentNode
                            $countLikes = $parrentItem.querySelector('.countLike')
                            $asd = Number($countLikes.textContent)
                            $countLikes.textContent = $asd-1;
                        }

                    })
                    .catch((error) => {
                        console.log(error);
                    });
            })

            let favorite = document.querySelector('.favorite')
            const tokenFavorite = '{{ csrf_token() }}';
            let postIdFavorite = favorite.dataset.postid
            fetch(`/favorite/check/${postIdFavorite}`, {method: 'POST', mode: "cors", headers: {
                    "X-CSRF-Token": tokenFavorite,
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
                        favorite.dataset.statusfavorite = 'created'
                    }
                    else{
                        favorite.dataset.statusfavorite = 'notСreated'
                    }
                })
                .catch((error) => {
                    console.log(error);
                });

            favorite.addEventListener('click', (event)=>{
                event.preventDefault();
                const tokenFavoriteCSRF = '{{ csrf_token() }}';
                fetch(`/favorite/store/${postIdFavorite}`, {method: 'POST', mode: "cors",headers: {
                        "X-CSRF-Token": tokenFavoriteCSRF
                    },})
                    .then((response) => {
                        return response.json();

                    })
                    .then((statusFavorite) => {
                        console.log(statusFavorite.statusFavorite);
                        if (statusFavorite.statusFavorite == 'create'){
                            favorite.dataset.statusfavorite = 'created'
                            $parrentItem = favorite.parentNode
                            $countFavorite = $parrentItem.querySelector('.countFavorite')
                            console.log($countFavorite)
                            $countFavoriteNumber = Number($countFavorite.textContent)
                            $countFavorite.textContent = $countFavoriteNumber+1;
                        }
                        else{
                            favorite.dataset.statusfavorite = 'notСreated'
                            $parrentItem = favorite.parentNode
                            $countFavorite = $parrentItem.querySelector('.countFavorite')
                            $countFavoriteNumber = Number($countFavorite.textContent)
                            $countFavorite.textContent = $countFavoriteNumber-1;
                        }

                    })
                    .catch((error) => {
                        console.log(error);
                    });
            })

            let likes = document.querySelectorAll('.heartComment')
            likes.forEach((item)=>{
                const token = '{{ csrf_token() }}';
                let commentId = item.dataset.commentid
                fetch(`/post/comment/like/check/${commentId}`, {method: 'POST', mode: "cors", headers: {
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
                    fetch(`/post/comment/like/store/${commentId}`, {method: 'POST', mode: "cors",headers: {
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
                                $countLikes = $parrentItem.querySelector('.countLikeComment')
                                $asd = Number($countLikes.textContent)
                                $countLikes.textContent = $asd+1;
                            }
                            else{
                                item.dataset.statuslike = 'notСreated'
                                $parrentItem = item.parentNode
                                $countLikes = $parrentItem.querySelector('.countLikeComment')
                                $asd = Number($countLikes.textContent)
                                $countLikes.textContent = $asd-1;
                            }

                        })
                        .catch((error) => {
                            console.log(error);
                        });
                })
            })

            //Вывод изображений из input
            if (window.FileList && window.File && window.FileReader) {
                let input = document.querySelector('.photoCommentStore[data-method = "create"]')
                input.addEventListener('change', event => {
                    let output = input.parentNode
                    output.style.url = ''
                    const file = event.target.files[0];
                    if (!file.type) {
                        return;
                    }
                    if (!file.type.match('image.*')) {
                        return;
                    }
                    const reader = new FileReader();
                    reader.addEventListener('load', event => {
                        let iconImage = document.querySelector('.labelPhotoComment')
                        output.style = `background-image: url("${event.target.result}"); width: 150px; height: 84.38px`;
                        iconImage.style = `background: none`;
                        output.insertAdjacentHTML('afterend',`
                            <div class="iconDeletePhoto"></div>
                        `)
                        let parrentBlock = output.parentNode
                        let icon = document.querySelector('.iconDeletePhoto')
                        icon.addEventListener('click', ()=>{
                            output.style = `background: none; width: 25px; height: 25px`;
                            iconImage.style = `background-image: url("/assets/image/imageIcon.png");`;
                            parrentBlock.removeChild(icon)
                            input.value = ""; // Очищаем значение поля, чтобы сработало событие `change`
                            input.type = "text"; // Переключаем тип поля на текстовый
                            input.type = "file"; // Переключаем обратно на файловое поле
                        })
                    });
                    reader.readAsDataURL(file);
                })
            }
            window.onbeforeunload = function () {
                window.scrollTo(0, 0);
            }
            // Проверка на заполненность полей перед отпрвкой, должно быть хотя-бы 1 поле заполнено
            let form = document.querySelector('.formCommentStore')
            form.addEventListener('submit', event => {
                let isFormValid = false;

                // Перебираем все поля формы и проверяем, заполнены ли они
                form.querySelectorAll('.textCommentStore, .photoCommentStore').forEach(field => {
                    if (field.value) {
                        console.log(field.value)
                        isFormValid = true;
                    }
                });
                // Если все поля не заполнены, отменяем отправку формы
                if (!isFormValid) {
                    event.preventDefault();
                }
            });
            var fixedWrapper = document.querySelector('.detailsPost');
            var fixedBlock = document.querySelector('.detailPost');
            var initialTop = fixedWrapper.getBoundingClientRect().top + fixedWrapper.clientHeight - fixedBlock.clientHeight - 20;
            var initialLeft = fixedBlock.getBoundingClientRect().left + window.pageXOffset;
            var isFixed = false;

            window.addEventListener('scroll', function() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > initialTop && !isFixed) {
                    isFixed = true;
                    fixedBlock.style.position = 'fixed';
                    fixedBlock.style.top = '20px';
                    fixedBlock.style.left = initialLeft + 'px';
                }

                if (scrollTop < initialTop && isFixed) {
                    isFixed = false;
                    fixedBlock.style.position = 'static';
                    fixedBlock.style.top = 'auto';
                    fixedBlock.style.left = 'auto';
                }
            });

            let updates = document.querySelectorAll('.updateComment')
            updates.forEach(update =>{
                update.addEventListener('click', ()=>{
                    let updateParrent = update.parentNode.parentNode
                    let blockForm = updateParrent.parentNode.querySelector('.formCommentStore')
                    if (blockForm){
                        updateParrent.parentNode.removeChild(blockForm);
                    }
                    else{
                        let blockFormUpdate = document.querySelector('.formCommentStore[data-method = "update"]')
                        if (blockFormUpdate){
                            blockFormUpdate.parentNode.removeChild(blockFormUpdate);
                        }
                        let idComment = update.dataset.commentid
                        let text = update.dataset.text
                        let photo = update.dataset.photo
                        if (photo) {
                            updateParrent.insertAdjacentHTML('afterend', `
                        <form class="formCommentStore" action="/post/comment/update/${idComment}/{{$post->id}}" data-method="update" method="POST" enctype="multipart/form-data">
                            @csrf
                            <textarea type="text" name="text" class="textCommentStore @error('text') is-invalid @enderror" aria-describedby="textHelp"></textarea>
                            @error('text')<div id="textHelp" class="form-error">{{$message}}</div>@enderror
                            <div class="commentImage">
                                <input type="hidden" class="oldImageUpdateComment" name="current_image" value="${photo}">
                                <label for="exampleInputPhotoUpdate" class="labelPhotoComment"></label>
                                <input accept="image/*" data-method="update" type="file" name="photo" class="photoCommentStore @error('photo') is-invalid @enderror" id="exampleInputPhotoUpdate" aria-describedby="photoHelp">
                            </div>
                            @error('photo')<div id="photoHelp" class="form-error">{{$message}}</div>@enderror
                            <button class="commentStoreButton" type="submit">ОТПРАВИТЬ</button>
                        </form>
                        `);
                            let textformUpdate = updateParrent.parentNode.querySelector('.textCommentStore')
                            let photoformUpdate = updateParrent.parentNode.querySelector('.photoCommentStore[data-method = "update"]')
                            let oldImageUpdateComment = updateParrent.parentNode.querySelector('.oldImageUpdateComment')
                            textformUpdate.value = text;
                            if (oldImageUpdateComment) {
                                let iconImage = updateParrent.parentNode.querySelector('.labelPhotoComment')
                                iconImage.parentNode.style = `background-image: url("/storage/comment/images/${photo}"); width: 150px; height: 84.38px`;
                                iconImage.style = `background: none`;
                                iconImage.parentNode.insertAdjacentHTML('afterend', `
                            <div class="iconDeletePhoto updateDeleteIconPhoto"></div>
                        `)
                                let parrentBlock = iconImage.parentNode.parentNode
                                let icon = parrentBlock.querySelector('.iconDeletePhoto')
                                icon.addEventListener('click', () => {
                                    iconImage.parentNode.style = `background: none; width: 25px; height: 25px`;
                                    iconImage.style = `background-image: url("/assets/image/imageIcon.png");`;
                                    parrentBlock.removeChild(icon)
                                    console.log(parrentBlock)
                                    oldImageUpdateComment.parentNode.removeChild(oldImageUpdateComment);

                                    photoformUpdate.addEventListener('change', event => {
                                        let output = photoformUpdate.parentNode
                                        output.style.url = ''
                                        const file = event.target.files[0];
                                        if (!file.type) {
                                            return;
                                        }
                                        if (!file.type.match('image.*')) {
                                            return;
                                        }
                                        const reader = new FileReader();
                                        reader.addEventListener('load', event => {
                                            let iconImage = output.querySelector('.labelPhotoComment')
                                            output.style = `background-image: url("${event.target.result}"); width: 150px; height: 84.38px`;
                                            iconImage.style = `background: none`;
                                            output.insertAdjacentHTML('afterend', `
                            <div class="iconDeletePhoto updateDeleteIconPhoto"></div>
                        `)
                                            let parrentBlock = output.parentNode
                                            let icon = parrentBlock.querySelector('.iconDeletePhoto')
                                            icon.addEventListener('click', () => {
                                                output.style = `background: none; width: 25px; height: 25px`;
                                                iconImage.style = `background-image: url("/assets/image/imageIcon.png");`;
                                                parrentBlock.removeChild(icon)
                                                photoformUpdate.value = ""; // Очищаем значение поля, чтобы сработало событие `change`
                                                photoformUpdate.type = "text"; // Переключаем тип поля на текстовый
                                                photoformUpdate.type = "file"; // Переключаем обратно на файловое поле
                                            })
                                        });
                                        reader.readAsDataURL(file);
                                    })
                                })
                            }
                        }
                        else {
                            updateParrent.insertAdjacentHTML('afterend', `
                        <form class="formCommentStore" action="/post/comment/update/${idComment}/{{$post->id}}" data-method="update" method="POST" enctype="multipart/form-data">
                            @csrf
                            <textarea type="text" name="text" class="textCommentStore @error('text') is-invalid @enderror" aria-describedby="textHelp"></textarea>
                            @error('text')<div id="textHelp" class="form-error">{{$message}}</div>@enderror
                            <div class="commentImage">
                                <label for="exampleInputPhotoUpdate" class="labelPhotoComment"></label>
                                <input accept="image/*" data-method="update" type="file" name="photo" class="photoCommentStore @error('photo') is-invalid @enderror" id="exampleInputPhotoUpdate" aria-describedby="photoHelp">
                            </div>
                            @error('photo')<div id="photoHelp" class="form-error">{{$message}}</div>@enderror
                            <button class="commentStoreButton" type="submit">ОТПРАВИТЬ</button>
                        </form>
                        `);
                            let textformUpdate = updateParrent.parentNode.querySelector('.textCommentStore')
                            let photoformUpdate = updateParrent.parentNode.querySelector('.photoCommentStore')
                            textformUpdate.value = text;
                            photoformUpdate.addEventListener('change', event => {
                                let output = photoformUpdate.parentNode
                                output.style.url = ''
                                const file = event.target.files[0];
                                if (!file.type) {
                                    return;
                                }
                                if (!file.type.match('image.*')) {
                                    return;
                                }
                                const reader = new FileReader();
                                reader.addEventListener('load', event => {
                                    let iconImage = output.querySelector('.labelPhotoComment')
                                    output.style = `background-image: url("${event.target.result}"); width: 150px; height: 84.38px`;
                                    iconImage.style = `background: none`;
                                    output.insertAdjacentHTML('afterend', `
                            <div class="iconDeletePhoto updateDeleteIconPhoto"></div>
                        `)
                                    let parrentBlock = output.parentNode
                                    let icon = parrentBlock.querySelector('.iconDeletePhoto')
                                    icon.addEventListener('click', () => {
                                        output.style = `background: none; width: 25px; height: 25px`;
                                        iconImage.style = `background-image: url("/assets/image/imageIcon.png");`;
                                        parrentBlock.removeChild(icon)
                                        photoformUpdate.value = ""; // Очищаем значение поля, чтобы сработало событие `change`
                                        photoformUpdate.type = "text"; // Переключаем тип поля на текстовый
                                        photoformUpdate.type = "file"; // Переключаем обратно на файловое поле
                                    })
                                });
                                reader.readAsDataURL(file);
                            })
                        }
                        // Проверка на заполненность полей перед отпрвкой, должно быть хотя-бы 1 поле заполнено
                        let formUpdate = updateParrent.parentNode.querySelector('.formCommentStore')
                        formUpdate.addEventListener('submit', event => {
                            let isFormValid = false;

                            // Перебираем все поля формы и проверяем, заполнены ли они
                            formUpdate.querySelectorAll('.textCommentStore, .photoCommentStore, .oldImageUpdateComment').forEach(field => {
                                if (field.value) {
                                    console.log(field.value)
                                    isFormValid = true;
                                }
                            });
                            // Если все поля не заполнены, отменяем отправку формы
                            if (!isFormValid) {
                                event.preventDefault();
                            }
                        });
                    }
                })
            })
            let deletes = document.querySelectorAll('.deleteComment')
            if (deletes){
                deletes.forEach(item=>{
                    let commentId = item.dataset.commentid
                    let userLogin = item.dataset.userlogin
                    item.addEventListener('click', ()=>{
                        document.body.insertAdjacentHTML('afterbegin',`
                    <div class="modal" data-idcomment="${commentId}">
                        <div class="modalBlock">
                            <div class="headerModal">Удаление комментария</div>
                            <div class="bodyModal">Вы точно хотите удалить комментарий пользователя: ${userLogin}?</div>
                            <div class="footerModal">
                                <button class="btn-modalClose">Отмена</button>
                                <form action="/post/comment/destroy/${commentId}/{{$post->id}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn-modalAccept">Подтвердить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                `)

                        let closeModalButton = document.querySelector('.btn-modalClose')
                        let modal = document.querySelector('.modal')
                        closeModalButton.addEventListener('click', item=>{
                            document.body.removeChild(modal)
                        })
                    })
                })
            }

            let deletePost = document.querySelector('.btn-deletePost')
            if (deletePost){
                    let postId = deletePost.dataset.postid
                    let postName = deletePost.dataset.postname
                deletePost.addEventListener('click', ()=>{
                        document.body.insertAdjacentHTML('afterbegin',`
                    <div class="modal" data-idpost="${postId}">
                        <div class="modalBlock">
                            <div class="headerModal">Удаление поста</div>
                            <div class="bodyModal">Вы точно хотите удалить пост: ${postName}?</div>
                            <div class="footerModal">
                                <button class="btn-modalClose">Отмена</button>
                                <form action="/post/destroy/${postId}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn-modalAccept">Подтвердить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                `)

                    let closeModalButton = document.querySelector('.btn-modalClose')
                    let modal = document.querySelector('.modal')
                    closeModalButton.addEventListener('click', item=>{
                        document.body.removeChild(modal)
                    })
                })
            }
        }
    </script>
@endsection

