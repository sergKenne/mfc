document.addEventListener('DOMContentLoaded', function(){
    if(site.module === 'services' && site.method === 'lists'){
        document.querySelector('input[type="reset"]').onclick = function(){ location.href = '/services';}
    }

    if(site.module === 'services' && site.method === 'check_status'){
        var infoModal = document.querySelector('.applyInfoModal');
        var shortRes = document.querySelector('.applyResult');
        document.querySelector('.send-code').onclick = function(e){
            e.preventDefault();
            var number = document.getElementById('number').value;
            shortRes.textContent = '';
            setXHR('/services/check_status?number='+number, false, 'GET', function(response){
                if(response['status']){
                    infoModal.querySelector('#applyNumber').textContent = number;
                    infoModal.querySelector('#applyStatus').textContent = response['status'];
                    infoModal.querySelector('#applyStartDate').textContent = response['created'];
                    infoModal.querySelector('#applyEndDate').textContent = response['planeFinish'];
                    $(infoModal).slideDown();
                }
                else{
                    shortRes.textContent = 'Заявка не найдена';
                }
            });
        }
    }
});