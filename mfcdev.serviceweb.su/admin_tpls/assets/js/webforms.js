document.addEventListener('DOMContentLoaded',function(){
    const myScript = document.createElement('script');
    myScript.src = '/admin_tpls/assets/js/guides.js';
    document.querySelector('body').appendChild(myScript);
    setTimeout(function(){ guidesInit();}, 500);
});