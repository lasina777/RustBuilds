@extends('index')

@section('title', 'Страница профиля')

@section('content')
    @if(session()->has('banned'))
        <div class="message" id="red">Вы успешно заблокировали пользователя</div>
    @endif
    @if(session()->has('Unbanned'))
        <div class="message" id="green">Вы успешно разблокировали пользователя</div>
    @endif
    <div class="imageProfile" ></div>
    <div class="container">
        <div class="infoUserBlock">
            <img class="photoUser" width="200px" height="200px"  src="{{'/storage/' . $user->photo}}" alt="...">
            <div class="infoUser">
                <div class="loginUser">
                    {{$user->login}}
                </div>
                <div class="itemsInfo">
                    @if($user->name != '')
                        <div class="itemInfo">Имя: {{$user->name}}</div>
                    @endif
                    @if($user->surname != '')
                        <div class="itemInfo">Фамилия: {{$user->surname}}</div>
                    @endif
                    @if($user->patronymic != '')
                        <div class="itemInfo">Отчество: {{$user->patronymic}}</div>
                    @endif
                    @if($user->link_steam != '')
                        <div class="itemInfo">Стим: {{$user->link_steam}}</div>
                    @endif
                    @if($user->email != '')
                        <div class="itemInfo">Почта: {{$user->email}}</div>
                    @endif
                </div>
                <div class="achievementsUser">
                    <div class="achievementUser">
                        <div class="countAchievementUser">
                            {{count($posts)}}
                        </div>
                        <div class="nameAchievementUser">
                            Посты
                        </div>
                    </div>
                    <div class="achievementUser">
                        <div class="countAchievementUser">
                            <?php
                            $count = 0;
                            foreach ($user->post as $item){
                                $countLikePost = 0;
                                foreach($item->like as $like){
                                    if (!empty($like->post->status)){
                                        $countLikePost+=1;
                                    }
                                }
                                $count += (int)$countLikePost;
                            }
                            echo $count
                            ?>
                        </div>
                        <div class="nameAchievementUser">
                            Лайки
                        </div>
                    </div>
                    <div class="achievementUser">
                        <div class="countAchievementUser">
                            <?php
                            $count = 0;
                            foreach ($user->post as $item){
                                $countLikePost = 0;
                                foreach($item->favorite as $favorite){
                                    if (empty($favorite->post->status)){
                                        $countLikePost+=1;
                                    }
                                }
                                $count += (int)$countLikePost;
                            }
                            echo $count
                            ?>
                        </div>
                        <div class="nameAchievementUser">
                            Избранное
                        </div>
                    </div>
                    <div class="achievementUser">
                        <div class="countAchievementUser">
                            {{count($user->like)}}
                        </div>
                        <div class="nameAchievementUser">
                            Поставил(а) лайки
                        </div>
                    </div>
                    <div class="achievementUser">
                        <div class="countAchievementUser">
                            {{count($user->favorite)}}
                        </div>
                        <div class="nameAchievementUser">
                            Добавил(а) в избранное
                        </div>
                    </div>
                </div>
            </div>
            <div class="dateCreate">Регистрация: {{$user->created_at->format('d m Y')}}</div>

        @if($user->last_online_at > now()->subMinutes(5))
                <div class="statusUser"><div class="online"></div>online</div>
            @else
                <div class="statusUser"><div class="offline"></div>offline</div>
            @endif
            @auth
                @if(\Illuminate\Support\Facades\Auth::id() == $user->id)
                    <a class="buttonUpdateUser" href="{{route('profile.updateAccount', ['user' => \Illuminate\Support\Facades\Auth::id()])}}"><img class="updateUser" src="/assets/image/updateUser.png" alt="..."></a>
                @endauth
            @endauth
            @auth
                @if(\Illuminate\Support\Facades\Auth::user()->role->name == 'Администратор' && $user->role->name != 'Администратор')
                    @if($bannedBool == false)
                        <button class="buttonBannedUSer" data-userid="{{$user->id}}" data-userlogin="{{$user->login}}">Заблокировать</button>
                    @else
                        <button class="buttonUnbannedUSer" data-userid="{{$user->id}}" data-userlogin="{{$user->login}}">Разблокировать</button>
                    @endif
                @endauth
            @endauth
        </div>
    </div>
    <div class="container">
        <div class="postsUserBlock">
            <ul class="functionsPostIndex">
                <li class="liNameFunction" data-typepost="posts">
                    <div class="nameFunction">
                        Посты
                    </div>
                </li>
                <li class="liNameFunction" data-typepost="likes">
                    <div class="nameFunction">
                        Лайки
                    </div>
                </li>
                <li class="liNameFunction" data-typepost="favorites">
                    <div class="nameFunction">
                        Избранное
                    </div>
                </li>
            </ul>
            <div class="postIndex">

            </div>
        </div>
    </div>
<script>
    let BannedButton = document.querySelector('.buttonBannedUSer')
    if (BannedButton){
        let userId = BannedButton.dataset.userid
        let userLogin = BannedButton.dataset.userlogin
        BannedButton.addEventListener('click', ()=>{
            document.body.insertAdjacentHTML('afterbegin',`
                    <div class="modal" data-iduser="${userId}">
                        <div class="modalBlockBannedUser">
                            <div class="headerModalBannedUser">Блокировка пользователя ${userLogin}</div>
                            <div class="bodyModalCreateBannedUser">
                                <form action="/admin/banneds/store/${userId}" class="addBannedUser" method="POST">
                                    @csrf
                                    <select required name="period" class="modalSelect">
                                        <option value="1">1 день</option>
                                        <option value="3">3 дня</option>
                                        <option value="7">7 дней</option>
                                        <option value="30">30 дней</option>
                                        <option value="Навсегда">Навсегда</option>
                                    </select>
                                    <textarea maxlength="300" required class="causeBannedUser" placeholder="Причина" name="cause"></textarea>
                                    <div class="footerModalBannedUser">
                                        <button class="btn-modalClose">Отмена</button>
                                        <button type="submit" class="btn-modalAccept">Подтвердить</button>
                                    </div>
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

    let UnbannedButton = document.querySelector('.buttonUnbannedUSer')
    if (UnbannedButton){
        UnbannedButton.addEventListener('click', ()=>{
            document.body.insertAdjacentHTML('afterbegin',`
                    <div class="modal" data-iduser="{{$user->id}}">
                        <div class="modalBlockBannedUser">
                            <div class="headerModalBannedUser">Разблокировка пользователя {{$user->login}}</div>
                            <div class="bodyModalCreateBannedUser UnbannedModal">
                                <div class="causeBannedModal">Причина: {{$user->banned->last() ? $user->banned->last()->cause : 'null'}}</div>
                                <div class="created_atBannedModal">Дата блокировки: {{$user->banned->last() ? $user->banned->last()->created_at->format('d m Y H:i') : 'null'}}</div>
                                <div class="periodBannedModal">Период: {{$user->banned->last() ? $user->banned->last()->period : 'null'}} дня(дней)</div>
                                <form action="{{route('admin.banneds.destroy', ['banned' => $user->banned->last() ? $user->banned->last()->id : 'null', 'user' => $user->id])}}" class="addBannedUser" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <div class="footerModalBannedUser">
                                        <button class="btn-modalClose">Отмена</button>
                                        <button type="submit" class="btn-modalAccept">Подтвердить</button>
                                    </div>
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


    let buttonsLi = document.querySelectorAll('.liNameFunction')
    let indexPost = document.querySelector('.postIndex')
    buttonsLi.forEach((item) => {
        item.addEventListener('click', ()=>{
            let type = item.dataset.typepost
            console.log(type)
            if (type == 'posts'){
                if (!item.classList.contains('active')){
                    indexPost.innerHTML = ''
                    indexPost.insertAdjacentHTML('afterbegin',`
                        @foreach($posts as $post)
                            <div class="cardHover">
                                <div class="card">
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
                                            <div class="author">
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
                                </div>
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
                    `)
                    buttonsLi.forEach(type=>{
                        type.classList.remove('active');
                    })
                    item.classList.add('active');
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

                        item.addEventListener('click', ()=>{
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

                        item.addEventListener('click', ()=>{
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
                }
            }
            else if(type == 'likes'){
                if (!item.classList.contains('active')){
                    indexPost.innerHTML = ''
                    indexPost.insertAdjacentHTML('afterbegin',`
                        @foreach($likes as $like)
                            <div class="cardHover">
                                <div class="card">
                                    <svg class="favorite" data-postid="{{$like->id}}" viewBox="0 0 12 12" enable-background="new 0 0 12 12" id="Слой_1" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <polygon class="polygonFavorite" fill="#A7A8A8FF" points="1.5,0 1.5,12 6,9.8181763 10.5,12 10.5,0 "></polygon>
                                        </g>
                                    </svg>
                                    <img class="imagePostCard" src="/storage/post/images/{{$like->photo}}" alt="...">
                                    <div class="infoCard">
                                        <div class="headerCard">{{$like->name}}</div>
                                        <div class="footerCard">
                                            <div class="author">
                                                {{$like->user->login}}
                                            </div>
                                            <div class="functionsCard">
                                                <div class="countLike">
                                                    {{count($like->like)}}
                                                </div>
                                                <svg class="heart" data-postid="{{$like->id}}" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
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
                                            @foreach($like->hashtag as $hashtag)
                                            <div class="tagPost">
                                            #{{$hashtag->name}}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <?php
                        if($likes->currentPage()+1 <= $likes->lastPage() || $likes->currentPage()-1 >= 1){
                            ?>
                            <div class="paginateBlock">
                                <a class="paginateElem" href="{{$likes->url(1)}}">Первая</a>
                                <?php
                                if ($likes->currentPage()-1 >= 1) {
                                    ?>
                                    <a class="paginateElem" href="{{$likes->previousPageUrl()}}">Предыдущая</a>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($likes->currentPage()-2 >= 1) {
                                    ?>
                                    <a class="paginateElem" href="{{$likes->url($likes->currentPage()-2)}}">{{$likes->currentPage()-2}}</a>
                                    <?php
                                }
                                if ($likes->currentPage()-1 >= 1) {
                                    ?>
                                    <a class="paginateElem" href="{{$likes->url($likes->currentPage()-1)}}">{{$likes->currentPage()-1}}</a>
                                    <?php
                                }
                                ?>
                                    <a class="paginateElem  activeElem" href="#">{{ $likes->currentPage() }}</a>
                                <?php
                                if ($likes->currentPage()+1 <= $likes->lastPage()) {
                                    ?>
                                    <a class="paginateElem" href="{{$likes->url($likes->currentPage()+1)}}">{{$likes->currentPage()+1}}</a>
                                    <?php
                                }
                                if ($likes->currentPage()+2 <= $likes->lastPage()) {
                                    ?>
                                    <a class="paginateElem" href="{{$likes->url($likes->currentPage()+2)}}">{{$likes->currentPage()+2}}</a>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($likes->currentPage()+1 <= $likes->lastPage()) {
                                    ?>
                                    <a class="paginateElem" href="{{$likes->nextPageUrl()}}">Следующая</a>
                                    <?php
                                }
                                ?>
                                    <a class="paginateElem" href="{{$likes->url($likes->lastPage())}}">Последняя</a>
                            </div
                            <?php
                        }
                        ?>
                    `)
                    buttonsLi.forEach(type=>{
                        type.classList.remove('active');
                    })
                    item.classList.add('active');
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

                        item.addEventListener('click', ()=>{
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

                        item.addEventListener('click', ()=>{
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
                }
            }
            else if(type == 'favorites'){
                if (!item.classList.contains('active')){
                    indexPost.innerHTML = ''
                    indexPost.insertAdjacentHTML('afterbegin',`
                        @foreach($favorites as $favorite)
                        <div class="cardHover">
                            <div class="card">
                                <svg class="favorite" data-postid="{{$favorite->id}}" viewBox="0 0 12 12" enable-background="new 0 0 12 12" id="Слой_1" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <polygon class="polygonFavorite" fill="#A7A8A8FF" points="1.5,0 1.5,12 6,9.8181763 10.5,12 10.5,0 "></polygon>
                                    </g>
                                </svg>
                                <img class="imagePostCard" src="/storage/post/images/{{$favorite->photo}}" alt="...">
                                <div class="infoCard">
                                    <div class="headerCard">{{$favorite->name}}</div>
                                    <div class="footerCard">
                                        <div class="author">
                                            {{$favorite->user->login}}
                                        </div>
                                        <div class="functionsCard">
                                            <div class="countLike">
                                                {{count($favorite->like)}}
                                            </div>
                                            <svg class="heart" data-postid="{{$favorite->id}}" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
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
                                        @foreach($favorite->hashtag as $hashtag)
                                        <div class="tagPost">
                                        #{{$hashtag->name}}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <?php
                        if($favorites->currentPage()+1 <= $favorites->lastPage() || $favorites->currentPage()-1 >= 1){
                            ?>
                            <div class="paginateBlock">
                                <a class="paginateElem" href="{{$favorites->url(1)}}">Первая</a>
                                <?php
                                if ($favorites->currentPage()-1 >= 1) {
                                    ?>
                                    <a class="paginateElem" href="{{$favorites->previousPageUrl()}}">Предыдущая</a>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($favorites->currentPage()-2 >= 1) {
                                    ?>
                                    <a class="paginateElem" href="{{$favorites->url($favorites->currentPage()-2)}}">{{$favorites->currentPage()-2}}</a>
                                    <?php
                                }
                                if ($favorites->currentPage()-1 >= 1) {
                                    ?>
                                    <a class="paginateElem" href="{{$favorites->url($favorites->currentPage()-1)}}">{{$favorites->currentPage()-1}}</a>
                                    <?php
                                }
                                ?>
                                    <a class="paginateElem  activeElem" href="#">{{ $favorites->currentPage() }}</a>
                                        <?php
                                if ($favorites->currentPage()+1 <= $favorites->lastPage()) {
                                    ?>
                                    <a class="paginateElem" href="{{$favorites->url($favorites->currentPage()+1)}}">{{$favorites->currentPage()+1}}</a>
                                    <?php
                                }
                                if ($favorites->currentPage()+2 <= $favorites->lastPage()) {
                                    ?>
                                    <a class="paginateElem" href="{{$favorites->url($favorites->currentPage()+2)}}">{{$favorites->currentPage()+2}}</a>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($favorites->currentPage()+1 <= $favorites->lastPage()) {
                                    ?>
                                    <a class="paginateElem" href="{{$favorites->nextPageUrl()}}">Следующая</a>
                                    <?php
                                }
                                ?>
                                    <a class="paginateElem" href="{{$favorites->url($favorites->lastPage())}}">Последняя</a>
                            </div
                            <?php
                    }
                        ?>
                    `)
                    buttonsLi.forEach(type=>{
                        type.classList.remove('active');
                    })
                    item.classList.add('active');
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

                        item.addEventListener('click', ()=>{
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

                        item.addEventListener('click', ()=>{
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
                }
            }
        })
    })
</script>
    @if(request()->has('posts-page'))
        <script>
            document.querySelector('.liNameFunction[data-typepost="posts"]').click()
        </script>
    @endif
    @if(request()->has('likes-page'))
        <script>
            document.querySelector('.liNameFunction[data-typepost="likes"]').click()
        </script>
    @endif
    @if(request()->has('favorites-page'))
        <script>
            document.querySelector('.liNameFunction[data-typepost="favorites"]').click()
        </script>
    @endif
@endsection
