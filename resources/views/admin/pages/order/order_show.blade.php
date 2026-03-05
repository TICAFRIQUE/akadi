@extends('admin.layouts.app')
@section('title', 'Détail commande')
@section('sub-title', 'Détail de la commande #' . $orders->code)

@section('css')
<style>
.info-section { background: #f8f9fa; border-radius: 10px; padding: 16px 20px; margin-bottom: 16px; }
.info-section h6 { font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #6c757d; margin-bottom: 10px; border-bottom: 2px solid #dee2e6; padding-bottom: 6px; }
.info-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: .9rem; }
.info-row span:first-child { color: #6c757d; }
.info-row span:last-child { font-weight: 600; }
.product-card { border: 1px solid #e9ecef; border-radius: 10px; padding: 12px; display: flex; gap: 12px; align-items: flex-start; }
.product-card img { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; }
.product-card .no-img { width: 56px; height: 56px; background: #f0f4ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
.total-recap .line { display: flex; justify-content: space-between; padding: 5px 0; }
.total-recap .final { font-size: 1.2rem; font-weight: 700; border-top: 2px solid #dee2e6; padding-top: 10px; margin-top: 5px; }
.total-recap .solde { color: #dc3545; }
.total-recap .acompte { color: #28a745; }
.status-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: .82rem; font-weight: 700; color: #fff; }
.source-badge { display: inline-flex; align-items: center; gap: 5px; background: #f0f4ff; padding: 3px 10px; border-radius: 12px; font-size: .78rem; font-weight: 600; color: #4e73df; }
.caisse-badge { background: #e8f5e9; color: #2e7d32; padding: 3px 10px; border-radius: 12px; font-size: .78rem; font-weight: 600; display: inline-block; }
</style>
@endsection

@section('content')
<div class="section-body">
    @include('admin.components.validationMessage')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    {{-- ── Barre d'action ── --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:8px">
        <button class="btn btn-link p-0" onclick="history.back()">
            <i data-feather="arrow-left"></i> Retour
        </button>
        <div class="d-flex" style="gap:8px;flex-wrap:wrap">
            <a href="{{ route('pos.edit', $orders->id) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-1"></i> Modifier
            </a>
            @if(!in_array($orders->status, ['livrée','annulée']))
            <div class="dropdown">
                <a href="#" data-toggle="dropdown" class="btn btn-dark dropdown-toggle">
                    <i class="fas fa-exchange-alt mr-1"></i> Changer statut
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($statuts as $stKey => $st)
                        @if($stKey === $orders->status)
                        <span class="dropdown-item text-success font-weight-bold" style="cursor:default;background:#f0fff4">
                            <i class="fas fa-check-circle mr-1"></i>{{ $st['label'] }}
                        </span>
                        @else
                        <a href="{{ route('order.changeState') }}?cs={{ $stKey }}&id={{ $orders->id }}"
                            class="dropdown-item {{ $stKey==='annulée' ? 'text-danger' : '' }}">
                            {{ $st['label'] }}
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
            <a href="{{ route('order.invoice', $orders->id) }}" target="_blank" class="btn btn-outline-secondary">
                <i data-feather="file-text"></i> Facture
            </a>
        </div>
    </div>

    <div class="row">
        {{-- ════ COLONNE GAUCHE ════ --}}
        <div class="col-12 col-lg-8">

            {{-- En-tête --}}
            <div class="info-section">
                <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:8px">
                    <div>
                        <h5 class="mb-1">
                            Commande <strong>#{{ $orders->code }}</strong>
                            &nbsp;
                            @php
                                $statusColors = ['attente'=>'warning','en_attente_acompte'=>'warning','confirmée'=>'success','en_cuisine'=>'info','cuisine_terminee'=>'primary','en_livraison'=>'secondary','livrée'=>'success','annulée'=>'danger'];
                                $statusLabels = ['attente'=>'En attente','en_attente_acompte'=>'Attente acompte','confirmée'=>'Confirmée','en_cuisine'=>'En cuisine','cuisine_terminee'=>'Cuisine terminée','en_livraison'=>'En livraison','livrée'=>'Livrée','annulée'=>'Annulée'];
                            @endphp
                            <span class="status-badge bg-{{ $statusColors[$orders->status] ?? 'secondary' }}">
                                {{ $statusLabels[$orders->status] ?? $orders->status }}
                            </span>
                        </h5>
                        <small class="text-muted">
                            Créée le {{ $orders->created_at->format('d/m/Y à H:i') }}
                            @if($orders->createdBy)
                                &nbsp;·&nbsp; par <strong>{{ $orders->createdBy->name }}</strong>
                            @endif
                        </small>
                    </div>
                    <div class="d-flex" style="gap:6px;flex-wrap:wrap">
                        @php
                            $srcIcon  = \App\Models\Order::$sources[$orders->source]['icon'] ?? 'fa-question';
                            $srcLabel = \App\Models\Order::$sources[$orders->source]['label'] ?? ($orders->source ?? '—');
                        @endphp
                        <span class="source-badge"><i class="fab {{ $srcIcon }}"></i> {{ $srcLabel }}</span>
                        @if($orders->caisse)
                        <span class="caisse-badge"><i class="fas fa-cash-register mr-1"></i> {{ $orders->caisse->nom }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Articles --}}
            <div class="info-section">
                <h6><i class="fas fa-box-open mr-1"></i>Articles ({{ $orders->quantity_product }})</h6>
                <div class="row">
                    @foreach($orders->products as $item)
                    @php
                        $pivot     = $item->pivot;
                        $netPrice  = $pivot->prix_apres_remise ?? ($pivot->unit_price - ($pivot->discount ?? 0));
                        $lineTotal = $pivot->total ?? ($netPrice * $pivot->quantity);
                    @endphp
                    <div class="col-12 col-md-6 mb-3">
                        <div class="product-card">
                            @if($item->getFirstMediaUrl('principal_img'))
                                <img src="{{ $item->getFirstMediaUrl('principal_img') }}" alt="">
                            @else
                                <div class="no-img"><i class="fas fa-box text-muted"></i></div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">{{ $item->title }}</div>
                                <div class="text-muted small mt-1">
                                    Qté&nbsp;: <strong>{{ $pivot->quantity }}</strong>
                                    &nbsp;·&nbsp; PU&nbsp;: {{ number_format($pivot->unit_price, 0, '', ' ') }} FCFA
                                    @if(($pivot->discount ?? 0) > 0)
                                    <br>
                                    <span class="text-danger">Remise&nbsp;: –{{ number_format($pivot->discount, 0, '', ' ') }} %</span>
                                    &nbsp;·&nbsp; Net&nbsp;: {{ number_format($netPrice, 0, '', ' ') }} FCFA
                                    @endif
                                </div>
                                <div class="font-weight-bold text-dark mt-1">
                                    {{ number_format($lineTotal, 0, '', ' ') }} FCFA
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Livraison --}}
            <div class="info-section">
                <h6><i class="fas fa-truck mr-1"></i>Livraison</h6>
                <div class="info-row"><span>Mode</span><span>{{ $orders->mode_livraison ?? '—' }}</span></div>
                @if($orders->delivery_name)
                <div class="info-row"><span>Zone</span><span>{{ $orders->delivery_name }}</span></div>
                @endif
                @if($orders->delivery_price)
                <div class="info-row"><span>Frais livraison</span><span>{{ number_format($orders->delivery_price, 0, '', ' ') }} FCFA</span></div>
                @endif
                @if($orders->address)
                <div class="info-row"><span>Adresse</span><span>{{ $orders->address }}</span></div>
                @endif
                @if($orders->delivery_planned)
                <div class="info-row"><span>Date prévue</span><span>{{ $orders->delivery_planned }}</span></div>
                @endif
                @if($orders->delivery_date)
                <div class="info-row"><span>Date effective</span><span>{{ \Carbon\Carbon::parse($orders->delivery_date)->format('d/m/Y H:i') }}</span></div>
                @endif
            </div>

            @if($orders->raison_annulation_cmd)
            <div class="info-section" style="border-left:4px solid #dc3545">
                <h6 class="text-danger"><i class="fas fa-ban mr-1"></i>Motif d'annulation</h6>
                <p class="mb-0">{{ $orders->raison_annulation_cmd }}</p>
            </div>
            @endif

            @if($orders->note)
            <div class="info-section">
                <h6><i class="fas fa-sticky-note mr-1"></i>Note</h6>
                <p class="mb-0">{{ $orders->note }}</p>
            </div>
            @endif
        </div>

        {{-- ════ COLONNE DROITE ════ --}}
        <div class="col-12 col-lg-4">

            {{-- Client --}}
            <div class="info-section">
                <h6><i class="fas fa-user mr-1"></i>Client</h6>
                <div class="info-row"><span>Nom</span><span>{{ $orders->nom_client }}</span></div>
                <div class="info-row"><span>Téléphone</span><span>{{ $orders->tel_client ?: '—' }}</span></div>
                @if($orders->user?->email)
                <div class="info-row"><span>Email</span><span>{{ $orders->user->email }}</span></div>
                @endif
            </div>

            {{-- Financier --}}
            <div class="info-section">
                <h6><i class="fas fa-calculator mr-1"></i>Récapitulatif financier</h6>
                <div class="total-recap">
                    <div class="line"><span>Sous-total</span><span>{{ number_format($orders->subtotal ?? 0, 0, '', ' ') }} FCFA</span></div>
                    @if(($orders->discount ?? 0) > 0)
                    <div class="line text-warning"><span>Remise</span><span>– {{ number_format($orders->discount, 0, '', ' ') }} %</span></div>
                    @endif
                    @if(($orders->delivery_price ?? 0) > 0)
                    <div class="line"><span>Livraison</span><span>{{ number_format($orders->delivery_price, 0, '', ' ') }} FCFA</span></div>
                    @endif
                    <div class="line final"><span>Total</span><span class="text-dark">{{ number_format($orders->total ?? 0, 0, '', ' ') }} FCFA</span></div>
                    <div class="line acompte"><span>Acompte versé</span><span>{{ number_format($orders->acompte ?? 0, 0, '', ' ') }} FCFA</span></div>
                    <div class="line solde"><span>Solde restant</span><strong>{{ number_format($orders->solde_restant ?? 0, 0, '', ' ') }} FCFA</strong></div>
                </div>
            </div>

            {{-- Ajouter acompte --}}
            @if(($orders->acompte ?? 0) == 0 && !in_array($orders->status, ['annulée','livrée']))
            <div class="info-section" style="border-left:4px solid #28a745">
                <h6 class="text-success"><i class="fas fa-hand-holding-usd mr-1"></i>Enregistrer un acompte</h6>
                <form id="form-acompte">
                    @csrf
                    <div class="input-group">
                        <input type="number" id="montant-acompte" class="form-control"
                            placeholder="Montant (FCFA)" min="1" max="{{ $orders->solde_restant }}" step="any">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success">Valider</button>
                        </div>
                    </div>
                    <small class="text-muted">Solde restant : {{ number_format($orders->solde_restant, 0, '', ' ') }} FCFA</small>
                </form>
            </div>
            @endif

            {{-- Paiement --}}
            <div class="info-section">
                <h6><i class="fas fa-credit-card mr-1"></i>Paiement</h6>
                <div class="info-row">
                    <span>Moyen de paiement</span>
                    <span>{{ $orders->paymentMethod?->nom ?? '—' }}</span>
                </div>
                <div class="info-row"><span>Type commande</span><span>{{ $orders->type_order ?? '—' }}</span></div>
            </div>

            {{-- Statuts rapides --}}
            @if(!in_array($orders->status, ['livrée','annulée']))
            <div class="info-section">
                <h6><i class="fas fa-exchange-alt mr-1"></i>Changer le statut</h6>
                <div class="d-flex flex-column" style="gap:6px">
                    @foreach($statuts as $stKey => $st)
                        @if($stKey === $orders->status)
                        <span class="btn btn-sm btn-success font-weight-bold" style="cursor:default;opacity:1">
                            <i class="fas fa-check-circle mr-1"></i>{{ $st['label'] }}
                        </span>
                        @else
                        <a href="{{ route('order.changeState') }}?cs={{ $stKey }}&id={{ $orders->id }}"
                            class="btn btn-sm btn-outline-{{ ['annulée'=>'danger'][$stKey] ?? 'secondary' }}">
                            {{ $st['label'] }}
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    @include('admin.pages.order.motif_annulation')
</div>
@endsection

@section('script')
<script>
@if(($orders->solde_restant ?? 0) > 0 && !in_array($orders->status, ['annulée','livrée']))
document.getElementById('form-acompte')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const montant = document.getElementById('montant-acompte').value;
    if (!montant || montant <= 0) return;
    fetch('{{ route('pos.addAcompte', $orders->id) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ montant })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) Swal.fire({ icon: 'error', title: 'Erreur', text: data.error });
        else Swal.fire({ icon: 'success', title: 'Acompte enregistré !', timer: 1500, showConfirmButton: false }).then(() => location.reload());
    });
});
@endif

$('.motif_autre').hide();
$('#motif_annulation').hide();
$('.btnCancel').click(function(e) {
    e.preventDefault();
    $('#commandeId').val($(this).attr('data-id'));
    $('html,body').animate({ scrollTop: $("#motif_annulation").offset().top - 90 }, 500);
    $('#motif_annulation').show();
    $('#motif_selected').change(function() {
        if ($(this).val() === 'autre') { $('.motif_autre').show(); $('#_motif_autre').prop('required', true); }
        else { $('.motif_autre').hide(); $('#_motif_autre').prop('required', false); }
    });
});
$('.btn-close').click(function(e) {
    e.preventDefault();
    $('#motif_selected').val(''); $('#_motif_autre').val('');
    $('#motif_annulation').hide(); $('.motif_autre').hide();
});
</script>
@endsection
