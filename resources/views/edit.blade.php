@extends('layouts.app')

@section('content')

<ul class="collection with-header">

	<li class="collection-header"><h4>Дни недели</h4></li>

	@foreach ($weekDays as $weekDay)

	<li class="collection-item">
		<div class="flex-center">
			<form class="inline-form" method="POST" action="/admin/edit/weekday/{{ $weekDay->id }}" >
				{{ csrf_field() }}{{ method_field('PUT') }}

				<p style="width: 20px">{{ $weekDay->name }}</p>

				<select name="stamp_beg" class="browser-default">
					@for ($i = 0; $i < 48; $i++)
					<option value="{{ $i }}"
						@if ($i == $weekDay->stamp_beg )
						selected
						@endif
					>{{ \App\library\LocalTiming::stampToStr($i) }}</option>
					@endfor
				</select>

				<select name="stamp_end" class="browser-default">
					@for ($i = 0; $i < 48; $i++)
					<option value="{{ $i }}"
						@if ($i == $weekDay->stamp_end )
						selected
						@endif
					>{{ \App\library\LocalTiming::stampToStr($i) }}</option>
					@endfor
				</select>

				<label>
					<input name="day_off" type="checkbox"
						@if ($weekDay->day_off)
						checked="checked"
						@endif
					/><span>выходной</span>
				</label>

				<input type="submit" class="btn" value="OK" title="сохранить изменения">

			</form>

		</div>

	</li>

@endforeach

</ul>

<ul class="collection with-header">

	<li class="collection-header"><h4>Специальные дни</h4></li>

	@foreach ($specialDays as $specialDay)

	<li class="collection-item">
		<div class="flex-center">
			<form class="inline-form" method="POST" action="/admin/edit/specialday/{{ $specialDay->id }}" >
				{{ csrf_field() }}{{ method_field('PUT') }}

				<input type="date" name="date" value="{{ $specialDay->date }}">

				<select name="stamp_beg" class="browser-default">
					@for ($i = 0; $i < 48; $i++)
					<option value="{{ $i }}"
						@if ($i == $specialDay->stamp_beg )
						selected
						@endif
					>{{ \App\library\LocalTiming::stampToStr($i) }}</option>
					@endfor
				</select>

				<select name="stamp_end" class="browser-default">
					@for ($i = 0; $i < 48; $i++)
					<option value="{{ $i }}"
						@if ($i == $specialDay->stamp_end )
						selected
						@endif
					>{{ \App\library\LocalTiming::stampToStr($i) }}</option>
					@endfor
				</select>

				<label>
					<input name="day_off" type="checkbox"
						@if ($specialDay->day_off)
						checked="checked"
						@endif
					/><span>выходной</span>
				</label>

				<input type="submit" class="btn" value="OK" title="сохранить изменения">

			</form>

			<form class="secondary-content" method="POST" action="/admin/edit/specialday/{{ $specialDay->id }}" >
				{{ csrf_field() }}{{ method_field('DELETE') }}
				<input type="submit" class="red lighten-1 btn" value="X" title="удалить">
			</form>

		</div>

	</li>

@endforeach
	<li class="collection-item">
		<div class="flex-center">
			<form class="inline-form" method="POST" action="/admin/edit/specialday/" >
				{{ csrf_field() }}

				<input type="date" name="date">

				<select name="stamp_beg" class="browser-default">
					@for ($i = 0; $i < 48; $i++)
					<option value="{{ $i }}">
						{{ \App\library\LocalTiming::stampToStr($i) }}
					</option>
					@endfor
				</select>

				<select name="stamp_end" class="browser-default">
					@for ($i = 0; $i < 48; $i++)
					<option value="{{ $i }}"
						@if ($i == 40 )
						selected
						@endif
					>{{ \App\library\LocalTiming::stampToStr($i) }}
					</option>
					@endfor
				</select>

				<label>
					<input name="day_off" type="checkbox"><span>выходной</span>
				</label>

				<input type="submit" class="btn" value="добавить">

			</form>

		</div>

	</li>
</ul>

<ul class="collection with-header col sm6">

	<li class="collection-header"><h4>Столы</h4></li>

	<table>
      <tr>
          <th>#</th>
          <th>Тип</th>
          <th>Мест</th>
          <th></th>
      </tr>

	@foreach ($tables as $table)

      <tr>
          <td><b>{{ $table->id }}</b></td>
          <td>{{ $table->type->name }}</td>
          <td>({{ $table->size }})</td>
          <td><form method="POST" action="/admin/edit/tables/{{ $table->id }}" >
          	{{ csrf_field() }}{{ method_field('DELETE') }}
          	<input type="submit" class="btn btn-small btn-floating red lighten-1" value="x" title="удалить">
          </form></td>
      </tr>

	@endforeach
	</table>

	<li class="collection-item">
		<div class="flex-center">
			<form class="inline-form" method="POST" action="/admin/edit/tables/" >
				{{ csrf_field() }}

				<select name="size" class="browser-default">
					@foreach ($tableTypes as $type)
					<option value="{{ $type->size }}">
						{{ $type->name }} ({{ $type->size }})
					</option>
					@endforeach
				</select>

			
				<input type="submit" class="btn" value="добавить">

			</form>

		</div>

	</li>

</ul>


@endsection

