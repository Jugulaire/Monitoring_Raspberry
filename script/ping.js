//Ping du serveur 
// Indiquez IP ci dessous 

var ip = window.location.toString();
var arrayIp=ip.split("/");
var ip = arrayIp[2];

function ping(ip, callback) {

    if (!this.inUse) {
        this.status = 'unchecked';
        this.inUse = true;
        this.callback = callback;
        this.ip = ip;
        var _that = this;
        this.img = new Image();
        this.img.onload = function () {
            _that.inUse = false;
            _that.callback('Online');

        };
        this.img.onerror = function (e) {
            if (_that.inUse) {
                _that.inUse = false;
                _that.callback('Online', e);
            }

        };
        this.start = new Date().getTime();
        this.img.src = "http://" + ip;
        this.timer = setTimeout(function () {
            if (_that.inUse) {
                _that.inUse = false;
                _that.callback('Timeout');
            }
        }, 1500);
    }
}
setInterval(function() {
  ping( ip,function(data){
  elem = document.getElementById('etat');
  elem.innerHTML= "&nbsp" + data;
  if (data == "Timeout")
  {
  	elem.style.color = "rgba(254,0,0,0.8)";
  } 
  else
  {
	elem.style.color = "rgba(0,174,67,1)";
  }
});
}, 10000);
