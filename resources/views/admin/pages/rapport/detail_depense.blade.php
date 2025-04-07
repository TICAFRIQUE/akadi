@extends('admin.layouts.app')
@section('title', 'rapport')
@section('sub-title', 'Detail depenses')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div>
                        @include('admin.components.validationMessage');
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Détail des Dépenses
                                >
                                {{ App\Models\CategorieDepense::whereId(request('categorie_depense'))->first()->libelle }}
                                >{{ App\Models\LibelleDepense::whereId(request('libelle_depense'))->first()->libelle }}

                                @if ($dateDebut || $dateFin)
                                    du
                                    @if ($dateDebut)
                                        {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                                    @endif
                                    @if ($dateFin)
                                        au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                                    @endif
                                @endif
                            </h5>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Libellé</th>
                                            <th>Date de dépense</th>
                                            <th>Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($depenses as $key => $depense)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $depense['libelle_depense']['libelle'] ?? '' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($depense['date_depense'])->format('d/m/Y') }}
                                                </td>
                                                <td>{{ number_format($depense['montant'], 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
