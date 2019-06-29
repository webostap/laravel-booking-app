function BookingForm(formId, resId, arDates) {
    
    "use strict";
    
    var form = document.getElementById(formId);
    var success = document.getElementById(resId);
    var button = form.querySelector('button[type=submit]');

    //form fields
    var name = form.querySelector('#name');
    var phone = form.querySelector('#phone');
    var token = form.querySelector('input[name=_token]');

    //edited labels
    var phoneLabel = form.querySelector('label[for=phone]');
    var dateLabel = form.querySelector('label[for=datepicker]');

    //materialize elems
    var selects = form.querySelectorAll('select');
    var datepicker = form.querySelector('.datepicker');

    var time = new Time(selects[2]);

    var lastDate;
    var pickerParams = {
        i18n: i18nTimesRus,
        firstDay: 1,
        minDate: new Date(),
        setDefaultDate: true,
        format: "yyyy-mm-dd",
        disableDayFn(d) {
            if (arDates.specialDays.includes(d.yyyymmdd('-'))) {
                return arDates.freeDates.includes(d.yyyymmdd('-'));
            } else {
                return (arDates.freeDays.includes(d.getDay7()));
            }
        },
        onOpen() {
            lastDate = datepicker.value;
        },
        onClose() {
            if (datepicker.value && datepicker.value != lastDate) {
                sDate.value = prettyDate(datepicker.value);
                dateLabel.classList.add('active');

                if (selects[0].value && selects[1].value) {
                    getDateStamps();
                }
            }
        }
    };

    //init materialize plugins
    var datepickerI = M.Datepicker.init(datepicker, pickerParams);
    var selectI = M.FormSelect.init(selects);

    //setting phone mask
    var formattedPhone = new Formatter(phone, {
        'pattern': '+7({{999}}) {{999}} {{99}} {{99}}',
        'persistent': true
    });
    phoneLabel.classList.add('active');

    //prepare for pretty date output
    var datePickerParent = datepicker.parentElement;
    var sDate = document.createElement("input");
    datepicker.type = 'hidden';
    sDate.type = 'text';
    sDate.readOnly = true;
    datePickerParent.append(sDate);


    sDate.onclick = function() {
        datepickerI.open();
    }
    sDate.onkeypress = function(e) {
        e.preventDefault();
        if (e.keyCode === 13) {
            datepickerI.open();
        }
    }


    //preselected date correction
    if (datepicker.value) {
        sDate.value = prettyDate(datepicker.value);
    }

    //bind selectors
    if (selects[0].value && selects[1].value) {
        getNearDateStamps(datepicker.value);
    } else time.disable();

    for (let i = 0; i < 2; i++) {
        selects[i].onchange = function() {
            if (selects[0].value && selects[1].value) {
                getNearDateStamps(datepicker.value);
            } else time.disable();
        }
    }

    button.onclick = function(e) {
        e.preventDefault();
        submit();
    }



    function getLoadObj() {
        return {
            table_size: selects[0].value,
            duration: selects[1].value,
            date: datepicker.value,
        };
    }

    function getFormData() {
        var formData = getLoadObj();
        
        formData.stamp_beg = selects[2].value;
        formData.name  =  name.value;
        formData.phone = phone.value;
        formData.token = token.value;

        return formData;
    }

    function getDateStamps() {

        time.disable();

        xhrGET(base_dir + '/time', getLoadObj(), function(stamps) {
            time.Update(stamps);
        });
    }
    function getNearDateStamps() {

        time.disable();

        xhrGET(base_dir + '/date', getLoadObj(), function(response) {

            datepicker.value = response.date;
            datepickerI.setDate(new Date(response.date));
            sDate.value = prettyDate(response.date);
            dateLabel.classList.add('active');
            time.Update(response.stamps);

        });
    }

    function submit() {
        
        xhrPOST(base_dir + '/submit', getFormData(), function(response) {

            if (response == 3) {
                M.toast({
                    html: 'Все поля обязательны для заполнения!'
                });
            } else if (response == 2) {
                M.toast({
                    html: 'К сожалению, в это время мест нет<br>Но мы обновили данные специально для вас<br>Пожалуйста, выберете заново'
                });
                getNearDateStamps(datepicker.value);
            } else if (response == 1) {
                form.classList.add('out');
                success.classList.add('open');
            }

        });
    }
}