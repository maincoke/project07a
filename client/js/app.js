class EventsManager {
  constructor() {
      this.obtenerDataInicial();
  }

  obtenerDataInicial() {
      let url = '../server/getEvents.php';
      $.ajax({
        url: url,
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        type: 'GET',
        success: (data) => {
          if (data.msg == 'ok') {
            this.poblarCalendario(data.eventos);
          } else {
            alert(data.msg);
            window.location.href = 'index.html';
          }
        },
        error: (error) => {
          alert("Hubo un error en la comunicación con el servidor!!");
        }
      });
  }

  poblarCalendario(eventos) {
    let today = new Date();
      $('.calendario:first').fullCalendar({
          header: {
      		left: 'prev,next today',
      		center: 'title',
      		right: 'month,agendaWeek,basicDay'
        },
        aspectRatio: 1.5,
        fixedWeekCount: false,
        timezone: 'local',
        defaultDate: today.getFullYear().toString() + '-' + (today.getMonth() + 1).toString() + '-' + today.getDate().toString(),
        nowIndicator: true,
        locale: 'es',
        themeSystem: 'jquery-ui',
      	navLinks: true,
        eventStartEditable: true,
        eventDurationEditable: false,
      	eventLimit: true,
        droppable: true,
        dragRevertDuration: 0,
        timeFormat: 'H:mm',
        events: eventos,
        eventDrop: (event) => {
            this.actualizarEvento(event);
        },
        eventDragStart: (event, jsEvent) => {
          $('.delete-btn').find('img').attr('src', "img/trash-open.png");
          $('.delete-btn').css('background-color', '#d4404a');
        },
        eventDragStop: (event, jsEvent) => {
          var trashEl = $('.delete-btn');
          var ofs = trashEl.offset();
          var x1 = ofs.left;
          var x2 = ofs.left + trashEl.outerWidth(true);
          var y1 = ofs.top;
          var y2 = ofs.top + trashEl.outerHeight(true);
          if (jsEvent.pageX >= x1 && jsEvent.pageX <= x2 &&
              jsEvent.pageY >= y1 && jsEvent.pageY <= y2) {
                this.eliminarEvento(event, jsEvent);
                $('.calendario').fullCalendar('removeEvents', event.id);
          }
          $('.delete-btn').find('img').attr('src', "img/trash.png");
          $('.delete-btn').css('background-color', '#8B0913');
        }
      });
  }

  anadirEvento(){
    var form_data = new FormData();
    form_data.append('titulo', $('#titulo').val());
    form_data.append('start_date', $('#start_date').val());
    form_data.append('allDay', $('#allDay').prop('checked'));
    if (!$('#allDay').prop('checked')) {
      form_data.append('end_date', $('#end_date').val());
      form_data.append('end_hour', $('#end_hour').val());
      form_data.append('start_hour', $('#start_hour').val());
    } else {
      form_data.append('end_date', "");
      form_data.append('end_hour', "");
      form_data.append('start_hour', "");
    }
    $.ajax({
      url: '../server/new_event.php',
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      data: form_data,
      type: 'POST',
      success: (data) => {
        if (data.result == 'ok') {
          alert(data.msg);
          if ($('#allDay').prop('checked')) {
            $('.calendario').fullCalendar('renderEvent', {
              id: data.idevt,
              title: $('#titulo').val(),
              start: $('#start_date').val(),
              allDay: true
            }, true);
          } else {
            $('.calendario').fullCalendar('renderEvent', {
              id: data.idevt,
              title: $('#titulo').val(),
              start: $('#start_date').val() + "T" + $('#start_hour').val(),
              allDay: false,
              end: $('#end_date').val() + "T" + $('#end_hour').val()
            }, true);
          }
          initForm();
        } else {
          alert(data.msg);
        }
      },
      error: () => {
        alert("Hubo un error en la comunicación con el servidor!!");
      }
    });
  }

  eliminarEvento(event, jsEvent){
    var form_data = new FormData()
    form_data.append('id', parseFloat(event.id));
    $.ajax({
      url: '../server/delete_event.php',
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      data: form_data,
      type: 'POST',
      success: (data) => {
        if (data.result == 'ok') {
          alert(data.msg);
        } else {
          alert(data.msg);
        }
        $('.calendario').fullCalendar('rerenderEvents');
      },
      error: () => {
        alert("Hubo un error en la comunicación con el servidor!!");
      }
    });
    $('.delete-btn').find('img').attr('src', "img/trash.png");
    $('.delete-btn').css('background-color', '#8B0913');
  }

  actualizarEvento(evento) {
    console.log(evento);
    let id = evento.id,
        start = moment(evento.start).format('YYYY-MM-DD HH:mm:ss'),
        end = moment(evento.end).format('YYYY-MM-DD HH:mm:ss'),
        start_date,
        end_date,
        start_hour,
        end_hour,
        form_data = new FormData();
    start_date = start.substr(0,10);
    end_date = end.substr(0,10);
    start_hour = start.substr(11,8);
    end_hour = end.substr(11,8);
    form_data.append('id', parseFloat(id));
    form_data.append('start_date', start_date.toString());
    form_data.append('end_date', end_date.toString());
    form_data.append('start_hour', start_hour.toString());
    form_data.append('end_hour', end_hour.toString());
    form_data.append('fullday', evento.allDay);
    console.log(id);
    console.log(start_date);
    console.log(end_date);
    console.log(start_hour);
    console.log(end_hour);
    console.log(evento.allDay);
    $.ajax({
      url: '../server/update_event.php',
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      data: form_data,
      type: 'POST',
      success: (data) => {
        if (data.result == 'ok') {
          alert(data.msg);
        } else {
          alert(data.msg);
        }
        initForm();
      },
      error: (error) => {
        console.log(error);
        alert("Hubo un error en la comunicación con el servidor!!");
      }
    });
  }
}

function initForm(){
  $('form#eventForm').trigger('reset');
  $('#start_date, #titulo, #end_date').val('');
  $('#start_date, #end_date').datepicker({
    dateFormat: "yy-mm-dd"
  });
  $('.timepicker').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
    minTime: '5',
    maxTime: '23:30',
    defaultTime: '7',
    startTime: '5:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
  });
  $('#allDay').on('change', function(){
    if (this.checked) {
      $('.timepicker, #end_date').attr("disabled", "disabled");
    } else {
      $('.timepicker, #end_date').removeAttr("disabled");
    }
  });
  $('#start_date').change(function() {
    $('#end_date').val($(this).val()) ;
  });
  $('#start_hour, #end_hour').val('07:00');
  $('.timepicker, #end_date').attr("disabled", "disabled");
}

$(function(){
  $(document).foundation();
  initForm();
  var evt = new EventsManager();
  $('form#eventForm').submit(function(event) {
    event.preventDefault();
    evt.anadirEvento();
  });
});