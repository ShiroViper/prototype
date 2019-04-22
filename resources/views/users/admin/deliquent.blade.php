@extends('layouts.app')

@section('title')
    <title>Deliquent Account</title>
@endsection

@section('content')
<h3 class="header mt-2">Delinquent Accounts</h3>
<div class="row mt-3">
        <div class="col">
                {{-- <h6 class="card-header">Deliquent Accounts</h6> --}}
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Due Date & Time</th>
                            <th>Member Name</th>
                            {{-- <th>Collector Name</th> --}}
                            <th>Loan Balance</th>   
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($deliquents) > 0)
                            @foreach ($deliquents as $deliquent)
                                <tr>
                                    <td>{{date("h:i A M d, Y", strtotime($deliquent->due_date))}}</td>
                                    <td>{{$deliquent->lname}}, {{$deliquent->fname}} {{$deliquent->mname}} </td>
                                    {{-- <td>{{$deliquent->col_lname}}, {{$deliquent->fname}} {{$deliquent->mname}} </td> --}}
                                    <td>₱ {{number_format($deliquent->balance, 2)}} </td>
                                </tr> 
                            @endforeach
                        @else
                        <tr>
                            <td colspan="100%" class="text-center"><h4 class="text-muted">No Entries Found</h4></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $deliquents->links() }}
            </div>
        </div>
    </div>

@endsection