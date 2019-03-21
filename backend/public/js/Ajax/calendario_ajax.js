toHex = function(str) {

  if(!str) return false;
  var hash = 0;
  if (str.length === 0) return hash;
  for (var i = 0; i < str.length; i++) {
      hash = str.charCodeAt(i) + ((hash << 5) - hash);
      hash = hash & hash;
  }
  var color = '#';
  for (var i = 0; i < 3; i++) {
      var value = (hash >> (i * 8)) & 255;
      color += ('00' + value.toString(16)).substr(-2);
  }
  return color;
}

/* CUSTOM */

  var initCalendarDragNDrop = function() {
    $('#external-events div.external-event').each(function() {

      // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
      // it doesn't need to have a start or end
      var eventObject = {
        title: $.trim($(this).text()) // use the element's text as the event title
      };

      // store the Event Object in the DOM element so we can get to it later
      $(this).data('eventObject', eventObject);

      // make the event draggable using jQuery UI
      $(this).draggable({
        zIndex: 999,
        revert: false,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
      });

    });
  };

  var initCalendar = function(google_events) {

    parsed_google_events = [];
  
    for(i in google_events){

      //console.log(google_events[i].event_color);
  
      var temp_object = {
        "title" : google_events[i].summary,
        "start" : google_events[i].start.dateTime,
        "description" : google_events[i].description,
        "end" : google_events[i].end.dateTime,
        "id" : google_events[i].id,
        //"className": 'fc-event-info',
        "backgroundColor" : google_events[i].event_color,
        "borderColor" : google_events[i].event_color,
        "calendar_id" : google_events[i].calendar_id,

      }

      parsed_google_events.push(temp_object);

    }

    var $calendar = $('#calendar');

    $calendar.fullCalendar({
      lang: 'pt',
      header: {
        left: 'title',
        right: 'prev,today,next,basicDay,basicWeek,month'
      },
      eventDrop: function(event, delta, revertFunc) {

        //no changes, just date update
        btl_google_calendar.update_date(event);
    
      },
      eventClick: function(event, jsEvent, view) {

        var event_modal_popup = $("#event-modal-edit");

        //prepare data
        event_modal_popup.find(".card-title").html(event.title);
        event_modal_popup.find("input[name=event_title]").val(event.title);
        event_modal_popup.find("textarea[name=event_description]").val(event.description);
        event_modal_popup.find("input[name=event_id]").val(event.id);

        //dates
        event_modal_popup.find("input[name=event_date_start]").val(event.start ? event.start.format("YYYY/MM/DD") : "");
        event_modal_popup.find("input[name=event_time_start]").val(event.start ? event.start.format("HH:MM") : "");
        event_modal_popup.find("input[name=event_date_end]").val(event.end ? event.end.format("YYYY/MM/DD") : event.start.format("YYYY/MM/DD"));
        event_modal_popup.find("input[name=event_time_end]").val(event.end ? event.end.format("HH:MM") : "");
        

        //open the popup
        $.magnificPopup.open({
          items: {
            src: event_modal_popup
          },
          type: 'inline'
        });

        //bind event actions
        bind_event_actions(event);

      },

      timeFormat: 'H:mm',

      themeButtonIcons: {
        prev: 'fa fa-caret-left',
        next: 'fa fa-caret-right',
      },

      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar !!!
      drop: function(date, allDay) { // this function is called when something is dropped
        var $externalEvent = $(this);
        // retrieve the dropped element's stored Event Object
        var originalEventObject = $externalEvent.data('eventObject');

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);

        // assign it the date that was reported
        copiedEventObject.start = date;
        copiedEventObject.allDay = allDay;
        copiedEventObject.className = $externalEvent.attr('data-event-class');

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

        // is the "remove after drop" checkbox checked?
        if ($('#RemoveAfterDrop').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove();
        }

      },
      events: parsed_google_events
      //,eventColor: '#378006'
    });

    // FIX INPUTS TO BOOTSTRAP VERSIONS
    var $calendarButtons = $calendar.find('.fc-header-right > span');
    $calendarButtons
      .filter('.fc-button-prev, .fc-button-today, .fc-button-next')
        .wrapAll('<div class="btn-group mt-sm mr-md mb-sm ml-sm"></div>')
        .parent()
        .after('<br class="hidden"/>');

    $calendarButtons
      .not('.fc-button-prev, .fc-button-today, .fc-button-next')
        .wrapAll('<div class="btn-group mb-sm mt-sm"></div>');

    $calendarButtons
      .attr({ 'class': 'btn btn-sm btn-default' });
  };

/* GOOGLE */


/* GOOGLE CALENDAR */
// Client ID and API key from the Developer Console
var CLIENT_ID = CONFIG[0].D001_GA_client_id;
var API_KEY = CONFIG[0].D001_GA_api_key;

// Array of API discovery doc URLs for APIs used by the quickstart
var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];

// Authorization scopes required by the API; multiple scopes can be
// included, separated by spaces.
var SCOPES = "https://www.googleapis.com/auth/calendar";

var authorizeButton = document.getElementById('authorize_button');
var signoutButton = document.getElementById('signout_button');

/**
 *  On load, called to load the auth2 library and API client library.
 */
function handleClientLoad() {
  gapi.load('client:auth2', initClient);
}

/**
 *  Initializes the API client library and sets up sign-in state
 *  listeners.
 */
function initClient() {
  gapi.client.init({
    apiKey: API_KEY,
    clientId: CLIENT_ID,
    discoveryDocs: DISCOVERY_DOCS,
    scope: SCOPES
  }).then(function () {
    // Listen for sign-in state changes.
    gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);

    // Handle the initial sign-in state.
    updateSigninStatus(gapi.auth2);

    try{
      authorizeButton.onclick = handleAuthClick;
      signoutButton.onclick = handleSignoutClick;
    }
    catch(e){
      console.log("Erro no bind de eventos de autorização de comunicação Google - elementos HTML não encontrados");
      console.log(e);
    }
    
  }, function(error) {
    console.log(JSON.stringify(error, null, 2));
  });
}

/**
 *  Called when the signed in status changes, to update the UI
 *  appropriately. After a sign-in, the API is called.
 */
function updateSigninStatus(auth) {

  if(auth && typeof(auth) == "object")
    var isSignedIn = auth.getAuthInstance().isSignedIn.get();

  try{

    
    if (isSignedIn) {

      //google authentication info - to be displayed in the html
      var ga_info = {
        id :  gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile().getId(),
        name :  gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile().getName(),
        image :  gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile().getImageUrl(),
        email :  gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile().getEmail()    
      }
      
      //draw html if exists
      if($(".google-calendar-signed-in-info").length > 0){
        var js_html = `
        <div class="google-signed-in-user">
          <p>O calendário está sincronizado com a sua conta Google <i class="fa fa-check"></i></p>
          <span> <strong>${ga_info.name}</strong><br />(${ga_info.email})</span>
          <img src="${ga_info.image}" />
        </div>
        `;
        
        $(".google-calendar-signed-in-info").html(js_html);
      }

      if($("#calendar").length > 0)
        listUpcomingEvents();
        
      authorizeButton.style.display = 'none';
      signoutButton.style.display = 'block';
    } else {

      if($("#calendar").length > 0){
        $("#calendar").html("Por favor, reveja a configuração do seu calendário na <a href='configuracao/config_calendario'>configuração da plataforma</a>.");
      }

      authorizeButton.style.display = 'block';
      signoutButton.style.display = 'none';
    }
  }
  catch(e){
    console.log("Erro na actualização do estado de sign - in; elementos HTML não encontrados")
    console.log(e);
  }

}

/**
 *  Sign in the user upon button click.
 */
function handleAuthClick(event) {
  
  var login_status = gapi.auth2.getAuthInstance().signIn().then(function(event){    
    
    var signed_in = gapi.auth2.getAuthInstance().isSignedIn.get();
    var notification = {};

    if(signed_in){
      notification = {"message" : "Calendário associado com sucesso", "type" : "success"}
    }
    else
      notification = {"message" : "Ocorreu um erro na autenticação", "type" : "error"}

    new PNotify({
      title: "Autenticação",
      text: notification.message,
      type: notification.type,
      delay: 1800
    });

    setTimeout('location.reload()', 2500);
  });
}

/**
 *  Sign out the user upon button click.
 */
function handleSignoutClick(event) {
  //revoke das permissões
  gapi.auth2.getAuthInstance().signOut();
  gapi.auth2.getAuthInstance().disconnect().then(function(){
    console.log("Signed out successfully (both ways) - refreshing...");
    setTimeout('location.reload()', 2500);
  });

  event.preventDefault();  

}

/**
 * Print the summary and start datetime/date of the next ten events in
 * the authorized user's calendar. If no events are found an
 * appropriate message is printed.
 */
function listUpcomingEvents() {

  // var calendar_ids = [
  //   //"88bpsdkeflgvl1alb7n4eh9qi0@group.calendar.google.com",
  //   CONFIG[0].D001_GA_calendar_id,
  //   "ird1fct2laod4ugmvrgsi4ejmk@group.calendar.google.com"
  //   //"primary"
  //   ];

  var calendar_ids = calendars;
  var events_output = [];
  var events = [];
  var the_calendar_id = "";
  var calendario_flybizz = [];


  for(i in calendar_ids){

    calendario_flybizz[i] = calendar_ids[i];

    the_calendar_id = calendar_ids[i];
    btl_google_calendar.calendars.push({id : calendar_ids[i], color : toHex(calendar_ids[i])}); //shoves a calendar id into global var

    gapi.client.calendar.events.list({
      'calendarId': calendar_ids[i],
      //'timeMin': (new Date()).toISOString(),
      //'showDeleted': true,
      'singleEvents': true,
      'orderBy': 'startTime'
    }).then( response => {

      //console.log("CALENDAR ID: " + the_calendar_id);
      events = response.result.items;
       
      if (events.length > 0) {

        for (x = 0; x < events.length; x++) {
          
          var event = events[x];

          event.calendar_id = event.organizer.email; //this must passed beforehand, due to google api operations
          event.event_color = toHex(event.calendar_id); //this must passed beforehand, due to google api operations

          events_output.push(event);
          btl_google_calendar.events.push(event);
  
        }

        //btl_google_calendar.events.push(events_output);
        //console.log(events_output);
        //console.log(btl_google_calendar.events);

        //cycle done, output and render calendar
        //initCalendar(events_output);
        //initCalendarDragNDrop();

        //results OK RENDER
        //events_info.push(events);
        return events_output;
  
      } else {
        console.log('No upcoming events found.');
      }
  
    });
      

  }
  
}


//popup modal actions bind
function bind_event_actions(event){

  $("#event-modal-edit button[data-action]").unbind("click");

  //binds
  $("#event-modal-edit button[data-action]").click({event_info:event}, function(event){

    event.stopPropagation();

    //TODO: calendar ID must be dynamic and aggregated to the event; this information is lost when events are passed to fullcalendar
    //SETUP THINGS
    var the_event = event.data.event_info;
    var action = $(this).data("action");
    var output = {"type" : false, title: false, "message" : false};
    var form_info = [];
    $.each($('#event-modal-edit form').serializeArray(), function() {
        form_info[this.name] = this.value; //all user input will be here to update event information
    });

    switch(action){

      //DELETE
      case "event-delete":
  
        btl_google_calendar.delete(the_event.id);
      
      break;

      //SAVE & UPDATE
      case "event-save":

        //UPDATE
        if(the_event){

          //trim dates to match Google / Moment
          var datetime_start = moment(form_info.event_date_start + " " + form_info.event_time_start, "YYYY/MM/DD H:m:00");
          var datetime_end = moment(form_info.event_date_end + " " + form_info.event_time_end, "YYYY/MM/DD H:m:00");
          var event_update = {
            'summary': form_info.event_title,
            'description' : form_info.event_description,
            'start': {
              'dateTime': datetime_start,
              'timeZone': 'Europe/Lisbon'
            },
            'end': {
              'dateTime': datetime_end,
              'timeZone': 'Europe/Lisbon'
            }
          };

          //pass event info + event id
          btl_google_calendar.update(event_update, the_event.id);

        }

        //NEW
        else{
          console.log("Creating event from scratch");
        }

      break;

    }

    //reload calendar
    setTimeout(function(){
      btl_google_calendar.reload();
    }, 800)
    

    //close the modal?
    $.magnificPopup.close();
    return false;

  });

}

$(document).ready(function(){

  //bind click event
  $("#cal_gravar").click(function(){

    var event_data = {
      summary : $("#cal_tipo option:selected").text() + " - " + $("#cal_titulo").val(),
      description : $("#cal_texto").val(),
      datetime_start : moment($("#tar_dtstart").val() + " " + $("#tar_hrstart").val(), "DD/MM/YYYY H:m:00"),
      datetime_end : moment($("#tar_dtend").val() + " " + $("#tar_hrend").val(), "DD/MM/YYYY H:m:00"),
      priority : $("#tar_prioridade").val(),
      text : $("#cal_texto").val(),
      type : $("#cal_tipo").val(),
      client_id : $("#tar_client_id").val() //TODO - tem de ser dinâmico e preenchido opcionalmente

    }

    console.log(event_data);

    if( !event_data.summary 
      || !event_data.description 
      || !event_data.datetime_start 
      || !event_data.datetime_end 
      || !event_data.priority 
      || !event_data.text 
      || !event_data.type 
      || !event_data.client_id
    ){
      
      new PNotify({
        title: "Campos em falta",
        text: "Por favor verifique os campos",
        type: "warning",
        delay: 2500
      });

      return false;

    }
    
    btl_google_calendar.insert(event_data);

  })

})


//limpar o código
var btl_google_calendar = {

  calendarId : function(){
    try{
      return calendars[0]
    }
    catch(e){
      console.log("Calendars not found, main calendar not defined")
    }
  },
  calendars : [],
  events : [],
  element : $("#calendar"),

  insert : function(event_data){


    // if(btl_google_calendar.calendars.length > 1){
    //   new PNotify({
    //     title: "Erro",
    //     text: "Os eventos adicionados pelo administrador, não serão atribuídos aos utilizadores",
    //     type: "warning",
    //     delay: 2500
    //   });
    //   return false;
    // }

    var errors = {};

    //validate?
    if(!event_data.summary)
      errors.message = "Verifique o client_id";

    //report messages
    if(errors.message){
      
      new PNotify({
        title: "Erro",
        text: errors.message,
        type: "warning",
        delay: 2500
      });

      return false;
    }    

    //
    var event_insert = {
      'summary': event_data.summary,
      'description': event_data.description,
      'start': {
        'dateTime': event_data.datetime_start,
        'timeZone': 'Europe/Lisbon'
      },
      'end': {
        'dateTime': event_data.datetime_end,
        'timeZone': 'Europe/Lisbon'
      }
    };


    //save event data into Google
    var request = gapi.client.calendar.events.insert({
      'calendarId': btl_google_calendar.calendarId(),
      'resource': event_insert
    });

    request.execute(function(event) {
      console.log("Event created: " + event.htmlLink + " with id: " + event.id);
      var google_event_created = event.id ? true : false;
      event_data.google_event_id = event.id;

      //aqui tem de ser feito o bind, caso contrário google_event_created não existe (tempo que demora a conectar ao Google)
      var tar_dtstart = event_data.datetime_start.format("DD-MM-YYYY");
      var tar_hrstart = event_data.datetime_start.format("H:mm:s");
      var tar_dtend = event_data.datetime_end.format("DD-MM-YYYY");
      var tar_hrend = event_data.datetime_end.format("H:mm:s");

      //save event data to BTL timeline
      $.ajax({
        url: "/backend/Ajax.php",
        type: "POST",
        data: {'url':'tarefa/registar/id/'+event_data.client_id+'/tipo/'+event_data.type+'/titulo/'+event_data.summary+'/dtstart/'+tar_dtstart+'/hrstart/'+tar_hrstart+'/dtend/'+tar_dtend+'/hrend/'+tar_hrend+'/texto/'+event_data.text+'/prioridade/'+event_data.priority+'/google_event_id/' + event_data.google_event_id },
        success: function(response){
          
          //clear data and refresh calendar
          $(':input','form').not(':button, :submit, :reset, :hidden').val('').prop('checked', false).prop('selected', false);

          //notification
          new PNotify({
            title: "Sucesso",
            text: "Evento criado com sucesso",
            type: "success",
            delay: 2500
          });

          //reload
          setTimeout(function(){
            btl_google_calendar.reload();
          }, 1500);

        }
      });
      
    });

  },

  delete: function(google_event_id){
    
    //build request with gapi
    var request = gapi.client.calendar.events.delete({
      'calendarId': btl_google_calendar.calendarId(),
      'eventId' : google_event_id
    });

    request.execute(function(the_event) {

      console.log("HERE IS THE EVENT ID:");
      console.log(google_event_id);
      
      //errors
      if(the_event.code > 0){
        console.log("Ocorreu um erro durante a operação");
      }
      else{

        console.log(the_event);
        //btl_google_calendar.events = false;
        //console.log(btl_google_calendar.events);

        // for(i in btl_google_calendar.events){

        //   if(google_event_id == btl_google_calendar.events[i].id){
        //     console.log("ENCONTREI O EVENTO");
        //     console.log([i])
        //     console.log("VOU DELETAR");

        //   }

        // }

        console.log("Operação concluída com sucesso");

      }
   
    });

  },

  update : function(the_event, google_event_id){

    var calendar_event = the_event;

    //update all info
    var request = gapi.client.calendar.events.patch({
      'calendarId': btl_google_calendar.calendarId(),
      'eventId': google_event_id,
      'resource': the_event
    });

    request.execute(function(response) {
      //errors
      if(response.code > 0){
        console.log("Update de evento retornou um erro");
      }
      else{
        console.log("Update de evento concluído com sucesso");
        //actualizar na btl
        console.log("Actualizar na BTL - alteração via CLIQUE - posteriormente implementar alteração via DRAG");

        btl_google_calendar.update_btl(the_event, google_event_id);

      }
      
    });
  },

  //will update description, text, dates and other variables
  update_btl : function(the_event, google_event_id){
    
    //trim data
    var event_data = the_event;
    var tar_dtstart = event_data.start.dateTime.format("DD-MM-YYYY");
    var tar_hrstart = event_data.start.dateTime.format("H:mm:ss");
    var tar_dtend = event_data.end.dateTime.format("DD-MM-YYYY");
    var tar_hrend = event_data.end.dateTime.format("H:mm:ss");

    //save event data to BTL timeline
    $.ajax({
      url: "/backend/Ajax.php",
      type: "POST",
      data: {'url': `tarefa/alteracao/google_event_id/${google_event_id}/titulo/${event_data.summary}/dtstart/${tar_dtstart}/hrstart/${tar_hrstart}/dtend/${tar_dtend}/hrend/${tar_hrend}/texto/${event_data.description}/prioridade/${event_data.priority ? event_data.priority : 1}`},
      success: function(response){

        console.log("Informação do evento actualizada na base de dados");

      }
    });


  },

  //will only update the date of the event
  update_date_btl : function(the_event, google_event){
  
    //trim data
    var event_data = the_event;
    var tar_dtstart = event_data.start.dateTime.format("DD-MM-YYYY");
    var tar_hrstart = event_data.start.dateTime.format("H:mm:ss");
    var tar_dtend = event_data.end.dateTime.format("DD-MM-YYYY");
    var tar_hrend = event_data.end.dateTime.format("H:mm:ss");

    //save event data to BTL timeline
    $.ajax({
      url: "/backend/Ajax.php",
      type: "POST",
      data: {'url': `tarefa/alteracao/google_event_id/${google_event.id}/dtstart/${tar_dtstart}/hrstart/${tar_hrstart}/dtend/${tar_dtend}/hrend/${tar_hrend}`},
      success: function(response){

        console.log("Informação do evento actualizada na base de dados");
        console.log(response);

      }
    });

  },

  //keep all settings, only updates the dates
  update_date : function(event){

    //UPDATE
    if(event){     

      //trim dates to match Google / Moment      
      var google_event = {
        'start': {
          'dateTime': event.start,
          'timeZone': 'Europe/Lisbon'
        },
        'end': {
          'dateTime': event.end,
          'timeZone': 'Europe/Lisbon'
        }
      };

      var request = gapi.client.calendar.events.patch({
        'calendarId': btl_google_calendar.calendarId(),
        'eventId': event.id,
        'resource': google_event
      });

      request.execute(function(the_event) {

        //errors
        if(the_event.code > 0){
          console.log("Ocorreu um erro durante a operação");
        }
        else{
          console.log("Operação concluída com sucesso");
          console.log("Actualizar na BTL: 610");
          btl_google_calendar.update_date_btl(google_event, the_event);
        }
        
      });
    }
  },

  reload : function(){
    //only reload if the calendar is visible
    if($('#calendar').length > 0){

      btl_google_calendar.element.find(".loader").fadeIn();
      $('#calendar').fullCalendar('destroy');
      btl_google_calendar.events = [];
      listUpcomingEvents(); //google

      setTimeout(function(){
        initCalendar(btl_google_calendar.events);
        btl_google_calendar.element.find(".loader").fadeOut();
        console.log("FUNCTION RAN");
      }, 1000)
      

    }    
  },

  init : function(){
    this.render_labels()
    initCalendar(this.events);
    this.element.find(".loader").fadeOut();
    console.log("A iniciar");
  },

  render_labels(){

    try{
      var holder = $(".calendar-users-labels");
      var html = "";
      holder.empty()

      $.each( users_info, function( key, value ) {
        
        if(!users_info[key].usu_nome) return true;
        html += `
        <li><span style="background-color:${toHex(users_info[key].usu_calendar_id)};"></span> ${users_info[key].usu_nome}</li>
        `
      });

      $(holder).append(html);
    }
    catch(error){
      console.log(error)
    }

    

  }

}


$(document).ready(function(){
  
  $('.select2-data-ajax').select2({
    ajax: {
      url: "/backend/Ajax.php",
      dataType : 'json',
      data: function (params) {
        var query = {
          search: params.term,
          url: 'cliente/pesquisar_ajax/'
        }
        return query;
      }
     
    }
  });

  setTimeout(function(){
    btl_google_calendar.init();
  }, 1500)
  
})