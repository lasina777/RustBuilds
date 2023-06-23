@extends('index')

@section('title', 'Страница заявок')

@section('content')
    @if(session()->has('updatePost'))
        <div class="message" id="orange">Вы успешно изменили пост</div>
    @endif
    <div class="imageRoles"></div>
    <div class="container Column">
        <div class="applications">
                @foreach($posts as $post)
                    <div class="dropdownApplication">
                        <div class="headerDropdownApplication" style="{{$post->status->last()->status == 'Ожидание' ? 'border-bottom: 1px solid #e28c38;' : 'border-bottom: 1px solid #c45137;'}}">
                            <div class="ApplicationInfo">
                                <a class="profileUserApplication" href="{{route('profile.show', ['user' => $post->user->id])}}">
                                    <img class="photoUserApplication" style="width: 50px; height: 50px" src="/storage/{{$post->user->photo}}" alt="...">
                                </a>
                                <div class="ApplicationInfoPost">
                                    <a href="{{route('profile.show', ['user' => $post->user->id])}}" class="loginUserShowApplication">{{$post->user->login}}</a>
                                    <a class="namePostApplication" href="{{route('post.show', ['post' => $post->id])}}">Пост: {{$post->name}}</a>
                                </div>
                            </div>
                            <i class="arrowDown {{$post->status->last()->status == 'Ожидание' ? 'orangeArrow' : 'redArrow'}}" ></i>
                        </div>
                        <div class="mainDropdownApplication">
                            @foreach($post->status as $status)
                                @if($status->status != 'Ожидание')
                                    <div class="textStatus">{{$status->text}}</div>
                                @endif
                            @endforeach
                            @if($post->status->last()->status == 'Отменен')
                                <div class="buttonsApplications">
                                    <a href="{{route('post.edit', ['post' => $post->id])}}" class="buttonUpdatePost">Редактирование</a>
                                </div>
                            @endif
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
    </script>
@endsection
