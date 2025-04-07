@extends('admin.layouts.app')
@section('title', 'depense')
@section('sub-title', 'Créer une depense')

@section('content')
    <style>
        img {
            max-width: 180px;
        }

        input[type=file] {
            padding: 10px;
            background: #eaeaea;
        }
    </style>

    <section class="section">
        @php
            $msg_validation = 'Champs obligatoire';
        @endphp
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-12 m-auto">
                    @include('admin.components.validationMessage')
                    <div class="card">
                        <form class="row g-3 needs-validation m-3" method="post" action="{{ route('depense.store') }}"
                            novalidate>
                            @csrf

                            <div class="col-md-3">
                                <label for="validationCustom01" class="form-label">Categorie depense</label>
                                <select name="categorie_depense" class="form-control categorie-depense " required>
                                    <option selected value="">Selectionner</option>
                                    @foreach ($categorie_depense as $item)
                                        <!-- Si la catégorie a des libelleDepenses, rendre l'option non cliquable -->
                                        <option value="{{ $item['id'] }}" class="categorie">
                                            {{ strtoupper($item['libelle']) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="validationCustom01" class="form-label">Libellé depense
                                </label>
                                <div class="d-flex">
                                    <select name="libelle" class="form-control libelle-depense">
                                        <option disabled selected value="">Selectionner</option>

                                    </select>
                                    {{-- <button type="button" class="btn btn-primary ml-2 btn-sm btnAddLibelle"
                                        data-toggle="modal" data-target="#myModalAddLibelle"> <i
                                            class="fas fa-plus"></i>
                                    </button> --}}
                                </div>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="validationCustom01" class="form-label">Montant</label>
                                <input type="number" name="montant" class="form-control" id="validationCustom01" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label" for="meta-title-input">Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="currentDate" value="<?php echo date('Y-m-d'); ?>" name="date_depense"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label for="validationCustom01" class="form-label">Objet</label>
                                <textarea class="form-control" name="description" id="" cols="30" rows="10"></textarea>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3">Enregistrer</button>
                        </form>
                    </div>


                    @include('admin.pages.depense.partials.modalAddnewLibelleDepense')


                </div>

            </div>

        </div>

        </div>
        </div>




    </section>


    <script type="text/javascript">
        $(document).ready(function() {
            // let categories = @json($categorie_depense);
            let categories = @json($categorie_depense);

            $('.categorie-depense').on('change', function() {
                let selectedCategorieId = $(this).val();

                // Mettre la valeur dans le champ caché
                $('.CategorieId').val(selectedCategorieId);

                let $libelleDropdown = $('.libelle-depense');
                $libelleDropdown.empty(); // Vider la liste

                let selectedCategorie = categories.find(cat => cat.id == selectedCategorieId);

                if (selectedCategorie && selectedCategorie.libelle_depenses && selectedCategorie
                    .libelle_depenses.length > 0) {
                    // Ajouter les libellés
                    $.each(selectedCategorie.libelle_depenses, function(index, libelle) {
                        let $option = $('<option>', {
                            value: libelle.id,
                            text: libelle.libelle
                        });
                        $libelleDropdown.append($option);
                    });
                } else {
                    // Ajouter une option indiquant l'absence de libellé
                    let message = selectedCategorie ?
                        'Aucun libellé disponible pour cette catégorie' :
                        'Veuillez sélectionner une catégorie valide';

                    $libelleDropdown.append($('<option>', {
                        value: '',
                        text: message
                    }));
                }
            });

            /*
                // Enregistrement du nouveau libellé avec AJAX
                $('#formSave').on('submit', function(e) {
                    e.preventDefault();
        
                    const formData = new FormData(this);
        
                    $.ajax({
                        url: '{{ route('libelle-depense.store') }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.libelle) {
                                let $libelleDropdown = $('.libelle-depense');
                                let $newOption = $('<option>', {
                                    value: response.libelle.id,
                                    text: response.libelle.libelle
                                });
        
                                $libelleDropdown.prepend($newOption);
                                $libelleDropdown.val(response.libelle.id);
                                $('#formSave')[0].reset();
        
                                let myModal = bootstrap.Modal.getInstance($('#myModalAddLibelle')[0]);
                                myModal.hide();
        
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Opération réussie',
                                    text: 'Libellé ajouté avec succès.'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: 'Une erreur est survenue.'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Erreur:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Erreur lors de l\'enregistrement.'
                            });
                        }
                    });
                });
                */
        });
    </script>



@endsection
