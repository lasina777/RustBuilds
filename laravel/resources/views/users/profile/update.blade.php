@extends('index')

@section('title', 'Страница редактирования аккаунта')

@section('content')
    <div class="imageRegister" id="update"></div>
    <div class="container">
        @auth
            <div class="updateAccount">
                <div class="avatarBlock">
                    <div class="foreground">
                        <img class="avatarUpdate" width="100px" height="100px" src="{{'/storage/' . \Illuminate\Support\Facades\Auth::user()->photo}}" alt="...">
                        <form class="update" action="{{route('profile.updateAccountAvatar', ['user' => $user])}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="updateAvatar_input">
                                <label for="file-upload" class="custom-file-upload">
                                    Изменить
                                </label>
                                <input accept="image/*" id="file-upload" hidden type="file" name="photo" class="form-input @error('photo') is-invalid @enderror" aria-describedby="photoHelp" value="{{old('photo')}}">
                                @error('photo')<div id="photoHelp" class="form-error" data-type="avatarError">{{$message}}</div>@enderror
                            </div>
                            <button type="submit" class="btn-auth" id="lightBlue">СОХРАНИТЬ</button>
                        </form>
                    </div>
                </div>
                <div class="updateContentAccount">
                    <div class="avatarBlock">
                        <div class="foreground">
                            <form class="update" action="{{route('profile.updateAccountMain', ['user' => $user->id])}}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="divisionRow">
                                    <div class="divisionColumn">
                                        <div class="auth_input">
                                            <label for="exampleInputName" class="form-labelUpdate">Ваше имя:</label>
                                            <input type="text" name="name" class="form-inputUpdate @error('name') is-invalid @enderror" id="exampleInputName" aria-describedby="nameHelp" value="{{old('name')}}">
                                            @error('name')<div id="nameHelp" class="form-error">{{$message}}</div>@enderror
                                        </div>
                                        <div class="auth_input">
                                            <label for="exampleInputSurname" class="form-labelUpdate">Ваша фамилия:</label>
                                            <input type="text" name="surname" class="form-inputUpdate @error('surname') is-invalid @enderror" id="exampleInputSurname" aria-describedby="surnameHelp" value="{{old('surname')}}">
                                            @error('surname')<div id="surnameHelp" class="form-error">{{$message}}</div>@enderror
                                        </div>
                                        <div class="auth_input">
                                            <label for="exampleInputPatronymic" class="form-labelUpdate">Ваше отчество:</label>
                                            <input type="text" name="patronymic" class="form-inputUpdate @error('patronymic') is-invalid @enderror" id="exampleInputPatronymic" aria-describedby="patronymicHelp" value="{{old('patronymic')}}">
                                            @error('patronymic')<div id="patronymicHelp" class="form-error">{{$message}}</div>@enderror
                                        </div>
                                        <div class="auth_input">
                                            <label for="exampleInputEmail" class="form-labelUpdate">Ваша почта:</label>
                                            <input type="email" name="email" class="form-inputUpdate @error('email') is-invalid @enderror" id="exampleInputEmail" aria-describedby="emailHelp" value="{{old('email')}}">
                                            @error('email')<div id="emailHelp" class="form-error">{{$message}}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="divisionColumn">
                                        <div class="auth_input">
                                            <label for="exampleInputLogin" class="form-labelUpdate">Ваш логин:</label>
                                            <input type="text" name="login" class="form-inputUpdate @error('login') is-invalid @enderror" id="exampleInputLogin" aria-describedby="loginHelp" value="{{old('login')}}">
                                            @error('login')<div id="loginHelp" class="form-error">{{$message}}</div>@enderror
                                        </div>
                                        <div class="auth_input">
                                            <label for="exampleInputLink_steam" class="form-labelUpdate">Ссылка на стим:</label>
                                            <input type="text" name="link_steam" class="form-inputUpdate @error('link_steam') is-invalid @enderror" id="exampleInputLink_steam" aria-describedby="link_steamHelp" value="{{old('link_steam')}}">
                                            @error('link_steam')<div id="link_steamHelp" class="form-error">{{$message}}</div>@enderror
                                        </div>
                                        <div class="auth_input">
                                            <label for="exampleInputPasswordReal" class="form-labelUpdate">Ваш пароль:</label>
                                            <input type="password" name="passwordReal" class="form-inputUpdate @error('passwordReal') is-invalid @enderror" id="exampleInputPasswordReal" aria-describedby="passwordRealHelp">
                                            @error('passwordReal')<div id="passwordRealHelp" class="form-error">{{$message}}</div>@enderror
                                        </div>
                                        <div class="smallText">Для того чтобы данные аккаунта поменялись, вам необходимо ввести ваш действующий пароль</div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-auth" id="lightBlue">РЕДАКТИРОВАТЬ</button>
                            </form>
                        </div>
                    </div>
                    <div class="avatarBlock">
                        <div class="foreground">
                            <form class="update" action="{{route('profile.updateAccountPassword', ['user' => $user->id])}}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="divisionRow">
                                    <div class="auth_input">
                                        <label for="exampleInputOldPassword" class="form-labelUpdate">Ваш существующий пароль:</label>
                                        <input type="password" name="oldPassword" class="form-inputUpdate @error('oldPassword') is-invalid @enderror" id="exampleInputOldPassword" aria-describedby="oldPasswordHelp">
                                        @error('oldPassword')<div id="oldPasswordHelp" class="form-error">{{$message}}</div>@enderror
                                    </div>
                                    <div class="auth_input">
                                        <label for="exampleInputPassword" class="form-labelUpdate">Ваш пароль:</label>
                                        <input type="password" min="8" name="password" class="form-inputUpdate @error('password') is-invalid @enderror" id="exampleInputPassword" aria-describedby="passwordHelp">
                                        @error('password')<div id="passwordHelp" class="form-error">{{$message}}</div>@enderror
                                    </div>
                                    <div class="auth_input">
                                        <label for="exampleInputPasswordConfirmation" class="form-labelUpdate">Ваш пароль повторно:</label>
                                        <input type="password" min="8" name="password_confirmation" class="form-inputUpdate @error('password_confirmation') is-invalid @enderror" id="exampleInputPasswordConfirmation" aria-describedby="password_confirmationHelp">
                                        @error('password_confirmation')<div id="password_confirmationHelp" class="form-error">{{$message}}</div>@enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn-auth" id="lightBlue">РЕДАКТИРОВАТЬ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
    </div>
@endsection
