@extends('layouts.app')

@section('title')
<title>Alkansya</title>
@endsection

@section('content')
<h3 class="header mt-2">Edit User</h3>
<div class="row">
    <div class="col">
        <div class="py-3">
            <a class="btn btn-light border" role="button" href="/admin/users "><i class="fas fa-arrow-left"></i> Back to table</a>
            <div class="float-right">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col col-md-8">
        <div class="card">
            {!! Form::open(['action' => ['UsersController@update', $user->id], 'method' => 'POST']) !!}
            @csrf
            {{ Form::hidden('_method', 'PUT') }}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>User Information</h5>
                <div class="px-2">
                    <a class="btn btn-outline-danger mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Discard Changes" href="/admin/users/{{ $user->id }}"><i class="fas fa-times fa-lg"></i></a>
                    {{ Form::submit('Save Changes', ['class' => 'btn btn-success edit-button']) }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm col-md">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">Member ID</div>
                                <div class="col font-weight-bold">{{ $user->id }}</div>
                            </div>
                        </li>
                        
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">Last Name</div>
                                <div class="col font-weight-bold">
                                    {{ Form::text('lname', $user->lname, ['class' => $errors->has('lname') ? 'form-control is-invalid' : 'form-control']) }}
                                    {{-- <input name="lname" type="text" class="form-control{{ $errors->has('lname') ? ' is-invalid' : '' }}" value="{{ $errors->has('lname') ? old('lname') : $user->lname }}" required> --}}
                                    @if ($errors->has('lname'))
                                        <div class="invalid-feedback">{{ $errors->first('lname') }}</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">First Name</div>
                                <div class="col font-weight-bold">
                                    {{ Form::text('fname', $user->fname, ['class' => $errors->has('fname') ? 'form-control is-invalid' : 'form-control']) }}
                                    {{-- <input name="fname" type="text" class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" value="{{ $errors->has('fname') ? old('fname') : $user->fname }}" required> --}}
                                    @if ($errors->has('fname'))
                                        <div class="invalid-feedback">{{ $errors->first('fname') }}</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">Middle Name</div>
                                <div class="col font-weight-bold">
                                    {{ Form::text('mname', $user->mname, ['class' => $errors->has('mname') ? 'form-control is-invalid' : 'form-control']) }}
                                    {{-- <input name="mname" type="text" class="form-control{{ $errors->has('mname') ? ' is-invalid' : '' }}" value="{{ $errors->has('mname') ? old('mname') : $user->mname }}"> --}}
                                    @if ($errors->has('mname'))
                                        <div class="invalid-feedback">{{ $errors->first('mname') }}</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">Email</div>
                                <div class="col font-weight-bold">
                                    {{ Form::email('email', $user->email, ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control']) }}
                                    {{-- <input name="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ $errors->has('email') ? old('email') : $user->email }}" required> --}}
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">Contact Number</div>
                                <div class="col font-weight-bold">
                                    {{ Form::number('cell_num', $user->cell_num, ['class' => $errors->has('cell_num') ? 'form-control is-invalid' : 'form-control']) }}
                                    {{-- <input name="cell_num" type="text" class="form-control{{ $errors->has('cell_num') ? ' is-invalid' : '' }}" value="{{ $errors->has('cell_num') ? old('cell_num') : $user->cell_num }}" required> --}}
                                    @if ($errors->has('cell_num'))
                                        <div class="invalid-feedback">{{ $errors->first('cell_num') }}</div>
                                    @endif
                                    {{-- {{ $errors->first('cell_num') }} --}}
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">Role</div>
                                <div class="col">
                                    {{ Form::select('user_type', [0 => 'Member', 1 => 'Collector'], $user->user_type, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">Address</div>
                            </div>
                            <div class="row">
                                <div class="col font-weight-bold">
                                    {{ Form::textarea('address', $user->address, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Street number, Barangay, City/Town, Province, Philippines, Zip Code']) }}
                                    @if ($errors->has('address'))
                                        <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-footer text-muted">
                <small>Account Created: {{date("F d, Y", strtotime($user->created_at))}}  Time: {{date(" h:i:s A", strtotime($user->created_at))}}</small><br>
                <small>Account Updated: {{date("F d, Y", strtotime($user->updated_at))}}  Time: {{date(" h:i:s A", strtotime($user->updated_at))}}</small>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
<!-- <div class="col-sm col-md">
            <div class="row pr-3 mt-3 form-row">
                <div class="col-md form-group">
                    <label for="noOfDuty">Number of Days off</label>
                    <select name="noOfDuty" id="" class="form-control">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                    </select>
                </div>
            </div>
            <div class="row pr-3 mt-3 form-row">
                <div class="col-md form-group daysOff">
                    <label for="daysOff">Days Off</label>
                    <select name="dutyDate" id="" class="form-control">
                        <option value="0">Sunday</option>
                        <option value="1">Monday</option>
                        <option value="2">Tueday</option>
                        <option value="3">Wednesday</option>
                        <option value="4">Thursday</option>
                        <option value="5">Friday</option>
                        <option value="6">Saturday</option>
                    </select>
                </div>
            </div>
        </div> -->
            {{-- <div class="row mt-3">
                <div class="col">
                    <a class="btn btn-danger btn-block" role="button" href="/admin/users/{{ $user->id }}">Cancel</a>
                </div>
                <div class="col">
                    {{ Form::hidden('_method', 'PUT') }}
                    {{ Form::submit('Update', ['class' => 'btn btn-primary btn-block edit-button']) }}
                </div>
            </div> --}}
            {{-- <div class="d-flex justify-content-lg-center pt-3">
                <a class="btn btn-outline-danger mr-3 px-3" role="button" title="Discard Changes" href="/admin/users/{{ $user->id }}">Cancel</a>
                {{ Form::submit('Save Changes', ['class' => 'btn btn-success edit-button px-3']) }}
            </div> --}}