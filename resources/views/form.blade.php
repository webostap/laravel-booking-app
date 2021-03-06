<!DOCTYPE html>
<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
		<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
		<style type="text/css">body{background-image: url({{ URL::asset('images/invalid-name.jpg') }});}</style>
		<title></title>
	</head>
	<body>
	 <div class="floating-center">

		<div class="form-wrapper">
			<h1>Бронирование стола</h1>
			<form id="form" class="form" name="booking">
				<h2>Контакты</h2>
				<div class="row">
					<div class="input-field col s12 margin-v5">
						<input id="name" type="text">
						<label for="name">Имя</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12 margin-v5">
						<input id="phone" type="text">
						<label for="phone">Телефон</label>
					</div>
				</div>
				<h2>Стол</h2>
				<div class="row">
					<div class="input-field col s7">
						<select id="iSize">
							<option value="" selected>Выберете стол</option>
							@foreach ($formDate['tableTypes'] as $tableType)
								@if ($tableType->tables()->count())
								<option value="{{ $tableType->size }}">{{ $tableType->name }} ({{ $tableType->size }})</option>
								@endif
							@endforeach
						</select>
					</div>
					<div class="input-field col s5">
						<select id="iDuration">
							<option value="" selected>Длительность</option>

							<option value="2" style="display: none">1ч</option>
							<option value="4">2ч</option>
							<option value="6">3ч</option>
							<option value="8">4ч</option>
							
						</select>
					</div>
				</div>
				<h2>Время брони</h2>
				<div class="row">
					<div class="input-field col s7">
						<input id="iDate" type="text" class="datepicker">
						<label class="select-dropdown" for="datepicker" class="">Дата</label>
					</div>

					<div id="timePicker" class="input-field col s5">
						<select id="iTime" disabled="true">
							<option value="" selected>Время</option>
						</select>
					</div>
				</div>
				<button type="submit" id="submit" class="booking-button waves-effect waves-light ">Забронировать</button>
			<div id="result"></div>

		    {{ csrf_field() }}
			</form>

		    <div id="success" class="success">
		     <img src="{{ URL::asset('images/cotton-candy.svg') }}" alt="" class="cotton-candy">
		      <h2>Стол забронирован!</h2>
		      <p>Для подтверждения брони администратор позвонит<br> Вам по указанному номеру</p>
		    </div>
		</div>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
	<script src="{{ URL::asset('js/i18nTimesRus.js') }}"></script>
	<script src="{{ URL::asset('js/formatter.min.js') }}"></script>
	<script src="{{ URL::asset('js/fu.js') }}"></script>
	<script src="{{ URL::asset('js/form.js') }}"></script>

	<script type="text/javascript">

		var base_dir = '{{ URL::to('/') }}';

		var arDates = {
			specialDays:{!! $formDate['specialDays']!!},
			freeDays: 	{!! $formDate['freeDays'] 	!!},
			freeDates: 	{!! $formDate['freeDates'] 	!!}
		};
		document.addEventListener('DOMContentLoaded', function() {
		    bookingForm = new BookingForm('form', 'success', arDates);
		});

	</script>
</body></html>