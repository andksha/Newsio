import * as request from './request.js';

start();

function start() {
  enableRemoveInput();
  enableRestoreEventButton();
  enableRemoveLinkInput();
  enableRestoreLinkButton();
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
      let data = JSON.stringify({
        event_id: v.closest('.event').id,
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
            console.log(response.error_message);
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

function enableRestoreEventButton() {
  let csrfToken = document.querySelector('meta[name=csrf_token]').content;

  document.querySelectorAll('.restore-event').forEach(function (b) {
    b.addEventListener('click', function () {
      let data = JSON.stringify({
        event_id: b.closest('.event').id
      });

      request.send('PUT', 'admin/event', data, csrfToken, true);
      request.xmlRequest.onload = function () {
        try {
          let response = JSON.parse(request.xmlRequest.responseText);

          if (typeof response.event != 'undefined') {
            window.location.reload();
          } else if (typeof response.error_message != 'undefined') {
            alert(response.error_message);
            console.log(response.error_message);
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

function enableRemoveLinkInput() {
  let csrfToken = document.querySelector('meta[name=csrf_token]').content;

  document.querySelectorAll('.remove_link').forEach(function (x) {
    x.addEventListener('click', function () {
      x.parentElement.querySelector('.remove_link_block').style.display =
        x.parentElement.querySelector('.remove_link_block').style.display === 'none'
        || !x.parentElement.querySelector('.remove_link_block').style.display ? 'inline-block' : 'none';
    });
  });

  document.querySelectorAll('.confirm_removing_link').forEach( function (v) {
    v.addEventListener('click', function () {
      let data = JSON.stringify({
        link_id: v.parentElement.querySelector('.remove-link-input').id.replace('link-', ''),
        reason: v.parentElement.querySelector('.remove-link-input').value
      });

      request.send('DELETE', 'admin/link', data, csrfToken, true);

      request.xmlRequest.onload = function () {
        try {
          let response = JSON.parse(request.xmlRequest.responseText);

          if (typeof response.link != 'undefined') {
            window.location.reload();
          } else if (typeof response.error_message != 'undefined') {
            alert(response.error_message);
            console.log(response.error_message);
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

function enableRestoreLinkButton() {
  let csrfToken = document.querySelector('meta[name=csrf_token]').content;

  document.querySelectorAll('.restore-link').forEach(function (b) {
    b.addEventListener('click', function () {
      let data = JSON.stringify({
        link_id: b.id.replace('link-', '')
      });

      request.send('PUT', 'admin/link', data, csrfToken, true);
      request.xmlRequest.onload = function () {
        try {
          let response = JSON.parse(request.xmlRequest.responseText);

          if (response.hasOwnProperty('link')) {
            window.location.reload();
          } else if (typeof response.error_message != 'undefined') {
            alert(response.error_message);
            console.log(response.error_message);
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