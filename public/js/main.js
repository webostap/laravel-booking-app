document.addEventListener('DOMContentLoaded', function() {

	var form = document.getElementById('form');
	var result = document.getElementById('result');
	var submit = form.querySelector('#submit');
	var token = form.querySelector('input[name=_token]');
	var name = form.querySelector('#name');
	var phone = form.querySelector('#phone');

	var successContent = document.getElementById('success');

    var selects = form.querySelectorAll('select');
    var selectI = M.FormSelect.init(selects);

    var time = new Time(selects[2]);

    var formattedPhone = new Formatter(phone, {
	  'pattern': '+7({{999}}) {{999}} {{99}} {{99}}',
	  'persistent': true
	});

    var datepicker = form.querySelector('.datepicker');
    var pickerParams = {
    	i18n: i18nTimesRus,
		firstDay: 1,
		minDate: new Date(),
		setDefaultDate: true,
		format: "yyyy-mm-dd",
		disableDayFn: function (d) {
			if (specialDays.includes(d.yyyymmdd('-'))) {
				return freeDates.includes(d.yyyymmdd('-'));
			}
			else {
				let weekDay = d.getDay() ? d.getDay() : 7;
				return (freeDays.includes(weekDay));
			}
		}
	};

    var datepickerI = M.Datepicker.init(datepicker, pickerParams);

    var loadObj;

    if (!selects[0].value || !selects[1].value) 
    	time.disable();

    // if (selects[0].value && selects[1].value) {
    else {

    	loadObj = {
	     	table_size:selects[0].value,
	     	duration: selects[1].value,
	     	date: datepicker.value,
	     	token: token.value 
	     }

		xhr('/date/', loadObj, function(response) {
			time.Update(response.stamps);
			console.log(response.date);

			pickerParams.defaultDate = new Date(response.date);
		  	pickerParams.onSelect = function (d) {
				loadObj.date = d.yyyymmdd('-');
				xhr('/time/', loadObj, function(stamps) {
					if (selects[0].value && selects[1].value) {
						time.Update(stamps);
				     	console.log(stamps);
				     }
			     });
			};
			datepickerI = M.Datepicker.init(datepicker, pickerParams);

		});
		
    }

	for (let i = 0; i < 2; i++) {
	  selects[i].onchange = function() {


	  	if (!(selects[0].value && selects[1].value))
	  		time.disable();

	  	else {

	  		loadObj = {
		     	table_size: selects[0].value,
		     	duration: selects[1].value,
		     	date: datepicker.value,
		     	token: token.value 
		     }


	  		xhr('/date/', loadObj, function(response) {
				time.Update(response.stamps);
				console.log(response.date);

				pickerParams.defaultDate = new Date(response.date);
			  	pickerParams.onSelect = function (d) {
					loadObj.date = d.yyyymmdd('-');
					xhr('/time/', loadObj, function(stamps) {
						if (selects[0].value && selects[1].value) {
							time.Update(stamps);
					     	console.log(stamps);
					     }
				     });
				};
				datepickerI = M.Datepicker.init(datepicker, pickerParams);
			});
	 	}
	  }
	}

	submit.onclick = function (e) {
		e.preventDefault();
		loadObj = {
	     	name: name.value,
	     	phone: phone.value,
	     	table_size: selects[0].value,
	     	duration: selects[1].value,
	     	stamp_beg: selects[2].value,
	     	date: datepicker.value,
	     	token: token.value 
	   	 };
		xhr('/submit/', loadObj, function(response) {

	     	if(response==3) {
	     		M.toast({html: 'Все поля обязательны для заполнения!'});
	     	}
	     	else if(response==2) {
	     		M.toast({html: 'К сожалению, в это время мест нет<br>Но мы обновили данные специально для вас<br>Пожалуйста, выберете заново'});

	     		xhr('/date/', loadObj, function(response) {
					time.Update(response.stamps);
					console.log(response.date);

					pickerParams.defaultDate = new Date(response.date);
				  	pickerParams.onSelect = function (d) {
						loadObj.date = d.yyyymmdd('-');
						xhr('/time/', loadObj, function(stamps) {
							if (selects[0].value && selects[1].value) {
								time.Update(stamps);
						     	console.log(stamps);
						     }
					     });
					};
					datepickerI = M.Datepicker.init(datepicker, pickerParams);

				});
	     	}
	     	else if(response==1) {
	     		form.classList.add('out');
	     		successContent.classList.add('open');
	     	}

	     });
	};


}); //DOMContentLoaded
