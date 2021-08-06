/**
 * Created by Александров Олег on 08.07.19.
 */

document.addEventListener('DOMContentLoaded',function(){

});

var parsedLocationPath = location.pathname.split('/');
site.id = parsedLocationPath[3];

function timestampToDate(timestamp){
    var date = new Date(timestamp);
    return date.getFullYear()+'-'+addZeros((date.getMonth()+1),2)+'-'+addZeros(date.getDate(),2);
}
//конвертировать дату из ГГГГ-ММ-ДД в ДД.ММ.ГГГГ
function convertDate(dateString){
    var date = new Date(dateString);
    return addZeros(date.getDate(),2) + '.' + addZeros(date.getMonth() + 1, 2) + '.' + date.getFullYear();
}

function addZeros(number, numberLengthWithZeros){
    number = (number).toString();                                                       //преобразуем число в строку
    while((numberLengthWithZeros - number.length)>0){   number = "0" + number;}         //пока желаемая длина числа с нулями минус длина самого числа больше 0 - добавлять к числу нули
    return number;                                                                      //вернуть число с нулями
}

var preloader = document.querySelector('.global_preloader');
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

function notify(title, text, type){
    alert(text);

    /*if(!title) {title = 'Системное сообщение';}
    var noticeData = {
        title: title,
        message: text
    };

    switch(type){
        case 'success': $.growl.notice(noticeData); break;
        case 'warning': $.growl.warning(noticeData); break;
        case 'error': $.growl.error(noticeData); break;
        default: $.growl(noticeData);
    }*/
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

function setGET(objectFromGetGET){
    var getString = '';
    for(var key in objectFromGetGET){
        if(objectFromGetGET[key].length == 1){
            getString += (key + '=' + objectFromGetGET[key][0] + '&');
        }
    }
    return getString.substring(0,getString.length-1);
}

function downloadByLink(linkToFile,saveAs){
    var link = document.createElement('a');
    link.href = linkToFile;
    link.download = saveAs;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function validateForm(form){
    var allFilled = true;
    form.querySelectorAll('input[required]').forEach(function(input){
        if(!input.value){ input.classList.add('notFilled'); allFilled = false;}
        else { input.classList.remove('notFilled');}
    });
    return allFilled;
}

function callFunctionFromOtherScript(scriptfile, functionName, param1, param2, param3, param4, param5){
    var catalogScript = document.createElement('script');
    catalogScript.src = '/js/modules/'+scriptfile;
    document.body.appendChild(catalogScript);
    var checkLoading = setInterval(function(){
        try{ window[functionName](param1, param2, param3, param4, param5); clearInterval(checkLoading);}
        catch(err){console.log('function not found');}
    },1000);
}

function rus_to_latin ( str ) {

    var ru = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
        'е': 'e', 'ё': 'e', 'ж': 'j', 'з': 'z', 'и': 'i',
        'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
        'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u',
        'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch', 'ш': 'sh',
        'щ': 'shch', 'ы': 'y', 'э': 'e', 'ю': 'u', 'я': 'ya',
        ' ': '_'
    }, n_str = [];

    str = str.replace(/[ъь]+/g, '').replace(/й/g, 'i');

    for ( var i = 0; i < str.length; ++i ) {
        n_str.push(
            ru[ str[i] ]
                || ru[ str[i].toLowerCase() ] == undefined && str[i]
                || ru[ str[i].toLowerCase() ].replace(/^(.)/, function ( match ) { return match.toUpperCase() })
        );
    }

    return n_str.join('');
}