@extends('layouts.app')

@section('content')

<table class="centered">
<thead>
  <tr>
      <th>#</th>
      <th>Дата</th>
      <th>Размер</th>
      <th>Начало</th>
      <th>Окончание</th>
      <th>Имя</th>
      <th>Телефон</th>
      <th>Стол</th>
      <th>Удалить</th>
  </tr>
</thead>

<tbody>
	@foreach ($reserves as $reserve)
		@if ($reserve->confrimed)
			<tr class="teal lighten-5">
		@else
			<tr class="">
		@endif

			<td><b>{{ $reserve->id }}</b></td>
			<td>{{ date('d M', strtotime($reserve->date))}}</td>
			<td>{{ $reserve->table_size }}</td>
			<td>{{ date('H:i', strtotime($reserve->time_beg)) }}</td>
			<td>{{ date('H:i', strtotime($reserve->time_end)) }}</td>
			<td>{{ $reserve->name }}</td>
			<td>{{ $reserve->phone }}</td>

			<td>
    		@if (!$reserve->confrimed)
    		<form method="POST" action="/admin/edit/reserves/{{ $reserve->id }}" >
            	{{ csrf_field() }}{{ method_field('PUT') }}
            	<input type="submit" class="btn btn-small" value="Подтвердить">
            </form>
            @else
            {{ $reserve->table->id }}
    		@endif
    		</td>

            <td><form method="POST" action="/admin/edit/reserves/{{ $reserve->id }}" >
            	{{ csrf_field() }}{{ method_field('DELETE') }}
            	<input type="submit" class="btn btn-floating red lighten-1" value="X">
            </form></td>

		</tr>
	@endforeach
 
</tbody>
</table>

@endsection