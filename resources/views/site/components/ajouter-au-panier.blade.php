<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css
" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $('.addCart').click(function(e) {
        e.preventDefault();

        var getId = e.target.dataset.id;
        $.ajax({
            type: "GET",
            url: "/add-to-cart/" + getId,
            data: {
                getId,
            },
            dataType: "json",
            success: function(response) {
                console.log(response);
                $('.badge').html(response.totalQte)
                //   $('.pro-quantity').html(response.qte)
                //   $('.cart-price').html(response.price)
                //   $('.get-total').html(response.total)
                //   $('.img-cart').html('<img  src="'+response.image+ '">')
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: 'Produit ajouté au panier avec succès',
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

            }
        });



    });
</script>
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js
"></script>
