@if (session()->has('message'))
    @if(is_array(session('message')))
        @foreach(session('message') as $message)
            <div id="flash-message" class="alert alert-success" role="alert">
                {{$message}}
            </div>
        @endforeach
    @else
            <div id="flash-message" class="alert alert-success" role="alert">
                {{session('message')}}
            </div>
    @endif
@endif