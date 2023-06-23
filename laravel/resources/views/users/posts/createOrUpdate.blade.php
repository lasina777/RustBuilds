@extends('index')

@section('title', 'Страница создания или редактирования поста')

@section('content')
    <div class="imageCreatePost"></div>
        <div class="container" id="containerCreatePost">
            <form class="formPost" action="{{isset($post)? route('post.update', ['post' => $post->id])  : route('post.store', ['category' => $category->id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($post))
                    <input type="hidden" name="_method" value="PUT">
                @endif
                <div class="imagePostCreate" @if(isset($post)) style="background-image: url('/storage/post/images/{{$post->photo}}')" @endif>
                    <label for="exampleInputPhotoPost" class="form-label" id="labelPhotoPost"><span class="textLabelPhotoPost">Выберите изображение</span></label>
                    <input accept="image/*" type="file" @if(isset($post))  @else required @endif name="photo" class="form-inputPost imagePost @error('photo') is-invalid @enderror" id="exampleInputPhotoPost" aria-describedby="photoHelp" value="{{old('photo')}}">
                    @error('photo')<div id="photoHelp" class="form-error">{{$message}}</div>@enderror
                </div>
                <div class="blockInfoCreatePost">
                    <div class="HashtagsBlock">
                        <label class="form-label">Теги:</label>
                        <div class="addTags">
                            <input type="text" maxlength = "15" class="form-inputPost" id="hashtag">
                            <div class="addTag"></div>
                        </div>
                        <div class="tags" @if(isset($post)) data-status="update" @endif>

                        </div>
                    </div>
                    <div class="elementsPost" @if(isset($post)) data-status="update" @endif>
                        <div class="auth_input">
                            <label for="exampleInputName" class="form-label">Наименование поста:</label>
                            <input maxlength="40" type="text" required name="name" class="form-inputPost @error('name') is-invalid @enderror" id="exampleInputNamePost" aria-describedby="nameHelp" value="{{old('name')}}">
                            @error('name')<div id="nameHelp" class="form-error">{{$message}}</div>@enderror
                        </div>
                        <div class="auth_input" id="clip">
                            <label for="exampleInputFortify" id="labelFortify" class="form-label"><img style="position: relative; top: 3px" width="20px" height="20px" src="/assets/image/clip.png">    Выберите файл fortify</label>
                            <input accept="json" type="file" name="fortify" class="form-inputPost @error('fortify') is-invalid @enderror" id="exampleInputFortify" aria-describedby="fortifyHelp" value="{{old('fortify')}}">
                            @error('fortify')<div id="fortifyHelp" class="form-error">{{$message}}</div>@enderror
                        </div>
                        <div class="buttonsPost">
                            <button type="button" class="addBlockCategory">Добавить блок</button>
                            @if(isset($post))
                                <button type="submit" class="btn-post">Редактировать</button>
                            @else
                                <button type="submit" class="btn-post">Создать</button>
                            @endif
                        </div>
                    </div>
                </div>

            </form>
        </div>
    <script>
        // Добаление блока
        let i = 0
        let CountItems = 0
        let addBlock = document.querySelector('.addBlockCategory')
        let formPost = document.querySelector('.formPost')
        let formButton = formPost.querySelector('.buttonsPost')
        let elementsPost = document.querySelector('.elementsPost')

        if (elementsPost.dataset.status){
            @if(isset($post))
                @foreach($post->postItem as $postItem)
                i += 1;
                CountItems += 1;
                formButton.insertAdjacentHTML('beforeBegin',`
                    <div class="PostItems">
                        <div class="itemImage" style="background-image: url('/storage/postItems/images/{{$postItem->photo}}')">
                            <input type="hidden" class="oldImageUpdatePost" name="current_imagePost[${i-1}]" value="{{$postItem->photo}}">
                            <label for="exampleInputPhotoPost${i}" class="form-label" id="labelItemPost"><span class="textLabelPhotoPost">Выберите изображение</span></label>
                            <input accept="image/*" type="file" name="photos[]" class="form-inputPost imagePost @error('photos[]') is-invalid @enderror" id="exampleInputPhotoPost${i}" aria-describedby="photos[]Help">
                            @error('photos[]')<div id="photos[]Help" class="form-error">{{$message}}</div>@enderror
                        </div>
                        <div class="contentItem">
                            <div class="auth_input inputItemPost">
                                <label for="exampleInputHeaders[]}" class="form-label">Заголовок:</label>
                                <textarea type="text" required name="headers[]" class="form-inputPost nameItemPost @error('headers[]') is-invalid @enderror" placeholder = 'Шаг 1' id="exampleInputHeaders[]" aria-describedby="headers[]Help">{{$postItem->header}}</textarea>
                                @error('headers[]')<div id="headers[]Help" class="form-error">{{$message}}</div>@enderror
                            </div>
                            <div style="margin-top: 10px" class="auth_input inputItemPost">
                                <label for="exampleInputInformations[]" class="form-label">Основная информация:</label>
                                <textarea type="text" required name="informations[]" class="form-inputPost infoItemPost @error('informations[]') is-invalid @enderror" id="exampleInputInformations[]" aria-describedby="informations[]Help">{{$postItem->information}}</textarea>
                                @error('informations[]')<div id="informations[]Help" class="form-error">{{$message}}</div>@enderror
                            </div>
                        </div>
                        <img class="deleteBlock" style="" width="48px" height="48px" src="/assets/image/closeIcon.png">
                    </div>`)
                @endforeach
                // удаление блока
                let ButtonsDelete = document.querySelectorAll('.deleteBlock')
                ButtonsDelete.forEach((item)=>{
                    item.addEventListener('click', ()=>{
                        try{
                            elementsPost.removeChild(item.parentNode)
                            CountItems -=1
                        }
                        catch (e){
                            console.log()
                        }
                    })
                })
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
                                let oldImageUpdatePost = output.querySelector('.oldImageUpdatePost')
                                if (oldImageUpdatePost){
                                    oldImageUpdatePost.parentNode.removeChild(oldImageUpdatePost);
                                    let inputPostImage = output.querySelector('.form-inputPost')
                                    inputPostImage.required = true;
                                }
                            });
                            reader.readAsDataURL(file);
                            let inputs = document.querySelectorAll('.imagePost')
                            inputs.forEach((item) =>{
                                console.log(item.value)
                            })
                        })
                    });
                }
            @endif
        }

        if (addBlock != null && formPost != null){
            addBlock.addEventListener('click', ()=>{
                if (CountItems<3){
                    i += 1;
                    CountItems += 1;
                    formButton.insertAdjacentHTML('beforeBegin',`
                    <div class="PostItems">
                        <div class="itemImage">
                            <label for="exampleInputPhotoPost${i}" class="form-label" id="labelItemPost"><span class="textLabelPhotoPost">Выберите изображение</span></label>
                            <input accept="image/*" type="file" required name="photos[]" class="form-inputPost imagePost @error('photos[]') is-invalid @enderror" id="exampleInputPhotoPost${i}" aria-describedby="photos[]Help">
                            @error('photos[]')<div id="photos[]Help" class="form-error">{{$message}}</div>@enderror
                        </div>
                        <div class="contentItem">
                            <div class="auth_input inputItemPost">
                                <label for="exampleInputHeaders[]}" class="form-label">Заголовок:</label>
                                <textarea type="text" required name="headers[]" class="form-inputPost nameItemPost @error('headers[]') is-invalid @enderror" placeholder = 'Шаг 1' id="exampleInputHeaders[]" aria-describedby="headers[]Help"></textarea>
                                @error('headers[]')<div id="headers[]Help" class="form-error">{{$message}}</div>@enderror
                            </div>
                            <div style="margin-top: 10px" class="auth_input inputItemPost">
                                <label for="exampleInputInformations[]" class="form-label">Основная информация:</label>
                                <textarea type="text" required name="informations[]" class="form-inputPost infoItemPost @error('informations[]') is-invalid @enderror" id="exampleInputInformations[]" aria-describedby="informations[]Help"></textarea>
                                @error('informations[]')<div id="informations[]Help" class="form-error">{{$message}}</div>@enderror
                            </div>
                        </div>
                        <img class="deleteBlock" style="" width="48px" height="48px" src="/assets/image/closeIcon.png">
                    </div>`)

                    // удаление блока
                    let ButtonsDelete = document.querySelectorAll('.deleteBlock')
                    ButtonsDelete.forEach((item)=>{
                        item.addEventListener('click', ()=>{
                            try{
                                elementsPost.removeChild(item.parentNode)
                                CountItems -=1
                            }
                            catch (e){
                                console.log()
                            }
                        })
                    })

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
                                    let oldImageUpdatePost = output.querySelector('.oldImageUpdatePost')
                                    if (oldImageUpdatePost){
                                        oldImageUpdatePost.parentNode.removeChild(oldImageUpdatePost);
                                        let inputPostImage = output.querySelector('.form-inputPost')
                                        inputPostImage.required = true;
                                    }
                                });
                                reader.readAsDataURL(file);
                                let inputs = document.querySelectorAll('.imagePost')
                                inputs.forEach((item) =>{
                                    console.log(item.value)
                                })
                            })
                        });
                    }
                }
            })
        }

        // Добавление тега
        let countTags = 0
        let tag = document.getElementById('hashtag')
        regexTag = /^([а-яёa-z]+)$/i
        tag.addEventListener('input', (e)=>{
            let arr = e.target.value.split('')
            let last = arr.length-1
            if (!arr[last].match(regexTag)){
                arr.pop()
                e.target.value = arr.join('')
            }
        })
        let buttonTag = document.querySelector('.addTag')
        let tags = document.querySelector('.tags')
        if (tags.dataset.status){
            @if(isset($post))
                @foreach($post->hashtag as $hashtag)
                countTags += 1;
                tags.insertAdjacentHTML('afterbegin',`<input type="text" name="hashtags[]" readonly class="textTag" value="{{$hashtag->name}}">`)
                tag.value = ''
                @endforeach
                // Удаление тега
                let tagsBlock = document.querySelector('.tags')
                let hashtags = document.querySelectorAll('.textTag')
                hashtags.forEach((item, index)=>{
                    item.addEventListener('click', ()=>{
                        try{
                            tagsBlock.removeChild(item)
                            countTags -=1
                        }
                        catch (e){
                            console.log()
                        }
                    })
                })
            @endif
        }
        if (buttonTag != null && tag != null){
            buttonTag.addEventListener('click', ()=>{
                if (countTags<5 && tag.value != ''){
                    countTags += 1;
                    tags.insertAdjacentHTML('afterbegin',`<input type="text" name="hashtags[]" readonly class="textTag" value="${tag.value}">`)
                    tag.value = ''

                    // Удаление тега
                    let tagsBlock = document.querySelector('.tags')
                    let hashtags = document.querySelectorAll('.textTag')
                    hashtags.forEach((item, index)=>{
                        item.addEventListener('click', ()=>{
                            try{
                                tagsBlock.removeChild(item)
                                countTags -=1
                            }
                            catch (e){
                                console.log()
                            }
                        })
                    })
                }
            })
        }

        //Вывод изображений из input
        if (window.FileList && window.File && window.FileReader) {
            let inputs = document.querySelectorAll('.imagePost')
            inputs.forEach((item) =>{
                item.addEventListener('change', event => {
                    console.log(item)
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
    </script>
@endsection

