@extends('admin.layouts.app')
@section('title', 'Sorties de Stock')
@section('sub-title', 'Nouvelle sortie de stock')

@section('content')
    <section class="section">

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informations de la sortie</h4>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        <strong>Erreurs de validation :</strong>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('sortie-stock.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date_sortie">Date de sortie <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="date_sortie"
                                                class="form-control @error('date_sortie') is-invalid @enderror"
                                                value="{{ old('date_sortie', date('Y-m-d')) }}" required>
                                            @error('date_sortie')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="product_base_id">Produit de base <span
                                                    class="text-danger">*</span></label>
                                            <select name="product_base_id"
                                                class="form-control @error('product_base_id') is-invalid @enderror"
                                                id="product_base_id" required>
                                                <option value="">-- Sélectionner un produit --</option>
                                                @foreach ($productBases as $pb)
                                                    <option value="{{ $pb->id }}" data-stock="{{ $pb->stock }}"
                                                        data-unite="{{ $pb->unite }}"
                                                        {{ old('product_base_id') == $pb->id ? 'selected' : '' }}>
                                                        {{ $pb->nom }} (Stock: {{ $pb->stock }}
                                                        {{ $pb->unite }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('product_base_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!--motif de sortie-->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="motif">Motif de sortie <span class="text-danger">*</span></label>
                                            <select name="motif" id="motif"
                                                class="form-control @error('motif') is-invalid @enderror" required>
                                                <option value="">-- Sélectionner un motif --</option>
                                                @foreach ($motifs as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ old('motif') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('motif')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantite">Quantité a sortir <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-danger" id="decreaseQte"
                                                        title="Diminuer">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="number" name="quantite"
                                                    class="form-control @error('quantite') is-invalid @enderror"
                                                    id="quantite" step="0.5" min="0.5"
                                                    value="{{ old('quantite', '0.5') }}" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-success" id="increaseQte"
                                                        title="Augmenter">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted" id="stockInfo"></small>
                                            @error('quantite')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Enregistrer
                                    </button>
                                    <a href="{{ route('sortie-stock.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                </div>
                            </form>
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
            var stockDisponible = 0;

            // Afficher le stock disponible et définir max
            $('#product_base_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var stock = selectedOption.data('stock');
                var unite = selectedOption.data('unite');

                if (stock !== undefined) {
                    stockDisponible = parseFloat(stock) || 0;
                    $('#quantite').attr('max', stockDisponible);
                    $('#stockInfo').text('Stock disponible: ' + stock + ' ' + unite);

                    // Réinitialiser la quantité si elle dépasse le stock
                    var currentQte = parseFloat($('#quantite').val()) || 0;
                    if (currentQte > stockDisponible) {
                        $('#quantite').val(Math.min(stockDisponible, 0.5).toFixed(2));
                    }

                    validateQuantite();
                } else {
                    stockDisponible = 0;
                    $('#quantite').removeAttr('max');
                    $('#stockInfo').text('');
                }
            });

            // Vérifier si la valeur est un multiple de 0.5
            function isMultipleOfHalf(value) {
                return (value * 2) % 1 === 0;
            }

            // Validation de la quantité
            function validateQuantite() {
                var quantiteInput = $('#quantite');
                var currentValue = parseFloat(quantiteInput.val()) || 0;
                var selectedProduct = $('#product_base_id').val();

                if (!selectedProduct) {
                    quantiteInput.removeClass('is-invalid').removeClass('is-valid');
                    $('#stockInfo').removeClass('text-danger text-success').addClass('text-muted');
                    return true;
                }

                // Vérifier si négatif
                if (currentValue < 0) {
                    quantiteInput.addClass('is-invalid').removeClass('is-valid');
                    $('#stockInfo').removeClass('text-muted text-success').addClass('text-danger');
                    $('#stockInfo').html(
                        '<i class="fas fa-exclamation-triangle"></i> La quantité ne peut pas être négative !');
                    return false;
                }

                // Vérifier si multiple de 0.5
                if (!isMultipleOfHalf(currentValue)) {
                    quantiteInput.addClass('is-invalid').removeClass('is-valid');
                    $('#stockInfo').removeClass('text-muted text-success').addClass('text-danger');
                    $('#stockInfo').html(
                        '<i class="fas fa-exclamation-triangle"></i> Uniquement les multiples de 0.5 sont acceptés (ex: 0.5, 1, 1.5, 2, 2.5...)'
                    );
                    return false;
                }

                // Vérifier si dépasse le stock
                if (currentValue > stockDisponible) {
                    quantiteInput.addClass('is-invalid').removeClass('is-valid');
                    $('#stockInfo').removeClass('text-muted text-success').addClass('text-danger');
                    $('#stockInfo').html(
                        '<i class="fas fa-exclamation-triangle"></i> Quantité supérieure au stock disponible !');
                    return false;
                } else if (currentValue > 0) {
                    quantiteInput.addClass('is-valid').removeClass('is-invalid');
                    $('#stockInfo').removeClass('text-danger text-muted').addClass('text-success');
                    var unite = $('#product_base_id').find('option:selected').data('unite') || '';
                    $('#stockInfo').html('<i class="fas fa-check-circle"></i> Stock disponible: ' +
                        stockDisponible + ' ' + unite);
                    return true;
                } else {
                    quantiteInput.removeClass('is-invalid is-valid');
                    $('#stockInfo').removeClass('text-danger text-success').addClass('text-muted');
                    return true;
                }
            }

            // Validation en temps réel
            $('#quantite').on('input change', function() {
                validateQuantite();
            });

            // Bouton augmenter la quantité
            $('#increaseQte').on('click', function() {
                var quantiteInput = $('#quantite');
                var currentValue = parseFloat(quantiteInput.val()) || 0;
                var step = parseFloat(quantiteInput.attr('step')) || 1;
                var newValue = currentValue + step;

                if (stockDisponible > 0 && newValue > stockDisponible) {
                    quantiteInput.val(stockDisponible.toFixed(2));
                } else {
                    quantiteInput.val(newValue.toFixed(2));
                }

                validateQuantite();
            });

            // Bouton diminuer la quantité
            $('#decreaseQte').on('click', function() {
                var quantiteInput = $('#quantite');
                var currentValue = parseFloat(quantiteInput.val()) || 0;
                var step = parseFloat(quantiteInput.attr('step')) || 1;
                var minValue = parseFloat(quantiteInput.attr('min')) || 0.01;
                var newValue = currentValue - step;

                if (newValue >= minValue) {
                    quantiteInput.val(newValue.toFixed(2));
                }

                validateQuantite();
            });

            // Validation du formulaire avant soumission
            $('form').on('submit', function(e) {
                var currentValue = parseFloat($('#quantite').val()) || 0;

                if (currentValue < 0) {
                    e.preventDefault();
                    alert('La quantité ne peut pas être négative !');
                    return false;
                }

                if (!isMultipleOfHalf(currentValue)) {
                    e.preventDefault();
                    alert(
                        'Veuillez saisir uniquement des multiples de 0.5 (ex: 0.5, 1, 1.5, 2, 2.5, 3, 3.5...)'
                        );
                    return false;
                }

                if (!validateQuantite()) {
                    e.preventDefault();
                    alert('La quantité à sortir ne peut pas dépasser le stock disponible !');
                    return false;
                }
            });

            // Déclencher la validation initiale si un produit est déjà sélectionné
            if ($('#product_base_id').val()) {
                $('#product_base_id').trigger('change');
            }
        });
    </script>
@endpush
