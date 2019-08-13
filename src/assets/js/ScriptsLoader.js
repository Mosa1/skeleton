var loadedContent = {};


function loadCss(url) {
  if(typeof url == 'string') url = [url];

  for(var i = 0; i < url.length; i++) {
    var src = url[i];
    if(!loadedContent[src]) {
      var l = document.createElement( 'link' );
      l.rel = "stylesheet";
      l.type = "text/css";
      l.href = src;
      l.className="ll";
      document.body.appendChild(l)

      loadedContent[src] = true;
    }
  }
}

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