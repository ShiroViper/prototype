@extends('layouts.app')

@section('title')
<title>Alkansya - Request Loan</title>
@endsection

@section('content')
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
@if(!$unpaid)
    <h3 class="header mt-2">Request Loan</h3>
    {{-- <div class="bg-teal p-3 text-white">
        The minimum loan amount is ₱200.
    </div> --}}
    <div class="row">
        <div class="col-sm-10 col-md-7 col-lg-5 order-2 order-md-1 my-3">  
            {!! Form::open(['action' => 'LoanRequestsController@store', 'method' => 'POST']) !!}
            @csrf
            {{Form::hidden('token', $token) }}

            {{ Form::label('amount', 'Loan Amount', ['class' => 'h6']) }}
            <div class="form-group">
                    {{ Form::number('amount', '', ['class' => $errors->has('amount') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Enter amount', 'min'=>'200', 'step' => '.01', 'required']) }}
                    <small class="text-muted">The minimum loan amount is ₱200.</small>
                @if ($errors->has('amount'))
                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                @endif
            </div>

            {{ Form::label('months', 'Months Payable', ['class' => 'h6']) }}
            <div class="form-group">
            {{ Form::number('months', '', ['class' => $errors->has('months') ? 'form-control is-invalid' : 'form-control', 'min'=>'1', 'max'=>'12', 'required']) }}
                @if ($errors->has('months'))
                    <div class="invalid-feedback">{{ $errors->first('months') }}</div>
                @endif
            </div>

            {{ Form::label('reason', 'Reason', ['class' => 'h6']) }}
            <div class="form-group">
                <select name="reason" id="reason" class="form-control {{$errors->has('reason') ? ' is-invalid s' : '' }} " required>
                    <option selected hidden>-- Select Reason --</option>
                    <option value="For Personal Use">For Personal Use</option>
                    <option value="For Emergency Use">For Emergency Use</option>
                    <option value="3">Other</option>
                </select>
                <textarea name="other" id="other" rows="1" class="form-control mt-2" placeholder="Other (please specify)"></textarea>
            </div>            
            @if ($errors->has('reason'))
                {{-- <div class="invalid-feedback">Please Select</div> --}}
                Please select <br> 
            @endif
            
            <label for="pass"> Password</label>
            <div class="form-group">
                <input type="password" name="pass" class="form-control" required>
            </div>

            <div class="form-group">
                <div class="form-check">
                    {!! Form::checkbox('agreement', 'yes', false, ['class' => 'form-check-input', 'id' => 'agreement', 'required']) !!}
                    {{ Form::label('agreement', 'I agree with the ') }} 
                    {!! "<a href='/terms' target='_blank'>Terms and Conditions</a>" !!}
                </div>
            </div>
            {{ Form::submit('Request', ['class' => 'btn btn-primary']) }}
            {{-- <button class="btn btn-primary" role="button" data-toggle="modal" data-target="#termsModal">Continue</button> --}}
            {!! Form::close() !!}
        </div>
        <div class="col-sm col-md offset-lg-1 col-lg-4 order-1 order-md-2 my-3 pt-3">  
            <div class="card shadow">
                <div class="card-body note-box border-warning border-left d-flex flex-column rounded">
                    <div class="h4">
                        {{-- {{ $savings == null ?  'No Savings' : ($savings->savings == null ? 'No Savings': '₱'.$savings->savings)  }} --}}
                        {{ $savings && $savings->savings != null ? '₱'.$savings->savings : 'No Savings' }}
                    </div>
                    <div>Current Savings</div>
                </div>
            </div>
            {{-- Note: <br>
            200 minimum loan amount. <br>
            personal and other reason is equal to savings while emergency reason is acceptable to loan above the savings but it's the admin decision wheter to accept or decline the loan request. <br> --}}
        </div>
    </div>
@else
    {{-- <h3 class="header mt-3 text-center">Request Loan Not Available</h3>
    <h3 class="header mt-3 text-center">Please Settle your loan</h3> --}}
    <div class="failed-loan d-flex justify-content-center align-items-center">
        <h6 class="display-5 header text-center">Please settle your unfinished loan first.</h6>
        {{-- <h6 class="display-5 header text-center"></h6> --}}
        {{-- <img src="{{ asset('img/img.png') }}" alt="Failed-loan" min-width="50px" height="70px"> --}}
    </div>
@endif

{{-- <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reqModalLabel">User Terms and Condition Agreement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <h1 class="display-4">Terms and Conditions</h1>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}

@endsection
