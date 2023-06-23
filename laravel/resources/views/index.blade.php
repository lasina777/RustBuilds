<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="/assets/script.js" defer></script>
    <title>@yield('title', 'Главная страница')</title>
</head>
<body>
<header>
    <nav>
        <a href="{{route('main')}}"><img class="logo" src="/assets/image/logo.png" width="230px" height="70px"></a>
        <div class="navbar-items">
            <div class="functions">
                <div class="nav-link">
                    <a class="nav-item-link" href="{{route('post.index')}}">Посты</a>
                </div>
                @auth
                    <div class="nav-link">
                        <a class="nav-item-link" href="{{route('post.choiceCategory')}}">Создать пост</a>
                    </div>
                    <div class="nav-link">
                        <a class="nav-item-link" href="{{route('applications.index')}}">Заявки</a>
                    </div>
                @endauth
                <div class="nav-link">
                    <a class="nav-item-link" href="{{route('profile.index')}}">Пользователи</a>
                </div>
                @auth
                    @if(\Illuminate\Support\Facades\Auth::user()->role->name == 'Администратор')
                        <div class="dropdown">
                            <button class="dropbtn" id="firstDropdown">Администрирование <div class="down"></div></button>
                            <div id="myDropdown" class="dropdown-content">
                                <a class="dropdown-item" href="{{route('admin.roles.index')}}">Роли</a>
                                <a class="dropdown-item" href="{{route('admin.categories.index')}}">Категории</a>
                                <a class="dropdown-item" href="{{route('admin.applications.index')}}">Заявки</a>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
            @guest
                <div class="user-needs">
                    <div class="nav-item">
                        <a class="nav-item-link" href="{{route('register')}}">Регистрация</a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-item-link" href="{{route('login')}}">Авторизация</a>
                    </div>
                </div>
            @endguest

            @auth
            <div class="profile">
                <div class="dropdown" id="profile">
                    <button class="dropbtn" id="secondDropdown">
                        <img class="UserPhoto" width="40px" height="40px" id="profile-items" src="{{'/storage/' . \Illuminate\Support\Facades\Auth::user()->photo}}" alt="...">
                        @if(strlen(\Illuminate\Support\Facades\Auth::user()->login)>15)
                            <?php
                            echo substr(\Illuminate\Support\Facades\Auth::user()->login,0,15,)
                            ?>
                        @else
                            {{\Illuminate\Support\Facades\Auth::user()->login}}
                        @endif
                        <div class="down"></div>
                    </button>
                    <div id="myDropdown2" class="dropdown-content" data-side="left">
                        <a class="dropdown-item" href="{{route('profile.show', ['user' => \Illuminate\Support\Facades\Auth::user()])}}">Профиль</a>
                        <a class="dropdown-item" href="{{route('logout')}}">Выход</a>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </nav>
</header>
@yield('content')
</body>
</html>
