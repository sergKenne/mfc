var preloader = false;
function setXHR(url,data,type,callback,xhr){
    if(!xhr){ xhr = new XMLHttpRequest();}
    xhr.open(type,url);
    if(url.indexOf('tpl=1')==-1){xhr.setRequestHeader('Accept', 'text/json');}
    xhr.send(data);
    if(preloader) {preloader.style.display = '';}
    xhr.onreadystatechange = function(){
        if(this.readyState == 4 /*&& this.status == 200*/){
            if(preloader) {preloader.style.display = 'none';}
            var headers = getXHRHeaders(xhr.getAllResponseHeaders());
            if(headers['content-type'].indexOf('text/json')>-1){
                try {callback(JSON.parse(this.responseText));}
                catch(f){ callback(this.responseText);}
            }
            else{callback(this.responseText);}
        }
    };
    return xhr;
}

function getXHRHeaders(headers){
    var resultHeaders = {};
    headers.split('\r\n').forEach(function(item){
        var splitted = item.split(': ');
        resultHeaders[splitted[0]] = splitted[1];
    });
    return resultHeaders;
}

function getGET(url){
    if(!url){ url = location;}
    else { url = new URL(url);}

    var splitted = url.search.substring(1).split('&'), result = {};
    for(var i=0; i<splitted.length; i++){
        var element = splitted[i].split('=');
        if(!result[element[0]]) {result[element[0]] = Array();}
        result[element[0]].push(element[1]);
    }
    return result;
}

function modalSuccess(wrapper, text){
    var oldHtml = wrapper.innerHTML;
    wrapper.innerHTML = '<div class="modal__card modal__card--success">\n' +
        '               <div class="modal__success">\n' +
        '                    <img class="modal__success-img" src="/img/success.svg" alt="">\n' +
        '                    <p class="modal__success-description">'+text+'</p>\n' +
        '               </div>\n' +
        '            </div>';
    setTimeout(function(){wrapper.innerHTML = oldHtml; wrapper.closest('.modal').style.display = 'none';}, 2000);
}