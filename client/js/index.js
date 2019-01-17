class Login {
  constructor() {
    this.submitEvent();
  }

  submitEvent() {
    $('form#login').submit(event => {
      event.preventDefault();
      this.sendForm();
    });
  }

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
        if (php_response.msg == "OK") {
          window.location.href = 'main.html';
        } else {
          alert(php_response.msg);
        }
      },
      error: function() {
        alert("Hubo un error en la comunicación con el servidor!!");
      }
    });
  }
}

class Singup {
  constructor() {
    this.singInEvent();
  }

  singInEvent() {
    $('form#signup').submit(event => {
      event.preventDefault();
      let sw1 = $('#passwdusr').val() !== $('#confirmpw').val() ? false : true;
      let sw2 = $('#condterms').prop('checked');
      if (sw1 && sw2) {
        this.userSignUp();
      } else if (!sw1) {
        alert('Las contraseñas ingresadas no coinciden!!')
      } else if (!sw2) {
        alert('No ha aceptado los términos y codiciones del uso de la Agenda!!');
      }
    });
  }

  userSignUp() {
    let user_data =new FormData();
    user_data.append('userfnames', $('#fnamesusr').val() + ' ' + $('#lnamesusr').val());
    user_data.append('userdbirth', $('#dbirthusr').val());
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
        if (response.msg = 'ok') {
          alert('Usuario registrado con éxito!!');
          $('form#signup').reset();
          $('myModal').foundation('close');
        } else {
          alert(response.msg);
        }
      },
      error: function(error) {
        console.log(error);
        alert('Hubo un error en la comunicación con el servidor de registro!!');
      }
    });
  }
}

$(function() {
  $(document).foundation();
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
  var l = new Login();
  var r = new Singup();
});
