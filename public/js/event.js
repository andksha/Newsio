import * as request from './request.js';

let inputsDiv = document.getElementById('inputs');
let csrfToken = document.querySelector('meta[name=csrf_token]').content;

start();

function start() {
  let url = new URL(window.location.href);
  let category = url.searchParams.get('category');

  if (category) {
    document.querySelectorAll('.control-panel a').forEach(function (a) {
      a.href = a.href + '?category=' + category;
    });
  }

  enableAddEventButton();
  enableSubmitButton();

  document.querySelectorAll('.event_title').forEach(title => enableTitle(title));
  document.querySelectorAll('.event').forEach(event => enableEvent(event));
  document.querySelectorAll('.save-button').forEach(saveButton => enableSaveButton(saveButton));
  document.getElementById('search-input').placeholder = 'Search events';
}

function enableEvent(event) {
  enableLinksPublishedRemovedButtons(event);
  enableAddLinkButton(event);
  enableSubmitNewLinksButton(event);
}

function enableAddLinkButton(event) {
  let addLinkButton = event.querySelector('.add-link-button');

  if (!addLinkButton) {
    return;
  }

  addLinkButton.addEventListener('click', function () {
    let display = event.querySelector('.new-link-form').style.display;
    event.querySelector('.new-link-form').style.display = display === 'none' || !display ? 'block' : 'none';
  });
}

function enableSubmitNewLinksButton(event) {
  event.querySelector('.submit_links_button').addEventListener('click', function () {
    event.querySelector('.new-links-errors').style.display = 'none';
    let data = JSON.stringify({
      event_id: event.id,
      links: event.querySelector('.new-links-input').value.replace(/\s+/g, ' ').trim().split(/[\r?\n\s,]+/)
    });

    request.send('POST', 'links', data, csrfToken, true);
    request.xmlRequest.onload = function () {
      try {
        let response = JSON.parse(request.xmlRequest.responseText);

        if (typeof response.error_message != 'undefined') {
          let message = response.error_message;

          if (typeof response.error_data.event != 'undefined') {
            message += ": <a href=\"/event/" + response.error_data.event.id + "\">" + response.error_data.event.title + "</a>"
          }

          event.querySelector('.new-links-errors').style.display = 'block';
          event.querySelector('.new-links-errors').innerHTML = message;
          event.querySelector('.new-links-errors').classList.add('input_error');
        } else if (typeof response.new_links != 'undefined') {
          for (const new_link in response.new_links) {
            if (response.new_links.hasOwnProperty(new_link)) {
              let a = document.createElement('a');
              a.href = response.new_links[new_link];
              a.classList.add('event_link');
              a.target = '_blank';
              a.innerHTML = response.new_links[new_link];
              event.querySelector('.links').append(a);
              event.querySelector('.new-link-form').style.display = 'none';
            }
          }
        }else {
          alert('Server error. Try again.');
        }
      } catch (e) {
        console.log(e);
        alert(e);
      }
    }
  });
}

function enableAddEventButton() {
  let addButton = document.getElementById('add-event-button');

  if (!addButton) {
    return;
  }

  addButton.addEventListener('click', function () {
    inputsDiv.style.display = inputsDiv.style.display === 'none' || !inputsDiv.style.display ? 'flex' : 'none';
  });
}

function enableSubmitButton() {
  let submitButton = document.getElementById('submit_event_button');
  let data = [];

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

        if (typeof response.error_data != 'undefined' || typeof response.error_message != 'undefined') {
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
  document.querySelector('.error_message').innerHTML = error_message;

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
  let linksDiv = div.cloneNode();
  let publishedLinks = div.cloneNode();
  let addLinksButton = document.createElement('button');
  let newLinksErrors = div.cloneNode();
  let newLinksForm = div.cloneNode();
  let newLinksTextarea = document.createElement('textarea');
  let newLinksSubmit = document.createElement('input');
  let removedLinks = div.cloneNode();

  let publishedRemovedLinks = document.createElement('span');
  publishedRemovedLinks.classList.add('published-removed-links');
  publishedRemovedLinks.innerHTML = "<a class=\"show_published_links active\">published (" + event.links.length + ")</a>" +
    " | " +
    "<a class=\"show_removed_links\">removed (0)</a>".trim();

  eventDiv.id = event.id;

  title.classList.add('event_title');
  title.innerHTML = event.title.charAt(0).toUpperCase() + event.title.slice(1);
  table.classList.add('col-md-12');
  table.append(title);
  table.append(publishedRemovedLinks);

  tags.classList.add('event_tags');
  links.classList.add('event_links');
  linksDiv.classList.add('links');
  publishedLinks.classList.add('published-links');

  addLinksButton.classList.add('add-button');
  addLinksButton.classList.add('add-link-button');
  addLinksButton.innerHTML = '+';

  newLinksErrors.classList.add('new-links-errors');
  newLinksForm.classList.add('new-link-form');
  newLinksTextarea.classList.add('new-links-input');
  newLinksSubmit.classList.add('submit_links_button');
  newLinksSubmit.type = 'submit';
  newLinksSubmit.value = 'Submit';

  removedLinks.classList.add('removed-links');

  publishedLinks.append(addLinksButton);
  publishedLinks.append(newLinksErrors);
  publishedLinks.append(linksDiv);
  publishedLinks.append(newLinksForm);
  newLinksForm.append(newLinksTextarea);
  newLinksForm.append(newLinksSubmit);
  links.append(publishedLinks);
  links.append(removedLinks);

  for (const tag in event.tags) {
    if (event.tags.hasOwnProperty(tag)) {
      let eventTag = document.createElement('a');
      eventTag.href = window.location.href + '?tag=' + event.tags[tag];
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
      link.target = '_blank';
      link.innerHTML = event.links[published_link];
      linksDiv.append(link);
    }
  }

  table.append(links);

  enableTitle(title);
  eventDiv.append(table);
  enableEvent(eventDiv);

  document.querySelector('#inputs').after(eventDiv);
}

function enableSaveButton(saveButton) {
  saveButton.addEventListener('click', function () {
    let data = JSON.stringify({
      event_id: saveButton.closest('.event').id
    });

    request.send('POST', 'event/save', data, csrfToken, true);
    request.handleResponse('success', function () {
      alert('Event saved.');
      saveButton.parentElement.prepend('Saved');
      saveButton.remove();
    });
  });
}