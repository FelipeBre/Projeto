var menuItem = document.querySelectorAll('.item-menu')

function selectLink(){
    menuItem.forEach((item)=>
      item.classList.remove('ativo')
    )
    this.classList.add('ativo')
}

menuItem.forEach((item)=>
   item.addEventListener('click',selectLink)
)

//Expação do menu

var btnExp = document.querySelector('#btn-Exp')
var menuSide = document.querySelector('.menu-lateral')

btnExp.addEventListener('click', function(){
  menuSide.classList.toggle('bnt-expandir')
})