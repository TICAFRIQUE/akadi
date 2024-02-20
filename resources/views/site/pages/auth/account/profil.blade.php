@extends('site.layouts.app')

@section('title', 'Mon profil')


@section('content')
    <div class="row">
        <div class="breadcumb-wrapper " data-bg-src="">
            <div class="container z-index-common">
                <div class="breadcumb-content">
                    <h1 class="breadcumb-title">Mon profil</h1>
                    <ul class="breadcumb-menu">
                        <li><a href="{{ route('liste-produit') }}">Liste des plats</a></li>
                        <li><a href="i{{ route('page-acceuil') }}">Accueil</a></li>
                        <li>Mon profil</li>
                    </ul>
                </div>
            </div>
        </div>



        <!--==============================
                Team Area
                ==============================-->
        <section class="bg-white my-4">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 m-auto position-relative mb-40 mb-xl-0">
                        <div class="about-details">
                            <div class="row gy-30">
                                <div class="col-md-7">
                                    <h3 class="box-title">Mes informations</h3>
                                    <div class="team-contact">
                                        <div class="team-contact_icon">
                                            <i class="fal fa-user"></i>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="team-contact_title">Nom & Prenoms</h4>
                                            <p class="team-contact_text"> {{Auth::user()->name}} </p>
                                        </div>
                                    </div>
                                    <div class="team-contact">
                                        <div class="team-contact_icon">
                                            <i class="fal fa-phone"></i>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="team-contact_title">Telephone</h4>
                                            <p class="team-contact_text"><a href="tel:{{Auth::user()->phone}}"> {{Auth::user()->phone}} </a></p>
                                        </div>
                                    </div>
                                    <div class="team-contact">
                                        <div class="team-contact_icon">
                                            <i class="fal fa-envelope-open"></i>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="team-contact_title">Email Addresse</h4>
                                            <p class="team-contact_text"><a
                                                    href="mailto:needhelp.pizzan@gmail.com"> {{Auth::user()->email}} </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                              
                            </div>
                           
                        </div>
                    </div>


                    <!-- ========== Start modifier mes informations ========== -->
                    {{-- <div class="col-xl-6 align-self-center">
                        <div class="">
                            <h4 class="mb-20">Modifier mes informations</h4>
                            <form action="mail.php" method="POST" class="team-form">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <input type="text" class="form-control rounded-2" id="name" name="name"
                                            placeholder="Your Name">
                                        <i class="far fa-user"></i>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" class="form-control rounded-2" id="subject" name="subject"
                                            placeholder="Subject">
                                        <i class="far fa-tag"></i>
                                    </div>
                                    <div class="form-group col-12">
                                        <textarea name="message" id="message" cols="30" rows="3" class="form-control rounded-2"
                                            placeholder="Write Message"></textarea>
                                        <i class="far fa-comment"></i>
                                    </div>
                                </div>
                                <div class="form-btn">
                                    <button class="th-btn rounded-2">Send Message<i
                                            class="fa-solid fa-arrow-right ms-2"></i></button>
                                </div>
                                <p class="form-messages mb-0 mt-3"></p>
                            </form>
                        </div>
                    </div> --}}
                    <!-- ========== End modifier mes informations ========== -->
                    
                    
                </div>
            </div>
        </section><!--==============================
                 Footer Area
                ==============================-->

    </div>



@endsection
