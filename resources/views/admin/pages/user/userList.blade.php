@extends('admin.layouts.app')
@section('title', 'auth')
@section('sub-title', 'Liste des utilisateurs')


@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @include('admin.components.validationMessage')
                        <div class="card-header d-flex justify-content-between align-items-center">
                            @if (request()->has('client'))
                                <h4 class="mb-0">
                                    @if (request('client') == 'prospect') Prospects
                                    @elseif (request('client') == 'fidele') Clients fidèles
                                    @else Tous les clients
                                    @endif
                                    <span class="badge badge-primary ml-1">{{ count($users) }}</span>
                                </h4>
                            @elseif (request()->has('admin'))
                                <h4 class="mb-0">Administrateurs / Équipe
                                    <span class="badge badge-primary ml-1">{{ count($users) }}</span>
                                </h4>
                            @elseif (request('user'))
                                <h4 class="mb-0">Utilisateurs — {{ ucfirst(request('user')) }}
                                    <span class="badge badge-primary ml-1">{{ count($users) }}</span>
                                </h4>
                            @else
                                <h4 class="mb-0">Tous les utilisateurs
                                    <span class="badge badge-primary ml-1">{{ count($users) }}</span>
                                </h4>
                            @endif

                            <a href="{{ route('user.registerForm') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Ajouter
                            </a>
                        </div>

                        @if (request()->has('client'))
                        <div class="card-body border-bottom py-2 bg-light">
                            <form method="GET" action="{{ url('/admin/auth') }}"
                                class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                                <input type="hidden" name="client" value="{{ request('client') }}">

                                {{-- Filtre type --}}
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="/admin/auth?client"
                                        class="btn {{ !request('client') ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Tous
                                    </a>
                                    <a href="/admin/auth?client=prospect"
                                        class="btn {{ request('client') == 'prospect' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Prospects
                                    </a>
                                    <a href="/admin/auth?client=fidele"
                                        class="btn {{ request('client') == 'fidele' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Fidèles
                                    </a>
                                </div>

                                <div class="vr mx-1" style="height:30px; border-left:1px solid #ccc;"></div>

                                {{-- Filtre date --}}
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
                                    <a href="/admin/auth?client{{ request('client') ? '='.request('client') : '' }}"
                                        class="btn btn-sm btn-outline-secondary" title="Réinitialiser les dates">
                                        <i class="fa fa-undo"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                        @endif
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
                                            <th>Type utilisateur</th>
                                            <th>Commandes</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $item)
                                            <tr id="row_{{ $item['id'] }}">
                                                <td>{{ ++$key }} </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $item->orders->count() > 0 ? 'success' : 'primary' }}">{{ $item->orders->count() > 0 ? 'Fidèle' : 'Prospect' }}</span>
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
                                                <td> {{ $date }}
                                                </td>


                                                <td>
                                                    {{ $item['role'] }}
                                                    {{-- @foreach ($item['roles'] as $role)
                                                        <br> <span
                                                            class="text-capitalize fw-bold">{{ $role['name'] }}</span>
                                                    @endforeach --}}
                                                </td>

                                                <td>
                                                    {{ $item->orders->count() }}
                                                </td>


                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('user.detail', $item['id']) }}"
                                                                class="dropdown-item has-icon"><i class="far fa-eye"></i>
                                                                Detail</a>
                                                            <a href="{{ route('user.edit', $item['id']) }}"
                                                                class="dropdown-item has-icon"><i class="far fa-edit"></i>
                                                                Edit</a>

                                                            <a href="#" role="button" data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon text-danger delete"><i
                                                                    class="far fa-trash-alt"></i>Delete</a>
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
                // destroy: true,
                dom: 'Bfrtip',
                buttons: [

                    // {
                    //     extend: 'colvis',
                    //     text: 'Colonne à afficher',
                    //     className: 'btn btn-warning',
                    //     exportOptions: {
                    //        columns: ':visible'
                    //     },

                    // },

                    {
                        extend: 'copy',
                        // text: 'Copier',
                        // className: 'btn btn-primary',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },

                    {
                        extend: 'csv',
                        // text: 'Csv',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },


                    {
                        extend: 'excel',
                        // text: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },

                    {
                        extend: 'pdf',
                        // text: 'Pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },

                    {
                        extend: 'print',
                        // text: 'Imprimer',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },


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
                                    url: "/admin/auth/destroy/" + Id,
                                    dataType: "json",
                                    data: {
                                        _token: '{{ csrf_token() }}',

                                    },
                                    success: function(response) {
                                        if (response.status === 200) {
                                            Swal.fire({
                                                toast: true,
                                                icon: 'success',
                                                title: 'Utilisateur supprimé avec success',
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
