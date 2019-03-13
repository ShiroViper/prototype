@extends('layouts.app')

@section('title')
    <title>Reports</title>
@endsection

@section('content')
<div class="row pt-5">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Member Reports</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table" style="text-align:center">
                            <thead>
                                <tr>
                                    <th>Due Date</th>
                                    <th>Account</th>
                                    <th>Member ID</th>
                                    <th>Member Name</th>
                                    <th>Collector Name</th>
                                    <th>Outstanding Balance</th>
                                    <th>Remaining Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($reports) > 0)
                                    @foreach ($reports as $report)
                                    <tr>
                                        <td>{{date("M d, Y", strtotime($report->created_at))}}</td>
                                        @if(NOW() < $report->due_date)
                                            <td></td>
                                        @else

                                        @endif
                                        <td>{{$report->member_id}} </td>
                                        <td>{{$report->lname}} {{$report->fname}}</td>
                                        <td>{{$report->col_lname}} {{$report->fname}} </td>
                                        <td>{{$report->balance}} </td>
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
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection