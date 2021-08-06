/**
 * Created by Stardisk on 02.04.20.
 */

document.addEventListener('DOMContentLoaded',function(){

    if(site.module == 'settings' && site.method == 'lists'){
        document.querySelectorAll('input').forEach(function(item){
            if(item.type == 'text'){
                item.onblur = function(){
                    var data = new FormData();
                    data.append('name', item.name);
                    data.append('value', item.value);
                    setXHR('/admin/settings/saveSetting', data, 'POST', function(response){
                        if(response['success']){
                            notify(false,'Настройка изменена', 'success');
                        }
                    });
                }
            }

            if(item.type == 'checkbox'){
                item.onclick = function(){
                    var data = new FormData();
                    data.append('name', item.name);
                    if(this.checked) { data.append('value', 1);}
                    else { data.append('value', 0);}

                    setXHR('/admin/settings/saveSetting', data, 'POST', function(response){
                        if(response['success']){
                            notify(false,'Настройка изменена', 'success');
                        }
                    });
                }
            }
        });
    }
});