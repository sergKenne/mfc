document.addEventListener('DOMContentLoaded', function(){
    if(site.method == 'edit'){
        document.getElementById('group').onchange = function(){
            setXHR('/admin/services/loadCategories/'+this.value, false, 'GET', function(response){
                var html = '<option value="0">Не выбрано</option>';
                if(response['data']){
                    response['data'].forEach(function(item){
                        html+= '<option value="'+item['id']+'">'+item['name']+'</option>';
                    });
                }
                document.querySelector('select[name="category"]').innerHTML = html;
            });
        }
    }
});