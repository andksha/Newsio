import * as request from './request.js';

let inputsDiv = document.getElementById('inputs');

function enableAddEventButton() {
  let addButton = document.getElementById('add-event-button');

  addButton.addEventListener('click', function () {
    inputsDiv.style.display = inputsDiv.style.display === 'none' || !inputsDiv.style.display ? 'flex' : 'none';
  });
}

function enableSubmitButton() {
  let submitButton = document.getElementById('submit_event_button');
  let data = [];
  let csrfToken = document.querySelector('meta[name=csrf_token]').content;

  submitButton.addEventListener('click', function () {
    clearErrors();

    data = JSON.stringify({
      title: document.getElementById('event_title_input').value,
      tags: document.getElementById('event_tags_input').value.replace(/\s+/g, ' ').trim().split(/[\r?\n\s,]+/),
      links: document.getElementById('event_links_input').value.replace(/\s+/g, ' ').trim().split(/[\r?\n\s,]+/),
      category: document.getElementById('event_category_input').value
    });

    request.send('POST', 'event', data, csrfToken, true);

    request.xmlRequest.onload = function () {
      try {
        let response = JSON.parse(request.xmlRequest.responseText);

        if (typeof response.error_data != 'undefined') {
          handleError(response);
        } else if(typeof response.event != 'undefined') {
          handleEvent(response.event);
        } else {
          alert('Server error. Try again.');
        }
      } catch (e) {
        console.log(e);
        alert(e);
      }
    }
  });
}

function clearErrors() {
  let inputs = document.querySelectorAll('[id$=_input]');

  for (let i = 0; i < inputs.length; i++) {
    inputs[i].classList.remove('input_error');
  }
}

function handleError(response) {
  let error_message = response.error_message;
  let error_data = response.error_data;

  if (typeof error_data.event != 'undefined') {
    displayEventError(error_data.event, error_message)
  } else {
    typeof error_data.title != 'undefined' ? displayInputError('title', error_data.title) : '';
    typeof error_data.tags != 'undefined' ? displayInputError('tags', error_data.tags) : '';
    typeof error_data.links != 'undefined' ? displayInputError('links', error_data.links) : '';
  }
}

function displayInputError(field, message) {
  document.querySelector('#event_' + field + '_input').classList.add('input_error');
  document.querySelector('#event_' + field + '_error').innerHTML = ' ' + message;
}

function displayEventError(event, message) {
  document.querySelector('.error_message').innerHTML = message + ": <a href=\"/event/" + event.id + "\">" + event.title + "</a>";
}

function enableTitle(title) {
  title.addEventListener('click', function () {
    title.parentElement.querySelectorAll('.event_link').forEach(function (link) {
      link.style.display = link.style.display === 'none' || !link.style.display ? 'block' : 'none';
    });
  });
}

function handleEvent(event) {
  inputsDiv.style.display = 'none';
  clearErrors();

  let eventDiv = document.querySelector('.row.event').cloneNode();
  let eventTable = document.createElement('div');
  let eventTitle = document.createElement('span');

  eventTitle.classList.add('event_title');
  eventTitle.innerHTML = event.title.charAt(0).toUpperCase() + event.title.slice(1) + ' (' + event.links.length + ')';

  eventTable.classList.add('col-md-12');
  eventTable.append(eventTitle);

  for (const tag in event.tags) {
    if (event.tags.hasOwnProperty(tag)) {
      let eventTag = document.createElement('span');
      eventTag.classList.add('event_tag');
      eventTag.innerHTML = event.tags[tag];
      eventTable.append(eventTag);
    }
  }

  for (const link in event.links) {
    if (event.links.hasOwnProperty(link)) {
      let eventLink = document.createElement('a');
      eventLink.classList.add('event_link');
      eventLink.href = event.links[link];
      eventLink.innerHTML = event.links[link];
      eventTable.append(eventLink);
    }
  }

  enableTitle(eventTitle);
  eventDiv.append(eventTable);

  document.querySelector('#inputs').after(eventDiv);
}

export function start() {
  enableAddEventButton();
  enableSubmitButton();

  document.querySelectorAll('.event_title').forEach(title => enableTitle(title));
}
