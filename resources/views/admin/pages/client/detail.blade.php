@extends('admin.layouts.app')
@section('title', 'clients')
@section('sub-title', 'Détail client')

@section('content')
    <section class="section">

        {{-- ── Fiche client (col-12) ── --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap" style="gap: 24px;">

                            {{-- Avatar --}}
                            <div class="text-center" style="min-width:90px;">
                                <img alt="avatar" src="{{ asset('site/assets/img/custom/avatar.png') }}"
                                    class="rounded-circle" style="width:80px;height:80px;object-fit:cover;">
                                <div class="mt-1">
                                    <span class="badge badge-{{ $user['type_client'] === 'fidele' ? 'success' : 'warning' }}">
                                        {{ ucfirst($user['type_client'] ?? 'prospect') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Infos principales --}}
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                    <h4 class="mb-0 mr-2">{{ $user['name'] }}</h4>
                                    <span class="badge badge-primary">client</span>
                                </div>
                                <div class="text-muted small mb-2">Inscrit le {{ \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y') }}</div>
                                <div class="row">
                                    @php
                                        $Y = date('Y');
                                        $nex_date = $user['date_anniversaire'] . '-' . $Y;
                                        $dateAnniv = \Carbon\Carbon::parse($nex_date)->locale('fr_FR');
                                        $dateAnniv = $dateAnniv->day . ' ' . $dateAnniv->monthName;
                                    @endphp
                                    <div class="col-6 col-md-3">
                                        <span class="text-muted small">Contact</span><br>
                                        <strong>{{ $user['phone'] ?: '—' }}</strong>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="text-muted small">Email</span><br>
                                        <strong>{{ $user['email'] ?: '—' }}</strong>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="text-muted small">Localisation</span><br>
                                        <strong>{{ $user['localisation'] ?: '—' }}</strong>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="text-muted small">Anniversaire</span><br>
                                        <strong>{{ $dateAnniv }}</strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex flex-column" style="gap: 8px; min-width:120px;">
                                <a href="{{ route('client.edit', $user['id']) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit mr-1"></i> Modifier
                                </a>
                                <a href="{{ route('client.list') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left mr-1"></i> Retour
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── KPIs (col-12) ── --}}
        <div class="row">
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary"><i class="fas fa-shopping-cart"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Total</h4></div>
                        <div class="card-body">{{ $user['orders_count'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info"><i class="fas fa-calendar-alt"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Ce mois</h4></div>
                        <div class="card-body">{{ $orders_mois }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Livrées</h4></div>
                        <div class="card-body">{{ $orders_livre }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning"><i class="fas fa-spinner"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>En cours</h4></div>
                        <div class="card-body">{{ $orders_en_cours }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger"><i class="fas fa-times-circle"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Annulées</h4></div>
                        <div class="card-body">{{ $orders_annule }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-dark"><i class="fas fa-coins"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>CA total</h4></div>
                        <div class="card-body" style="font-size:13px;">{{ number_format($ca_total, 0, ',', ' ') }} F</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CA mois --}}
        <div class="alert alert-info py-2 mb-3 d-flex justify-content-between align-items-center">
            <span><i class="fas fa-chart-line mr-1"></i> CA du mois en cours :</span>
            <strong>{{ number_format($ca_mois, 0, ',', ' ') }} FCFA</strong>
        </div>

        {{-- ── Tableau commandes (col-12) ── --}}
        <div class="row">
            <div class="col-12">
                <div class="card" id="printZone">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:8px;">
                        <h5 class="mb-0">
                            <i class="fas fa-list mr-1"></i> Commandes
                            <span class="badge badge-secondary ml-1">{{ $user['orders_count'] }}</span>
                        </h5>
                        <div class="d-flex align-items-center flex-wrap no-print" style="gap:8px;">
                            {{-- Filtre dates --}}
                            <form method="GET" action="{{ route('client.detail', $user['id']) }}"
                                class="d-flex align-items-center flex-wrap" style="gap:6px;">
                                <span class="text-muted small font-weight-bold">Du</span>
                                <input type="date" name="date_debut" class="form-control form-control-sm"
                                    value="{{ $dateDebut }}" style="width:140px;">
                                <span class="text-muted small font-weight-bold">Au</span>
                                <input type="date" name="date_fin" class="form-control form-control-sm"
                                    value="{{ $dateFin }}" style="width:140px;">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fa fa-search mr-1"></i> Filtrer
                                </button>
                                @if($dateDebut || $dateFin)
                                    <a href="{{ route('client.detail', $user['id']) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Réinitialiser">
                                        <i class="fa fa-undo"></i>
                                    </a>
                                @endif
                            </form>
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                <i class="fas fa-print mr-1"></i> Imprimer
                            </button>
                        </div>
                    </div>

                    @if($dateDebut || $dateFin)
                        <div class="card-header py-2 bg-light border-top-0">
                            <small class="text-muted">
                                <i class="fas fa-filter mr-1"></i> Filtre appliqué :
                                {{ $dateDebut ? 'Du '.\Carbon\Carbon::parse($dateDebut)->format('d/m/Y') : '' }}
                                {{ $dateFin   ? ' au '.\Carbon\Carbon::parse($dateFin)->format('d/m/Y')  : '' }}
                                — {{ $orders->count() }} commande(s) affichée(s)
                            </small>
                        </div>
                    @endif

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0" id="tableCommandes">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Statut</th>
                                        <th>Articles</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                        <th class="no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $index => $order)
                                        @php
                                            $statut = \App\Models\Order::$statuts[$order->status]
                                                      ?? ['label' => $order->status, 'color' => 'secondary'];
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="text-monospace">{{ $order->code }}</span></td>
                                            <td>
                                                <span class="badge badge-{{ $statut['color'] }}">
                                                    {{ $statut['label'] }}
                                                </span>
                                            </td>
                                            <td>{{ $order->quantity_product }}</td>
                                            <td>{{ number_format($order->total, 0, ',', ' ') }} F</td>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                                            <td class="no-print">
                                                <a href="{{ route('order.show', $order->id) }}"
                                                    class="btn btn-xs btn-outline-primary" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">
                                                Aucune commande pour cette période
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($orders->count() > 0)
                                    <tfoot>
                                        <tr class="font-weight-bold bg-light">
                                            <td colspan="4" class="text-right">Total :</td>
                                            <td>{{ number_format($orders->where('status','!=','annulée')->sum('total'), 0, ',', ' ') }} F</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <style>
        @@media print {
            .no-print,
            .navbar,
            .sidebar,
            nav,
            .section-header,
            .main-sidebar { display: none !important; }
            #printZone { box-shadow: none !important; border: none !important; }
            body { font-size: 12px; }
        }
    </style>

    <script>
        $(document).ready(function () {
            $('#tableCommandes').DataTable({
                order: [[5, 'desc']],
                pageLength: 25,
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy',  exportOptions: { columns: [0,1,2,3,4,5] } },
                    { extend: 'csv',   exportOptions: { columns: [0,1,2,3,4,5] } },
                    { extend: 'excel', exportOptions: { columns: [0,1,2,3,4,5] } },
                    { extend: 'pdf',   exportOptions: { columns: [0,1,2,3,4,5] } },
                    { extend: 'print', exportOptions: { columns: [0,1,2,3,4,5] } },
                ],
            });
        });
    </script>
@endsection
