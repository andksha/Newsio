import * as request from './request.js';

let inputsDiv = document.getElementById('inputs');

export function start() {
  enableAddEventButton();
  enableSubmitButton();

  document.querySelectorAll('.event_title').forEach(title => enableTitle(title));
  document.querySelectorAll('.event').forEach(event => enableLinksPublishedRemovedButtons(event));
}

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

function enableTitle(title) {
  title.addEventListener('click', function () {
    let links = title.parentElement.querySelector('.event_links');
    links.style.display = links.style.display === 'none' || !links.style.display ? 'block' : 'none';
  });
}

function clearErrors() {
  let inputs = document.querySelectorAll('[id$=_input]');

  for (let i = 0; i < inputs.length; i++) {
    inputs[i].classList.remove('input_error');
  }
}

function enableLinksPublishedRemovedButtons(event) {
  event.querySelector('.show_published_links').addEventListener('click', function () {
    event.querySelector('.show_published_links').classList.add('active');
    event.querySelector('.show_removed_links').classList.remove('active');
    event.querySelector('.published-links').style.display = 'block';
    event.querySelector('.removed-links').style.display = 'none';
  });

  event.querySelector('.show_removed_links').addEventListener('click', function () {
    event.querySelector('.show_removed_links').classList.add('active');
    event.querySelector('.show_published_links').classList.remove('active');
    event.querySelector('.removed-links').style.display = 'block';
    event.querySelector('.published-links').style.display = 'none';
  });
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

function handleEvent(event) {
  inputsDiv.style.display = 'none';
  clearErrors();

  let eventDiv = document.querySelector('.row.event').cloneNode();
  let div = document.createElement('div');
  let table = div.cloneNode();
  let title = document.createElement('span');
  let tags = div.cloneNode();
  let links = div.cloneNode();
  let publishedLinks = div.cloneNode();
  let removedLinks = div.cloneNode();

  let publishedRemovedLinks = document.createElement('span');
  publishedRemovedLinks.classList.add('published-removed-links');
  publishedRemovedLinks.innerHTML = "<a href=\"#\" class=\"show_published_links active\">published (" + event.links.length + ")</a>" +
    " | " +
    "<a href=\"#\" class=\"show_removed_links\">removed (0)</a>".trim();

  title.classList.add('event_title');
  title.innerHTML = event.title.charAt(0).toUpperCase() + event.title.slice(1);
  table.classList.add('col-md-12');
  table.append(title);
  table.append(publishedRemovedLinks);

  tags.classList.add('event_tags');
  links.classList.add('event_links');
  publishedLinks.classList.add('published-links');
  removedLinks.classList.add('removed-links');

  links.append(publishedLinks);
  links.append(removedLinks);

  for (const tag in event.tags) {
    if (event.tags.hasOwnProperty(tag)) {
      let eventTag = document.createElement('span');
      eventTag.classList.add('event_tag');
      eventTag.innerHTML = event.tags[tag];
      tags.append(eventTag);
    }
  }
  table.append(tags);

  for (const published_link in event.links) {
    if (event.links.hasOwnProperty(published_link)) {
      let link = document.createElement('a');
      link.classList.add('event_link');
      link.href = event.links[published_link];
      link.innerHTML = event.links[published_link];
      publishedLinks.append(link);
    }
  }

  table.append(links);

  enableTitle(title);
  eventDiv.append(table);
  enableLinksPublishedRemovedButtons(eventDiv);

  document.querySelector('#inputs').after(eventDiv);
}
