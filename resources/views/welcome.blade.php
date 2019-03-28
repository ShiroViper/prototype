@extends('layouts.guest')

@section('title')
{{-- <title>{{ config('app.name', 'Alkansya') }}</title> --}}
<title>Welcome to Alkansya</title>
@endsection

@section('content')
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
<div class="main-feature">
    <div class="container">
        <div class="main-header">
            <div class="row">
                <div class="col">
                    <div class="text-primary">
                        <span class="welcome-title header pr-2">Welcome to</span> <span class="brand-title-header">ALKANSYA</span>
                    </div>
                    <div class="featured-desc my-3">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut earum doloremque quas a maiores amet expedita modi, ea magnam quis eum corrupti, deleniti quia tenetur eligendi illum tempora nemo eos?</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis repudiandae hic nesciunt cum, non, recusandae quod dolor earum deleniti ipsam ipsum vitae fugiat aliquid quasi molestiae vel architecto mollitia, nobis.</p>
                    </div>
                    <div class="row py-3">
                        <div class="col col-sm col-md col-lg-10 col-xl-6">
                            {{-- <a href="/login" class="btn btn-outline-secondary px-3">Already a member</a> --}}

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#member">
                                I want to be a member
                            </button>
                            <a href="/login" class="btn btn-outline-primary ml-3">Members Sign In</a>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4 featured-img">
                    <img src="img/img.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="addon-feature pt-5">
            <div class="row mb-3">
                <div class="col">
                    <span class="text-info title-header">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</span>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-lg">
                    <div class="box bg-light border rounded mb-3 shadow">
                        <div class="row d-flex align-items-center m-auto">
                            <div class="col">
                                <h4 class="title-header font-weight-bold">Easy</h4>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2 col-lg-3">
                                <img src="img/ez.png" alt="" class="img-fluid" min-height="33px">
                            </div>
                        </div>
                        <div class="row px-3">
                             <div class="col col-md featured-desc">
                                <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse cupiditate a quis laboriosam facilis omnis non dolorum modi aperiam.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="box bg-light border rounded mb-3 shadow">
                        <div class="row d-flex align-items-center m-auto">
                            <div class="col">
                                <h4 class="title-header font-weight-bold">Fast</h4>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2 col-lg-3">
                                <img src="img/rr.png" alt="" class="img-fluid" style="max-height: 60px;">
                            </div>
                            <div class="row px-3">
                                <div class="col col-md featured-desc">
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, facilis vitae! Aperiam numquam vitae, facilis illo, nostrum itaque provident maiores.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="box bg-light border rounded mb-3 shadow">
                        <div class="row d-flex align-items-center m-auto">
                            <div class="col">
                                <h4 class="title-header font-weight-bold">Reliable</h4>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2 col-lg-3">
                                <img src="img/tu.png" alt="" class="img-fluid" style="max-height: 60px;">
                            </div>
                            <div class="row px-3">
                                <div class="col col-md featured-desc">
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam odio esse cumque earum minima quidem qui doloremque hic possimus porro.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  
  <!-- Modal -->
<div class="modal fade" id="member" tabindex="-1" role="dialog" aria-labelledby="memberLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="memberLabel">Be part of us</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Please enter your credentials</h6>
                {!!Form::open(['action'=> 'MemberRequestController@memberRequest', 'method'=>'POST']) !!}
                    @csrf

                    <div class="form-group">
                        {{ Form::label('lname', 'Last Name') }}
                        {{ Form::text('lname', '', ['class' => $errors->has('lname') ? 'form-control is-invalid' : 'form-control']) }}
                        @if ($errors->has('lname'))
                            <div class="invalid-feedback">{{ $errors->first('lname') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        {{ Form::label('fname', 'First Name') }}
                        {{ Form::text('fname', '', ['class' => $errors->has('fname') ? 'form-control is-invalid' : 'form-control']) }}
                        @if ($errors->has('fname'))
                            <div class="invalid-feedback">{{ $errors->first('fname') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        {{ Form::label('mname', 'Middle Name') }}
                        {{ Form::text('mname', '', ['class' => $errors->has('mname') ? 'form-control is-invalid' : 'form-control']) }}
                        @if ($errors->has('mname'))
                            <div class="invalid-feedback">{{ $errors->first('mname') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        {{ Form::label('email', 'Email') }}
                        {{ Form::email('email', '', ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control']) }}
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <small class="text-muted"><b>Note</b>: Please wait for our administrator to validate your request.</small>
                    <div class="pt-3">
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary btn-block mb-3']) }}
                    </div>
                {!!Form::close()!!}
                {{-- <form method="POST" action="/request" >  
                    <div class="form-group">
                        <label for="">Last Name</label>
                        <input type="text" class="form-control" name="lname">
                    </div>
                    <div class="form-group">
                        <label for="">First Name</label>
                        <input type="text" class="form-control" name="fname">
                    </div>
                    <div class="form-group">
                        <label for="">Middle Name</label>
                        <small>*Optional</small>
                        <input type="text" class="form-control" name="mname">
                    </div>
                    <div class="form-group">
                        <label for="">Email Address</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <small class="text-muted"><b>Note</b>: Please wait for our administrator to validate your request.</small>
                    <div class="py-3">
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    @if (count($errors) > 0)
        $('#member').modal('show');
    @endif
</script>
@endsection