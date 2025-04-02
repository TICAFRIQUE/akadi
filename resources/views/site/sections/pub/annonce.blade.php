{{-- @if ($information)
    <div style="background-color: rgb(240, 74, 74)" class="" class="">
        <span class="text"> {!! $information['texte'] !!} </span>
    </div>
@endif --}}


@if ($information)
    <!-- Info Alert -->
    <div id="bande-annonce" class="alert alert-info text-center  align-items-center justify-content-rounded"
        style="display: block; position: fixed; top: 0; width: 100%; z-index: 9999; padding: 10px;">
        <div class="d-flex align-items-center">
            {{-- <i class="fas fa-bullhorn" style="font-size: 24px; margin-right: 10px;"></i> --}}
            <div>
                <strong id="annonce-titre"> <marquee  direction="right"> {!! $information['texte'] !!} </marquee> </strong>
            </div>
        </div>
        <button class="btn btn-sm btn-danger close">×</button>
    </div>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

      // arret de la bande annonce
        // $('#bande-annonce').hide();// hide the element

        function blinker() {
            $('.texte').fadeOut(500);
            $('.texte').fadeIn(500);
        }

        setInterval(blinker, 8000);

        // fermer la bande annonce
        $('.close').click(function() {
          $('#bande-annonce').hide(500);
           
        })
    })
</script>
