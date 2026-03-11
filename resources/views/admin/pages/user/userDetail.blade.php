@extends('admin.layouts.app')
@section('title', 'Détail utilisateur')
@section('sub-title', 'Fiche utilisateur – ' . $user->name)

@section('content')
    <section class="section">

        {{-- ── Fiche utilisateur ── --}}
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
                                    <span
                                        class="badge badge-primary">{{ $user->roles->first()?->name ?? 'utilisateur' }}</span>
                                </div>
                            </div>

                            {{-- Infos principales --}}
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                    <h4 class="mb-0 mr-2">{{ $user->name }}</h4>
                                </div>
                                <div class="text-muted small mb-2">
                                    Inscrit le {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                                </div>
                                <div class="row">
                                    @php
                                        $Y = date('Y');
                                        $nex_date = $user->date_anniversaire . '-' . $Y;
                                        $dateAnniv = \Carbon\Carbon::parse($nex_date)->locale('fr_FR');
                                        $dateAnniv = $dateAnniv->day . ' ' . $dateAnniv->monthName;
                                    @endphp
                                    <div class="col-6 col-md-3">
                                        <span class="text-muted small">Contact</span><br>
                                        <strong>{{ $user->phone ?: '—' }}</strong>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="text-muted small">Email</span><br>
                                        <strong>{{ $user->email ?: '—' }}</strong>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="text-muted small">Localisation</span><br>
                                        <strong>{{ $user->localisation ?: '—' }}</strong>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="text-muted small">Anniversaire</span><br>
                                        <strong>{{ $dateAnniv }}</strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex flex-column" style="gap: 8px; min-width:120px;">
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit mr-1"></i> Modifier
                                </a>
                                <a href="{{ route('user.list') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left mr-1"></i> Retour
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── KPIs ── --}}
        <div class="row">
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary"><i class="fas fa-shopping-cart"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total</h4>
                        </div>
                        <div class="card-body">{{ $user->orders_count }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info"><i class="fas fa-calendar-alt"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Ce mois</h4>
                        </div>
                        <div class="card-body">{{ $orders_mois }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Livrées</h4>
                        </div>
                        <div class="card-body">{{ $orders_livre }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning"><i class="fas fa-spinner"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>En cours</h4>
                        </div>
                        <div class="card-body">{{ $orders_en_cours }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger"><i class="fas fa-times-circle"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Annulées</h4>
                        </div>
                        <div class="card-body">{{ $orders_annule }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-dark"><i class="fas fa-coins"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>CA total</h4>
                        </div>
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

        {{-- ── Tableau commandes ── --}}
        <div class="row">
            <div class="col-12">
                <div class="card" id="printZone">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap:8px;">
                        <h5 class="mb-0">
                            <i class="fas fa-list mr-1"></i> Commandes
                            <span class="badge badge-secondary ml-1">{{ $user->orders_count }}</span>
                        </h5>
                        <div class="d-flex align-items-center flex-wrap no-print" style="gap:8px;">
                            <form method="GET" action="{{ route('user.detail', $user->id) }}"
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
                                @if ($dateDebut || $dateFin)
                                    <a href="{{ route('user.detail', $user->id) }}"
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

                    @if ($dateDebut || $dateFin)
                        <div class="card-header py-2 bg-light border-top-0">
                            <small class="text-muted">
                                <i class="fas fa-filter mr-1"></i> Filtre appliqué :
                                {{ $dateDebut ? 'Du ' . \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') : '' }}
                                {{ $dateFin ? ' au ' . \Carbon\Carbon::parse($dateFin)->format('d/m/Y') : '' }}
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
                                            $statut = \App\Models\Order::$statuts[$order->status] ?? [
                                                'label' => $order->status,
                                                'color' => 'secondary',
                                            ];
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
                                @if ($orders->count() > 0)
                                    <tfoot>
                                        <tr class="font-weight-bold bg-light">
                                            <td colspan="4" class="text-right">Total :</td>
                                            <td>{{ number_format($orders->where('status', '!=', 'annulée')->sum('total'), 0, ',', ' ') }}
                                                F</td>
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
        {{-- ── Permissions directes ── --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-key mr-1"></i> Permissions directes</h5>
                        <span class="badge badge-info">{{ $user->getDirectPermissions()->count() }} /
                            {{ $permissions->count() }}</span>
                    </div>
                    <div class="card-body">

                        {{-- Rôles actuels (lecture seule) --}}
                        <div class="mb-3">
                            <span class="text-muted small font-weight-bold">Rôles :</span>
                            @forelse($user->roles as $role)
                                <span class="badge badge-primary mr-1">{{ $role->name }}</span>
                            @empty
                                <span class="text-muted small">Aucun rôle assigné</span>
                            @endforelse
                        </div>

                        @if (session('success'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Succès',
                                        text: '{{ session('success') }}',
                                        timer: 2500,
                                        showConfirmButton: false
                                    });
                                });
                            </script>
                        @endif
                        @if (session('error'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur',
                                        text: '{{ session('error') }}'
                                    });
                                });
                            </script>
                        @endif

                        @php
                            $groupLabels = [
                                'dashboard'      => ['label' => 'Dashboard',       'icon' => 'fas fa-tachometer-alt'],
                                'catalogue'      => ['label' => 'Catalogue',       'icon' => 'fas fa-tags'],
                                'ventes'         => ['label' => 'Ventes',          'icon' => 'fas fa-shopping-cart'],
                                'caisse'         => ['label' => 'Caisse',          'icon' => 'fas fa-cash-register'],
                                'contenu'        => ['label' => 'Contenu',         'icon' => 'fas fa-photo-video'],
                                'depenses'       => ['label' => 'Dépenses',        'icon' => 'fas fa-file-invoice-dollar'],
                                'rapports'       => ['label' => 'Rapports',        'icon' => 'fas fa-chart-bar'],
                                'administration' => ['label' => 'Administration',  'icon' => 'fas fa-cogs'],
                                'p'              => ['label' => 'Spécial ventes',  'icon' => 'fas fa-star'],
                            ];
                            $grouped = $permissions->groupBy(function($p) {
                                return str_contains($p->name, '.') ? explode('.', $p->name)[0] : explode('-', $p->name)[0];
                            });
                        @endphp

                        <form action="{{ route('user.permissions.sync', $user->id) }}" method="POST" id="formPermissions">
                            @csrf
                            @if ($permissions->isEmpty())
                                <p class="text-muted text-center py-3">Aucune permission définie.</p>
                            @else
                                {{-- Barre d'outils globale --}}
                                <div class="d-flex align-items-center flex-wrap mb-3" style="gap:8px;">
                                    <div class="input-group" style="max-width:260px;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0">
                                                <i class="fas fa-search text-muted" style="font-size:.8rem;"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="searchPerm" class="form-control border-left-0"
                                            placeholder="Rechercher une permission…" autocomplete="off"
                                            style="font-size:.85rem;">
                                    </div>
                                    <button type="button" id="btnCheckAll" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-check-square mr-1"></i> Tout cocher
                                    </button>
                                    <button type="button" id="btnUncheckAll" class="btn btn-outline-secondary btn-sm">
                                        <i class="far fa-square mr-1"></i> Tout décocher
                                    </button>
                                </div>

                                {{-- Pas de résultats --}}
                                <p id="noSearchResult" class="text-muted small d-none">Aucune permission ne correspond à la recherche.</p>

                                {{-- Groupes --}}
                                @foreach ($grouped as $prefix => $group)
                                    @php
                                        $meta  = $groupLabels[$prefix] ?? ['label' => ucfirst($prefix), 'icon' => 'fas fa-key'];
                                        $gId   = 'group_' . $prefix;
                                    @endphp
                                    <div class="perm-group mb-3" id="{{ $gId }}">
                                        <div class="d-flex align-items-center justify-content-between mb-1
                                                    border-bottom pb-1">
                                            <span class="font-weight-bold text-dark" style="font-size:.9rem;">
                                                <i class="{{ $meta['icon'] }} mr-1 text-primary"></i>
                                                {{ $meta['label'] }}
                                                <span class="badge badge-light text-muted ml-1 group-count">{{ $group->count() }}</span>
                                            </span>
                                            <div style="gap:4px;" class="d-flex">
                                                <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-1 btn-group-check"
                                                        data-group="{{ $gId }}" style="font-size:.75rem;">
                                                    <i class="fas fa-check-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-1 btn-group-uncheck"
                                                        data-group="{{ $gId }}" style="font-size:.75rem;">
                                                    <i class="far fa-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row perm-group-items">
                                            @foreach ($group as $permission)
                                                <div class="col-6 col-md-4 col-lg-3 mb-1 perm-item" data-name="{{ strtolower($permission->name) }}">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input perm-checkbox"
                                                            id="perm_{{ $permission->id }}" name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            {{ in_array($permission->name, $userPermissions) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="perm_{{ $permission->id }}"
                                                            style="font-size:.82rem;">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save mr-1"></i> Enregistrer les permissions
                                    </button>
                                </div>
                            @endif
                        </form>

                        <script>
                            (function () {
                                // Tout cocher / décocher global
                                document.getElementById('btnCheckAll')?.addEventListener('click', function () {
                                    document.querySelectorAll('.perm-item:not([style*="display: none"]) .perm-checkbox').forEach(cb => cb.checked = true);
                                });
                                document.getElementById('btnUncheckAll')?.addEventListener('click', function () {
                                    document.querySelectorAll('.perm-item:not([style*="display: none"]) .perm-checkbox').forEach(cb => cb.checked = false);
                                });

                                // Cocher / décocher par groupe
                                document.querySelectorAll('.btn-group-check').forEach(btn => {
                                    btn.addEventListener('click', function () {
                                        document.querySelectorAll('#' + this.dataset.group + ' .perm-item:not([style*="display: none"]) .perm-checkbox')
                                            .forEach(cb => cb.checked = true);
                                    });
                                });
                                document.querySelectorAll('.btn-group-uncheck').forEach(btn => {
                                    btn.addEventListener('click', function () {
                                        document.querySelectorAll('#' + this.dataset.group + ' .perm-item:not([style*="display: none"]) .perm-checkbox')
                                            .forEach(cb => cb.checked = false);
                                    });
                                });

                                // Recherche temps réel
                                document.getElementById('searchPerm')?.addEventListener('input', function () {
                                    const q = this.value.trim().toLowerCase();
                                    let totalVisible = 0;

                                    document.querySelectorAll('.perm-group').forEach(function (group) {
                                        let groupVisible = 0;
                                        group.querySelectorAll('.perm-item').forEach(function (item) {
                                            const match = !q || item.dataset.name.includes(q);
                                            item.style.display = match ? '' : 'none';
                                            if (match) { groupVisible++; totalVisible++; }
                                        });
                                        group.style.display = groupVisible > 0 ? '' : 'none';
                                        const badge = group.querySelector('.group-count');
                                        if (badge) badge.textContent = groupVisible;
                                    });

                                    const noResult = document.getElementById('noSearchResult');
                                    if (noResult) noResult.classList.toggle('d-none', totalVisible > 0);
                                });
                            })();
                        </script>
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
            .main-sidebar {
                display: none !important;
            }

            #printZone {
                box-shadow: none !important;
                border: none !important;
            }

            body {
                font-size: 12px;
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#tableCommandes').DataTable({
                order: [
                    [5, 'desc']
                ],
                pageLength: 25,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                ],
            });
        });
    </script>
@endsection
