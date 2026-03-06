@extends('admin.layouts.app')
@section('title', 'clients')
@section('sub-title', 'Liste des clients')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @include('admin.components.validationMessage')
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                @if (request('type') == 'prospect') Prospects
                                @elseif (request('type') == 'fidele') Clients fidèles
                                @else Tous les clients
                                @endif
                                <span class="badge badge-primary ml-1">{{ count($users) }}</span>
                            </h4>
                            <a href="{{ route('client.createForm') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Ajouter un client
                            </a>
                        </div>

                        {{-- Filtres --}}
                        <div class="card-body border-bottom py-2 bg-light">
                            <form method="GET" action="{{ route('client.list') }}"
                                class="d-flex align-items-center flex-wrap" style="gap: 10px;">

                                {{-- Filtre type --}}
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('client.list') }}"
                                        class="btn {{ !request('type') ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Tous
                                    </a>
                                    <a href="{{ route('client.list') }}?type=prospect"
                                        class="btn {{ request('type') == 'prospect' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Prospects
                                    </a>
                                    <a href="{{ route('client.list') }}?type=fidele"
                                        class="btn {{ request('type') == 'fidele' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Fidèles
                                    </a>
                                </div>

                                <div class="vr mx-1" style="height:30px; border-left:1px solid #ccc;"></div>

                                {{-- Filtre date --}}
                                @if (request('type'))
                                    <input type="hidden" name="type" value="{{ request('type') }}">
                                @endif
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <span class="text-muted small font-weight-bold">Du</span>
                                    <input type="date" name="date_debut" class="form-control form-control-sm"
                                        value="{{ $dateDebut }}" style="width:145px;">
                                    <span class="text-muted small font-weight-bold">Au</span>
                                    <input type="date" name="date_fin" class="form-control form-control-sm"
                                        value="{{ $dateFin }}" style="width:145px;">
                                    <button class="btn btn-sm btn-primary" type="submit">
                                        <i class="fa fa-search mr-1"></i> Filtrer
                                    </button>
                                    <a href="{{ route('client.list') }}{{ request('type') ? '?type='.request('type') : '' }}"
                                        class="btn btn-sm btn-outline-secondary" title="Réinitialiser les dates">
                                        <i class="fa fa-undo"></i>
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Status</th>
                                            <th>Nom</th>
                                            <th>Contact</th>
                                            <th>Email</th>
                                            <th>Date anniversaire</th>
                                            <th>Type</th>
                                            <th>Commandes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $item)
                                            <tr id="row_{{ $item['id'] }}">
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $item->orders_count > 0 ? 'success' : 'primary' }}">
                                                        {{ $item->orders_count > 0 ? 'A commandé' : 'Aucune commande' }}
                                                    </span>
                                                </td>
                                                <td>{{ $item['name'] }}</td>
                                                <td>{{ $item['phone'] }}</td>
                                                <td>{{ $item['email'] }}</td>
                                                @php
                                                    $Y = date('Y');
                                                    $nex_date = $item['date_anniversaire'] . '-' . $Y;
                                                    $date = \Carbon\Carbon::parse($nex_date)->locale('fr_FR');
                                                    $date = $date->day . ' ' . $date->monthName;
                                                @endphp
                                                <td>{{ $date }}</td>
                                                <td>
                                                    @php
                                                        $badgeColor = match($item['type_client']) {
                                                            'fidele'   => 'success',
                                                            'prospect' => 'warning',
                                                            default    => 'secondary',
                                                        };
                                                    @endphp
                                                    <span class="badge badge-{{ $badgeColor }}">
                                                        {{ ucfirst($item['type_client'] ?? 'prospect') }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->orders_count }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('client.detail', $item['id']) }}"
                                                                class="dropdown-item has-icon">
                                                                <i class="far fa-eye"></i> Détail
                                                            </a>
                                                            <a href="{{ route('client.edit', $item['id']) }}"
                                                                class="dropdown-item has-icon">
                                                                <i class="far fa-edit"></i> Modifier
                                                            </a>
                                                            <a href="#" role="button" data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon text-danger delete">
                                                                <i class="far fa-trash-alt"></i> Supprimer
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
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

    <script>
        $(document).ready(function() {
            var table = $('#tableExport').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy',  exportOptions: { columns: [0,1,2,3,4,5,6,7] } },
                    { extend: 'csv',   exportOptions: { columns: [0,1,2,3,4,5,6,7] } },
                    { extend: 'excel', exportOptions: { columns: [0,1,2,3,4,5,6,7] } },
                    { extend: 'pdf',   exportOptions: { columns: [0,1,2,3,4,5,6,7] } },
                    { extend: 'print', exportOptions: { columns: [0,1,2,3,4,5,6,7] } },
                ],
                drawCallback: function(settings) {
                    $('.delete').on("click", function(e) {
                        e.preventDefault();
                        var Id = $(this).attr('data-id');
                        swal({
                            title: "Suppression",
                            text: "Veuillez confirmer la suppression",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Confirmer",
                            cancelButtonText: "Annuler",
                        }).then((result) => {
                            if (result) {
                                $.ajax({
                                    type: "POST",
                                    url: "/admin/clients/destroy/" + Id,
                                    dataType: "json",
                                    data: { _token: '{{ csrf_token() }}' },
                                    success: function(response) {
                                        if (response.status === 200) {
                                            Swal.fire({
                                                toast: true,
                                                icon: 'success',
                                                title: 'Client supprimé avec succès',
                                                animation: false,
                                                position: 'top',
                                                background: '#3da108e0',
                                                iconColor: '#fff',
                                                color: '#fff',
                                                showConfirmButton: false,
                                                timer: 500,
                                                timerProgressBar: true,
                                            });
                                            $('#row_' + Id).remove();
                                        }
                                    }
                                });
                            }
                        });
                    });
                }
            });
        });
    </script>
@endsection
