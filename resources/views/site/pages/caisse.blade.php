@extends('site.layouts.app')

@section('title', 'Finaliser ma commande')

@section('content')
    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Caisse</h1>
                <ul class="breadcumb-menu">
                    <li><a href="{{ route('liste-produit') }}">Liste des plats</a></li>
                    <li><a href="i{{ route('page-acceuil') }}">Accueil</a></li>
                    <li>Resumé de ma commande</li>
                </ul>
            </div>
        </div>
    </div>
    @include('site.sections.pub.infos')


    @include('admin.components.validationMessage')

    <!-- ========== Start panier ========== -->
    <div class="th-cart-wrapper mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="woocommerce-notices-wrapper">
                        <div class="woocommerce-message">Panier <span
                                class="quantityProduct">({{ count((array) session('cart')) }})</span></div>
                    </div>
                    @php $sousTotal = 0 @endphp

                    @if (session('cart'))
                        <form action="#" class="woocommerce-cart-form">
                            <table class="cart_table">
                                <thead>
                                    <tr>
                                        <th class="cart-col-image">Produit</th>
                                        <th class="cart-col-price">Prix</th>
                                        <th class="cart-col-quantity">Quantité</th>
                                        <th class="cart-col-total">Total</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    @foreach (session('cart') as $id => $details)
                                        @php $sousTotal += $details['price'] * $details['quantity'] /** sous total***/ @endphp

                                        <tr class="cart_item" id="row_{{ $id }}">
                                            <td data-title="Product">
                                                <a class="cart-productimage"
                                                    href="{{ route('detail-produit', $details['slug']) }}"><img
                                                        width="91" height="91" src="{{ $details['image'] }} "
                                                        alt="Image">
                                                    <br> <span>{{ $details['title'] }}</span>
                                                </a>
                                            </td>
                                            <td data-title="Price">
                                                <span class="amount text-dark"><bdi>
                                                        {{ number_format($details['price'], 0, ',', ' ') }}
                                                        <span></span>
                                                    </bdi></span>
                                            </td>
                                            <td data-title="Quantity">
                                                <span> {{ $details['quantity'] }} </span>
                                            </td>
                                            <td data-title="Total">
                                                @php
                                                    $total = $details['price'] * $details['quantity']; // prix article * quantité
                                                @endphp
                                                <span class="amount text-dark fw-bold"><bdi><span
                                                            id="totalPriceQty{{ $id }}">
                                                            {{ number_format($total, 0, ',', ' ') }}
                                                        </span> FCFA</bdi></span>
                                                <!-- ========== Start coupon ========== -->



                                                @if ($product_coupon)
                                                    @foreach ($product_coupon as $item)
                                                        @if (in_array($id, [$item['coupon'][0]['pivot']['product_id']]))
                                                            <div class="d-flex">
                                                                <input type="text" name="coupon"
                                                                    value="{{ $item['coupon'][0]['code'] }}"
                                                                    placeholder="Coupon de réduction" required readonly>
                                                                <button role="button" product-id={{ $id }}
                                                                    data-total={{ $total }}
                                                                    data-sousTotal={{ $sousTotal }}
                                                                    data-pourcentage={{ $item['coupon'][0]['pourcentage_coupon'] }}
                                                                    data-code={{ $item['coupon'][0]['code'] }}
                                                                    class="btn btn-dark text-white applyCouponBtn"
                                                                    id="btnApply_{{ $id }}">Appliquer</button>

                                                            </div>
                                                             <small class="text-danger">Profitez de <b>{{ $item['coupon'][0]['pourcentage_coupon'] }} % </b> de réduction sur le total</small>

                                                        @endif
                                                    @endforeach
                                                @endif

                                                <!-- ========== End coupon ========== -->
                                            </td>



                                        </tr>
                                    @endforeach


                                    <tr>
                                        <td colspan="6" class="actions">
                                            {{-- <button type="submit" class="th-btn rounded-2">Update cart</button> --}}
                                            <a href="{{ route('liste-produit') }}" class="th-btn rounded-2 w-100">Continuer
                                                les achats</a>
                                            {{-- <div class="th-cart-coupon">
                                                <input type="text" class="form-control" placeholder="Coupon Code...">
                                                <button type="submit" class="th-btn rounded-2">Appliquer</button>
                                            </div> --}}

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    @else
                        <p>Vous n'avez pas de produits dans votre panier</p>
                        <a href="{{ route('liste-produit') }}" class="th-btn rounded-2">Commandez maintenant</a>

                    @endif
                </div>

                @if (session('cart'))
                    <div class="col-md-4">
                        <div class="row">
                            <div class="">
                                <h2 class="h4 summary-title">Resumé du panier</h2>

                                <div class="mb-3">
                                    <h6>Informations du client</h6>
                                    <span>Nom : <b>{{ Auth::user()->name }}</b> </span>
                                    <br> <span>Telephone : <b>{{ Auth::user()->phone }}</b> </span>
                                    <br><span class="{{ Auth::user()->email ?? 'd-none ' }}">Email :
                                        <b> {{ Auth::user()->email }} </b></span>
                                </div>
                                <table class="cart_totals" cellspacing="0">
                                    <tbody>

                                        <tr>
                                            <td>Sous total</td>
                                            <td data-title="Total">
                                                <span data-subTotal="{{ $sousTotal }}" class="amount sousTotal">
                                                    <h6 class="text-danger">{{ number_format($sousTotal, 0, ',', ' ') }}
                                                        FCFA</h6>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="_delivery_price">
                                            <td>Livraison
                                                <span class=" text-danger delivery_name"></span>
                                            </td>
                                            <td data-title="Total">
                                                <span class="amount delivery_price">
                                                    <h6 class="text-danger delivery_price">0 FCFA</h6>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="shipping">
                                            {{-- <th>Lieu de livraison</th> --}}
                                            <td colspan="2" data-title="Shipping and Handling">
                                                <form action="#" method="post">
                                                    <!-- ========== Start mode de livraison ========== -->
                                                    {{-- <a href="#" class="shipping-calculator-button">Choisir un mode de
                                                        livraison</a> --}}
                                                    <div class="">
                                                        <select class="form-control delivery_mode">
                                                            <option disabled selected value> Choisir un mode de
                                                                livraison</option>
                                                            {{-- <option value="Livraison à domicile" tag='domicile'>Livraison à
                                                                domicile
                                                            </option> --}}
                                                            <option value="Livraison Yango Moto" tag='yango'>Livraison
                                                                Yango Moto
                                                            </option>
                                                            <option value="Je passe récupérer" tag='recuperer'>Je passe
                                                                récupérer
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <!-- ========== End mode de livraison ========== -->



                                                    <!-- ========== Start lieu et prix de livraison ========== -->
                                                    {{-- <a href="#" class="shipping-calculator-button">Choisir un lieu de
                                                        livraison</a> --}}
                                                    <div class="mt-3">
                                                        <select class="form-control delivery">
                                                            <option disabled selected value> Choisir un lieu de
                                                                livraison</option>
                                                            @foreach ($delivery as $item)
                                                                <option value="{{ $item['id'] }}">
                                                                    {{ $item['zone'] }} ({{ $item['tarif'] }}) FCFA
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- ========== End lieu et prix de livraison ========== -->


                                                    <!-- ========== Start preciser address ========== -->
                                                    <div class="my-3">
                                                        <input type="text" name="address" id="address"
                                                            class="form-control border border-danger"
                                                            placeholder="preciser le lieu" required>
                                                    </div>

                                                    <div class="my-3">
                                                        <input type="text" name="address_yango" id="address_yango"
                                                            class="form-control border border-danger"
                                                            placeholder="preciser la rue destination" required>
                                                    </div>
                                                    <!-- ========== End preciser address  ========== -->



                                                    <!-- ========== Start note du client ========== -->
                                                    <div class="my-3">
                                                        <textarea name="note" id="note" class="border border-danger" cols="30" rows="10"
                                                            placeholder="Ecrivez un commentaire pour votre commande (Ex: Ne pas mettre de sel) "></textarea>
                                                    </div>
                                                    <!-- ========== End note du client ========== -->


                                                    <!-- ========== Start check type commande ========== -->
                                                    <div class="form-check">
                                                        <input class="form-check-input typeCmd" type="radio"
                                                            name="type_commande" value="cmd_normal"
                                                            id="flexRadioDefault1" checked>
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Commande normal <small class="text-danger"
                                                                style="font-size: 10px">(Commande instantanée)</small>
                                                        </label>
                                                    </div>
                                                    <div class="form-check py-3">
                                                        <input class="form-check-input typeCmd" type="radio"
                                                            name="type_commande" value="cmd_precommande"
                                                            id="flexRadioDefault2">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            Précommande <small class="text-danger"
                                                                style="font-size: 10px">(Vous pouvez choisir une date
                                                                ulterieure pour recevoir la commande)</small>
                                                        </label>
                                                    </div>
                                                    <!-- ========== End check type commande ========== -->



                                                    <!-- ========== Start date precommande ========== -->
                                                    <div class="my-3">
                                                        <input type="text" class="datetimepicker"
                                                            name="date_precommande" id="date_precommande"
                                                            placeholder="Choisir une date et heure de livraison" readonly>
                                                    </div>

                                                    <!-- ========== End date precommande ========== -->

                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="order-total">

                                            <td>
                                                <h6>TOTAL TTC</h6>
                                            </td>
                                            <td data-title="Total">
                                                <strong><span class="amount">
                                                        <h6 class="text-danger total_order"> </h6>
                                                    </span>

                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date & Heure de livraison : </td>
                                            <td>
                                                <span id="date_livraison"> </span>

                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="wc-proceed-to-checkout mb-30">
                                    @auth

                                        <button href="#" class="th-btn rounded-2 confirmOrder w-100">Confirmer la
                                            commande

                                        </button>

                                        <button disabled class="btn btn-dark fw-bold btn_loading">Un instant ! commande en cour
                                            de traitement
                                            <div class="spinner-grow text-white " role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </button>
                                    @endauth


                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
    <!-- ========== End panier ========== -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" crossorigin="anonymous"></script>


    <script>
        //variable global
        var prix_livraison = 0
        var lieu_livraison = ''
        var type_cmd = 'cmd_normal'
        var dlp = '' // date livraison precommande



        //stocker le sous total en storage
        var sousTotal = $('.sousTotal').attr('data-subTotal');
        localStorage.setItem('sousTotal', sousTotal)


        //gestion de coupon de reduction

        $('.applyCouponBtn').click(function(e) {
            e.preventDefault();

            var isNewSousTotal = localStorage.getItem('sousTotal');

            var productId = $(this).attr('product-id');
            var total = $(this).attr('data-total'); // total article * qté
            var sous_total = $('.sousTotal').attr('data-subTotal');
            var pourcentage = $(this).attr('data-pourcentage');
            var code_coupon = $(this).attr('data-code');


            //calcul de montant coupon ---apres reduction
            var montant_reduction = parseFloat(total) * parseFloat(pourcentage / 100);
            var new_total = parseFloat(total) - parseFloat(montant_reduction) // total prix article * Qte
            $('#totalPriceQty' + productId).html(parseInt(new_total).toLocaleString())

            //disabled btn apply
            $('#btnApply_' + productId).prop('disabled', true);

            // recalculer le sous total
            var newSousTotal = isNewSousTotal - montant_reduction
            localStorage.setItem('sousTotal', newSousTotal);

            $('.sousTotal').html('<h6 class="text-danger">' + parseInt(newSousTotal).toLocaleString() +
                ' FCFA</h6>');

            $('.total_order').html(parseInt(newSousTotal).toLocaleString() + ' FCFA');




            var data = {
                productId,
            }

            // $.ajax({
            //     type: "GET",
            //     url: "/refresh-coupon/" + productId,
            //     data: {
            //         data
            //     },
            //     dataType: "json",
            //     success: function(response) {
            //         console.log(typeof(response.new_sousTotal), response);
            //         $('#totalPriceQty' + productId).html(response.new_total);
            //         $('.sousTotal').html(+response.new_sousTotal + ' FCFA');
            //     }
            // });


        });




        // mettre à jour la livraison
        $('.delivery').change(function(e) {
            e.preventDefault();
            var deliveryId = $('.delivery option:selected').val();
            // var subTotal = $('.sousTotal').attr('data-subTotal');
            var subTotal = localStorage.getItem('sousTotal');


            //Afficher le champs address si le lieu de livraison est choisi"
            $('#address').show(300)

            $.ajax({
                type: "GET",
                url: "/refresh-shipping/" + deliveryId,
                data: {
                    sub_total: subTotal
                },
                dataType: "json",
                success: function(response) {
                    $('.delivery_price').html(response.delivery_price + 'FCFA');
                    $('.delivery_name').html('(' + response.delivery_name + ')');
                    $('.total_order').html(response.total_price + 'FCFA');

                    prix_livraison = response.delivery_price
                    lieu_livraison = response.delivery_name

                }
            });


        });


        //Recuperer les information  de livraison
        //cacher le champs address par defaut
        $('#address').hide()
        $('#address_yango').hide()
        $('.delivery').hide()


        //afficher le champs lieu de livraison si mode est domicile
        $('.delivery_mode').change(function(e) {
            e.preventDefault();
            var mode_livraison = $('.delivery_mode option:selected').attr('tag');
            var subTotal = localStorage.getItem('sousTotal');

            //si mode_livraison == domicile
            if (mode_livraison == 'domicile') {
                $('.delivery').show(200)
                $('.delivery').val('')
                $('._delivery_price').show()

            } else {
                $('.delivery').hide(100)
            }

            //si mode_livraison == yango
            if (mode_livraison == 'yango') {
                $('#address_yango').show(200)
                $('.delivery_price').html('les frais sont votre charge')
                $('.total_order').html(parseInt(subTotal).toLocaleString() + ' FCFA');
                $('.delivery_name').html('')
                $('._delivery_price').show()

                $('#address').hide()

                //initialisation 

                prix_livraison = 0
                lieu_livraison = ''


            } else {
                $('#address_yango').hide(100)
                $('.delivery_price').html('')
            }

            //si mode_livraison == recuperer
            var subTotal = localStorage.getItem('sousTotal');;
            if (mode_livraison == 'recuperer') {
                $('._delivery_price').hide()
                $('#address').hide()
                $('#address_yango').hide()
                $('.delivery').hide()
                $('.total_order').html(parseInt(subTotal).toLocaleString() + ' FCFA');

                //initialisation 

                prix_livraison = 0
                lieu_livraison = ''

            }

        });

        //on met les champs non requis par defaut
        $('#address_yango').prop('required', false)
        $('#address').prop('required', false)


        //Enregistrer les informations de la commande 
        // $('.spinner-grow').hide();

        $('.btn_loading').hide();
        $('.confirmOrder').click(function(e) {
            e.preventDefault()

            var deliveryId = $('.delivery').val(); // ID du lieu de livraison choisi
            var address_yango = $('#address_yango').val() // adresse de destinantion pour mode yango
            var address = $('#address').val() // precision du lieu exact de livraison 
            var delivery_mode = $('.delivery_mode').val() // mode de la livraison
            var note = $('#note').val() //commentaire sur le produit du user



            //si le lieu de livraison n'est pas choisi , on affiche un message d'alerte
            if (!delivery_mode) {
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    width: '100%',
                    title: 'Veuillez choisir un mode de livraison',
                    animation: true,
                    position: 'top',
                    background: '#eb0029',
                    iconColor: '#fff',
                    color: '#fff',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                });
            } else if ($('.delivery').is(':visible') && !deliveryId) {
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    width: '100%',
                    title: 'Veuillez choisir un lieu de livraison',
                    animation: true,
                    position: 'top-right',
                    background: '#eb0029',
                    iconColor: '#fff',
                    color: '#fff',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                });
            } else if ($('#address').is(':visible') && !address) {
                $('#address').prop('required', true)

                Swal.fire({
                    toast: true,
                    icon: 'error',
                    width: '100%',
                    title: 'Veuillez preciser le lieu exact de la livraison',
                    animation: true,
                    position: 'top-right',
                    background: '#eb0029',
                    iconColor: '#fff',
                    color: '#fff',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                });
            } else if ($('#address_yango').is(':visible') && !address_yango) {
                $('#address_yango').prop('required', true)

                Swal.fire({
                    toast: true,
                    icon: 'error',
                    width: '100%',
                    title: 'Veuillez preciser le lieu de destination',
                    animation: true,
                    position: 'top-right',
                    background: '#eb0029',
                    iconColor: '#fff',
                    color: '#fff',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                });
            } else {
                //send data to back
                $('.btn_loading').show();
                $('.confirmOrder').hide();


                var subTotal = localStorage.getItem('sousTotal');

                var total_order = parseFloat(subTotal) + parseFloat(prix_livraison)
                var type_commande = type_cmd // type de la commande ...precommande , normal
                var delivery_planned = $('#date_livraison').html(); // date de livraison prevue

                var data = {
                    subTotal,
                    address,
                    address_yango,
                    prix_livraison,
                    lieu_livraison,
                    delivery_mode,
                    total_order,
                    note,
                    type_commande,
                    delivery_planned
                }

                $.ajax({
                    type: "GET",
                    url: "save-order",
                    data: {
                        data
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 200) {
                            $('.btn_loading').hide();

                            let timerInterval
                            Swal.fire({
                                title: 'Enregistrement de la commande',
                                html: 'Veuillez patienter dans <b></b> Secondes.',
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                    const b = Swal.getHtmlContainer().querySelector('b')
                                    timerInterval = setInterval(() => {
                                        b.textContent = Swal.getTimerLeft()
                                    }, 100)
                                },
                                willClose: () => {
                                    clearInterval(timerInterval)
                                }
                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'Commande enregistré avec success',
                                        width: '100%',
                                        animation: false,
                                        position: 'top',
                                        background: '#3da108e0',
                                        iconColor: '#fff',
                                        color: '#fff',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                    });

                                    window.location.href = "{{ route('user-order') }}";
                                }
                            })

                        }
                    }
                });


            }
        });



        $("#date_precommande").hide();

        // type de commande  ...precommande ou commande normatl
        //recuperation des valeurs selectionnée
        $("input[name=type_commande]").on("click", function() {
            var typeCmd = $('input[name="type_commande"]:checked').val();
            type_cmd = typeCmd

            if (typeCmd == 'cmd_precommande') {
                $("#date_precommande").show();
                // $("#date_precommande").val('');

            } else {
                type_cmd = 'cmd_normal'
                $("#date_precommande").hide();
                $("#date_precommande").val('');
                let dt = new Date();
                var hours = dt.getHours();
                var minute = dt.getMinutes();

                var tomorrow = dt + 1
                if (hours >= 17 || hours <= 9) {
                    dt.setDate(dt.getDate() + 1);
                    $('#date_livraison').html('Livraison prévu le ' + dt.toLocaleDateString() + ' à 10h30m');
                    dlp = dt.toLocaleDateString()
                } else if (hours >= 8) {
                    dt.setMinutes(dt.getMinutes() + 90);
                    $('#date_livraison').html(dt.toLocaleString("fr-FR"))
                    dlp = dt.toLocaleDateString()

                }
            }

        })



        //datepicker


        $(".datetimepicker").each(function() {

            let dt = new Date();
            $(this).datetimepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy-mm-dd',

                minDate: dt.setDate(dt.getDate() + 1),
                //   maxDate : +3,
                // minTime: '10:00',
                // maxTime: '18:00',
                allowTimes: [

                    '10:30', '12:00', '13:30', '15:00', '16:30', '17:30',
                ],

            });

        });

        //get date_precommande value
        //on verifie s'il n'est pas encore 18h

        let dt = new Date();
        var hours = dt.getHours();
        var minute = dt.getMinutes();

        var tomorrow = dt + 1
        if (hours >= 17 || hours <= 9) {
            dt.setDate(dt.getDate() + 1);
            $('#date_livraison').html('Livraison prévu le ' + dt.toLocaleDateString() + ' à 10h30m');
            dlp = dt.toLocaleDateString()

        } else if (hours >= 8) {
            dt.setMinutes(dt.getMinutes() + 90);
            $('#date_livraison').html(dt.toLocaleString("fr-FR"))
            dlp = dt.toLocaleDateString()

        }




        $('#date_precommande').change(function(e) {
            e.preventDefault();
            var date_precommande = $(this).val();
            // var newDateTime = moment(date_precommande, "YYYY/MM/DD hh:mm:ss")
            //     .add(10, 'minutes')
            //     .format('YYYY-MM-DD hh:mm:ss');

            let dt = new Date(date_precommande);

            var hours = dt.getHours();
            var minute = dt.getMinutes();
            var days = dt.getDate();
            var month = dt.getMonth();
            var fullYear = dt.getFullYear();

            if (hours > 17 || hours <= 9) {
                // dt.setMinutes(dt.getMinutes() + 1080);     
                $('#date_livraison').html('Livraison prévu le ' + dt.toLocaleDateString() +
                    ' à 10h30m');
            } else if (hours >= 8) {
                // dt.setMinutes(dt.getMinutes() + 90);
                $('#date_livraison').html(dt.toLocaleString("fr-FR"))
            }

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>


@endsection
