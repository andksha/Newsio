import * as request from "./request.js";

start();

let csrfToken = document.querySelector('meta[name=csrf_token]').content;

function start() {
  enableRegister();
  enableLogin();
  enableResetPassword();
  enableTags();
}

function enableRegister() {
  let registerButton = document.getElementById('register');

  if (!registerButton) {
    return;
  }

  registerButton.addEventListener('click', function () {
    document.getElementById('login-block').style.display = 'none';
    document.getElementById('reset-password-block').style.display = 'none';

    let registerBlock = document.getElementById('register-block');

    registerBlock.style.display = registerBlock.style.display === 'none' || !registerBlock.style.display ? 'block' : 'none';
  });

  document.getElementById('submit-registration').addEventListener('click', function () {
    document.querySelector('.response-error').style.display = 'none';
    let data = JSON.stringify({
      email: document.getElementById('register-email').value,
      password: document.getElementById('register-password').value,
      password_confirmation: document.getElementById('register-password_confirmation').value
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

function enableLogin() {
  let loginButton = document.getElementById('login');

  if (!loginButton) {
    return;
  }

  loginButton.addEventListener('click', function () {
    document.getElementById('register-block').style.display = 'none';
    document.getElementById('reset-password-block').style.display = 'none';

    let loginBlock = document.getElementById('login-block');

    loginBlock.style.display = loginBlock.style.display === 'none' || !loginBlock.style.display ? 'block' : 'none';
  });

  document.getElementById('submit-login').addEventListener('click', function () {
    document.querySelector('.response-error').style.display = 'none';
    let data = JSON.stringify({
      email: document.getElementById('login-email').value,
      password: document.getElementById('login-password').value,
    });

    request.send('POST', 'login', data, csrfToken, true);
    request.xmlRequest.onload = function () {
      try {
        let response = JSON.parse(request.xmlRequest.responseText);

        if (response.hasOwnProperty('error_message') || typeof response.error_message != 'undefined') {
          document.querySelector('.response-error').innerHTML = response.error_message;
          document.querySelector('.response-error').style.display = 'block';
        } else if (response.hasOwnProperty('success') || typeof response.success != 'undefined') {
          window.location.reload();
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

function enableResetPassword() {
  let resetPassword = document.getElementById('reset-password');

  if (!resetPassword) {
    return;
  }

  resetPassword.addEventListener('click', function () {
    document.getElementById('register-block').style.display = 'none';
    document.getElementById('login-block').style.display = 'none';
    let resetPasswordBlock = document.getElementById('reset-password-block');

    resetPasswordBlock.style.display = resetPasswordBlock.style.display === 'none' || !resetPasswordBlock.style.display ? 'block' : 'none';
  });

  document.getElementById('submit-reset').addEventListener('click', function () {
    document.querySelector('.response-error').style.display = 'none';
    let data = JSON.stringify({
      email: document.getElementById('reset-email').value,
    });

    request.send('POST', 'password', data, csrfToken, true);
    request.xmlRequest.onload = function () {
      try {
        let response = JSON.parse(request.xmlRequest.responseText);

        if (response.hasOwnProperty('error_message') || typeof response.error_message != 'undefined') {
          document.querySelector('.response-error').innerHTML = response.error_message;
          document.querySelector('.response-error').style.display = 'block';
        } else if (response.hasOwnProperty('success') || typeof response.success != 'undefined') {
          alert('Password reset link successfully sent.')
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

function enableTags() {
  let popularTags = document.querySelector('.popular-tags');
  let rareTags = document.querySelector('.rare-tags');

  document.querySelectorAll('.tag-filter').forEach(function (filter) {
    filter.addEventListener('click', function () {
      document.querySelector('.response-error').style.display = 'none';

      request.send('GET', 'tags?period=' + filter.id, [], '', true);
      request.handleResponse('tags', function (response) {
        let linkElement = document.createElement('a');

        linkElement.classList.add('event_tag');
        popularTags.innerHTML = '';
        rareTags.innerHTML = '';

        response.tags.popular.forEach(function (popularTag) {
          fillTagsElement(popularTag, popularTags)
        });

        response.tags.rare.forEach(function (rareTag) {
          fillTagsElement(rareTag, rareTags)
        });

        function fillTagsElement (tag, tagsElement) {
          let link = linkElement.cloneNode();
          link.href = location.protocol + '//' + location.host + location.pathname + '?tag=' + tag.tag.name;
          link.innerHTML = tag.tag.name;
          tagsElement.append(link);
        }
      });
    });
  });
}