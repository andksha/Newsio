import * as request from './request.js';

start();

let csrfToken = document.querySelector('meta[name=csrf_token]').content;

function start() {
  enableApproveButton();
  enableRejectInput();
}

function enableApproveButton() {
  document.querySelectorAll('.approve').forEach(function (b) {
    b.addEventListener('click', function () {
      let data = JSON.stringify({
        website_id: b.closest('.website').id
      });

      request.send('PUT', 'admin/website', data, csrfToken, true);
      request.xmlRequest.onload = function () {
        try {
          let response = JSON.parse(request.xmlRequest.responseText);

          if (response.hasOwnProperty('error_message')) {
            document.querySelector('.response-error').innerHTML = response.error_message;
            document.querySelector('.response-error').style.display = 'block';
          } else if (response.hasOwnProperty('website')) {
            window.location.reload();
          } else {
            console.log(response);
          }
        } catch (e) {
          console.log(e);
          alert(e);
        }
      }
    })
  });
}

function enableRejectInput() {
  document.querySelectorAll('.reject').forEach(function (b) {
    b.addEventListener('click', function () {
      let display = b.parentElement.querySelector('.remove_block').style.display;
      b.parentElement.querySelector('.remove_block').style.display = display === 'none' || !display ? 'inline-block' : 'none';
    });
  });

  document.querySelectorAll('.confirm_removing').forEach(function (s) {
    s.addEventListener('click', function () {
      let data = JSON.stringify({
        website_id: s.closest('.website').id,
        reason: s.parentElement.querySelector('.reject-input').value
      });

      request.send('DELETE', 'admin/website', data, csrfToken, true);
      request.xmlRequest.onload = function () {
        try {
          let response = JSON.parse(request.xmlRequest.responseText);

          if (response.hasOwnProperty('error_message')) {
            document.querySelector('.response-error').innerHTML = response.error_message;
            document.querySelector('.response-error').style.display = 'block';
          } else if (response.hasOwnProperty('website')) {
            window.location.reload();
          } else {
            console.log(response);
          }
        } catch (e) {
          console.log(e);
          console.log(request.xmlRequest.responseText);
          alert(e);
        }
      }
    });
  });
}