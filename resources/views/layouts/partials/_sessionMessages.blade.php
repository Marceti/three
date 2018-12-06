@if (session()->has('message'))
    @foreach(session('message') as $message)
        <div id="flash-message" class="alert alert-success" role="alert">
            {{$message}}
        </div>
    @endforeach
@endif