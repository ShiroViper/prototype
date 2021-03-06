@extends('layouts.guest')

@section('title')
<title>Who we are</title>
@endsection

@section('content')
<div class="container">
    {{-- <div class="main-feature">
        <h1 class="brand-title-header text-primary text-center">About Us</h1>
        <div class="row">
            <div class="col-sm-12 text-justify about-desc lead mt-3">
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Corrupti ullam tempore aperiam numquam repellendus, obcaecati necessitatibus ab placeat libero ut non, voluptate mollitia nemo delectus rem perferendis adipisci iusto maxime!</p>
                
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime optio sint et? Exercitationem tenetur, sapiente ab recusandae itaque voluptatem voluptas eligendi autem eum excepturi corrupti voluptates expedita, omnis dignissimos voluptate?</p>
            </div>
            <div class="col d-flex align-items-center justify-content-center flex-column mt-3">
                <img src="img/br.jpg" alt="" class="rounded-circle dp img-fluid">
                <div class="text-center">
                    <h5 class="about-header font-weight-bold mt-3">Brenda Cordero</h5>
                    <p class="card-text about-desc">Owner</p>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="main-feature">
        <h1 class="brand-title-header text-primary text-center">The Team</h1>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-deck pt-3">
                    <div class="card border-0 align-items-center">
                        <img class="card-img-top rounded-circle dp" src="img/ag.png" alt="Card image cap">
                        <div class="card-body text-center">
                            <h5 class="about-header font-weight-bold">Ansleigh Añora</h5>
                            <p class="card-text about-desc">Front End Developer / Back End Developer</p>
                        </div>
                    </div>
                    <div class="card border-0 align-items-center">
                        <img class="card-img-top rounded-circle dp" src="img/dj.jpg" alt="Card image cap">
                        <div class="card-body text-center">
                            <h5 class="about-header font-weight-bold">Daniel Joseph Momo</h5>
                            <p class="card-text about-desc">Front End Developer / Back End Developer</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card-deck pt-3">
                    <div class="card border-0 align-items-center">
                        <img class="card-img-top rounded-circle dp" src="img/nc.png" alt="Card image cap">
                        <div class="card-body text-center">
                            <h5 class="about-header font-weight-bold">Nike Marti Caballes</h5>
                            <p class="card-text about-desc">Front End Developer / Back End Developer</p>
                        </div>
                    </div>
                    <div class="card border-0 align-items-center">
                        <img class="card-img-top rounded-circle dp" src="img/jeff.png" alt="Card image cap">
                        <div class="card-body text-center">
                            <h5 class="about-header font-weight-bold">Jefriel Cortes</h5>
                            <p class="card-text about-desc">Front End Developer / Back End Developer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection