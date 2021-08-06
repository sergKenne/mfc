/**
 * Created by Stardisk on 29.07.20.
 */

document.addEventListener('DOMContentLoaded', function(){
    var menuWrapper = document.querySelector('.menu_wrapper');
    var newItemName = document.querySelector('.new_item_wrapper input[name="item_name"]'),
        newItemLink = document.querySelector('.new_item_wrapper input[name="item_link"]'),
        addButton = document.querySelector('.new_item_wrapper .addItem'),
        cancelButton = document.querySelector('.new_item_wrapper .cancel');

    menuWrapper.onclick = function(e){
        e.preventDefault();
        if(!menuWrapper.querySelector('li.edit')){
            //изменить пункт
            if(e.target.classList.contains('edit_item')){
                var li = e.target.closest('li');
                newItemName.value = li.querySelector('.menu_item').textContent;
                newItemLink.value = li.querySelector('.menu_item').getAttribute('href');
                addButton.classList.add('edit'); addButton.textContent = 'Изменить';
                li.classList.add('edit');
                cancelButton.style.display = '';
            }

            //удалить пункт
            if(e.target.classList.contains('del_item')){    e.target.closest('li').remove();}
        }
    };

    //добавить пункт
    addButton.onclick = function(e){
        e.preventDefault();

        if(this.classList.contains('edit')){
            var editItem = menuWrapper.querySelector('li.edit .menu_item');
            editItem.textContent = newItemName.value;
            editItem.href = newItemLink.value;
            cancelButton.click();
        }
        else{
            var id = guidGenerator();
            if(!menuWrapper.querySelector('ul')){ menuWrapper.insertAdjacentHTML('beforeEnd','<ul></ul>');}
            menuWrapper.querySelector('ul').insertAdjacentHTML('beforeEnd',
                '<li>' +
                    '<a class="menu_item" id="'+id+'" draggable="true" href="'+newItemLink.value+'">'+newItemName.value+'</a>' +
                    '<a class="move_in" href="#" style="display: none"> [вложить]</a>'+
                    '<a class="edit_item" href="#"> [ред.]</a>'+
                    '<a class="del_item" href="#"> [удал.]</a>'+
                    '</li>');
            newItemName.value = ''; newItemLink.value = '';
        }
    };

    //отмена изменений
    cancelButton.onclick = function(e){
        e.preventDefault();
        this.style.display = 'none';
        menuWrapper.querySelector('li.edit').classList.remove('edit');
        addButton.classList.remove('edit');
        addButton.textContent = 'Добавить';
        newItemName.value = '';
        newItemLink.value = '';
    };

    //выбор страницы
    selectPageLink(document.querySelector('.selectPage'), function(selectedItem){
        newItemName.value = selectedItem.textContent;
        newItemLink.value = selectedItem.getAttribute('data-url');
    });

    //перетаскивание пунктов
    menuWrapper.ondragstart = function(e){
        if(e.target.getAttribute('draggable') && e.target.classList.contains('menu_item')){
            menuWrapper.querySelectorAll('.move_in').forEach(function(moveInElement){ moveInElement.style.display = '';});
            menuWrapper.querySelectorAll('.edit_item, .del_item').forEach(function(otherElement){ otherElement.style.display = 'none';});
            e.dataTransfer.setData("element", e.target.id);
        }
        else{ return false;}
    };
    menuWrapper.ondragenter = function(e){e.preventDefault();};
    menuWrapper.ondragover  = function(e){e.preventDefault();};
    menuWrapper.ondrop = function(e){
        //e.target - куда притащили
        e.preventDefault();
        var currentLinkId = e.dataTransfer.getData('element');
        var currentLinkLi = document.getElementById(currentLinkId).closest('li');

        if(e.target.classList.contains('menu_item')){
            var newParentId = e.target.id;
            if(newParentId!=currentLinkId){
                e.target.closest('li').before(currentLinkLi);
            }
        }
        else if(e.target.classList.contains('move_in')){
            var targetLi = e.target.closest('li');
            var nearestUl = targetLi.querySelector('ul');
            if(nearestUl){
                nearestUl.appendChild(currentLinkLi);
            }
            else{
                var newUl = document.createElement('ul');
                targetLi.appendChild(newUl);
                newUl.appendChild(currentLinkLi);
            }
        }

        menuWrapper.querySelectorAll('.move_in').forEach(function(moveInElement){ moveInElement.style.display = 'none';});
        menuWrapper.querySelectorAll('.edit_item, .del_item').forEach(function(otherElement){ otherElement.style.display = '';});
    };

    function formMenuArray(parentEl){
        var result = [];
        parentEl.childNodes.forEach(function(node){
            if(node.tagName == 'UL'){
                result = formMenuArray(node);
            }
            else if(node.tagName == 'LI'){
                var link = node.querySelector('.menu_item');
                var itemObj = {
                    id: link.id,
                    name: link.textContent,
                    href: link.getAttribute('href'),
                    children: []
                };
                itemObj.children = formMenuArray(node);
                result.push(itemObj);
            }
        });
        return result;
    }

    document.getElementById('form').onsubmit = function(e){
        this.querySelector('textarea[name="structure"]').textContent = JSON.stringify(formMenuArray(menuWrapper));
    }

});
