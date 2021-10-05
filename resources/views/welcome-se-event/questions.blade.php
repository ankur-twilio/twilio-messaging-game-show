@extends('welcome-se-event.layout')
@section('content')
	<div class="text-instruction">Send <span>Hello</span> to <br>+1 (866) 338-0030 or <br>WhatsApp +1 (304) 337-5429
	</div>

	<h1>{{ $game->name }}</h1>
		<div class="questions">
		@foreach($questions as $tempQuestion)
			<div class='game-selection'>
				{{ $tempQuestion->quick_title }} <a href="/game/{{ $game->id }}/question/{{ $tempQuestion->id }}">[Enter]</a>
			</div>
		@endforeach
		</div>
@endsection