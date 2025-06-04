@extends('admin.layouts.app')

@section('title', 'auth')
@section('sub-title', 'Creer un utilisateur')

@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('admin/assets/bundles/jquery-selectric/selectric.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="container mt-1">
            <div class="row">
                <a class="btn btn-primary fas fa-arrow-left mb-2" href="{{route('user.list')}}"> Retour à la liste des utilisateurs</a>
                <div class="col-12 col-sm-10 offset-sm-1 col-md-10 offset-md-2 col-lg-10 offset-lg-2 col-xl-10 offset-xl-2 m-auto">
                    @if (session('user_auth'))
                    @php
                         $getData =Session::get('user_auth');
                    @endphp
                   
                        <div class="alert alert-primary">
                            <h5>Les informations de connexions du dernier utilisateur</h5>
                        Email:   {{$getData['email']}}
                        <br> Mot de passe :  {{$getData['pwd']}}

                        </div>
                  
                  @endif
                   
                    <div class="card card-primary">
                        @include('admin.components.validationMessage')
                        <div>
                    
                        </div>
                        <div class="card-header">
                            <h4>Nouvel utilisateur</h4>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" method="POST" action="{{route('user.register')}}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="frist_name">Nom & prenoms</label>
                                        <input id="frist_name" type="text" class="form-control" name="name"
                                            autofocus required>
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="last_name">Telephone</label>
                                        <input id="last_name" type="number" class="form-control" name="phone" required>
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    {{-- <div class="form-group col-6">
                                        <label for="password" class="d-block">Mot de password</label>
                                        <input id="password" type="password" class="form-control pwstrength"
                                            data-indicator="pwindicator" name="password">
                                        <div id="pwindicator" class="pwindicator">
                                            <div class="bar"></div>
                                            <div class="label"></div>
                                        </div>
                                    </div> --}}
                                    <div class="form-group col-6">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" >
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="password2" class="d-block">Role</label>
                                        <select name="role" class="form-control select2"  required>
                                            <option disabled selected value>Choisir un role</option>
                                            @foreach ($roles as $item)
                                                <option value="{{ $item['name'] }}"> {{ $item['name'] }} </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                </div>

                                {{-- auth fornisseur / vendeur --}}
                                    {{-- @if (request('u')) --}}
                                        
                                    {{-- <div class="row">
                                        <div class="form-group col-6">
                                            <label for="shopName">Nom de boutique</label>
                                            <input id="shopName" type="text" class="form-control" name="shop_name" required>
                                            <div class="invalid-feedback">
                                                Champs obligatoire
                                            </div>
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="shopName">Lieu de residence (boutique)   <small class="text-danger">Ex: Abidjan, cocody angre</small> </label>
                                            <input id="shopName" type="text" class="form-control" name="localisation" required>
                                            <div class="invalid-feedback">
                                                Champs obligatoire
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- @endif --}}
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset('admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script> --}}
@endsection
