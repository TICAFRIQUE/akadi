@extends('admin.layouts.app')
@section('title', 'Inventaires')
@section('sub-title', 'Liste des inventaires')
@section('content')
    <section class="section">
        
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4></h4>
                    <div class="card-header-action">
                        <a href="{{ route('achat.rapport') }}" class="btn btn-info mr-2">
                            <i class="fas fa-chart-bar"></i> Rapport
                        </a>
                        <a href="{{ route('achat.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouvel achat
                        </a>
                    </div>
                </div>
                @include('admin.components.validationMessage')

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tableExport">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Réalisé par</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventaires as $key => $inv)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            @php
                                                $date = $inv->date_inventaire;
                                                if (!($date instanceof \Carbon\Carbon)) {
                                                    $date = \Carbon\Carbon::parse($date);
                                                }
                                            @endphp
                                            {{ $date->format('d/m/Y H:i') }}
                                        </td>
                                        <td>{{ $inv->user->name }}</td>

                                        <td>
                                            <a href="{{ route('inventaire.show', $inv->id) }}"
                                                class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Voir</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                'Liste des Inventaires';
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
