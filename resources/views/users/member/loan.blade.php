@extends('layouts.app')

@section('title')
<title>Alkansya - Request Loan</title>
@endsection

@section('content')
{{-- {{dd(session()->get('loan.0.months') == 1 ? 'selected' : '', session()->all())}} --}}
{{-- {{dd(Illuminate\Support\Facades\Input::all())}} --}}
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
{{-- {{dd($paid)}} --}}
@if(!$paid ? $status ? $status->savings > 0 :'' : '')
    <h3 class="header mt-2">Request Loan</h3>
    {{-- <div class="bg-teal p-3 text-white">
        The minimum loan amount is ₱200.
    </div> --}}
    <div class="row">
        <div class="col-sm-10 col-md-7 col-lg-5 order-2 order-md-1 my-3">  
            {!! Form::open(['action' => 'LoanRequestsController@store', 'method' => 'POST']) !!}
            @csrf
            {{Form::hidden('token', $token) }}

            {{-- <div class="form-group">
                <p class="h6">Loan Interest = Loan Amount * 6% (Monthly Interest)</p><hr>
                <div class="row">
                    <div class="col">
                        <p class="h6" id="interest"></p>
                    </div>
                </div>
            </div> --}}

            {{ Form::label('amount', 'Loan Amount', ['class' => 'h6']) }}
            <div class="form-group">
                    {{ Form::number('amount', '', ['onkeyup'=>'loan()', 'class' => $errors->has('amount') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Enter amount', 'min'=>'200', 'max'=> $status->savings, 'step' => '1', 'required']) }}
                    {{-- <input type="number" name="amount" value="{{session()->get('loan.0.amount')}} " class="form-control {{$errors->has('amount') ? 'is-invalid' : '' }} " placeholder="Enter Amount" min="200" max="{{$status->savings}} " step="1" required> --}}
                    <small class="text-muted">The minimum loan amount is ₱200.</small>
                @if ($errors->has('amount'))
                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                @endif
            </div>

            {{ Form::label('months', 'End of Term', ['class' => 'h6']) }}
            <div class="form-group">
            {{-- {{ Form::number('months', '', ['class' => $errors->has('months') ? 'form-control is-invalid' : 'form-control', 'min'=>'1', 'max'=> $count_end_month, 'step'=> 1, 'required']) }}
                @if ($errors->has('months'))
                    <div class="invalid-feedback">{{ $errors->first('months') }}</div>
                @endif --}}
                <select name="months" id="months" onchange="loan()" class="form-control {{$errors->has('months') ? ' is-invalid' : '' }} " required>
                    <option selected disabled hidden value="">-- Select Month --</option>
                    @php $i = 1; @endphp
                    @foreach($get_end_name as $g)
                        <option value="{{$i}}" {{session()->get('loan.0.months') == $i ? 'selected' : '' }}> {{date('F', strtotime($g))}} {{date('d, Y', strtotime(NOW()))}} </option>
                        @php $i++; @endphp
                    @endforeach
                </select>
                </div>

            {{ Form::label('reason', 'Reason', ['class' => 'h6']) }}
            <div class="form-group">
                <select name="reason" id="reason" class="form-control {{$errors->has('reason') ? ' is-invalid s' : '' }} " required>
                    <option selected disabled hidden value="">-- Select Reason --</option>
                    <option value="For Personal Use" {{session()->get('loan.0.reason') == "For Personal Use" ? 'selected' : ''}} >For Personal Use</option>
                    <option value="For Emergency Use" {{session()->get('loan.0.reason') == "For Emergency Use" ? 'selected' : ''}}>For Emergency Use</option>
                    <option value="3" {{session()->get('loan.0.reason') == 3 ? 'selected' : ''}}>Other</option>
                </select>
                <textarea name="other" value="{{session()->get('loan.0.other')}}" id="other" rows="1" class="form-control mt-2" placeholder="Other (please specify)"></textarea>
            </div>            
            @if ($errors->has('reason'))
                {{-- <div class="invalid-feedback">Please Select</div> --}}
                Please select <br> 
            @endif
            <div>
            </div>

            {{-- <label for="pass"> Password</label>
            <div class="form-group">
                <input type="password" value="{{session()->get('loan.0.pass')}}" name="pass" class="form-control" required>
            </div> --}}

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
                        {{ $savings && $savings->savings != null ? '₱'.number_format(floor($savings->savings), 2) : 'No Savings' }}
                    </div>
                    <div>Current Savings</div>
                </div>
            </div>
            {{-- Note: <br>
            200 minimum loan amount. <br>
            personal and other reason is equal to savings while emergency reason is acceptable to loan above the savings but it's the admin decision wheter to accept or decline the loan request. <br> --}}
        </div>
    </div>
@elseif($status == NULL || $status->savings <=0 )
    <div class="failed-loan d-flex justify-content-center align-items-center">
        <h6 class="display-5 header text-center">Cannot Loan: No Savings or In Debt </h6>
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

<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reqModalLabel">Loan Breakdown</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    Loan Amount * 6% / Months Payable = Monthly Payment
                    Remaining Balanace * 6% / Months Payable = Monthly Payment 
                    <p id="interest"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- forget session loan --}}
{{session()->forget('loan')}}
@endsection
