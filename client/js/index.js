/** 
 *  Clase controladora de Verificación y Login del Calendario *
*/
class Login {
  constructor() {
    this.submitEvent();
  }
  /** Funcion/Evento de envio de las credenciales para el acceso */
  submitEvent() {
    $('form#login').submit(event => {
      event.preventDefault();
      this.sendForm();
    });
  }
  /** Funcion/Evento para el control en el envio de las credenciales para el acceso */
  sendForm() {
    let form_data = new FormData();
    form_data.append('username', $('#user').val());
    form_data.append('password', $('#password').val());
    $.ajax({
      url: '../server/check_login.php',
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      data: form_data,
      type: 'POST',
      success: function(php_response) {
        if (php_response.access) {
          window.location.href = 'main.html';
        } else {
          alert(php_response.reason);
        }
      },
      error: function() {
        alert("Hubo un error en la comunicación con el servidor!!");
      }
    });
  }
}
/**
 * Clase controladora de creación de Usuario para utilizar el calendario *
 */
class Singup {
  constructor() {
    this.singInEvent();
  }
  /** Funcion/Evento para la construccion de fecha ISO en caracteres */
  stringToDate(strDate) {
    let dmy = strDate.split('-');
    return dmy[2] + '-' + dmy[1] + '-' + dmy[0];
  }
  /** Funcion/Evento que de envio de los datos del nuevo usuario */
  singInEvent() {
    $('form#signup').submit(event => {
      event.preventDefault();
      this.userSignUp();
    });
  }
  /** Funcion/Evento para el control y verificación de los datos del nuevo usuario */
  userSignUp() {
    let user_data =new FormData();
    user_data.append('userfnames', $('#fnamesusr').val() + ' ' + $('#lnamesusr').val());
    user_data.append('userdbirth', this.stringToDate($('#dbirthusr').val()));
    user_data.append('useremaila', $('#emailausr').val());
    user_data.append('userpasswd', $('#passwdusr').val());
    $.ajax({
      url: '../server/create_user.php',
      type: 'POST',
      dataType: 'json',
      cache: false,
      processData: false,
      contentType: false,
      data: user_data,
      success: function(response) {
        if (response.result == 'ok') {
          alert(response.msg);
          $('#myModal').foundation('close');
        }
        window.location = 'index.html';
      },
      error: function() {
        alert('Hubo un error en la comunicación con el servidor de registro!!');
        window.location = 'index.html';
      }
    });
  }
}
/**
 *  Funcion Ready Document *
 */
$(function() {
  /** Sentencia que inicializa el Framework CSS Foundation */
  $(document).foundation();
  /** Inicializacion de Evento para la ventana Modal en el registro de nuevo usuario */
  $('#myModal').on('closed.zf.reveal', function() {
    window.location = 'index.html';
  });
  /** Inicializacion de Eventos para el formulario de registro de nuevo usuario */
  $('form#signup').on('valid.zf.abide', function(event) {
    let validForm = $(event.target).attr('id');
    if ($.inArray(validForm, dataUsr) == -1) { dataUsr.push(validForm) }
    if (dataUsr.length == 7) { $('#signupUser').removeAttr('disabled').removeClass('disabled') }
    }).on('invalid.zf.abide', function(event) {
      let invalidForm = $(event.target).attr('id');
      if (dataUsr.length != 0 && $.inArray(invalidForm, dataUsr) != -1) { 
        dataUsr = $.grep(dataUsr, function(val) { return val != invalidForm });
      }
      if (dataUsr.length != 7 ) { $('#signupUser').addClass('disabled').attr('disabled', 'true') }
    });
  /** Inicializacion del Elemento: Selector de Fecha de Foundation */
  $('#dbirthusr').fdatepicker({
    format: 'dd-mm-yyyy',
    disableDblClickSelection: true,
    startView: 4,
    closeButton: true,
    closeIcon: '<i class = "fi-x"> </i>',
    leftArrow: '<i class = "fi-rewind"></i>',
    rightArrow: '<i class = "fi-fast-forward"></i>',
    language: 'es'
  });
  /** Inicialización de Instancias de Objeto para el Login y Registro de usuarios */
  var l = new Login();
  var r = new Singup();
  var dataUsr = Array();
});