@extends('admin.layouts.app')
@section('title', 'Sorties de Stock')
@section('sub-title', 'Détails de la sortie de stock')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Détails de la Sortie</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('sortie-stock.index') }}">Sorties de Stock</a></div>
            <div class="breadcrumb-item">Détails</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations de la sortie</h4>
                        <div class="card-header-action">
                            <form action="{{ route('sortie-stock.destroy', $sortieStock) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr ? Le stock sera restauré.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 200px;">Numéro:</th>
                                <td><strong class="text-primary">{{ $sortieStock->numero }}</strong></td>
                            </tr>
                            <tr>
                                <th>Date de sortie:</th>
                                <td>{{ $sortieStock->date_sortie->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Produit:</th>
                                <td><strong>{{ $sortieStock->productBase->nom }}</strong></td>
                            </tr>
                            <tr>
                                <th>Quantité:</th>
                                <td><strong class="text-danger">{{ number_format($sortieStock->quantite, 2) }} {{ $sortieStock->productBase->unite }}</strong></td>
                            </tr>
                            <tr>
                                <th>Motif:</th>
                                <td>
                                    <span class="badge badge-{{ $sortieStock->motif == 'Casse' ? 'danger' : ($sortieStock->motif == 'Don' ? 'success' : 'warning') }}">
                                        {{ $sortieStock->motif }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td>{{ $sortieStock->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Enregistré par:</th>
                                <td>{{ $sortieStock->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Date d'enregistrement:</th>
                                <td>{{ $sortieStock->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Stock actuel du produit:</th>
                                <td class="text-info"><strong>{{ number_format($sortieStock->productBase->stock, 2) }} {{ $sortieStock->productBase->unite }}</strong></td>
                            </tr>
                        </table>

                        <div class="mt-4">
                            <a href="{{ route('sortie-stock.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
