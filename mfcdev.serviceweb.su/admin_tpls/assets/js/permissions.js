/**
 * Created by Stardisk on 22.06.20.
 */
document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('newUserType').onsubmit = function(e){
        e.preventDefault();
        setXHR(this.action, new FormData(this), 'POST', function(response){
            if(response['success']){ location.reload();}
            else{ alert(response['info']);}
        })
    };

    document.querySelectorAll('.delType').forEach(function(item){
        item.onclick = function(e){
            e.preventDefault();
            setXHR(this.href, false, 'GET', function(response){
                if(response['success']){ location.reload();}
                else{ alert(response['info']);}
            });
        }
    });

    document.querySelectorAll('input[type="checkbox"]').forEach(function(item){
        item.onclick = function(e){
            var value = (this.checked) ? 1:0;
            var module = this.getAttribute('data-module'),
                method = this.getAttribute('data-method'),
                group = this.getAttribute('data-group');

            var data = new FormData();
            data.append('module', module);
            data.append('method', method);
            data.append('group', group);
            data.append('value', value);
            setXHR('/admin/permissions/modifyPermission', data, 'POST', function(response){
                if(!response['success']){
                    item.checked = (value) ? false : true;
                    alert(response['info']);
                }
            });
        }
    });
});