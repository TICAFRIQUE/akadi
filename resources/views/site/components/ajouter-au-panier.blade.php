<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $('.addCart').click(function(e) {
        e.preventDefault();

        var getId = $(this).data('id');
        $.ajax({
            type: "GET",
            url: "/add-to-cart/" + getId,
            data: {
                getId,
            },
            dataType: "json",
            success: function(response) {
                console.log(response);
                
                // Mettre à jour tous les badges du panier
                $('.badge').html(response.totalQte);
                // Afficher badge desktop si caché
                if (response.totalQte > 0) {
                    $('.ak-cart-badge.badge').css('display', 'flex');
                }

                // Toast succès
                var alertHtml = `
                    <div style="position:fixed;top:80px;right:20px;z-index:9999;background:#3da108;color:#fff;padding:14px 20px;border-radius:10px;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 6px 20px rgba(0,0,0,.15);animation:slideInRight .3s ease;">
                        <i class="fas fa-check-circle"></i> Plat ajouté au panier !
                    </div>
                `;
                $('body').append(alertHtml);
                setTimeout(function() {
                    $('body').find('[style*="Plat ajouté"]').fadeOut(300, function() { $(this).remove(); });
                }, 2000);
            }
        });
    });

    // ── Ajouter un plat du menu du jour au panier ────────────────────────────────
    $('.addCartMenu').click(function(e) {
        e.preventDefault();

        var getId = $(this).data('id');
        $.ajax({
            type: "GET",
            url: "/add-menu-to-cart/" + getId,
            dataType: "json",
            success: function(response) {
                $('.badge').html(response.totalQte);
                if (response.totalQte > 0) {
                    $('.ak-cart-badge.badge').css('display', 'flex');
                }

                var alertHtml = `
                    <div style="position:fixed;top:80px;right:20px;z-index:9999;background:#3da108;color:#fff;padding:14px 20px;border-radius:10px;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 6px 20px rgba(0,0,0,.15);animation:slideInRight .3s ease;">
                        <i class="fas fa-check-circle"></i> Plat du menu du jour ajouté au panier !
                    </div>
                `;
                $('body').append(alertHtml);
                setTimeout(function() {
                    $('body').find('[style*="menu du jour ajouté"]').fadeOut(300, function() { $(this).remove(); });
                }, 2000);
            }
        });
    });
</script>
