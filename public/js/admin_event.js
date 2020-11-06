import * as request from './request.js';

start();

function start() {
  enableRemoveInput()
}

function enableRemoveInput() {
  let csrfToken = document.querySelector('meta[name=csrf_token]').content;

  document.querySelectorAll('.remove_event').forEach(function (x) {
    x.addEventListener('click', function () {
      x.parentElement.querySelector('.remove_block').style.display =
        x.parentElement.querySelector('.remove_block').style.display === 'none'
        || !x.parentElement.querySelector('.remove_block').style.display ? 'inline-block' : 'none';
    });
  });

  document.querySelectorAll('.confirm_removing_event').forEach( function (v) {
    v.addEventListener('click', function () {
      document.querySelector('.new-links-errors').style.display = 'none';

      let data = JSON.stringify({
        event_id: v.parentElement.querySelector('.remove-input').id,
        reason: v.parentElement.querySelector('.remove-input').value
      });

      request.send('DELETE', 'admin/event', data, csrfToken, true);

      request.xmlRequest.onload = function () {
        try {
          let response = JSON.parse(request.xmlRequest.responseText);

          if (typeof response.event != 'undefined') {
            window.location.reload();
          } else if (typeof response.error_message != 'undefined') {
            alert(response.error_message);
          } else {
            console.log(response);
          }
        } catch (e) {
          console.log(e);
        }
      }
    });
  });
}