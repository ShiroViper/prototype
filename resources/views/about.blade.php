@extends('layouts.guest')

@section('title')
<title>Who we are</title>
@endsection

@section('content')
<div class="main-feature">
    <div class="container">
        <h1 class="h1 welcome-title text-primary text-center">The Team</h1>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-deck pt-3">
                    <div class="card border-0 align-items-center">
                        <img class="card-img-top rounded-circle dp" src="img/ag.png" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="text-center about-header font-weight-bold">Ansleigh Añora</h5>
                            <p class="card-text about-desc">Front End Developer / Back End Developer</p>
                        </div>
                    </div>
                    <div class="card border-0 align-items-center">
                        <img class="card-img-top rounded-circle dp" src="img/dj.jpg" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="text-center about-header font-weight-bold">Daniel Joseph Momo</h5>
                            <p class="card-text about-desc">Front End Developer / Back End Developer</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card-deck pt-3">
                    <div class="card border-0 align-items-center">
                        <img class="card-img-top rounded-circle dp" src="img/nc.png" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="text-center about-header font-weight-bold">Nike Marti Caballes</h5>
                            <p class="card-text about-desc">Front End Developer / Back End Developer</p>
                        </div>
                    </div>
                    <div class="card border-0 align-items-center">
                        <img class="card-img-top rounded-circle dp" src="img/jeff.png" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="text-center about-header font-weight-bold">Jefriel Cortes</h5>
                            <p class="card-text about-desc">Front End Developer / Back End Developer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection