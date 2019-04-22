@extends('layouts.app')

@section('title')
    <title>Deliquent Account</title>
@endsection

@section('content')
@push('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
<div class="row pt-5">
        <div class="col">
            <div class="card">
                <h6 class="card-header">Deliquent Accounts</h6>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table" style="text-align:center">
                            <thead>
                                <tr>
                                    <th>Due Dateasdad</th>
                                    <th>Member ID</th>
                                    <th>Member Name</th>
                                    <th>Collector Name</th>
                                    <th>Outstanding Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($deliquents) > 0)
                                    @foreach ($deliquents as $deliquent)
                                        <tr>
                                            <td>{{date("M d, Y", strtotime($deliquent->due_date))}}</td>
                                            <td>{{$deliquent->member_id}} </td>
                                            <td>{{$deliquent->lname}} {{$deliquent->fname}}</td>
                                            <td>{{$deliquent->col_lname}} {{$deliquent->fname}} </td>
                                            <td>{{$deliquent->balance}} </td>
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
        </div>
    </div>

@endsection