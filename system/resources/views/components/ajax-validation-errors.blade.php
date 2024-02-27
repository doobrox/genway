@props(['errors', 'bag' => null])

@if ($bag != null && $errors->getBag($bag) && $errors->$bag->any())
    @foreach ($errors->$bag->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
@endif
