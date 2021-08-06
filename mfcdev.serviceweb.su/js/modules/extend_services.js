document.addEventListener('DOMContentLoaded', function(){
    if(site.method == 'view'){
        var lot = document.querySelector('select[name="lot"]');
        var amount = document.querySelector('select[name="amount"]');
        var priceWrp = document.getElementById('price');
        if(lot && amount){
            lot.onchange = amount.onchange = function(){
                if(lot.value && amount.value){
                    var price = Number(lot.selectedOptions[0].getAttribute('data-price')) * Number(amount.value);
                    if(price){
                        priceWrp.textContent = price + ' рублей';
                        priceWrp.classList.add('filled');
                    }
                }
            }
        }
    }
});