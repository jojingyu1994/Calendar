<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>
    <body class="antialiased">
		<form method="post" action = '/index'>
			@csrf <!--csrf설정 -->
			<label>음력임력</label>
			<input type="date" id="lun_date" name= "lun_date" value="">
			<input type="submit" value="submit">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		</form>
    </body>
</html>
