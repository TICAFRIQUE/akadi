@if ($information)
    <div style="background-color: rgb(240, 74, 74)" class="" class="">
        <span class="text"> {!! $information['texte'] !!} </span>
    </div>
@endif


<script>
  function blinker() {
  $('.text').fadeOut(500);
  $('.text').fadeIn(500);
}

setInterval(blinker, 8000);
</script>