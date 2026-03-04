@extends('admin.layouts.app')
@section('title', 'order')
@section('sub-title', 'Liste des commandes')

@section('css')
<style>
    .stat-card {
        border-radius: 12px;
        padding: 16px 20px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 80px;
    }
    .stat-card .stat-icon {
        font-size: 2rem;
        opacity: .85;
        width: 44px;
        text-align: center;
    }
    .stat-card .stat-label { font-size: .78rem; opacity: .9; margin-bottom: 2px; }
    .stat-card .stat-value { font-size: 1.5rem; font-weight: 700; line-height: 1; }
    .stat-card .stat-sub   { font-size: .72rem; opacity: .8; margin-top: 2px; }

    .bg-attente    { background: linear-gradient(135deg,#f7971e,#ffd200); }
    .bg-confirmee  { background: linear-gradient(135deg,#11998e,#38ef7d); }
    .bg-livree     { background: linear-gradient(135deg,#06a278,#12d298); }
    .bg-annulee    { background: linear-gradient(135deg,#cb2d3e,#ef473a); }
    .bg-total      { background: linear-gradient(135deg,#289cf5,#84c0ec); }
    .bg-montant    { background: linear-gradient(135deg,#6a3093,#a044ff); }

    .filter-bar {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        padding: 12px 20px;
    }
    .status-pills .btn { border-radius: 20px; font-size: .8rem; padding: 4px 14px; }
</style>
@endsection

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    {{-- ===== STATS CARDS ===== --}}
                    @php
                        $countAttente   = $orders->where('status','attente')->count();
                        $countConfirmee = $orders->where('status','confirmée')->count();
                        $countLivree    = $orders->where('status','livrée')->count();
                        $countAnnulee   = $orders->where('status','annulée')->count();
                        $montantTotal   = $orders->sum('total');
                    @endphp
                    <div class="card-body pb-2">
                        <div class="row g-3">
                            <div class="col-6 col-md-4 col-xl-2">
                                <div class="stat-card bg-total">
                                    <div class="stat-icon"><i class="fas fa-list-ol"></i></div>
                                    <div>
                                        <div class="stat-label">Total</div>
                                        <div class="stat-value">{{ count($orders) }}</div>
                                        <div class="stat-sub">commandes</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-xl-2">
                                <div class="stat-card bg-attente">
                                    <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                                    <div>
                                        <div class="stat-label">Attente</div>
                                        <div class="stat-value">{{ $countAttente }}</div>
                                        <div class="stat-sub">en cours</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-xl-2">
                                <div class="stat-card bg-confirmee">
                                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                                    <div>
                                        <div class="stat-label">Confirmées</div>
                                        <div class="stat-value">{{ $countConfirmee }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-xl-2">
                                <div class="stat-card bg-livree">
                                    <div class="stat-icon"><i class="fas fa-shipping-fast"></i></div>
                                    <div>
                                        <div class="stat-label">Livrées</div>
                                        <div class="stat-value">{{ $countLivree }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-xl-2">
                                <div class="stat-card bg-annulee">
                                    <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                                    <div>
                                        <div class="stat-label">Annulées</div>
                                        <div class="stat-value">{{ $countAnnulee }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-xl-2">
                                <div class="stat-card bg-montant">
                                    <div class="stat-icon"><i class="fas fa-coins"></i></div>
                                    <div>
                                        <div class="stat-label">Montant</div>
                                        <div class="stat-value" style="font-size:1.1rem">{{ number_format($montantTotal,0,',',' ') }}</div>
                                        <div class="stat-sub">FCFA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== FILTRE BAR ===== --}}
                    <div class="filter-bar">
                        <form action="{{ route('order.index') }}" method="get">
                            <div class="row align-items-end g-2">

                                {{-- Pills statut --}}
                                <div class="col-12 col-lg-5">
                                    <label class="small font-weight-bold d-block mb-1">Statut</label>
                                    <div class="status-pills d-flex flex-wrap" style="gap:6px;">
                                        @php
                                            $statuts = [
                                                'all'         => ['label' => 'Tous',         'class' => 'btn-secondary'],
                                                'attente'     => ['label' => 'Attente',       'class' => 'btn-warning'],
                                                'confirmée'   => ['label' => 'Confirmée',     'class' => 'btn-success'],
                                                'livrée'      => ['label' => 'Livrée',        'class' => 'btn-info'],
                                                'annulée'     => ['label' => 'Annulée',       'class' => 'btn-danger'],
                                                'precommande' => ['label' => 'Précommande',   'class' => 'btn-dark'],
                                            ];
                                            $currentStatus = request('status', 'all');
                                        @endphp
                                        @foreach($statuts as $val => $opt)
                                            <a href="{{ route('order.index') }}?status={{ $val }}&date_debut={{ $dateDebut }}&date_fin={{ $dateFin }}"
                                               class="btn btn-sm {{ $currentStatus == $val ? $opt['class'] : 'btn-outline-'.\Str::after($opt['class'],'btn-') }}">
                                               {{ $opt['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Dates --}}
                                <div class="col-5 col-lg-2">
                                    <label class="small font-weight-bold">Du</label>
                                    <input type="hidden" name="status" value="{{ request('status','all') }}">
                                    <input type="date" name="date_debut" class="form-control form-control-sm"
                                        value="{{ $dateDebut }}">
                                </div>
                                <div class="col-5 col-lg-2">
                                    <label class="small font-weight-bold">Au</label>
                                    <input type="date" name="date_fin" class="form-control form-control-sm"
                                        value="{{ $dateFin }}">
                                </div>
                                <div class="col-2 col-lg-1">
                                    <div class="d-flex" style="gap:4px">
                                        <button type="submit" class="btn btn-sm btn-primary px-3">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{ route('order.index') }}" class="btn btn-sm btn-outline-secondary" title="Réinitialiser">
                                            <i class="fa fa-undo"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </form>
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
                                        <th class="text-center">
                                            #
                                        </th>
                                        <th>Statut</th>
                                        <th>code</th>
                                        <th>Nom du client</th>
                                        <th>Contact du client</th>
                                        <th>Email du client</th>
                                        {{-- <th>Livraison</th> --}}
                                        <th>Total</th>
                                        <th>date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key => $item)
                                        <tr id="row_{{ $item['id'] }}">
                                            <td>{{ ++$key }} </td>

                                            <td>
                                                <span
                                                    class="badge {{ $item['status'] == 'attente' ? 'badge-primary' : ($item['status'] == 'livrée' ? 'badge-success' : ($item['status'] == 'confirmée' ? 'badge-info' : ($item['status'] == 'annulée' ? 'badge-danger' : ($item['status'] == 'precommande' ? 'badge-dark' : '')))) }} text-white p-1 px-3"><i
                                                        class="{{ $item['status'] == 'precommande' ? 'fa fa-clock' : '' }}"></i>
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>

                                            <td><span style="font-weight:bold">{{ $item['code'] }}</span> </td>
                                            <td>{{ $item['user']['name'] }}</td>
                                            <td> {{ $item['user']['phone'] }}</td>
                                            <td> {{ $item['user']['email'] }}</td>
                                            {{-- <td>{{ $item['delivery_name'] }} - {{ $item['delivery_price'] }} </td> --}}
                                            <td>{{ number_format($item['total'], 0, '', ' ') }} </td>
                                            <td>
                                                {{-- {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                                                <br> --}}
                                                {{ \Carbon\Carbon::parse($item['created_at'])->isoFormat('dddd D MMMM YYYY à HH:mm') }}

                                            </td>

                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" data-toggle="dropdown"
                                                        class="btn btn-warning dropdown-toggle">Options</a>
                                                    <div class="dropdown-menu">
                                                        <a href="{{ route('order.show', $item['id']) }}"
                                                            class="dropdown-item has-icon"><i class="fas fa-eye"></i>
                                                            Detail</a>

                                                        @if ($item['status'] != 'livrée' && $item['status'] != 'annulée')
                                                            <a href="/admin/order/changeState?cs=confirmée && id={{ $item['id'] }}"
                                                                class="dropdown-item has-icon"><i
                                                                    class="fas fa-check"></i>
                                                                Confirmée</a>
                                                            <a href="/admin/order/changeState?cs=livrée && id={{ $item['id'] }}"
                                                                class="dropdown-item has-icon"><i
                                                                    class="fas fa-shipping-fast"></i>
                                                                Livrée</a>
                                                            <a href="/admin/order/changeState?cs=attente && id={{ $item['id'] }}"
                                                                class="dropdown-item has-icon"><i
                                                                    class="fas fa-arrow-down"></i>
                                                                Attente</a>

                                                            {{-- <a href="/admin/order/changeState?cs=annulée && id={{ $item['id'] }}"
                                                                    role="button" data-id="{{ $item['id'] }}"
                                                                    class="dropdown-item has-icon text-danger delete"><i
                                                                        data-feather="x-circle"></i> Annuler</a> --}}


                                                            <a href="#" role="button"
                                                                data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon text-danger btnCancel"><i
                                                                    data-feather="x-circle"></i> Annuler</a>

                                                            {{-- @include('admin.pages.order.motif_annulation') --}}
                                                        @endif


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

                    $('.motif_autre').hide(); //textarea autre_motif
                    $('#motif_annulation').hide(); // div form

                    //si on appuie sur annuler une commande
                    $('.btnCancel').click(function(e) {
                        e.preventDefault();
                        //on recupere ID de la commande
                        var cmdId = $(this).attr('data-id');

                        $('#commandeId').val(cmdId);

                        //scroller jusqu'au formulaire motif annulation
                        $('html, body').animate({
                            scrollTop: $("#motif_annulation").offset().top - 90
                        }, 500);

                        //afficher le formulaire
                        $('#motif_annulation').show(); // div form


                        //choisir le motif
                        $('#motif_selected').change(function(e) {
                            e.preventDefault();
                            var motif_select = $(this).val()

                            if (motif_select == 'autre') {
                                $('.motif_autre').show();
                                $('#_motif_autre').prop('required', true);

                            } else {
                                $('.motif_autre').hide();
                                $('#_motif_autre').prop('required', false);

                            }

                        });
                    });


                    //fermer le formulaire motif annulation
                    $('.btn-close').click(function(e) {
                        e.preventDefault();
                        $('#motif_selected').val('')
                        $('#_motif_autre').val('')
                        $('#motif_annulation').hide(); // div form
                        $('.motif_autre').hide(); //textarea autre_motif
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
