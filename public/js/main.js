//Слушатели
var main_form = document.getElementById('subscribe_form');
main_form.addEventListener('submit', function(event) {
    onSubscribe(this);
    event.preventDefault();
});
main_form.addEventListener('keydown', function() {
    this.elements[0].classList.remove('onerror');
    if (this.elements[1].disabled) {
        this.elements[1].disabled = false;
    }
    var div = document.getElementById('error_popup');
    if (div) {
        div.remove();
    }
});

//Основная функция
function onSubscribe(form){
    if (!validateEmail(form.elements[0].value)) {
        renderError(form, 'К сожалению, введеный Вами адрес электронной почты некорректен.<br>Пожалуйста, исправьте ошибки и попробуйте снова.')
    } else {
        form.elements[0].classList.add('on-load');
        form.elements[0].disabled = true;
        form.elements[1].disabled = true;

        reqwest({
            url: 'api/v1/follow',
            method: 'post',
            type: 'json',
            data: {email: form.elements[0].value},
            contentType: 'application/x-www-form-urlencoded',
            error: function (date) {
                console.log(date);
                form.elements[0].classList.remove('on-load');
                form.elements[0].disabled = false;

                date = JSON.parse(date.response);
                renderError(form, date.property.text)
            },
            success: function (date) {
                console.log(date);
                renderSuccess(form, date.property.text);
            }
        });
    }
}

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

function renderError(form, text) {
    form.elements[0].classList.add('onerror');

    var div = document.createElement('div');
    div.id = 'error_popup';
    div.className = 'row popup';
    div.innerHTML = '<p class="footer-copyright">'+ text +'</p>';

    var old_div = document.getElementById('error_popup');
    if (old_div) {
        old_div.innerHTML = div.innerHTML;
    } else {
        document.getElementById('dynamic-wrapper').appendChild(div);
    }
}

function renderSuccess(form, text) {
    var content = '<div class="row icon success"></div>';
    content += '<p class="info-text mail">' + form.elements[0].value + '</p>';
    content += '<p class="info-text">' + text + '</p>';

    document.getElementById('dynamic-wrapper').innerHTML = content;
}