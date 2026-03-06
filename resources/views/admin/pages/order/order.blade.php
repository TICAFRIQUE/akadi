@extends('admin.layouts.app')
@section('title', 'order')
@section('sub-title', 'Liste des commandes')

@section('css')
    <style>
        /* ─── Stat cards ─────────────────────────────────────────── */
        .stat-card {
            border-radius: 10px;
            padding: 12px 16px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 72px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
            transition: transform .15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            font-size: 1.6rem;
            opacity: .85;
            width: 36px;
            text-align: center;
            flex-shrink: 0;
        }

        .stat-card .stat-label {
            font-size: .72rem;
            opacity: .9;
            margin-bottom: 1px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .stat-card .stat-value {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .stat-card .stat-sub {
            font-size: .68rem;
            opacity: .8;
            margin-top: 2px;
        }

        .bg-attente {
            background: linear-gradient(135deg, #f7971e, #ffd200);
        }

        .bg-confirmee {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .bg-livree {
            background: linear-gradient(135deg, #06a278, #12d298);
        }

        .bg-annulee {
            background: linear-gradient(135deg, #cb2d3e, #ef473a);
        }

        .bg-total {
            background: linear-gradient(135deg, #289cf5, #84c0ec);
        }

        .bg-montant {
            background: linear-gradient(135deg, #6a3093, #a044ff);
        }

        /* ─── Filter bar ──────────────────────────────────────────── */
        .filter-bar {
            background: #f8f9fc;
            border-top: 1px solid #e3e6f0;
            border-bottom: 1px solid #e3e6f0;
            padding: 14px 20px;
        }

        .filter-section-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #6c757d;
            margin-bottom: 6px;
        }

        /* ─── Status pills ────────────────────────────────────────── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 2px solid transparent;
            transition: all .15s;
            white-space: nowrap;
            color: #495057;
            background: #fff;
            border-color: #dee2e6;
        }

        .status-pill:hover {
            text-decoration: none;
            filter: brightness(.95);
        }

        .status-pill .pill-count {
            background: rgba(0, 0, 0, .12);
            color: inherit;
            border-radius: 10px;
            padding: 0 6px;
            font-size: .7rem;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
        }

        /* couleurs actif */
        .pill-all.active {
            background: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }

        .pill-attente.active {
            background: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .pill-acompte.active {
            background: #fd7e14;
            border-color: #fd7e14;
            color: #fff;
        }

        .pill-confirmee.active {
            background: #28a745;
            border-color: #28a745;
            color: #fff;
        }

        .pill-cuisine.active {
            background: #17a2b8;
            border-color: #17a2b8;
            color: #fff;
        }

        .pill-cuisinetm.active {
            background: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .pill-livraison.active {
            background: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }

        .pill-livree.active {
            background: #20c997;
            border-color: #20c997;
            color: #fff;
        }

        .pill-annulee.active {
            background: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        .pill-precommande.active {
            background: #6f42c1;
            border-color: #6f42c1;
            color: #fff;
        }

        /* couleurs hover */
        .pill-attente:hover {
            border-color: #ffc107;
            color: #856404;
        }

        .pill-acompte:hover {
            border-color: #fd7e14;
            color: #7a3e00;
        }

        .pill-confirmee:hover {
            border-color: #28a745;
            color: #155724;
        }

        .pill-cuisine:hover {
            border-color: #17a2b8;
            color: #0c5460;
        }

        .pill-cuisinetm:hover {
            border-color: #007bff;
            color: #004085;
        }

        .pill-livraison:hover {
            border-color: #6c757d;
        }

        .pill-livree:hover {
            border-color: #20c997;
            color: #0a6a4a;
        }

        .pill-annulee:hover {
            border-color: #dc3545;
            color: #721c24;
        }

        .pill-precommande:hover {
            border-color: #6f42c1;
            color: #3d0d6e;
        }

        .badge-source {
            font-size: .7rem;
            padding: 2px 7px;
            border-radius: 12px;
            font-weight: 600;
            background: #f0f4ff;
            color: #4e73df;
        }
    </style>
@endsection

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    {{-- ===== BOUTON NOUVELLE VENTE ===== --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-shopping-cart mr-2"></i>Commandes</h4>
                        <a href="{{ route('pos.create') }}" class="btn btn-success">
                            <i class="fas fa-plus mr-1"></i> Nouvelle vente
                        </a>
                    </div>

                    {{-- ===== STATS CARDS ===== --}}
                    @php
                        $countAttente = $orders->where('status', 'attente')->count();
                        $countAttenteAcompte = $orders->where('status', 'en_attente_acompte')->count();
                        $countPrecommande = $orders->where('status', 'precommande')->count();
                        $countConfirmee = $orders->where('status', 'confirmée')->count();
                        $countCuisine = $orders->where('status', 'en_cuisine')->count();
                        $countCuisineTm = $orders->where('status', 'cuisine_terminee')->count();
                        $countLivraison = $orders->where('status', 'en_livraison')->count();
                        $countLivree = $orders->where('status', 'livrée')->count();
                        $countAnnulee = $orders->where('status', 'annulée')->count();
                        $montantTotal = $orders->sum('total');
                        $montantSolde = $orders->sum('solde_restant');
                    @endphp
                    <div class="card-body pb-0 pt-3">
                        <div class="row" style="row-gap:10px">
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="stat-card bg-total">
                                    <div class="stat-icon"><i class="fas fa-list-ol"></i></div>
                                    <div>
                                        <div class="stat-label">Total</div>
                                        <div class="stat-value">{{ count($orders) }}</div>
                                        <div class="stat-sub">commandes</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="stat-card bg-attente">
                                    <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                                    <div>
                                        <div class="stat-label">Attente</div>
                                        <div class="stat-value">{{ $countAttente + $countAttenteAcompte }}</div>
                                        <div class="stat-sub">{{ $countAttenteAcompte }} acompte &bull;
                                            {{ $countPrecommande }} précmd</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="stat-card bg-confirmee">
                                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                                    <div>
                                        <div class="stat-label">Confirmées</div>
                                        <div class="stat-value">{{ $countConfirmee }}</div>
                                        <div class="stat-sub">{{ $countCuisine + $countCuisineTm }} en cuisine</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="stat-card bg-livree">
                                    <div class="stat-icon"><i class="fas fa-shipping-fast"></i></div>
                                    <div>
                                        <div class="stat-label">Livrées</div>
                                        <div class="stat-value">{{ $countLivree }}</div>
                                        <div class="stat-sub">{{ $countLivraison }} en route</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="stat-card bg-annulee">
                                    <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                                    <div>
                                        <div class="stat-label">Annulées</div>
                                        <div class="stat-value">{{ $countAnnulee }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="stat-card bg-montant">
                                    <div class="stat-icon"><i class="fas fa-coins"></i></div>
                                    <div>
                                        <div class="stat-label">CA Total</div>
                                        <div class="stat-value" style="font-size:.9rem">
                                            {{ number_format($montantTotal, 0, ',', ' ') }}</div>
                                        <div class="stat-sub">Solde: {{ number_format($montantSolde, 0, ',', ' ') }} F</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== FILTRE BAR ===== --}}
                    <div class="filter-bar">
                        @php
                            $currentStatus = request('status', 'all');
                            $currentSource = request('source', '');
                            $statusCountMap = [
                                'all' => count($orders),
                                'attente' => $countAttente,
                                'en_attente_acompte' => $countAttenteAcompte,
                                'precommande' => $countPrecommande,
                                'confirmée' => $countConfirmee,
                                'en_cuisine' => $countCuisine,
                                'cuisine_terminee' => $countCuisineTm,
                                'en_livraison' => $countLivraison,
                                'livrée' => $countLivree,
                                'annulée' => $countAnnulee,
                            ];
                            $pillList = [
                                'all' => ['label' => 'Tous', 'cls' => 'pill-all'],
                                'attente' => ['label' => 'Attente', 'cls' => 'pill-attente'],
                                'en_attente_acompte' => ['label' => 'Att. acompte', 'cls' => 'pill-acompte'],
                                'precommande' => ['label' => 'Précommande', 'cls' => 'pill-precommande'],
                                'confirmée' => ['label' => 'Confirmée', 'cls' => 'pill-confirmee'],
                                'en_cuisine' => ['label' => 'En cuisine', 'cls' => 'pill-cuisine'],
                                'cuisine_terminee' => ['label' => 'Cuisine termin.', 'cls' => 'pill-cuisinetm'],
                                'en_livraison' => ['label' => 'En livraison', 'cls' => 'pill-livraison'],
                                'livrée' => ['label' => 'Livrée', 'cls' => 'pill-livree'],
                                'annulée' => ['label' => 'Annulée', 'cls' => 'pill-annulee'],
                            ];
                        @endphp

                        {{-- Pills statut avec compteur --}}
                        <div class="mb-3">
                            <div class="filter-section-label"><i class="fas fa-filter mr-1"></i>Statut</div>
                            <div class="d-flex flex-wrap" style="gap:6px">
                                @foreach ($pillList as $val => $opt)
                                    <a href="{{ route('order.index') }}?status={{ $val }}&date_debut={{ $dateDebut }}&date_fin={{ $dateFin }}&source={{ $currentSource }}"
                                        class="status-pill {{ $opt['cls'] }} {{ $currentStatus == $val ? 'active' : '' }}">
                                        {{ $opt['label'] }}
                                        <span class="pill-count">{{ $statusCountMap[$val] ?? 0 }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Provenance + dates sur la même ligne --}}
                        <div class="d-flex flex-wrap align-items-end" style="gap:16px">

                            <div style="flex:1;min-width:180px">
                                <div class="filter-section-label"><i class="fas fa-globe mr-1"></i>Provenance</div>
                                <div class="d-flex flex-wrap" style="gap:5px">
                                    <a href="{{ route('order.index') }}?status={{ $currentStatus }}&date_debut={{ $dateDebut }}&date_fin={{ $dateFin }}"
                                        class="status-pill pill-all {{ !$currentSource ? 'active' : '' }}">Toutes</a>
                                    @foreach ($sources as $srcKey => $src)
                                        <a href="{{ route('order.index') }}?status={{ $currentStatus }}&date_debut={{ $dateDebut }}&date_fin={{ $dateFin }}&source={{ $srcKey }}"
                                            class="status-pill pill-confirmee {{ $currentSource == $srcKey ? 'active' : '' }}">
                                            <i class="fab {{ $src['icon'] }}"></i> {{ $src['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <form action="{{ route('order.index') }}" method="get" class="d-flex align-items-end"
                                style="gap:8px;flex-shrink:0">
                                <input type="hidden" name="status" value="{{ $currentStatus }}">
                                <input type="hidden" name="source" value="{{ $currentSource }}">
                                <div>
                                    <div class="filter-section-label">Du</div>
                                    <input type="date" name="date_debut" class="form-control form-control-sm"
                                        value="{{ $dateDebut }}" style="width:138px">
                                </div>
                                <div>
                                    <div class="filter-section-label">Au</div>
                                    <input type="date" name="date_fin" class="form-control form-control-sm"
                                        value="{{ $dateFin }}" style="width:138px">
                                </div>
                                <div class="d-flex" style="gap:4px;margin-bottom:1px">
                                    <button type="submit" class="btn btn-sm btn-primary"><i
                                            class="fa fa-search"></i></button>
                                    <a href="{{ route('order.index') }}" class="btn btn-sm btn-outline-secondary"
                                        title="Réinitialiser"><i class="fa fa-undo"></i></a>
                                </div>
                            </form>

                        </div>
                    </div>

                    @include('admin.components.validationMessage')

                    <div class="card-body">

                        <!-- ========== Start Section ========== -->
                        @include('admin.pages.order.motif_annulation')
                        <!-- ========== End Section ========== -->
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tableExport">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Statut</th>
                                        <th>Provenance</th>
                                        <th>Code</th>
                                        <th>Client</th>
                                        <th>Contact</th>
                                        <th>Total</th>
                                        <th>Acompte</th>
                                        <th>Solde</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key => $item)
                                        @php
                                            $statusColors = [
                                                'attente' => 'warning',
                                                'en_attente_acompte' => 'warning',
                                                'confirmée' => 'success',
                                                'en_cuisine' => 'info',
                                                'cuisine_terminee' => 'primary',
                                                'en_livraison' => 'secondary',
                                                'livrée' => 'success',
                                                'annulée' => 'danger',
                                            ];
                                            $statusLabels = [
                                                'attente' => 'En attente',
                                                'en_attente_acompte' => 'Attente acompte',
                                                'confirmée' => 'Confirmée',
                                                'en_cuisine' => 'En cuisine',
                                                'cuisine_terminee' => 'Cuisine terminée',
                                                'en_livraison' => 'En livraison',
                                                'livrée' => 'Livrée',
                                                'annulée' => 'Annulée',
                                            ];
                                            $sc = $statusColors[$item->status] ?? 'secondary';
                                            $sl = $statusLabels[$item->status] ?? $item->status;
                                            $srcIcon =
                                                \App\Models\Order::$sources[$item->source]['icon'] ?? 'fa-question';
                                            $srcLabel =
                                                \App\Models\Order::$sources[$item->source]['label'] ??
                                                ($item->source ?? '—');
                                        @endphp
                                        <tr id="row_{{ $item->id }}">
                                            <td>{{ ++$key }}</td>
                                            <td>
                                                <span class="badge badge-{{ $sc }} text-white p-1 px-2"
                                                    style="white-space:nowrap;font-size:.75rem">
                                                    {{ $sl }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-source">
                                                    <i class="fab {{ $srcIcon }} mr-1"></i>{{ $srcLabel }}
                                                </span>
                                            </td>
                                            <td><strong>{{ $item->code }}</strong></td>
                                            <td>{{ $item->nom_client }}</td>
                                            <td>{{ $item->tel_client }}</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($item->total ?? 0, 0, '', ' ') }} FCFA</td>
                                            <td class="text-right text-success small">
                                                {{ number_format($item->acompte ?? 0, 0, '', ' ') }}</td>
                                            <td
                                                class="text-right small {{ ($item->solde_restant ?? 0) > 0 ? 'text-danger' : 'text-muted' }}">
                                                {{ number_format($item->solde_restant ?? 0, 0, '', ' ') }}
                                            </td>
                                            <td style="white-space:nowrap;font-size:.82rem">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" data-toggle="dropdown"
                                                        class="btn btn-sm btn-warning dropdown-toggle">Options</a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="{{ route('order.show', $item->id) }}"
                                                            class="dropdown-item has-icon"><i class="fas fa-eye"></i>
                                                            Détail</a>
                                                        <a href="{{ route('pos.edit', $item->id) }}"
                                                            class="dropdown-item has-icon"><i class="fas fa-edit"></i>
                                                            Modifier</a>
                                                        {{-- @if (!in_array($item->status, ['livrée', 'annulée']))
                                                            <div class="dropdown-divider"></div>
                                                            @foreach ($statuts as $stKey => $st)
                                                                @if ($stKey !== $item->status)
                                                                    <a href="{{ route('order.changeState') }}?cs={{ $stKey }}&id={{ $item->id }}"
                                                                        class="dropdown-item has-icon {{ $stKey === 'annulée' ? 'text-danger' : '' }}">
                                                                        {{ $st['label'] }}
                                                                    </a>
                                                                @endif
                                                            @endforeach
                                                            <div class="dropdown-divider"></div>
                                                            <a href="#" role="button"
                                                                data-id="{{ $item->id }}"
                                                                class="dropdown-item has-icon text-danger btnCancel">
                                                                <i data-feather="x-circle"></i> Annuler
                                                            </a>
                                                        @endif --}}
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

    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>

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
                    // $('.delete').on("click", function(e) {
                    //     e.preventDefault();
                    //     var Id = $(this).attr('data-id');
                    //     swal({
                    //         title: "Suppression",
                    //         text: "Veuillez confirmer la suppression",
                    //         type: "warning",
                    //         showCancelButton: true,
                    //         confirmButtonText: "Confirmer",
                    //         cancelButtonText: "Annuler",
                    //     }).then((result) => {
                    //         if (result) {
                    //             $.ajax({
                    //                 type: "POST",
                    //                 url: "/admin/auth/destroy/" + Id,
                    //                 dataType: "json",
                    //                 data: {
                    //                     _token: '{{ csrf_token() }}',

                    //                 },
                    //                 success: function(response) {
                    //                     if (response.status === 200) {
                    //                         Swal.fire({
                    //                             toast: true,
                    //                             icon: 'success',
                    //                             title: 'Utilisateur supprimé avec success',
                    //                             animation: false,
                    //                             position: 'top',
                    //                             background: '#3da108e0',
                    //                             iconColor: '#fff',
                    //                             color: '#fff',
                    //                             showConfirmButton: false,
                    //                             timer: 500,
                    //                             timerProgressBar: true,
                    //                         });
                    //                         $('#row_' + Id).remove();

                    //                     }
                    //                 }
                    //             });
                    //         }
                    //     });
                    // });

                    // motif d'annulation 

                    $('.motif_autre').hide();

                    $('.btnCancel').on('click', function(e) {
                        e.preventDefault();
                        var cmdId = $(this).attr('data-id');
                        $('#commandeId').val(cmdId);
                        $('#motif_selected').val('');
                        $('#_motif_autre').val('');
                        $('.motif_autre').hide();
                        $('#_motif_autre').prop('required', false);
                        $('#modalMotifAnnulation').modal('show');
                    });

                    $('#motif_selected').on('change', function() {
                        if ($(this).val() === 'autre') {
                            $('.motif_autre').show();
                            $('#_motif_autre').prop('required', true);
                        } else {
                            $('.motif_autre').hide();
                            $('#_motif_autre').prop('required', false);
                        }
                    });





                    //executer la fonction chaque 5 secondes
                    // setInterval(newOrders, 5000);

                    setInterval(function() {
                        moment.locale('fr'); // définit la langue en français
                        $.ajax({
                            url: "{{ route('order.checkNewOrder') }}",
                            method: "GET",
                            success: function(data) {
                                console.log(data);

                                if (data.orders && data.orders.length > 0) {

                                    // jouer la notification
                                    const audio = new Audio(
                                        '/assets/sound/notification.mp3');
                                    audio.play();
                                    // Afficher une notification
                                    const notification = document.createElement(
                                        'div');
                                    notification.className = 'notification';
                                    notification.innerHTML = `
                                    <div class="notification-content">
                                        <strong>Nouvelle commande</strong>
                                        <p>Vous avez ${data.orders.length} nouvelle(s) commande(s).</p>
                                    </div>
                                `;
                                    document.body.appendChild(notification);
                                    // Supprimer la notification après 5 secondes
                                    setTimeout(() => {
                                        notification.remove();
                                    })


                                    data.orders.forEach(function(item, index) {
                                        if (!document.querySelector(
                                                '#row_' + item
                                                .id)) {
                                            let badgeClass = '';
                                            let icon = '';

                                            switch (item.status) {
                                                case 'attente':
                                                    badgeClass =
                                                        'badge-primary';
                                                    break;
                                                case 'livrée':
                                                    badgeClass =
                                                        'badge-success';
                                                    break;
                                                case 'confirmée':
                                                    badgeClass =
                                                        'badge-info';
                                                    break;
                                                case 'annulée':
                                                    badgeClass =
                                                        'badge-danger';
                                                    break;
                                                case 'precommande':
                                                    badgeClass =
                                                        'badge-dark';
                                                    icon =
                                                        '<i class="fa fa-clock"></i> ';
                                                    break;
                                            }

                                            const html = `
                                    <tr id="row_${item.id}">
                                        <td> <span class="badge badge-success text-white p-1 px-3"> Nouveau </span>  </td>
                                        <td><span class="badge ${badgeClass} text-white p-1 px-3">${icon}${item.status}</span></td>
                                        <td><span style="font-weight:bold">${item.code}</span></td>
                                        <td>${item.user.name}</td>
                                        <td>${item.user.phone}</td>
                                        <td>${item.user.email}</td>
                                        <td>${Number(item.total).toLocaleString()}</td>
                                        <td>${item.created_at}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" data-toggle="dropdown" class="btn btn-warning dropdown-toggle">Options</a>
                                                <div class="dropdown-menu">
                                                    <a href="/admin/order/show/${item.id}" class="dropdown-item has-icon">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                    ${item.status !== 'livrée' && item.status !== 'annulée' ? `
                                                                            <a href="/admin/order/changeState?cs=confirmée && id=${item.id}" class="dropdown-item has-icon">
                                                                                <i class="fas fa-check"></i> Confirmée
                                                                            </a>
                                                                            <a href="/admin/order/changeState?cs=livrée && id=${item.id}" class="dropdown-item has-icon">
                                                                                <i class="fas fa-shipping-fast"></i> Livrée
                                                                            </a>
                                                                            <a href="/admin/order/changeState?cs=attente && id=${item.id}" class="dropdown-item has-icon">
                                                                                <i class="fas fa-arrow-down"></i> Attente
                                                                            </a>
                                                                            <a href="#" role="button" data-id="${item.id}" class="dropdown-item has-icon text-danger btnCancel">
                                                                                <i data-feather="x-circle"></i> Annuler
                                                                            </a>
                                                                        ` : ''}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>`;

                                            $('#tableExport tbody').prepend(
                                                html
                                            ); // remplace #myTable par l’ID réel de ton tableau
                                        }
                                    });
                                }
                            }
                        });
                    }, 5000);

                }
            });
        });
    </script>
@endsection
