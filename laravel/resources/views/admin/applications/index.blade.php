@extends('index')

@section('title', 'Страница заявок')

@section('content')
    @if(session()->has('accept'))
        <div class="message" id="green">Вы успешно утвердили пост</div>
    @endif
    @if(session()->has('cancel'))
        <div class="message" id="green">Вы успешно отменили пост</div>
    @endif
    <div class="imageRoles"></div>
    <div class="container Column">
        <div class="applications">
                @foreach($posts as $post)
                    <div class="dropdownApplication">
                        <div class="headerDropdownApplication">
                            <div class="ApplicationInfo">
                                <a class="profileUserApplication" href="{{route('profile.show', ['user' => $post->user->id])}}">
                                    <img class="photoUserApplication" style="width: 50px; height: 50px" src="/storage/{{$post->user->photo}}" alt="...">
                                </a>
                                <div class="ApplicationInfoPost">
                                    <a href="{{route('profile.show', ['user' => $post->user->id])}}" class="loginUserShowApplication">{{$post->user->login}}</a>
                                    <a class="namePostApplication" href="{{route('post.show', ['post' => $post->id])}}">Пост: {{$post->name}}</a>
                                </div>
                            </div>
                            <i class="arrowDown"></i>
                        </div>
                        <div class="mainDropdownApplication">
                            @foreach($post->status as $status)
                                @if($status->status != 'Ожидание')
                                    <div class="textStatus">{{$status->text}}</div>
                                @endif
                            @endforeach
                            <div class="buttonsApplications">
                                <button class="buttonApplicationsCancel" data-postid="{{$post->id}}" data-postname="{{$post->name}}">Отмена</button>
                                <button class="buttonApplicationsAccept" data-postid="{{$post->id}}" data-postname="{{$post->name}}">Подтвердить</button>
                            </div>
                        </div>
                    </div>
                @endforeach
        </div>
        <?php
        ?>
        @if($posts->currentPage()+1 <= $posts->lastPage() || $posts->currentPage()-1 >= 1)
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
            ?>
        @endif
    </div>
    <script>
        let DropdownApplicationButton = document.querySelectorAll('.arrowDown')
        if (DropdownApplicationButton){
            DropdownApplicationButton.forEach(item=>{
                item.addEventListener('click', () => {
                    let mainApplication = item.parentNode.parentNode.querySelector('.mainDropdownApplication')
                    if (!mainApplication.style.display){
                        mainApplication.style.display = 'flex'
                    }
                    else if  (mainApplication.style.display == 'none'){
                        mainApplication.style.display = 'flex'
                    }
                    else{
                        mainApplication.style.display = 'none'
                    }
                })
            })
        }
        let AcceptsPost = document.querySelectorAll('.buttonApplicationsAccept')
        if (AcceptsPost){
            AcceptsPost.forEach(AcceptPost=>{
                let postId = AcceptPost.dataset.postid
                let postName = AcceptPost.dataset.postname
                AcceptPost.addEventListener('click', ()=>{
                    document.body.insertAdjacentHTML('afterbegin',`
                    <div class="modal" data-idpost="${postId}">
                        <div class="modalBlock">
                            <div class="headerModal">Утверждение поста</div>
                            <div class="bodyModal">Вы точно хотите утвердить пост: ${postName}?</div>
                            <div class="footerModal">
                                <button class="btn-modalClose">Отмена</button>
                                <form action="/admin/applications/destroy/${postId}" method="POST">
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

        let CancelsPost = document.querySelectorAll('.buttonApplicationsCancel')
        if (CancelsPost){
            CancelsPost.forEach(CancelPost=>{
                let postId = CancelPost.dataset.postid
                let postName = CancelPost.dataset.postname
                CancelPost.addEventListener('click', ()=>{
                    document.body.insertAdjacentHTML('afterbegin',`
                    <div class="modal" data-idpost="${postId}">
                        <div class="modalBlockBannedUser">
                            <div class="headerModalBannedUser">Отмена поста ${postName}</div>
                            <div class="bodyModalCreateBannedUser">
                                <form action="/admin/applications/store/${postId}" class="addBannedUser" method="POST">
                                    @csrf
                                    <textarea maxlength="300" required class="causeBannedUser" placeholder="Причина" name="text"></textarea>
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
            })
        }
    </script>
@endsection
