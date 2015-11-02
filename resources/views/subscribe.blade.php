<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width" />
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Kurale&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <title>AlterBooks</title>
</head>
<body>
    <div class="container">
        <div class="row header">
            <h1><span class="red">A</span>lter<span class="red">B</span>ooks</h1>
            <p class="moto">книги для людей, а не издательств</p>
        </div>
        <div id="dynamic-wrapper">
            <div class="row mainform">
                <form name="subscribeform" id="subscribe_form">
                    <input type="text" class="textfield" placeholder="Введите ваш e-mail...">
                    <button class="button" id="send_button"><span class="button-sign">ПОДПИСАТЬСЯ</span></button>
                </form>
            </div>
        </div>
    </div>
    <div class="row footer">
        <p class="footer-copyright">
            AlterBooks <span class="footer-copyright-sign">©</span> 2015<br>Inkognitoo
        </p>
    </div>
</body>
<script>
    //Слушатели
    var main_form = document.getElementById('subscribe_form');
    main_form.addEventListener('submit', function(event) {
        onSubscribe(this);
        event.preventDefault();
    });
    main_form.addEventListener('keydown', function(event) {
        this.elements[0].classList.remove('onerror');
        var div = document.getElementById('error_popup');
        if (div) {
            div.remove();
        }
    });

    //Основная функция
    function onSubscribe(form){
        if (!validateEmail(form.elements[0].value)) {
            renderError(form, 'К сожалению, введеный Вами адрес электронной почты некорректен.<br>Пожалуйста, исправьте ошибки и попробуйте снова.')
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
</script>
</html>