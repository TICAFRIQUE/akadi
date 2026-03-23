@extends('admin.layouts.app')
@section('title', 'produits de base')
@section('sub-title', 'Liste des produits de base')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Produits de Base</h4>
                            <div class="card-header-action">
                                <a href="{{ route('product-base.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter un produit de base
                                </a>
                            </div>
                        </div>

                        @include('admin.components.validationMessage')

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="tableExport">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Nom</th>
                                            <th>Stock</th>
                                            <th>Stock d'alerte</th>
                                            <th>Unité</th>
                                            <th>Prix moyen d'achat</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($productBases as $productBase)
                                            <tr class="{{ $productBase->isStockFaible() ? 'table-warning' : '' }}">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $productBase->nom }}</strong>
                                                    @if ($productBase->isStockFaible())
                                                        <span class="badge badge-warning ml-2">
                                                            <i class="fas fa-exclamation-triangle"></i> Stock faible
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $productBase->stock > 0 ? 'badge-success' : 'badge-danger' }}">
                                                        {{ format_price($productBase->stock) }}
                                                        {{ $productBase->unite }}
                                                    </span>
                                                </td>
                                                <td>{{ format_price($productBase->stock_alerte) }}
                                                    {{ $productBase->unite }}</td>
                                                <td>{{ $productBase->unite }}</td>
                                                <td>{{ format_price($productBase->prix_achat_moyen) }} FCFA
                                                </td>
                                                <td>
                                                    @if ($productBase->actif)
                                                        <span class="badge badge-success">Actif</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inactif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('product-base.edit', $productBase->id) }}"
                                                        class="btn btn-primary btn-sm" data-toggle="tooltip"
                                                        title="Modifier">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('product-base.destroy', $productBase->id) }}"
                                                        method="POST" style="display: inline-block;"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit de base ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            data-toggle="tooltip" title="Supprimer">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <p class="text-muted">Aucun produit de base trouvé</p>
                                                </td>
                                            </tr>
                                        @endforelse
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

@push('js')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();


            var titre =
                'Liste des produits de base - {{ now()->format('d/m/Y') }}';
            $('#tableExport').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
                },
                "order": [
                    [2, "desc"]
                ],

                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        title: titre,
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclure la colonne des actions
                        }
                    },
                    {
                        extend: 'csv',
                        title: titre,
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclure la colonne des actions
                        }
                    },
                    {
                        extend: 'excel',
                        title: titre,
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclure la colonne des actions
                        }
                    },
                    {
                        extend: 'pdf',
                        title: titre,
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclure la colonne des actions
                        }

                    },
                    {
                        extend: 'print',
                        title: titre,
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclure la colonne des actions
                        }


                    }
                ]
            });
        });
    </script>
@endpush
