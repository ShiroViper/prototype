@extends('layouts.app')

@section('title')
    <title>Failed Expected Deposit Accounts</title>
@endsection

@section('content')
<div class="row pt-5">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Failed Expected Deposit Accounts</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table" style="text-align:center">
                            <thead>
                                <tr>
                                    <th>Due Date</th>
                                    <th>Member ID</th>
                                    <th>Member Name</th>
                                    <th>Collector Name</th>
                                    <th>Outstanding Balance</th>
                                    <th>Remaining Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($failures) > 0)
                                    @foreach ($failures as $failed)
                                        <tr>
                                            <td>{{date("M d, Y", strtotime($failed->due_date))}}</td>
                                            <td>{{$failed->member_id}} </td>
                                            <td>{{$failed->lname}}, {{$failed->fname}}</td>
                                            <td>{{$failed->col_lname}}, {{$failed->fname}} </td>
                                            <td>{{$failed->balance}} Php</td>
                                            <td>For Deposit</td>
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
                        {{ $failures->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection