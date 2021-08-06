$(document).ready(function(){
  
  $(".input_phone").mask("+7 (999) 999-99-99");
  $(".input_snils").mask("999-999-999 99");
  $(".input_date").mask("99.99.9999");
  
    $("#webcam-js").click(function(){
      $("#modal-webcam-js").slideDown();
        Webcam.set({
          width: 480,
          height: 320,
          image_format: 'jpeg',
          jpeg_quality: 100,
          dest_width: 640,
          dest_height: 480
        });
		    Webcam.attach( '#my_camera' );
      
        return false;
    });
  
  $('#file_upload_btn').click(function(){
    $("#file_upload_input").click();
    return false;
  });
  $("#file_upload_input").change(function(){
    $('#file_upload_str').val($(this)[0].files[0].name);
    $("#file_upload_str").change();
    $("#webcam_upload_input").val('');
  });
  
     $("#cancel-webcam-js").click(function(){
        $("#modal-webcam-js").slideUp();
     });
  
    $("#webcam_button").click(function(){
      Webcam.snap( function(data_uri) {
				$("#webcam_upload_input").val(data_uri);
        $("#file_upload_input").val(null);
        $("#file_upload_str").val('webcamera_photo.jpg');
        $("#file_upload_str").change();
        $("#cancel-webcam-js").click();
			});
      Webcam.reset();
    });
  
 			$(".select2").select2({"language": {"noResults": function(){return "Не найдено";}}});
  
  $(".f_required ").change(function(){
    $(this).parents('.user__form-item').find('.f_check').remove();
    $(this).removeClass('red-bd');
    })
  
   $(".user__inner-form .user__inner-btn--form").each(function(ind, btn){
     
      const $this = $(btn);
      $this.click(function(){
        return false;
       

      })
    })
  
  $('select[name="field[53]"]').change(function(){
    var id = $(':selected', this).attr('value');
    //alert(id); 
    $.ajax({
        type: 'GET',
        url: '/lk/get_ajax_items/55/'+id,
        dataType: "json",
        success: function (data) {
          $('select[name="field[55]"]').empty();
          if (data.length>1){
            $('select[name="field[55]"]').append('<option value="">&nbsp;</option>');
          }
          $.each(data, function(index, element) {
            
              $('select[name="field[55]"]').append('<option value="'+element.id+'" data-extra="'+element.extra+'">'+element.name+'</option>');
          });
          $('select[name="field[55]"]').change();
        }
    });

  });
  
  $('select[name="field[55]"]').change(function(){
     $('select[name="field[56]"]').empty();
    var id = $(':selected', this).attr('value');
    var municipal = $(':selected', this).attr('data-extra');
     $('select[name="field[53]"]').val(municipal).trigger('change.select2');
    //alert(id); 
    $.ajax({
        type: 'GET',
        url: '/lk/get_ajax_items/56/'+id,
        dataType: "json",
        success: function (data) {
          $('select[name="field[56]"]').empty();
          if (data.length>1){
            $('select[name="field[56]"]').append('<option value="">&nbsp;</option>');
          }
          $.each(data, function(index, element) {
            
              $('select[name="field[56]"]').append('<option value="'+element.id+'">'+element.name+'</option>');
          });
        }
    });

  });
  
  
   $(".user__inner-form .user__inner-btn--form_next").each(function(ind, btn){
      
      const $this = $(btn);
      $this.click(function(){
         $('.level-js .f_check').remove();
        if (check_req_fields()){
          $(`.user__list-number .user__item-number:nth-child(${ind+2})`).addClass("user__item-number--success");
          $(".user__form-level").removeClass("level-js");
          $(`.user__form-level:nth-child(${ind+2})`).addClass("level-js");
          
        }
  
      })
    })
  
  
  $('.end_user_button').click(function(){
    if (check_req_fields()){
    var formData = new FormData($("#form_gen")[0]);
      $.ajax({
          url: "/lk/save_form",
          type: "POST",
          data : formData,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function(data){
            $('#modal-success-js').find('.modal__success-description').html(data.message);
            $('#modal-success-js').show();
            if (data.redirect)
            setTimeout(function(){
                location.href =data.redirect;
            }, 3000);
          }
      });
    }
    return false;
  });
  
      //LEVEL-js

    /*$(".user__inner-form .user__inner-btn--form").each(function(ind, btn){
      console.log(btn);
      const $this = $(btn);
      $this.click(function(){
        $(`.user__list-number .user__item-number:nth-child(${ind+2})`).addClass("user__item-number--success");
        $(".user__form-level").removeClass("level-js");
        $(`.user__form-level:nth-child(${ind+2})`).addClass("level-js");

      })
    })*/

   
  $('.user__card-icon').click(function(){
    if($('.edit_btn').css('display')=='none'){
      $('.user__card-strong').each(function() {
        val =$( this ).html();
        $( this ).html('<input value="'+val+'" class="user__form-input edit_input">');
      });
      $('.edit_btn').show();
    }
  });
  
  $('.edit_btn').click(function(){
    $(this).hide();
     $('.edit_input').each(function() {
    $(this).parent().html($(this).val());
     });
    return false;
  });
  
  

 $('.input_cyr').on('input', function () {
    const str = this.value
     this.value= str.replace(/[^а-яА-ЯёЁ ]/g, '')
}) 
  $('.input_pass').on('input', function () {
    const str = this.value
     this.value= str.replace(/[^a-zA-Z0-9 ]/g, '')
}) 
  $('.input_mail').on('input', function () {
    const str = this.value
     this.value= str.replace(/[^a-zA-Z0-9@_\-. ]/g, '')
}) 
  
  

});

var ticket = '';
var docs_count = 1;

function getCalendarModule(date=''){
  
  $("#calend_date").val('');
  var formData = new FormData($("#form_gen")[0]);
  formData.append('docs_count', docs_count);
  formData.append('ticket', ticket);
      $.ajax({
          url: "/lk/get_calendar/"+date,
          type: "POST",
          dataType: 'JSON',
          data : formData,
          processData: false,
          contentType: false,
          success: function(data){

            generateCalendar(data.date_m, data.date_y, data.available_dates,data.date);
            $('.calender__list--time').html('');
            $.each(data.available_time, function( index, value ) {
              $('.calender__list--time').append('<button class="calender__list-item calender__list-item--time" onclick="time_select(this,'+index+',\''+data.date+'\',\''+value.start_time+'\');return false;">'+value.start_time+'</button>');
            });
            
            ticket = data.ticket;
            
            

          }
      });
  
}
  
  function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
           return false;
        }else{
           return true;
        }
      }

function change_date(date){
  getCalendarModule(date);
}


function check_req_fields(){
   is_validated=true;
         $('.level-js .f_check').remove();
        $('.level-js .f_required').each(function(){
            if($(this).is("select")) {
              if ($(':selected', this).attr('value')==''){
                $(this).addClass('red-bd');
                $(this).parent().after('<div class="f_check red" style="text-align:right;">Это поле обязательно для заполнения</div>');
                is_validated = false;
              }
            }else if ($(this).attr('type')=='checkbox'){
              if ($(this).is(':checked')==false){
                $(this).addClass('red-bd');
                $(this).parent().after('<div class="f_check red" style="text-align:right;">Это поле обязательно для заполнения</div>');
                is_validated = false;
              }  
            }else if ($(this).attr('type')=='email'){
              if ($(this).val() === ""){
                $(this).addClass('red-bd');
                $(this).parent().after('<div class="f_check red" style="text-align:right;">Это поле обязательно для заполнения</div>');
                is_validated = false;
              }
            }else if ($(this).val() === ""){
              $(this).addClass('red-bd');
              $(this).parent().after('<div class="f_check red" style="text-align:right;">Это поле обязательно для заполнения</div>');
              is_validated = false;
            }
        });
        $('.level-js .input_mail').each(function(){
          console.log($(this).val());
          if ($(this).val()!=''){
            if (IsEmail($(this).val())==false){
                  $(this).addClass('red-bd');
                  $(this).parent().after('<div class="f_check red" style="text-align:right;">Это поле заполнено не корректно</div>');
                  is_validated = false;
                }  
          }
        }); 
  return is_validated;
}

function submit_reservation(){

 $('.level-js .f_check').remove();
 if (check_req_fields()){
    var formData = new FormData($("#form_gen")[0]);
    formData.append('docs_count', docs_count);
    formData.append('ticket', ticket);
    $.ajax({
            url: "/lk/reserve/",
            type: "POST",
            data : formData,
            processData: false,
            contentType: false,
            success: function(data){
              $('.user__inner-left').html(data);
              window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }); 
  }
}
function time_select(el,time,date,timestr){
  $('.calender__list--time button').removeClass('calender__list-item--active');
  $(el).addClass('calender__list-item--active');
  //alert(time);
  $("#calend_date").val(time);
  //var formData = new FormData($("#form_gen")[0]);
  form_data = $("#form_gen").serializeArray();
  $.each(form_data, function( index, field ) {
    $("#res_"+field.name.replace(/[^0-9]/g,'')).html(field.value);
  });
  
  $('#res_80').html($("select[name='field[80]'] option:selected" ).text());
  $('#res_54').html($("select[name='field[54]'] option:selected" ).text());
  $('#res_55').html($("select[name='field[55]'] option:selected" ).text());
  $('#res_56').html($("select[name='field[56]'] option:selected" ).text());
  
  $('#res_date').html(date.replace(/-/g,'.'));
  $('#res_time').html(timestr);
  $('#res_docs_count').html(docs_count);
  return false;
}

function docs_count_set(el,counts){
  $('.docs_count_list button').removeClass('calender__list-item--active');
  $(el).addClass('calender__list-item--active');
  docs_count= counts;
  getCalendarModule();
}


function view_reservation(el){
  data = JSON.parse(atob($(el).parents('.jobs__card-item').data('val')));
  docs_count = $(el).parents('.jobs__card-item').data('docs');
  $("#modal-info-js .user__form-result-list--info").html('');
  bold_text = 'style="font-weight: 700; color: #2C2A29;"';
  red_text = 'style="color: #E04E39;"';
  $('.info_modal_docs').html(docs_count);
  $.each(data, function(i, item) {
    $("#modal-info-js .user__form-result-list--info").append('<li class="user__form-result-item"><p class="user__form-result-inner" '+(item.style.name=='bold'?bold_text:'')+' '+(item.style.name=='red'?red_text:'')+'>'+item.name+'</p><p class="user__form-result-inner" '+(item.style.value=='bold'?bold_text:'')+' '+(item.style.value=='red'?red_text:'')+'>'+item.value+'</p></li>');
});
  
                        
  
  //info-js
}

function cancel_reservation(el){
  data = JSON.parse(atob($(el).parents('.jobs__card-item').data('val')));
  date_ru = $(el).parents('.jobs__card-item').data('date');
  checksum = $(el).parents('.jobs__card-item').data('checksum');
  
  $('.cancel_f_checksum').val(checksum);
  $('.cancel_f_tokenv').val(data.token.value);
  $('.cancel_f_token').html('№: '+data.token.value);
  $('.cancel_f_time').html(data.time.value);
  $('.cancel_f_service').html(data.service.value);
  $('.cancel_f_date').html(date_ru);
  
}

function cancel_reservation2(el){
  checksumv = $('.cancel_f_checksum').val();
  tokenv = $('.cancel_f_tokenv').val();
  $.ajax({
          url: "/lk/cancel_reservation/",
          type: "POST",
          data : {token: tokenv,checksum: checksumv},
          success: function(data){
            $('.jobs__card-item[data-checksum='+checksumv+']').hide();
            $("#modal-cancel-js").css({display: "none"});
            $("#modal-success-js").find('.modal__success-description').html('Ваша запись успешна отменена!');
            $("#modal-success-js").css({display:"block"});
            setTimeout(()=> {
              $("#modal-success-js").css({display:"none"});
            }, 1400)
          }
   }); 
}







  
 //CALENDAR
let calendar = document.querySelector('.calendar')

const month_names = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']

isLeapYear = (year) => {
    return (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) || (year % 100 === 0 && year % 400 ===0)
}

getFebDays = (year) => {
    return isLeapYear(year) ? 29 : 28
}

var daysSaved = []; 
var datesSaved = [];

generateCalendar = (month, year,days,sday) => {
    if(days.length>0){
      daysSaved = days;
      datesSaved = sday;
    } else {
      days = daysSaved;
      sday= datesSaved;
      
    }
    days = days || [];
    let calendar_days = calendar.querySelector('.calendar-days')
    let calendar_header_year = calendar.querySelector('#year')

    let days_of_month = [31, getFebDays(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]

    calendar_days.innerHTML = ''

    let currDate = new Date()
    // if (!month) month = currDate.getMonth()
    // if (!year) year = currDate.getFullYear()

    let curr_month = `${month_names[month]}`
    month_picker.innerHTML = curr_month
    calendar_header_year.innerHTML = year

    // get first day of month
    
    let first_day = new Date(year, month, 0)

    for (let i = 0; i <= days_of_month[month] + first_day.getDay() - 1; i++) {
        let day = document.createElement('button')
       
        day.classList.add('calendar-day-hover')
        if (i >= first_day.getDay()) {
            //day.classList.add('calendar-day-hover')
            const d = ((i - first_day.getDay() + 1)<10) ? `0${i - first_day.getDay() + 1}` : i - first_day.getDay() + 1;
            const m = (month < 9) ? `0${month+1}` : month+1;
            const y = year; // currDate.getFullYear();
            const strDate = `${d}-${m}-${y}`
            //console.log(strDate);
             day.setAttribute('onclick', "change_date('"+strDate+"');return false;");

            //if(days.includes(strDate)) {
              if($.inArray(strDate, days) !== -1) {
              
              day.classList.add('day-active');
            } else {
              day.setAttribute("disabled", "disabled")
              //day.classList.add('disabled');
            }






            day.innerHTML = i - first_day.getDay() + 1 //+ 1
            day.innerHTML += `<span></span>
                            <span></span>
                            <span></span>
                            <span></span>`
            if (strDate==sday) {
                day.classList.add('curr-date')
             
            }
          
        }
        calendar_days.appendChild(day)
    }
}

let month_list = calendar.querySelector('.month-list')

month_names.forEach((e, index) => {
    let month = document.createElement('div')
    month.innerHTML = `<div data-month="${index}">${e}</div>`
    month.querySelector('div').onclick = () => {
        month_list.classList.remove('show')
        curr_month.value = index
        generateCalendar(index, curr_year.value)
    }
    month_list.appendChild(month)
})

let month_picker = calendar.querySelector('#month-picker')

month_picker.onclick = () => {
    month_list.classList.add('show')
}

let currDate = new Date()

const d = (currDate.getDay() < 10) ? `0${currDate.getDay()}` : currDate.getDay()
const m = (currDate.getMonth() < 10) ? `0${currDate.getMonth()}` : currDate.getMonth()
const y = currDate.getFullYear();
//console.log(`${d}-${m}-${y}`)

let curr_month = {value: currDate.getMonth()}
let curr_year = {value: currDate.getFullYear()}

generateCalendar(curr_month.value, curr_year.value, [])

document.querySelector('#prev-year').onclick = () => {
   
    //--curr_month.value

    if(curr_month.value < 1) {
      --curr_year.value
      curr_month.value = 11
      //curr_month
    } else {
      --curr_month.value
    }
   
    generateCalendar(curr_month.value, curr_year.value, [])
}

document.querySelector('#next-year').onclick = () => {
   
    if(curr_month.value > 10) {
      ++curr_year.value
      curr_month.value = 0
      //curr_month
    } else {
      ++curr_month.value
    }
    


    
    generateCalendar(curr_month.value, curr_year.value, [])
}
