{{-- @if ( count($errors) > 0 )
    <div class="alert alert-danger alert-dismissible fade show pop-messages" role="alert">
        @foreach ($errors->all() as $error)
            <ul>
                <li>{{ $error }}</li>
            </ul>
        @endforeach
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif --}}

@if (session('success'))
    <div class="alert alert-success fade show pop-messages position-absolute w-50 text-center shadow" role="alert">
        <strong> {{ session('success') }} </strong>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger fade show pop-messages position-absolute w-50 text-center shadow" role="alert">
        <strong> {{ session('error') }} </strong>
    </div>
@endif