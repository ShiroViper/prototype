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
                  
                    {!!Form::open(['action'=> 'MemberRequestController@memberRequest', 'method'=>'POST' , 'enctype' => 'multipart/form-data']) !!}
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
                            {{ Form::label('street_number', 'Street Number') }}
                            {{ Form::text('street_number', '', ['class' => $errors->has('street_number') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('street_number'))
                                <div class="invalid-feedback">{{ $errors->first('street_number') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('barangay', 'Barangay') }}
                            {{ Form::text('barangay', '', ['class' => $errors->has('barangay') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('barangay'))
                                <div class="invalid-feedback">{{ $errors->first('barangay') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            {{ Form::label('city_town', 'City/Town') }}
                            {{ Form::text('city_town', '', ['class' => $errors->has('city_town') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('city_town'))
                                <div class="invalid-feedback">{{ $errors->first('city_town') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('province', 'Province') }}
                            {{ Form::text('province', '', ['class' => $errors->has('province') ? 'form-control is-invalid' : 'form-control']) }}
                            @if ($errors->has('province'))
                                <div class="invalid-feedback">{{ $errors->first('province') }}</div>
                            @endif
                        </div>
                    
                        <div>
                        {{Form::label('id_type', 'ID Type')}}
                        {{Form::select('id_type', [
                        'Passport' => 'Passport', 
                        'Driver\'s License' => 'Driver\'s License', 
                        'SSS Card' => 'SSS Card', 
                        'Professional Regulation Commission (PRC ID)' => 'Professional Regulation Commission (PRC ID)', 
                        'GSIS e-Card Plus2' => 'GSIS e-Card Plus2', 
                        'Postal ID' => 'Postal ID', 
                        'Unified Multi-Purpose ID' => 'Unified Multi-Purpose ID', 
                        'NBI Clearance' => 'NBI Clearance', 
                        'Alien Certificate of Registration' => 'Alien Certificate of Registration',
                        'AFP ID' => 'AFP ID', 
                        'NCWDP Certificate' => 'NCWDP Certificate', 
                        'IBP ID' => 'IBP ID', 
                        'OWWA ID' => 'OWWA ID', 
                        'Seaman\'s Book' => 'Seaman\'s Book', 
                        'Bureau of Fire Protection ID' => 'Bureau of Fire Protection ID', 
                        'Integrated Bar of the Philippines ID' => 'Integrated Bar of the Philippines ID', 
                        'Philippine National Police ID' => 'Philippine National Police ID', 
                        'Police Clearance Card' => 'Police Clearance Card', 
                        'Police Clearance Certificate' => 'Police Clearance Certificate'],                         
                        "",
                        ['class' => 'form-control']
                        )}}
                        </div>


                        <div class="form-group">
                            {{Form::label('face_photo', 'Photo')}}
                            {{Form::file('face_photo'), ['required', 'accept' => 'image/*']  }}
                           
                        </div>

                        <div class="form-group">
                            {{Form::label('front_id_photo', 'Front ID Photo')}}
                            {{Form::file('front_id_photo'), ['required', 'accept' => 'image/*']  }}
                           
                        </div>
                        
                        <div class="form-group">
                            {{Form::label('back_id_photo', 'Back ID Photo')}}
                            {{Form::file('back_id_photo', ['required', 'accept' => 'image/*'])}}
                        </div>

	
                        <small class="text-muted"><b>Note</b>: Please wait for our administrator to verify your request.</small>
                        <div class="pt-2">
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary btn-block mb-3']) }}
                        </div>    
                        
<script type="text/javascript">
    @if (count($errors) > 0)
        $('#member').modal('show');
    @endif
   
  </script>
@endsection