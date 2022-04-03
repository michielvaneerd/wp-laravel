<h1>This is a settings page from Laravel</h1>

@foreach($stats as $stat)
<p>Stat: {{ $stat->key }}</p>
@endforeach

{{ trans('validation.accepted', ['attribute' => 'Test']) }}