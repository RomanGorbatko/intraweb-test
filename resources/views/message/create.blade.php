<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>The HTML5 Herald</title>
    <meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
    <form>
        <input type="text" name="message" size="20" placeholder="Message" />
        <input type="submit" />
    </form>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('form').on('submit', function (e) {
            e.preventDefault();

            var form = $(this);
            var message = form.find('input[name="message"]');

            $.post('/message', {message: message.val()})
                .done(function () {
                    return alert('Сообщение отправленно');
                })
                .fail(function () {
                    return alert('Ошибка');
                })
            ;

            message.val('');
        });
    </script>
</body>
</html>