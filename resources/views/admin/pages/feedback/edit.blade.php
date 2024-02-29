@extends('admin.layouts.app')
@section('title', 'temoignage')
@section('sub-title', 'Modifier un temoignage')


@section('content')
    <section class="section">
        @php
            $msg_validation = 'Champs obligatoire';
        @endphp
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-10 m-auto">
                    @include('admin.components.validationMessage')
                    <div class="card">
                        <form action="{{ route('temoignage.update', $feedback['id']) }}" class="needs-validation"
                            novalidate="" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nom de la personne</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="{{$feedback['nom']}}" name="nom" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                        <textarea name="description" class="form-control" required>
                                            {{$feedback['description']}}
                                        </textarea>
                                    </div>
                                    <div class="invalid-feedback">
                                        {{ $msg_validation }}
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Valider</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
