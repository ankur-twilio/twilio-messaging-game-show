@extends('welcome-se-event.layout')
@section('content')

	@if (session('status'))
	    <div class="alert alert-success">
	        {{ session('status') }}
	    </div>
	@endif
	
	<div class="text-instruction">Send <span>Hello</span> to <br>+1 (866) 338-0030 or <br>WhatsApp +1 (304) 337-5429
	</div>

	@foreach($games as $tempGame)
		<div class='game-selection'> 
			Game: {{ $tempGame->name }} <a href="/game/{{ $tempGame->id }}">[Enter]</a> 
			<form method="post" action="/game/{{ $tempGame->id }}/wipe" class="inline">
				@csrf
				<button type="submit" class="link-button">[Wipe]</button>
			</form>
		</div>
	@endforeach
@endsection