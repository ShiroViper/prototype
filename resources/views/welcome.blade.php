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
                <div class="col col-md col-lg">
                    <div class="text-primary">
                        <span class="welcome-title header pr-2">Welcome to</span> <span class="brand-title-header">ALKANSYA</span>
                    </div>
                    <div class="featured-desc lead my-3">
                        <p>
                            Join us and be part of an organization that aims to help people in their financial needs. 
                        </p>
                        <p>Alkansya is a mobile responsive web application system that is used to gain access to features like account management, money transfer, deposit, loan and payment. Managed by a non-profit organization located in Compostela, Cebu, Alkanysa turns the manual procedure of sinking fund into a process that is systematic with the help of the internet.</p>
                    </div>
                    <div class="row py-3">
                        <div class="col col-sm col-md col-lg-10 col-xl-6 d-flex align-items-sm-center">
                            {{-- <a href="/login" class="btn btn-outline-secondary px-3">Already a member</a> --}}

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary px-3 mt-3" data-toggle="modal" data-target="#member">
                                I want to be a member
                            </button>
                            <a href="/login" class="btn btn-outline-primary ml-3 px-4 mt-3">Members Sign In</a>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4 col-lg-6 featured-img">
                    <img src="img/img.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>
        {{-- <div class="addon-feature pt-5">
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
                             <div class="col col-md featured-desc lead">
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
                                <div class="col col-md featured-desc lead">
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
                                <div class="col col-md featured-desc lead">
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam odio esse cumque earum minima quidem qui doloremque hic possimus porro.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
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
            <div class="bg-orange border text-white text-center p-3">
                Please wait for the Administrator to verify your request
            </div>
            <div class="modal-body">
                <div class="container">
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
                            <small class="text-muted">(Optional)</small>
                            {{ Form::text('mname', '', ['class' => $errors->has('mname') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('mname'))
                                <div class="invalid-feedback">{{ $errors->first('mname') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('cell_num', 'Contact Number') }}
                            {{ Form::text('cell_num', '', ['class' => $errors->has('cell_num') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('cell_num'))
                                <div class="invalid-feedback">{{ $errors->first('cell_num') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('email', 'Email') }}
                            {{ Form::email('email', '', ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('address', 'Complete Address', ['class' => 'mb-0']) }}
                            <div class="mb-2"><small class="text-muted">Street number, Barangay, City/Town, Province, Philippines, Zip Code</small></div>
                            {{ Form::textarea('address', '', ['class' => $errors->has('address') ? 'form-control is-invalid' : 'form-control', 'rows' => 2]) }}
                            @if ($errors->has('address'))
                                <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                            @endif
                            {{-- <small class="text-muted">Default Password: 123456</small> --}}
                        </div>

                        {{-- <small class="text-muted"><b>Note</b>: Please wait for our administrator to verify your request.</small> --}}
                        <div class="pt-2">
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
</div>

<script type="text/javascript">
    @if (count($errors) > 0)
        $('#member').modal('show');
    @endif
</script>
@endsection