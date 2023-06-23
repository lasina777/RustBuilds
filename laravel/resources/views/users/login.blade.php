@extends('index')

@section('title', 'Страница авторизации')

@section('content')
    @if(session()->has('register'))
        <div class="message" id="green">
            <div class="messageText">
                Вы успешно зарегистрировались
            </div>
        </div>
    @endif
    @error('errorAuth')
    <div class="message" id="red">
        <div class="messageText">
            Логин или пароль не верный
        </div>
    </div>
    @enderror
    @auth
        <div class="message" id="red">
            <div class="messageText">
                Вы уже авторизованы, повторная авторизация невозможна
            </div>
        </div>
    @endauth
    <div class="imageRegister" id="login">
        <div class="container">
            @guest
                    <div class="authblock">
                        <div class="registerheader">АВТОРИЗАЦИЯ</div>
                        <form class="auth" action="{{route('login')}}" method="POST">
                            @csrf
                            <div class="auth_input">
                                <label for="exampleInputLogin" class="form-label">Ваш логин:</label>
                                <input type="text" name="login" class="form-input @error('login') is-invalid @enderror" id="exampleInputLogin" aria-describedby="loginHelp" value="{{old('login')}}">
                                @error('login')<div id="loginHelp" class="form-error">{{$message}}</div>@enderror
                            </div>
                            <div class="auth_input">
                                <label for="exampleInputPassword" class="form-label">Ваш пароль:</label>
                                <input type="password" name="password" class="form-input @error('password') is-invalid @enderror" id="exampleInputPassword" aria-describedby="passwordHelp">
                                @error('password')<div id="passwordHelp" class="form-error">{{$message}}</div>@enderror
                            </div>
                            @error('errorBanned')
                                <div class="errorBanned">
                                    Вы заблокированы. Разблокировка наступит через:{{$message}}
                                </div>
                            @enderror
                            <button type="submit" class="btn-auth">Авторизация</button>
                        </form>
                    </div>
            @endguest
        </div>
    </div>
@endsection
