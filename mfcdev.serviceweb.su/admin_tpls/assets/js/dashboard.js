$(function() {
    $('#myModal').on("show.bs.modal", function (e) {
         $("#comment-comment").val($(e.relatedTarget).data('comment'));
         $("#comment-id").val($(e.relatedTarget).data('id'));
    });
});

// Prevent bootstrap dialog from blocking focusin
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});


document.addEventListener('DOMContentLoaded', function(){
    // Prevent Bootstrap dialog from blocking focusin
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root").length) {
            e.stopImmediatePropagation();
        }
    });



    // подрубаем мультиселект
    $('select.form-control').multiselect();

    addEditGuideItemBtnsInit();

    //удаление файлов
    document.querySelectorAll('.delFile').forEach(function(item){
        item.onclick = function(e){
            e.preventDefault();
            if(confirm('Удалить файл? Восстановление будет невозможно!')){
                var data = new FormData();
                data.append('fileId', item.getAttribute('data-id'));
                data.append('guideId', this.getAttribute('data-guide'));
                data.append('itemId',this.getAttribute('data-item'));
                data.append('field', item.getAttribute('data-field'));

                setXHR('/files/delFromObject',data,'POST',function(response){
                    item.closest('.fileWrapper').remove();
                    if(response['info']){
                        alert(response['info']);
                    }
                });
            }
        }
    });

    if(site.module == 'pages' && site.method == 'lists'){
        pageTreeInit();
        document.getElementById('pagesListCurrentLang').onchange = function(e){
            location.href = '/admin/pages/lists/0/'+this.value;
        }
    }

    //подгрузка файлов шаблона
    var templateFolder = document.querySelector('select[name="template[folder]"]');
    var templateFile = document.querySelector('select[name="template[file]"]');
    if(templateFolder && templateFile){
        templateFolder.onchange = function(){
            setXHR('/admin/pages/phtmlFiles/'+templateFolder.value, false, 'GET', function(response){
                var html = '';
                response['phtml'].forEach(function(item){
                    html += '<option value="'+item+'">'+item+'</option>';
                });
                templateFile.innerHTML = html;
            });
        }
    }

    addPreviewsToFileInput();

    var objectLangSwitch;
    if(objectLangSwitch = document.getElementById('objectLangSwitch')){
        objectLangSwitch.onchange = function(){
            if(!location.search){ location.href = location.href + '?lang='+this.value;}
            else{
                var getParams = getGET();
                getParams['lang'][0] = this.value;
                location.href = location.origin + location.pathname + '?' + setGET(getParams);
            }
        }
    }

    var createPageCheck;
    if(createPageCheck = document.getElementById('input_create_page')){
        var pageTab = document.querySelector('.pageTab');
        createPageCheck.onclick = function(){
            if(this.checked){ pageTab.style.display = '';}
            else{ pageTab.style.display = 'none';}
        }
    }

    if(document.querySelector('.htmlTextArea')){
        tinymce.init({
            selector: '.htmlTextArea',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            plugins: 'advlist autolink lists charmap print preview anchor visualblocks searchreplace code fullscreen insertdatetime media table contextmenu paste link image',
            menubar: 'edit insert view format table tools',
            language: 'ru',
            font_formats: "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Circe=circe; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; PTSans=PT Sans; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats",
            content_style: "@import url('/fonts/circe.fonts.css'); @import url('/fonts/ptsans.fonts.css')"
        });
    }

    //перетаскивание файлов внутри набора файлов
    document.querySelectorAll('.fileListWrapper').forEach(function(item){
        Sortable.create(item, {
            animation: 150,
            onEnd: function(e){
                var data = new FormData();
                document.querySelectorAll('.fileListWrapper .fileWrapper').forEach(function(item2){
                    data.append('fileId[]',item2.getAttribute('data-file-id'));
                });

                data.append('field', item.getAttribute('data-field'));
                data.append('guideId',site.guideId);
                data.append('itemId', site.editId);

                setXHR('/admin/guides/item_changeFileOrder/', data, 'POST', function(response){

                });
            }
        });
    });
});

//------------ управление полем типа "значение справочника" с видом таблицы
function renderNewTD(response, item){
    var newRowHtml = '<td><a class="edit_guide_item" data-field="'+item.getAttribute('data-field')+'" data-toggle="modal-ajax" data-title="Редактировать элемент справочника" data-what="edit_guide_item" href="/admin/guides/edit_item_modal/'+response['guideId']+'/'+response['object']['id']+'/?tpl=1">'+response['object']['name']+'</a></td>';
    var fieldName;
    for(fieldName in response['fields']){
        if(response['fields'][fieldName]['table'] == 1){
            if(response['previews'][fieldName]){
                newRowHtml += '<td><img width="50" height="50" src="'+response['previews'][fieldName]+'"></td>';
            }
            else{
                newRowHtml += '<td>'+response['object'][fieldName]+'</td>';
            }
        }
    }
    newRowHtml +=
        '<td>'+
            '<input style="display: none" type="checkbox" checked name="'+item.getAttribute('data-field')+'[]" value="'+response['object']['id']+'">'+
            '<a class="del_guide_item" href="/admin/guides/del_item/'+response['guideId']+'/'+response['object']['id']+'">[X]</a>'+
            '</td>';
    return newRowHtml;
}

function addEditGuideItem(item){
    setXHR(item.href, false, 'GET', function(response){
        showModal(item.getAttribute('data-title'), response, false, function(modal){
            var formInModal = modal.querySelector('form');
            if(formInModal){
                formInModal.onsubmit = function(e){
                    e.preventDefault();
                    tinymce.triggerSave();
                    setXHR(formInModal.action, new FormData(formInModal), 'POST', function(response){
                        formInModal.reset();
                        switch(item.getAttribute('data-what')){
                            case 'add_guide_item':{
                                var need_table = item.closest('.form-group').querySelector('table[data-field="'+item.getAttribute('data-field')+'"]');
                                need_table.querySelector('tbody').insertAdjacentHTML('beforeEnd', renderNewTD(response, item));
                                break;
                            }

                            case 'edit_guide_item':{
                                var needTR = item.closest('tr');
                                needTR.innerHTML = renderNewTD(response, item);
                                break;
                            }

                            default:{}
                        }
                        $(modal).modal('toggle');
                    });
                };

                modal.querySelectorAll('.delFile').forEach(function(delButton){
                    delButton.onclick = function(e){
                        e.preventDefault();
                        if(confirm('Удалить файл? Восстановление будет невозможно!')){
                            var data = new FormData();
                            data.append('fileId', delButton.getAttribute('data-id'));
                            data.append('guideId', modal.querySelector('form').getAttribute('data-guide'));
                            data.append('itemId', modal.querySelector('form').getAttribute('data-item'));
                            data.append('field', delButton.getAttribute('data-field'));

                            setXHR('/files/delFromObject',data,'POST',function(response){
                                delButton.closest('.fileWrapper').remove();
                                if(response['info']){
                                    alert(response['info']);
                                }
                            });
                        }
                    }
                });
            }
        });
    });
}

function addEditGuideItemBtnsInit(wrapper){
    if(!wrapper){ wrapper = document;}
    wrapper.querySelectorAll('[data-what="add_guide_item"]').forEach(function(item){
        item.onclick = function(e){
            e.preventDefault();
            addEditGuideItem(item);
        }
    });

    wrapper.querySelectorAll('table.guideAsTable').forEach(function(table){
        table.onclick = function(e){
            //редактирование
            if(e.target.classList.contains('edit_guide_item')){
                e.preventDefault();
                addEditGuideItem(e.target);
            }

            //удаление
            if(e.target.classList.contains('del_guide_item') || e.target.closest('.del_guide_item')){
                e.preventDefault();
                var href = (e.target.href) ? e.target.href : e.target.closest('.del_guide_item').href;
                if(confirm('Удалить элемент? Действие необратимо')){
                    setXHR(href,false,'GET',function(response){
                        e.target.closest('tr').remove();
                    });
                }
            }
        }
    });
}

//------------ END --------------

function changeItemNameFromInput() {
    document.getElementById("itemName").innerHTML = '«' + this.value + '»';
}

function renderSelectOptions(data, element){
    var html = '';
    data.forEach(function(item){
        html += '<option value="'+item.id+'">'+item.name+'</option>';
    });
    element.innerHTML = html;
    $(element).multiselect('rebuild');
}

function loadImageAsBlobURL(source, dest){
    var id = new Date().getTime();
    // chat.userImgBoxFiles[id] = source;
    var fileReader = new FileReader();                                                                              //создаем новый класс чтения файлов
    fileReader.readAsDataURL(source);                                                                               //считываем файл в него как base64
    fileReader.onloadend = function(){                                                                              //когда закончит считывать...
        var imgDOM = document.createElement('img');
        imgDOM.setAttribute('src',this.result);
        imgDOM.setAttribute('title','Кликните для удаления');
        imgDOM.className = 'imgClipboard';
        imgDOM.setAttribute('width', '100');
        var previouslyAddedImg, previouslyAddedSpan;
        if(previouslyAddedImg = dest.querySelector('.imgClipboard')){ previouslyAddedImg.remove(); }
        if(!(previouslyAddedSpan = document.querySelector('.imgClipboardSpan'))){
            dest.insertAdjacentHTML('beforeEnd','<span class="imgClipboardSpan">Новое изображение:</span>');
        }
        dest.appendChild(imgDOM);

        imgDOM.onclick = function(e){
            e.preventDefault();
            this.parentNode.querySelector('input[type="file"]').value = '';
            this.remove();
        }
    }
}

//добавить превьюшку к файловому инпуту
function addPreviewsToFileInput(target){
    if(!target){ target = document;}
    document.querySelectorAll('input[type="file"]').forEach(function(input){
        if(!input.hasAttribute('multiple')){
            input.onchange = function(e){
                loadImageAsBlobURL(input.files[0], input.parentNode);
            }
        }
    });
}

//модалка "выбрать страницу"
function selectPageLink(element, callback){
    element.onclick = function(e){
        e.preventDefault();
        setXHR('/admin/pages/lists_modal/?tpl=1', false, 'GET', function(response){
            showModal('Выбрать страницу', response, false, function(modal){
                pageTreeInit();
                modal.querySelectorAll('.lang-switch').forEach(function(langSwitch){
                    langSwitch.onclick = function(e){
                        e.preventDefault();
                        modal.querySelector('#currentLang').removeAttribute('id');
                        langSwitch.setAttribute('id','currentLang');
                        setXHR(langSwitch.href, false, 'GET', function(pages){
                            modal.querySelector('#listOfPages').innerHTML = renderPages(pages, true);
                            pageTreeInit();
                        });
                    }
                });

                modal.querySelector('#listOfPages').addEventListener('click', function(e){
                    e.preventDefault();
                    if(e.target.classList.contains('pageLink')){
                        if(callback){   callback(e.target);}
                        $(modal).modal('toggle');
                    }
                });
            });
        })
    };
}

function showModal(title, content, hideButtons, callback){
    var htmlModal =
        '<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 900px">'+
        '<div class="modal-content">'+
        '<div class="modal-header">'+
        '<h5 class="modal-title">'+title+'</h5>'+
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '</div>'+
        '<div class="modal-body">'+
        content+
        '</div>'+
        '%buttons%'+
        '</div>'+
        '</div>';

    if(!hideButtons){
        htmlModal = htmlModal.replace('%buttons%',
            '<div class="modal-footer">'+
                '<button type="button" class="btn btn-primary">Сохранить</button>'+
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>'+
                '</div>');
    }

    var modal = document.createElement('div');
    modal.setAttribute('class','modal fade');
    modal.setAttribute('role','dialog');
    modal.innerHTML = htmlModal;

    document.body.appendChild(modal);

    $(modal).modal();

    if(callback){
        callback(modal);
        modal.querySelector('.btn.btn-primary').onclick = function(){modal.querySelector('form').onsubmit(new Event('submit'))};
    }

    addPreviewsToFileInput(modal);
    feather.replace();

    var tinymces;

    $(modal).on('hide.bs.modal', function(){
        this.remove();
        var toRemove = tinymce.get().slice(tinymces);
        toRemove.forEach(function(item){
            tinymce.remove(item);
        })
    });

    $(modal).on('shown.bs.modal', function () {
        tinymces = tinymce.get().length;
        tinymce.init({
            selector: '.htmlTextArea',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            plugins: 'advlist autolink lists charmap print preview anchor visualblocks searchreplace code fullscreen insertdatetime media table contextmenu paste link image',
            menubar: 'edit insert view format table tools',
            language: 'ru',
            font_formats: "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Circe=circe; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; PTSans=PT Sans; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats",
            content_style: "@import url('/fonts/circe.fonts.css'); @import url('/fonts/ptsans.fonts.css')"
        });
    });

    if(modal.querySelector('select.form-control')){
        $(modal).find('select.form-control').multiselect();
    }

    addEditGuideItemBtnsInit(modal);
}

//развернуть деерво страниц
function pageTreeInit(){
    var listOfPages = document.getElementById('listOfPages');
    var currentLang;
    if(document.getElementById('currentLang')){
        currentLang = document.getElementById('currentLang').getAttribute('data-prefix');
    }
    else{
        currentLang = document.getElementById('pagesListCurrentLang').value;
    }
    listOfPages.onclick = function(e){
        if(e.target.classList.contains('expandSubPages')){
            e.preventDefault();
            setXHR('/admin/pages/lists/'+ e.target.getAttribute('data-id')+'/'+currentLang,false,'GET',function(response){
                if(response['data']){
                    e.target.closest('li').insertAdjacentHTML('beforeEnd', renderPages(response, true));
                }
                else{
                    alert('Нет подстраниц');
                }
                e.target.remove();
            });
        }
    };

    listOfPages.ondragstart = function(e){
        if(e.target.getAttribute('draggable') && e.target.classList.contains('pageLink')){
            e.dataTransfer.setData("element", e.target.getAttribute('data-id'));
        }
        else{ return false;}
    };
    listOfPages.ondragenter = function(e){e.preventDefault();};
    listOfPages.ondragover  = function(e){e.preventDefault();};
    listOfPages.ondrop = function(e){
        //e.target - куда притащили
        e.preventDefault();
        if(e.target.classList.contains('pageLink')){
            var currentLinkId = e.dataTransfer.getData('element');
            var newParentId = e.target.getAttribute('data-id');
            if(newParentId!=currentLinkId){
                setXHR('/admin/pages/transfer/'+currentLinkId+'/'+newParentId,false,'GET',function(response){
                    var oldLink = document.querySelector('.pageLink[data-id="'+currentLinkId+'"]');
                    var ul;
                    if(newParentId == 0){
                        ul = document.querySelector('ul.root');
                        ul.insertAdjacentHTML('beforeEnd', renderPages({data: [{id: oldLink.getAttribute('data-id'), name: oldLink.textContent}] }))
                    }
                    else{
                        if(ul = e.target.closest('li').querySelector('ul')){    ul.innerHTML = renderPages(response, false);}
                        else{   e.target.closest('li').insertAdjacentHTML('beforeEnd', renderPages(response,true));}
                    }
                    oldLink.closest('li').remove();
                });
            }
        }
    };
}

function renderPages(response, addUL){
    var html = '';
    if(addUL){ html += '<ul>';}
    response['data'].forEach(function(page){
        html +=
            '<li>' +
                '<a href="#" class="expandSubPages" data-id="'+page['id']+'" title="Развернуть подстраницы">[+]</a>'+
                '<a draggable="true" class="pageLink" data-url="/'+page['url']+'" data-id="'+page['id']+'" href="/admin/pages/edit/'+page['id']+'">'+page['name']+'</a>'+
                '</li>'
    });
    if(addUL) {html += '</ul>';}
    return html;
}

function guidGenerator() {
    var S4 = function() {
        return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    };
    return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}


(function () {
    feather.replace();
}());
