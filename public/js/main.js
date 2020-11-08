import * as request from "./request.js";

start();

let csrfToken = document.querySelector('meta[name=csrf_token]').content;

function start() {
  document.getElementById('register').addEventListener('click', function () {
    let registerBlock = document.getElementById('register-block');

    registerBlock.style.display = registerBlock.style.display === 'none' || !registerBlock.style.display ? 'block' : 'none';
  });

  document.getElementById('submit-registration').addEventListener('click', function () {
    document.querySelector('.response-error').style.display = 'none';
    let data = JSON.stringify({
      email: document.getElementById('email').value,
      password: document.getElementById('password').value,
      password_confirmation: document.getElementById('password_confirmation').value
    });

    request.send('POST', 'register', data, csrfToken, true);
    request.xmlRequest.onload = function () {
      try {
        let response = JSON.parse(request.xmlRequest.responseText);

        if (response.hasOwnProperty('error_message') || typeof response.error_message != 'undefined') {
          document.querySelector('.response-error').innerHTML = response.error_message;
          document.querySelector('.response-error').style.display = 'block';
        } else if (response.hasOwnProperty('user') || typeof response.user != 'undefined') {
          alert('User ' + response.user.email + ' successfully registered.');
        } else {
          console.log(response);
        }
      } catch (e) {
        console.log(e);
        alert(e);
      }
    }
  });
}