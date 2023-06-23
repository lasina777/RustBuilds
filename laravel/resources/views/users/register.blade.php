@extends('index')

@section('title', 'Страница регистрации')

@section('content')
    <div class="imageRegister">
        <div class="container">
            @guest
                <div class="authblock">
                    <div class="registerheader">РЕГИСТРАЦИЯ</div>
                    <form class="auth" action="{{route('register')}}" method="POST">
                        @csrf
                        <div class="auth_input">
                            <label for="exampleInputEmail" class="form-label">Почта:</label>
                            <input maxlength="100" type="email" name="email" class="form-input @error('email') is-invalid @enderror" id="exampleInputEmail" aria-describedby="emailHelp" value="{{old('email')}}">
                            @error('email')<div id="emailHelp" class="form-error">{{$message}}</div>@enderror
                        </div>
                        <div class="auth_input">
                            <label for="exampleInputLogin" class="form-label">Логин:</label>
                            <input maxlength="14" type="text" name="login" class="form-input @error('login') is-invalid @enderror" id="exampleInputLogin" aria-describedby="loginHelp" value="{{old('login')}}">
                            @error('login')<div id="loginHelp" class="form-error">{{$message}}</div>@enderror
                        </div>
                        <div class="auth_input">
                            <label for="exampleInputPassword" class="form-label">Пароль:</label>
                            <input maxlength="30" type="password" name="password" class="form-input @error('password') is-invalid @enderror" id="exampleInputPassword" aria-describedby="passwordHelp">
                            @error('password')<div id="passwordHelp" class="form-error">{{$message}}</div>@enderror
                        </div>
                        <div class="auth_input">
                            <label for="exampleInputPasswordConfirmation" class="form-label">Пароль повторно:</label>
                            <input maxlength="30" type="password" name="password_confirmation" class="form-input @error('password_confirmation') is-invalid @enderror" id="exampleInputPasswordConfirmation" aria-describedby="password_confirmationHelp">
                            @error('password_confirmation')<div id="password_confirmationHelp" class="form-error">{{$message}}</div>@enderror
                        </div>
                        <div class="auth_input">
                            <div class="rulesblock">
                                <label class="form-check-label" for="exampleCheck1">Согласие на обработку данных</label>
                                <input type="checkbox" name="rules" class="form-check-input @error('rules') is-invalid @enderror" id="exampleCheck1" aria-describedby="rulesHelp">
                                <label class="checkboxRegister" for="exampleCheck1"></label>
                            </div>
                            @error('rules')<div id="rulesHelp" class="form-error">{{$message}}</div>@enderror
                        </div>
                        <button type="submit" class="btn-auth">РЕГИСТРАЦИЯ</button>
                    </form>
                </div>
            @endguest
            @auth
                <div class="message-red">Вы уже авторизованы, регистрация невозможна</div>
            @endauth
        </div>
    </div>
@endsection
