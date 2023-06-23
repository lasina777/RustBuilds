@extends('index')

@section('title', 'Страница всех ролей')

@section('content')
    <div class="imageRoles"></div>
    @if(session()->has('add'))
        <div class="message" id="green">Вы успешно добавили категорию</div>
    @endif
    @if(session()->has('update'))
        <div class="message" id="yellow">Вы успешно изменили категорию</div>
    @endif
    @if(session()->has('destroy'))
        <div class="message" id="red">Вы успешно удалили категорию</div>
    @endif
    <div class="containerRole">
        <button class="CreateButtonRole">Добавить</button>
        <table class="resp-tab">
            <thead class="headTable">
            <tr>
                <th>Изображение</th>
                <th>Наименование</th>
                <th>Функции</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr class="infoTable">
                    <td><img width="50px" height="50px" src="{{'/storage/categories/'.$category->photo}}" alt="..."></td>
                    <td>{{$category->name}}</td>
                    <td>
                        <button class="btn-roles EditButtonCategory" data-idcategory="{{$category->id}}" data-idname="{{$category->name}}">Редактировать</button>
                        <button class="btn-roles deleteButtonCategory" data-idcategory="{{$category->id}}" data-idname="{{$category->name}}">Удалить</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <?php
        if($categories->currentPage()+1 <= $categories->lastPage() || $categories->currentPage()-1 >= 1){
        ?>
        <div class="paginateBlock commentsPaginate">
            <a class="paginateElem" href="{{$categories->url(1)}}">Первая</a>
            <?php
            if ($categories->currentPage()-1 >= 1) {
            ?>
            <a class="paginateElem" href="{{$categories->previousPageUrl()}}">Предыдущая</a>
            <?php
            }
            ?>
            <?php
            if ($categories->currentPage()-2 >= 1) {
            ?>
            <a class="paginateElem" href="{{$categories->url($categories->currentPage()-2)}}">{{$categories->currentPage()-2}}</a>
            <?php
            }
            if ($categories->currentPage()-1 >= 1) {
            ?>
            <a class="paginateElem" href="{{$categories->url($categories->currentPage()-1)}}">{{$categories->currentPage()-1}}</a>
            <?php
            }
            ?>
            <a class="paginateElem  activeElem" href="#">{{ $categories->currentPage() }}</a>
            <?php
            if ($categories->currentPage()+1 <= $categories->lastPage()) {
            ?>
            <a class="paginateElem" href="{{$categories->url($categories->currentPage()+1)}}">{{$categories->currentPage()+1}}</a>
            <?php
            }
            if ($categories->currentPage()+2 <= $categories->lastPage()) {
            ?>
            <a class="paginateElem" href="{{$categories->url($categories->currentPage()+2)}}">{{$categories->currentPage()+2}}</a>
            <?php
            }
            ?>
            <?php
            if ($categories->currentPage()+1 <= $categories->lastPage()) {
            ?>
            <a class="paginateElem" href="{{$categories->nextPageUrl()}}">Следующая</a>
            <?php
            }
            ?>
            <a class="paginateElem" href="{{$categories->url($categories->lastPage())}}">Последняя</a>
        </div>
        <?php
        }
        ?>
    </div>
    <script>
        //Модальные окна
        //Редактирование роли
        let buttonsEdit = document.querySelectorAll('.EditButtonCategory')
        buttonsEdit.forEach( item=>{
            let categoryID = item.dataset.idcategory
            let categoryName = item.dataset.idname
            item.addEventListener('click', ()=>{
                document.body.insertAdjacentHTML('afterbegin',`
                <div class="modal">
                    <div class="modalBlockCategory">
                        <div class="headerModal">Изменение категории ${categoryName}</div>
                        <div class="bodyModalCreateCategory">
                            <form class="addCategory" action="/admin/categories/${categoryID}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="imageCategoryCreate">
                                    <label for="exampleInputPhotoCategory" class="form-label" id="labelPhotoCategory"><span class="textLabelPhotoCategory">Выберите изображение</span></label>
                                    <input accept="image/*" type="file" name="photo" class="form-inputPost imagePost @error('photo') is-invalid @enderror" id="exampleInputPhotoCategory" aria-describedby="photoHelp" value="{{old('photo')}}">
                                    @error('photo')<div id="photoHelp" class="form-error">{{$message}}</div>@enderror
                                </div>
                                <div class="auth_input inputCategory">
                                    <label for="exampleInputName" class="form-label">Наименование категории:</label>
                                    <input type="text" required name="name" class="form-input @error('name') is-invalid @enderror" id="exampleInputName" aria-describedby="nameHelp" value="{{old('name')}}">
                                    @error('name')<div id="nameHelp" class="form-error">{{$message}}</div>@enderror
                                </div>
                                <div class="footerModalCreateCategory">
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
        let buttonsDelete = document.querySelectorAll('.deleteButtonCategory')
        buttonsDelete.forEach(item =>{
            let categoryID = item.dataset.idcategory
            let categoryName = item.dataset.idname
            item.addEventListener('click', ()=>{
                document.body.insertAdjacentHTML('afterbegin',`
                    <div class="modal" data-idcategory="${categoryID}">
                        <div class="modalBlock">
                            <div class="headerModal">Удаление категории</div>
                            <div class="bodyModal">Вы точно хотите удалить категорию: ${categoryName}</div>
                            <div class="footerModal">
                                <button class="btn-modalClose">Отмена</button>
                                <form action="/admin/categories/${categoryID}" method="POST">
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
        //Создание категориии
        let buttonCreate = document.querySelector('.CreateButtonRole')
        buttonCreate.addEventListener('click', ()=>{
            document.body.insertAdjacentHTML('afterbegin',`
                <div class="modal">
                    <div class="modalBlockCategory">
                        <div class="headerModal">Создание роли</div>
                        <div class="bodyModalCreateCategory">
                            <form class="addCategory" action="{{route('admin.categories.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="imageCategoryCreate">
                                    <label for="exampleInputPhotoCategory" class="form-label" id="labelPhotoCategory"><span class="textLabelPhotoCategory">Выберите изображение</span></label>
                                    <input accept="image/*" type="file" required name="photo" class="form-inputPost imagePost @error('photo') is-invalid @enderror" id="exampleInputPhotoCategory" aria-describedby="photoHelp" value="{{old('photo')}}">
                                    @error('photo')<div id="photoHelp" class="form-error">{{$message}}</div>@enderror
                                </div>
                                <div class="auth_input inputCategory">
                                    <label for="exampleInputName" class="form-label">Наименование категории:</label>
                                    <input type="text" required name="name" class="form-input @error('name') is-invalid @enderror" id="exampleInputName" aria-describedby="nameHelp" value="{{old('name')}}">
                                    @error('name')<div id="nameHelp" class="form-error">{{$message}}</div>@enderror
                                </div>
                                <div class="footerModalCreateCategory">
                                    <button type="button" class="btn-modalClose">Отмена</button>
                                    <button type="submit" class="btn-modalAccept">Подтвердить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `)

            //Вывод изображений из input
            if (window.FileList && window.File && window.FileReader) {
                let inputs = document.querySelectorAll('.imagePost')
                inputs.forEach((item) =>{
                    item.addEventListener('change', event => {
                        let output = item.parentNode
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
                            output.style = `background-image: url("${event.target.result}")`;
                        });
                        reader.readAsDataURL(file);
                    })
                });
            }

            let closeModalButton = document.querySelector('.btn-modalClose')
            let modal = document.querySelector('.modal')
            closeModalButton.addEventListener('click', item=>{
                document.body.removeChild(modal)
            })
        })
    </script>
@endsection
