var fieldsCount, fieldWrp, clonedField;
document.addEventListener('DOMContentLoaded',function(){
    guidesInit();
});

function insertNewField(element){
    var lastInserted;
    if(!element){
        fieldsCount++;
        var clonedFieldHtml = clonedField.outerHTML.replace(/%id%/g,String(fieldsCount));
        document.getElementById('fields').insertAdjacentHTML('beforeEnd', clonedFieldHtml);
        lastInserted = document.querySelector('.fieldWrp[data-count="'+fieldsCount+'"]');
    }
    else{
        lastInserted = element;
    }

    var selectListItems = lastInserted.querySelectorAll('.selectListItems'),
        selectGuide = lastInserted.querySelectorAll('.selectGuide');

    //добавление элемента списка
    lastInserted.querySelector('.addAnotherListItem').onclick = function(e){
        e.preventDefault();
        var newListInput = this.parentNode.querySelector('.listItemWrp').cloneNode(true);
        this.insertAdjacentHTML('beforeBegin', newListInput.outerHTML);
    };

    //удаление элемента списка
    selectListItems[1].onclick = function(e){
        e.preventDefault();
        if(e.target.classList.contains('delListItem')){e.target.closest('p').remove(); }
    };

    //удаление поля
    lastInserted.querySelector('.delField').onclick = function(e){
        e.preventDefault();
        this.closest('.fieldWrp').remove();
    };

    //показать/скрыть дополнительные поля
    lastInserted.querySelector('select[name*="field_type"]').onchange = function(e){
        switch(this.value){
            case 'list':{
                selectListItems.forEach(function(item){ item.style.display = '';});
                selectGuide.forEach(function(item){ item.style.display = 'none';});
                break;
            }
            case 'guide':{
                selectGuide.forEach(function(item){ item.style.display = '';});
                selectListItems.forEach(function(item){ item.style.display = 'none';});
                break;
            }
            default:{
                selectListItems.forEach(function(item){ item.style.display = 'none';});
                selectGuide.forEach(function(item){ item.style.display = 'none';});
            }
        }
    }
}

function guidesInit(){
    if(site.method == 'add' || site.method == 'edit'){
        fieldWrp = document.querySelector('.fieldWrp');
        clonedField = fieldWrp.cloneNode(true);
        fieldWrp.remove();

        document.getElementById('addfield').onclick = function(e){
            e.preventDefault();
            insertNewField();
        };
    }

    if(site.method == 'add'){
        fieldsCount = -1;
        insertNewField(); //отрисовать начальное поле
    }

    if(site.method == 'edit'){
        var existedFields = document.querySelectorAll('.fieldWrp');
        fieldsCount = existedFields.length - 1;
        existedFields.forEach(function(item){ insertNewField(item);});
    }

    var form = document.getElementById('form');
    if(form && !form.classList.contains('no-ajax')){
        document.getElementById('form').onsubmit = function(e){
            e.preventDefault();
            var data = new FormData(this);
            setXHR(this.action,data,'POST',function(response){
                if(response['success']){
                    notify(false,response['info'],'success');
                    setTimeout(function(){
                        if(response['redirect']){ location.href = response['redirect'];}
                        else {location.href = '/admin/';}
                    },1000);
                }
                else{ notify(false, response['info'],'error');}
            });
        }
    }
}