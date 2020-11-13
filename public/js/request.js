export let xmlRequest = new XMLHttpRequest();

export function send(method, url, data, csrfToken, async = false) {
  xmlRequest.open(method, location.protocol + '//' + location.host + '/' + url, async);
  xmlRequest.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
  xmlRequest.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xmlRequest.send(data);
}

export function handleResponse(responseName, closure) {
  xmlRequest.onload = function () {
    try {
      let response = JSON.parse(xmlRequest.responseText);

      if (typeof response.error_data != 'undefined' || typeof response.error_message != 'undefined') {
        document.querySelector('.response-error').innerHTML = response.error_message;
        document.querySelector('.response-error').style.display = 'block';
      } else if(typeof response[responseName] != 'undefined') {
        closure(response);
      } else {
        alert('Server error. Try again.');
      }
    } catch (e) {
      console.log(e);
      alert(e);
    }
  }
}