<style>
    .card {
        overflow: hidden;
        position: relative;
        text-align: left;
        border-radius: 0.5rem;
        max-width: 400px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        background-color: #fff;
    }

    .dismiss {
        position: absolute;
        right: 10px;
        top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        /* padding: 0.5rem 1rem; */
        background-color: #fff;
        color: black;
        border: 2px solid #f88808;
        font-size: 1rem;
        font-weight: 300;
        width: 15px;
        height: 15px;
        border-radius: 7px;
        transition: .3s ease;
    }

    .dismiss:hover {
        background-color: #f85d05;
        border: 2px solid #f85d05;
        color: #fff;
    }

    .header {
        padding: 1.25rem 1rem 1rem 1rem;
    }

    .image {
        display: flex;
        margin-left: auto;
        margin-right: auto;
        background-color: #e2feee;
        flex-shrink: 0;
        justify-content: center;
        align-items: center;
        width: 3rem;
        height: 3rem;
        border-radius: 9999px;
        animation: animate .6s linear alternate-reverse infinite;
        transition: .6s ease;
    }

    .image svg {
        color: #f85d05;
        width: 2rem;
        height: 2rem;
    }

    .content {
        margin-top: 0.75rem;
        text-align: center;
    }

    .title {
        color: #f85d05;
        font-size: 1.5rem;
        font-weight: 600;
        line-height: 1.5rem;
        text-align: center
    }

    .message {
        margin-top: 0.5rem;
        color: #595b5f;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }

    .actions {
        margin: 0.75rem 1rem;
    }

    .history {
        display: inline-flex;
        padding: 0.5rem 1rem;
        background-color: #f85d05;
        color: #ffffff;
        font-size: 1rem;
        line-height: 1.5rem;
        font-weight: 500;
        justify-content: center;
        width: 100%;
        border-radius: 0.375rem;
        border: none;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .track {
        display: inline-flex;
        margin-top: 0.75rem;
        padding: 0.5rem 1rem;
        color: #242525;
        font-size: 1rem;
        line-height: 1.5rem;
        font-weight: 500;
        justify-content: center;
        width: 100%;
        border-radius: 0.375rem;
        border: 1px solid #D1D5DB;
        background-color: #fff;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    @keyframes animate {
        from {
            transform: scale(1);
        }

        to {
            transform: scale(1.09);
        }
    }
</style>



<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-6 col-xs-6 m-auto">
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="staticBackdropLabel">

                            </h5>
                            <button type="button" class="btn-close dismiss p-2" data-bs-dismiss="modal" aria-label="Close">
                                x</button>
                        </div>
                        <div class="modal-body border-top-0">
                            <div class="image mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                                    <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="1.5"
                                            stroke="#000000" d="M20 7L9.00004 18L3.99994 13"></path>
                                    </g>
                                </svg>
                            </div>
                            <span class="title text-justify"> Crée ton compte et bénéficie de la livraison gratuite sur tes prochaines
                                commandes
                            </span>
                        </div>
                        <div class="modal-footer border-0">
                            <a href="{{ route('register-form') }}"
                                style="background-color:#f85d05; border-color:#f85d05" role="button"
                                class="btn btn-secondary">Je m'inscris</a>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">J'ai déjà un
                                compte</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#staticBackdrop').modal('show');
            $("#staticBackdrop").css("z-index", "1500");
        }, 1000);

    });
</script>