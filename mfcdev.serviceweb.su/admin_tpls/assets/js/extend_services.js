document.addEventListener('DOMContentLoaded', function(){

    if(site.method == 'add' || site.method == 'edit'){
        var folderWrp = document.querySelector('.innerServicesWrp');
        var standardWrp = document.querySelector('.standardFieldsWrp');

        document.querySelector('input[name="is_folder"]').onclick = function(){
            if(this.checked){ folderWrp.style.display = 'block'; standardWrp.style.display = 'none';}
            else{ folderWrp.style.display = 'none'; standardWrp.style.display = 'block';}
        };

        var formInModal;
        setInterval(function(){
            formInModal = document.querySelector('.modal form[action*="extend_services"]');
            if(formInModal && !formInModal.querySelector('input[name="is_inner"]')){
                formInModal.insertAdjacentHTML('beforeEnd', '<input type="hidden" name="is_inner" value="1">');
            }
        }, 2000);
    }

});