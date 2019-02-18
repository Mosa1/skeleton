var loadedContent = {};

function loadScript(url, callback) {

  if (typeof url == 'string') url = [url];
  var loaded = 0;

  for (var i = 0; i < url.length; i++) {
    var src = url[i];

    if (loadedContent[src]){
      setTimeout(function () {
        callback && callback();
      },300);

      continue;
    }
    var s = document.createElement('script');
    loadedContent[src] = true;
    s.onload = function () {
      loaded++;
      if (loaded == url.length) callback && callback();
    };
    s.src = src;
    document.body.appendChild(s)

  }
}