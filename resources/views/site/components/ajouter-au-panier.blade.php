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
                
                // Mettre à jour tous les badges du panier (header et menu mobile)
                $('.badge').html(response.totalQte);
                
                // Afficher une alerte Bootstrap
                var alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show position-fixed" 
                         style="top: 80px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" 
                         role="alert">
                        <strong><i class="fas fa-check-circle"></i> Succès !</strong> Produit ajouté au panier avec succès.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                $('body').append(alertHtml);
                
                // Disparaît automatiquement après 3 secondes
                setTimeout(function() {
                    $('.alert-success').fadeOut(400, function() {
                        $(this).remove();
                    });
                }, 2000);
            }
        });
    });
</script>
