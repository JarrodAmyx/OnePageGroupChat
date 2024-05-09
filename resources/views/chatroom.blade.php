<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="/assets/css/style.css">
    <title>Simple Chat App</title>
</head>
<body>
	@if($errors->any())
		<div class="alert alert-danger" style="background-color:#f66">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{!! $error !!}</li>
				@endforeach
			</ul>
		</div>
		<br>
	@endif
	<div id="overlay">
		<div id="loader" class="loader"></div>
	</div>
	<div id="main">
		@include( $body )
	</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>
