@extends('index')

@section('title', 'Страница всех ролей')

@section('content')
    <div class="imageRoles"></div>
    @if(session()->has('add'))
        <div class="message" id="green">Вы успешно добавили роль</div>
    @endif
    @if(session()->has('update'))
        <div class="message" id="yellow">Вы успешно изменили роль</div>
    @endif
    @if(session()->has('destroy'))
        <div class="message" id="red">Вы успешно удалили роль</div>
    @endif
    <div class="containerRole">
        <button class="CreateButtonRole">Добавить</button>
        <table class="resp-tab">
            <thead class="headTable">
            <tr>
                <th>Наименование</th>
                <th>Функции</th>
            </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr class="infoTable">
                        <td>{{$role->name}}</td>
                        <td>
                            <button class="btn-roles EditButtonRole" data-idrole="{{$role->id}}" data-idname="{{$role->name}}">Редактировать</button>
                            <button class="btn-roles deleteButtonRole" data-idrole="{{$role->id}}" data-idname="{{$role->name}}">Удалить</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <?php
        if($roles->currentPage()+1 <= $roles->lastPage() || $roles->currentPage()-1 >= 1){
        ?>
        <div class="paginateBlock commentsPaginate">
            <a class="paginateElem" href="{{$roles->url(1)}}">Первая</a>
            <?php
            if ($roles->currentPage()-1 >= 1) {
            ?>
            <a class="paginateElem" href="{{$roles->previousPageUrl()}}">Предыдущая</a>
            <?php
            }
            ?>
            <?php
            if ($roles->currentPage()-2 >= 1) {
            ?>
            <a class="paginateElem" href="{{$roles->url($roles->currentPage()-2)}}">{{$roles->currentPage()-2}}</a>
            <?php
            }
            if ($roles->currentPage()-1 >= 1) {
            ?>
            <a class="paginateElem" href="{{$roles->url($roles->currentPage()-1)}}">{{$roles->currentPage()-1}}</a>
            <?php
            }
            ?>
            <a class="paginateElem  activeElem" href="#">{{ $roles->currentPage() }}</a>
            <?php
            if ($roles->currentPage()+1 <= $roles->lastPage()) {
            ?>
            <a class="paginateElem" href="{{$roles->url($roles->currentPage()+1)}}">{{$roles->currentPage()+1}}</a>
            <?php
            }
            if ($roles->currentPage()+2 <= $roles->lastPage()) {
            ?>
            <a class="paginateElem" href="{{$roles->url($roles->currentPage()+2)}}">{{$roles->currentPage()+2}}</a>
            <?php
            }
            ?>
            <?php
            if ($roles->currentPage()+1 <= $roles->lastPage()) {
            ?>
            <a class="paginateElem" href="{{$roles->nextPageUrl()}}">Следующая</a>
            <?php
            }
            ?>
            <a class="paginateElem" href="{{$roles->url($roles->lastPage())}}">Последняя</a>
        </div>
        <?php
        }
        ?>
    </div>
    <script>
        //Модальные окна
        //Редактирование роли
        let buttonsEdit = document.querySelectorAll('.EditButtonRole')
        buttonsEdit.forEach( item=>{
            let roleID = item.dataset.idrole
            let roleName = item.dataset.idname
            item.addEventListener('click', ()=>{
                document.body.insertAdjacentHTML('afterbegin',`
                <div class="modal">
                    <div class="modalBlock">
                        <div class="headerModal">Изменение роли: ${roleName}</div>
                        <div class="bodyModalCreateRole">
                            <form class="addRole" action="/admin/roles/${roleID}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="auth_input">
                                    <label for="exampleInputName" class="form-label">Наименование роли:</label>
                                    <input type="text" required name="name" class="form-input @error('name') is-invalid @enderror" id="exampleInputName" aria-describedby="nameHelp" value="{{old('name')}}">
                                    @error('name')<div id="nameHelp" class="form-error">{{$message}}</div>@enderror
                                </div>
                                <div class="footerModalCreateRole">
                                    <button type="button" class="btn-modalClose">Отмена</button>
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

        //Удаление роли
        let buttonsDelete = document.querySelectorAll('.deleteButtonRole')
        buttonsDelete.forEach(item =>{
            let roleID = item.dataset.idrole
            let roleName = item.dataset.idname
            item.addEventListener('click', ()=>{
                document.body.insertAdjacentHTML('afterbegin',`
                    <div class="modal" data-idrole="${roleID}">
                        <div class="modalBlock">
                            <div class="headerModal">Удаление роли</div>
                            <div class="bodyModal">Вы точно хотите удалить роль: ${roleName}</div>
                            <div class="footerModal">
                                <button class="btn-modalClose">Отмена</button>
                                <form action="/admin/roles/${roleID}" method="POST">
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
        //Создание роли
        let buttonCreate = document.querySelector('.CreateButtonRole')
        buttonCreate.addEventListener('click', ()=>{
            document.body.insertAdjacentHTML('afterbegin',`
                <div class="modal">
                    <div class="modalBlock">
                        <div class="headerModal">Создание роли</div>
                        <div class="bodyModalCreateRole">
                            <form class="addRole" action="{{route('admin.roles.store')}}" method="POST">
                                <div class="auth_input">
                                    @csrf
                                    <label for="exampleInputName" class="form-label">Наименование роли:</label>
                                    <input type="text" required name="name" class="form-input @error('name') is-invalid @enderror" id="exampleInputName" aria-describedby="nameHelp" value="{{old('name')}}">
                                    @error('name')<div id="nameHelp" class="form-error">{{$message}}</div>@enderror
                                </div>
                                <div class="footerModalCreateRole">
                                    <button type="button" class="btn-modalClose">Отмена</button>
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
    </script>
@endsection

