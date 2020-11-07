import * as request from './request.js';

start();

let csrfToken = document.querySelector('meta[name=csrf_token]').content;

function start() {
  enableApproveButton();
  enableRejectButton();
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
          } else if (response.hasOwnProperty('success') && response.success === true) {
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

function enableRejectButton() {

}