@extends($theme_path.'.layouts.theme')

@section('content')

	@foreach($settings['content_for_index'] as $section)
		@include($theme_path.'.components.'.$settings['sections'][$section]['type'], ['section_id' => $section, 'section' => $settings['sections'][$section]])
	@endforeach
	
@endsection