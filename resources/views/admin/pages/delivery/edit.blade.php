@extends('admin.layouts.app')
@section('title', 'Livraison')
@section('sub-title', 'Modifier une livraison')

@section('content')
    <section class="section">
        @php
            $msg_validation = 'Champs obligatoire';
        @endphp
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-8 m-auto">
                    @include('admin.components.validationMessage')
                    <div class="card">
                        <form action="{{ route('delivery.update', $delivery['id']) }}" class="needs-validation" novalidate=""
                            method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Zone</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="zone" value="{{ $delivery['zone'] }}"
                                            class="form-control" required="">
                                        <div class="invalid-feedback">
                                            {{ $msg_validation }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tarif</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="tarif" value="{{ $delivery['tarif'] }}"
                                            class="form-control" required="">
                                        <div class="invalid-feedback">
                                            {{ $msg_validation }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
