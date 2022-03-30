<h1>Hoi allemaal!</h1>

@foreach($stats as $stat)
<p>Ja: {{ $stat->key }}</p>
@endforeach

{{ trans('validation.accepted', ['attribute' => 'Test']) }}