document.addEventListener('DOMContentLoaded', function(){
    if(site.method == 'lists'){
        document.querySelectorAll('.announce__item').forEach(function(notice){
            notice.onclick = function(e){
                e.preventDefault();
                setXHR('/notices/view/'+notice.getAttribute('data-id'), false, 'GET', function(response){
                    if(response){
                        var modal = document.getElementById('modal-send-js');
                        modal.querySelector('.modal__card-title').textContent = response['title'];
                        modal.querySelector('.modal__card-text').innerHTML = response['content'];
                        modal.querySelector('.modal__card-date').textContent = response['date'];
                        $("#modal-send-js").slideDown();
                    }
                });
            };
        });
    }
});