@extends('index')

@section('title', 'Страница вывода всех пользователей')

@section('content')
    <div class="imageIndexProfile"></div>
    <div class="container Column">
        <div class="profiles">
            <div class="search endRow">
                <form class="search" action="{{route('profile.search')}}" method="GET">
                    <input placeholder="Поиск..." class="searchInput" type="text" name="login" value="{{old('search')}}">
                    <button class="searchButton" type="submit">Поиск</button>
                </form>
            </div>
            <div class="indexProfile">
                @foreach($users as $user)
                    <a class="profileUser" href="{{route('profile.show', ['user' => $user->id])}}">
                        <img class="photoUser" style="width: 150px; height: 150px" src="/storage/{{$user->photo}}" alt="...">
                        <div class="loginUserShow">{{$user->login}}</div>
                    </a>
                @endforeach
            </div>
        </div>
        <?php
        ?>
        @if($users->currentPage()+1 <= $users->lastPage() || $users->currentPage()-1 >= 1)
            <div class="paginateBlock">
                <a class="paginateElem" href="{{$users->url(1)}}">Первая</a>
                <?php
                if ($users->currentPage()-1 >= 1) {
                ?>
                <a class="paginateElem" href="{{$users->previousPageUrl()}}">Предыдущая</a>
                <?php
                }
                ?>
                <?php
                if ($users->currentPage()-2 >= 1) {
                ?>
                <a class="paginateElem" href="{{$users->url($users->currentPage()-2)}}">{{$users->currentPage()-2}}</a>
                <?php
                }
                if ($users->currentPage()-1 >= 1) {
                ?>
                <a class="paginateElem" href="{{$users->url($users->currentPage()-1)}}">{{$users->currentPage()-1}}</a>
                <?php
                }
                ?>
                <a class="paginateElem  activeElem" href="#">{{ $users->currentPage() }}</a>
                <?php
                if ($users->currentPage()+1 <= $users->lastPage()) {
                ?>
                <a class="paginateElem" href="{{$users->url($users->currentPage()+1)}}">{{$users->currentPage()+1}}</a>
                <?php
                }
                if ($users->currentPage()+2 <= $users->lastPage()) {
                ?>
                <a class="paginateElem" href="{{$users->url($users->currentPage()+2)}}">{{$users->currentPage()+2}}</a>
                <?php
                }
                ?>
                <?php
                if ($users->currentPage()+1 <= $users->lastPage()) {
                ?>
                <a class="paginateElem" href="{{$users->nextPageUrl()}}">Следующая</a>
                <?php
                }
                ?>
                <a class="paginateElem" href="{{$users->url($users->lastPage())}}">Последняя</a>
            </div
            <?php
            ?>
        @endif
    </div>
@endsection
