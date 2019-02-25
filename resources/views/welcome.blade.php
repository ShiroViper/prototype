@extends('layouts.guest')

@section('title')
{{-- <title>{{ config('app.name', 'Alkansya') }}</title> --}}
<title>Welcome to Alkansya</title>
@endsection

@section('content')
<div class="main-feature">
    <div class="container">
        <div class="main-header">
            <div class="row pb-3">
                <div class="col">
                    <span class="welcome-title brand-title-header h1 font-weight-bold">Welcome to ALKANSYA</span>
                    <div class="featured-desc py-2 my-3">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut earum doloremque quas a maiores amet expedita modi, ea magnam quis eum corrupti, deleniti quia tenetur eligendi illum tempora nemo eos?</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis repudiandae hic nesciunt cum, non, recusandae quod dolor earum deleniti ipsam ipsum vitae fugiat aliquid quasi molestiae vel architecto mollitia, nobis.</p>
                    </div>
                    <div class="row my-3">
                        <div class="col col-sm col-md col-lg-10 col-xl-6">
                            <a href="#" class="btn btn-primary px-3 mr-2">I want to be a member</a>
                            {{-- <a href="/login" class="btn btn-outline-secondary px-3">Already a member</a> --}}
                        </div>
                    </div>
                </div>
                <div class="col col-md-4 featured-img">
                    <img src="img/img.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="addon-feature pt-5">
            <div class="row mb-3">
                <div class="col">
                    <span class="text-info title-header">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</span>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-lg">
                    <div class="box bg-light border rounded mb-3 shadow">
                        <div class="row d-flex align-items-center m-auto">
                            <div class="col">
                                <h4 class="title-header font-weight-bold">Easy</h4>
                            </div>
                            <div class="col-2 col-sm-2 col-md-1 col-lg-3">
                                <img src="img/ez.png" alt="" class="img-fluid">
                            </div>
                        </div>
                        <div class="row px-3">
                             <div class="col col-md featured-desc">
                                <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse cupiditate a quis laboriosam facilis omnis non dolorum modi aperiam.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="box bg-light border rounded mb-3 shadow">
                        <div class="row d-flex align-items-center m-auto">
                            <div class="col">
                                <h4 class="title-header font-weight-bold">Fast</h4>
                            </div>
                            <div class="col-2 col-md-2 col-md-1 col-lg-3">
                                <img src="img/rr.png" alt="" class="img-fluid" style="max-height: 60px;">
                            </div>
                            <div class="row px-3">
                                <div class="col col-md featured-desc">
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, facilis vitae! Aperiam numquam vitae, facilis illo, nostrum itaque provident maiores.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="box bg-light border rounded mb-3 shadow">
                        <div class="row d-flex align-items-center m-auto">
                            <div class="col">
                                <h4 class="title-header font-weight-bold">Reliable</h4>
                            </div>
                            <div class="col-2 col-md-2 col-md-1 col-lg-3">
                                <img src="img/tu.png" alt="" class="img-fluid" style="max-height: 60px;">
                            </div>
                            <div class="row px-3">
                                <div class="col col-md featured-desc">
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam odio esse cumque earum minima quidem qui doloremque hic possimus porro.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection