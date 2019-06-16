"use strict";

class Time {
	constructor(node) {
		this.node = node;
	}

	disable () {
		this.node.disabled = true;
		this.node.firstElementChild.selected = true;
		M.FormSelect.init(this.node);
	}
	enable () {
  		this.node.disabled = false;
  		M.FormSelect.init(this.node);
	}

	static stampToStr (stamp) {
    	let i = stamp%2 ? '30' : '00';
    	let H = Math.floor(stamp/2) + 6;
		if (H >= 24) H-= 24;

		H = H < 10 ? '0'+H : H

		return H+':'+i;
	}

	Update (arStamps) {

		let child = this.node.lastElementChild;  
		while (child && child != this.node.firstElementChild) { 
			this.node.removeChild(child); 
			child = this.node.lastElementChild; 
		}

		if(!arStamps) {
			this.node.disabled = true;
			this.node.firstElementChild.selected = true;
			this.node.firstElementChild.innerHTML = 'мест нет';
			M.FormSelect.init(this.node);
			return;
		}
		else this.node.firstElementChild.innerHTML = 'Время';

		var self = this;
		arStamps.forEach(function(stamp, i) {
			let option = document.createElement('option');
			option.value = stamp;
			option.innerHTML = self.constructor.stampToStr(stamp);
			if (!i) option.selected = true;

			self.node.appendChild(option);
		});
  		this.node.disabled = false;
        M.FormSelect.init(this.node);
	}
}

Date.prototype.getDay7 = function() {
	return this.getDay() ? this.getDay() : 7;
};
Date.prototype.yyyymmdd = function(div = '-') {
  var mm = this.getMonth() + 1;
  var dd = this.getDate();

  return [this.getFullYear(),
          (mm>9 ? '' : '0') + mm,
          (dd>9 ? '' : '0') + dd
         ].join(div);
};
function prettyDate(str) {
	var now = new Date();
    var today = new Date(now.yyyymmdd('-'));
    var selectedDate = new Date(str);
	if(today.valueOf()==selectedDate.valueOf()) {
	    return 'Сегодня ('+selectedDate.getDate()+' '+i18nTimesRus.monthsCase[selectedDate.getMonth()]+')';
	}
	else {
	    return i18nTimesRus.weekdays[selectedDate.getDay7()-1]+' ('+selectedDate.getDate()+' '+i18nTimesRus.monthsCase[selectedDate.getMonth()]+')';
	}
};



function xhrGET (request, paramsObj, cb) {

  var xhr = new XMLHttpRequest();

  var requestParams = '?';
  for (let key in paramsObj) {
  	requestParams+= key+'='+paramsObj[key]+'&';
  }

  xhr.open('GET', request+requestParams);
  xhr.send();

  xhr.onreadystatechange = function () {
	if (this.readyState === 4)
		if (this.status == 200 && this.status < 300)
            if( typeof cb === 'function' )
                cb(JSON.parse(xhr.responseText));
  }
}


function xhrPOST (request, paramsObj, cb) {

  var xhr = new XMLHttpRequest();

  xhr.open('POST', request);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.setRequestHeader("X-CSRF-Token", paramsObj['token']);
  xhr.send(JSON.stringify(paramsObj));

  xhr.onreadystatechange = function () {
	if (this.readyState === 4)
		if (this.status == 200 && this.status < 300)
            if( typeof cb === 'function' )
                cb(JSON.parse(xhr.responseText));
  }
}