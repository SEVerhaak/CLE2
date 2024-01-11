<?php

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label" for="email">Email</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control has-icons-left">
                    <input class="input" id="email" type="text" name="email" value="" />
                    <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label" for="password">Password</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control has-icons-left">
                    <input class="input" id="password" type="password" name="password"/>
                    <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal"></div>
        <div class="field-body">
            <button class="button is-link is-fullwidth" type="submit" name="submit">Log in</button>
        </div>
    </div>


</body>
</html>
