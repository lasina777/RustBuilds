//dropdown admin
let dropdown = document.querySelector('#myDropdown')
const buttonDropdown = document.querySelector('#firstDropdown')
// const SecondbuttonDropdown = document.querySelector('.dropbtnRole')
// let Seconddropdown = document.querySelector('.dropdown-contentRole')
// const SecondbuttonDropdownCategories = document.querySelector('.dropbtnCategory')
// let SeconddropdownCategories = document.querySelector('.dropdown-contentCategory')
if (dropdown != null && buttonDropdown != null){
    buttonDropdown.addEventListener('click', () => {
        if (!dropdown.style.display){
            dropdown.style.display = 'flex'
        }
        else if  (dropdown.style.display == 'none'){
            dropdown.style.display = 'flex'
        }
        else{
            dropdown.style.display = 'none'
            // Seconddropdown.style.display = 'none'
        }
    })
    // SecondbuttonDropdown.addEventListener('click', () => {
    //     if (!Seconddropdown.style.display){
    //         SeconddropdownCategories.style.display = 'none'
    //         Seconddropdown.style.display = 'flex'
    //     }
    //     else if (Seconddropdown.style.display == 'none'){
    //         SeconddropdownCategories.style.display = 'none'
    //         Seconddropdown.style.display = 'flex'
    //     }
    //     else{
    //         Seconddropdown.style.display = 'none'
    //     }
    // })
    // SecondbuttonDropdownCategories.addEventListener('click', () => {
    //     if (!SeconddropdownCategories.style.display){
    //         Seconddropdown.style.display = 'none'
    //         SeconddropdownCategories.style.display = 'flex'
    //     }
    //     else if (SeconddropdownCategories.style.display == 'none'){
    //         Seconddropdown.style.display = 'none'
    //         SeconddropdownCategories.style.display = 'flex'
    //     }
    //     else{
    //         SeconddropdownCategories.style.display = 'none'
    //     }
    // })
}

//dropdown profile
let secondDropdown = document.querySelector('#myDropdown2')
const buttonSecondDropdown = document.querySelector('#secondDropdown')
if (secondDropdown != null && secondDropdown != null){
    buttonSecondDropdown.addEventListener('click', () => {
        if (!secondDropdown.style.display){
            secondDropdown.style.display = 'flex'
        }
        else if  (secondDropdown.style.display == 'none'){
            secondDropdown.style.display = 'flex'
        }
        else{
            secondDropdown.style.display = 'none'
        }
    })
}
//Модальные окна
//Категории
let buttonModalsCategories = document.querySelectorAll('.btn-categories')
let modalsCategories = document.querySelectorAll('.modal_categories')
if (buttonModalsCategories != null && modalsCategories != null){
    buttonModalsCategories.forEach(itemButton=>{
        modalsCategories.forEach(itemModal=>{
            if (itemButton.dataset['idcategory'] == itemModal.dataset['idcategory']){
                itemButton.addEventListener('click', ()=>{
                    itemModal.style.display = 'flex'
                })
                let ClosebuttonModals = itemModal.querySelector('.btn-modalClose')
                ClosebuttonModals.addEventListener('click', ()=>{
                    itemModal.style.display = 'none'
                })
            }
        })
    })
}

