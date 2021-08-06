/**
 * Created by Stardisk on 28.02.20.
 */

document.addEventListener('DOMContentLoaded', function(){
    if(site.method == 'add' || site.method == 'edit'){
        //задать источник данных странице
        var guideId = document.querySelector('select[name="page[source_guide]"]'), objectId = document.querySelector('select[name="page[source_object]"]');
        if(guideId){
            document.getElementById('search_source').onkeydown = function(e){
                if(e.keyCode == 13){
                    e.preventDefault();
                    if(this.value.length > 2 && guideId.value!=0){
                        setXHR('/admin/guides/list_items/'+guideId.value+'/?search='+this.value,false,'GET',function(response){
                            if(response['data']){
                                renderSelectOptions(response['data'], objectId);
                            }
                        });
                    }
                }
            };

            var newObjectButton = document.querySelector('.addNewObject');
            newObjectButton.onclick = function(e){
                e.preventDefault();
                if(guideId.value==0){ alert('Сначала выберите справочник');}
                else{
                    setXHR('/admin/guides/add_item_modal/'+guideId.value+'/?tpl=1', false, 'GET', function(response){
                        showModal(newObjectButton.getAttribute('data-title'), response, false, function(modal){
                            var formInModal = modal.querySelector('form');
                            formInModal.onsubmit = function(e){
                                e.preventDefault();
                                tinymce.triggerSave();
                                setXHR(formInModal.action, new FormData(formInModal), 'POST', function(response){
                                    if(response['success']){
                                        formInModal.reset();
                                        objectId.insertAdjacentHTML('beforeEnd','<option value="'+response['object']['id']+'">'+response['object']['name']+'</option>');
                                        objectId.value = response['object']['id'];
                                        $(objectId).multiselect('rebuild');
                                        $(modal).modal('toggle');
                                    }
                                });
                            };
                        });
                    });
                }
            }
        }
    }

    if(site.method == 'add_field' || site.method == 'edit_field'){
        var selectListItems = document.querySelectorAll('.selectListItems'),
            selectGuide = document.querySelectorAll('.selectGuide');

        //добавление элемента списка
        document.querySelector('.addAnotherListItem').onclick = function(e){
            e.preventDefault();
            var newListInput = this.parentNode.querySelector('.listItemWrp').cloneNode(true);
            this.insertAdjacentHTML('beforeBegin', newListInput.outerHTML);
        };

        //удаление элемента списка
        selectListItems[1].onclick = function(e){
            e.preventDefault();
            if(e.target.classList.contains('delListItem')){e.target.closest('p').remove(); }
        };

        document.querySelector('select[name*="field_type"]').onchange = function(e){
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

        selectPageLink(document.querySelector('.selectPage'), function(selectedItem){
            document.querySelector('.selectPage').textContent = selectedItem.textContent;
            document.querySelector('input[name="pageId"]').value = selectedItem.getAttribute('data-id');
        });
    }
});