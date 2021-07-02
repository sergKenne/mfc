var preloader = false;
function setXHR(url,data,type,callback,hidePreloader){
    var xhr = new XMLHttpRequest();
    xhr.open(type,url);
    if(url.indexOf('tpl=1')==-1){xhr.setRequestHeader('Accept', 'text/json');}
    xhr.send(data);
    if(preloader && !hidePreloader) {preloader.style.display = '';}
    xhr.onreadystatechange = function(){
        if(this.readyState == 4 /*&& this.status == 200*/){
            if(preloader) {preloader.style.display = 'none';}
            var headers = getXHRHeaders(xhr.getAllResponseHeaders());
            if(headers['content-type'].indexOf('text/json')>-1){
                callback(JSON.parse(this.responseText));
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