document.addEventListener('DOMContentLoaded', function(){
    if(site.method == 'view'){
        document.querySelector('.jobs__publish-btn').onclick = function(e){
            e.preventDefault();
            $('#modal-send-js').slideDown();
        }

        document.getElementById('modal-send-js').querySelector('form').onsubmit = function(e){
            e.preventDefault();
            setXHR(this.action, new FormData(this), 'POST', function(response){
                if(response['success']){
                    modalSuccess(document.getElementById('modal-send-js').querySelector('.modal__card-inner'), response['info']);
                }
            });
        }
    }
});