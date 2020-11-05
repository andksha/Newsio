export let xmlRequest = new XMLHttpRequest();

export function send(method, url, data, csrfToken, async = false) {
  xmlRequest.open(method, location.protocol + '//' + location.host + '/' + url, async);
  xmlRequest.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
  xmlRequest.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xmlRequest.send(data);
}