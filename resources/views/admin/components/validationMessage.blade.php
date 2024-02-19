

        @if ($message = Session::get('success'))
            <div class="alert alert-success text-white alert-dismissible show fade msg" style="background-color:rgb(9, 156, 9)">
                <div class="alert-body">
                    {{-- <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button> --}}
                    {{ $message }}
                </div>
            </div>
        @endif


        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible text-white show fade msg" style="background-color: rgb(202, 36, 36)">
                <div class="alert-body">
                    {{-- <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button> --}}
                    {{ $message }}
                </div>
            </div>
        @endif

        {{-- 
      <div class="alert alert-primary alert-dismissible show fade">
        <div class="alert-body">
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          This is a primary alert.
        </div>
      </div>

      <div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          This is a warning alert.
        </div>
      </div>

      <div class="alert alert-info alert-dismissible show fade">
        <div class="alert-body">
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          This is a info alert.
        </div>
      </div>

      <div class="alert alert-light alert-dismissible show fade">
        <div class="alert-body">
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          This is a light alert.
        </div>
      </div>

      <div class="alert alert-dark alert-dismissible show fade">
        <div class="alert-body">
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          This is a dark alert.
        </div>
      </div> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script>
        $(document).ready(function () {
          $('.msg').delay(5000).fadeOut('slow');
          
        });
      </script>

