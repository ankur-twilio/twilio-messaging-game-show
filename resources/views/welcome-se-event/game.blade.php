@extends('welcome-se-event.layout')
@section('content')
	<div class="text-instruction">Send <span>Hello</span> to <br>+1 (866) 338-0030 or <br>WhatsApp +1 (304) 337-5429
	</div>

	<div class='game-selection'> 
		Game: {{ $game->name }} <a href="/game/{{ $game->id }}/questions">[Start]</a> 

		<h4>Players</h4>
		<ul id="player-list">

		</ul>
	</div>
@endsection