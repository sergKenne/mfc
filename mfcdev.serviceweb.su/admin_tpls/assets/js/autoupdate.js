/**
 * Created by Stardisk on 23.04.20.
 */

document.addEventListener('DOMContentLoaded', function(){
    document.querySelector('.checkUpdate').onclick = function(e){
        e.preventDefault();
        setXHR('/autoupdate/loadUpdate',false, 'GET', function(response){
            var updateWrapper = document.querySelector('.updateWrapper');
            updateWrapper.style.display = '';

            if(response['new']){
                updateWrapper.querySelector('.version').textContent = response['version'];
            }
            else{
                updateWrapper.textContent = 'Обновлений нет!';
            }
        });
    }
});