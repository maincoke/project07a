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

class Singup {
  constructor() {
    this.singInEvent();
  }

  stringToDate(strDate) {
    let dmy = strDate.split('-');
    return dmy[2] + '-' + dmy[1] + '-' + dmy[0];
  }

  singInEvent() {
    $('form#signup').submit(event => {
      event.preventDefault();
      this.userSignUp();
    });
  }

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

$(function() {
  $(document).foundation();
  $('#myModal').on('closed.zf.reveal', function() {
    window.location = 'index.html';
  });
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
  var dataUsr = Array();
});
