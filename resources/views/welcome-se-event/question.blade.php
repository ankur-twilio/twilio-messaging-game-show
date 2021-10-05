@extends('welcome-se-event.layout')
@section('content')
	@if($question->type == '1_to_5');
	<div class="text-instruction">Send a number <span>1-5</span> to <br>+1 (866) 338-0030 or <br>WhatsApp +1 (304) 337-5429
	</div>
	@endif

	@if($question->type == 'multiple_choice');
	<div class="text-instruction">Send a letter <span>A-D</span> to <br>+1 (866) 338-0030 or <br>WhatsApp +1 (304) 337-5429
	</div>
	@endif
	
	@if($question->type == 'free_response');
	<div class="text-instruction">Send <span>anything</span> to <br>+1 (866) 338-0030 or <br>WhatsApp +1 (304) 337-5429
	</div>
	@endif

	<h1>{{ $question->title }}</h1>
	@if ($question->use_options)
	<div class="questions">
		@foreach($question->options as $key => $option)
			<div class='game-selection {{ $question->type }}'>
				@if ($question->type == 'multiple_choice')
					<h2>{{ $key . ': ' . $option }}</h2>
				@else
					<h2>{{ $key }}</h2>
				@endif
				<span id="key-{{ $key }}-count">0</span> votes
			</div>
		@endforeach
	</div>
	@endif

	@if ($question->type == 'free_response')
	<div class="questions">
		<div class='game-selection {{ $question->type }}'>
			<ul id="free_response"></ul>
		</div>
	</div>
	@endif
@endsection