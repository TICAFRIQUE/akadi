<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts">


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
    <div class="card">
        <div class="card-header">
            <h4>Produits les plus commandés sur les 3 derniers mois</h4>
            <!-- two date for get product between date-->
        </div>
        <form id="dateRangeForm" class="m-auto">
            <label for="start_date">Début:</label>
            <input type="date" id="start_date" name="start_date">
            <label for="end_date">Fin:</label>
            <input type="date" id="end_date" name="end_date">
            <button type="submit" class="bg-primary text-white border-0">Obtenir les données</button>
            <button type="button" id="resetDate" class="bg-secondary text-white border-0 ml-2">Réinitialiser</button>
            <a href="{{ route('rapport.vente') }}" target="_blank" class="btn btn-info ml-2">Voir rapport de vente complet</a>
        </form>
        <div class="card-body">
            <div class="">
                <div id="chart"></div>
            </div>
        </div>
    </div>
</div>






<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<script>
    $(document).ready(function() {
        // Bouton reset
        $('#resetDate').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            // Relancer la requête par défaut (3 derniers mois)
            $.ajax({
                url: "{{ route('dashboard.product-statistic') }}",
                method: 'GET',
                success: function(data) {
                    if (data.top_product_order.length > 0) {
                        $('#chart').html('');
                        var options = {
                            series: [{
                                name: 'Nombre de commande',
                                data: data.top_product_order.map(item => item.orders_count)
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                                toolbar: {
                                    show: true,
                                    tools: { download: false }
                                }
                            },
                            plotOptions: { bar: { borderRadius: 4, borderRadiusApplication: 'end', horizontal: true } },
                            dataLabels: { enabled: true },
                            colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#A133FF', '#FF7F33', '#33FFCC', '#FF3333', '#33FF66'],
                            xaxis: { categories: data.top_product_order.map(item => item.title) }
                        };
                        var chart = new ApexCharts(document.querySelector("#chart"), options);
                        chart.render();
                    } else {
                        $('#chart').html('<div class="alert alert-info" role="alert">Aucune données n\'a été trouvé pour les dates choisies</div>');
                    }
                }
            });
        });
        // without rangetime
        $.ajax({
            url: "{{ route('dashboard.product-statistic') }}",
            method: 'GET',

            success: function(data) {
                //map
                // console.log(data.top_product_order.map(item => item.title));

                var options = {
                    series: [{
                        name: 'Nombre de commande',
                        data: data.top_product_order.map(item => item
                            .orders_count)
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: true,
                            tools: {
                                download: false // <== line to add

                            }
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            borderRadiusApplication: 'end',
                            horizontal: true,
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                     colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1',
                                '#A133FF', '#FF7F33', '#33FFCC', '#FF3333',
                               
                            ],
                    xaxis: {

                        categories: data.top_product_order.map(item => item.title)
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            }
        });


        $('#dateRangeForm').submit(function(e) {
            e.preventDefault();
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            $.ajax({
                url: "{{ route('dashboard.product-statistic') }}",
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(data) {
                    //map

                    if (data.top_product_order.length > 0) {
                        $('#chart').html('');
                        var options = {
                            series: [{
                                name: 'Nombre de commande',
                                data: data.top_product_order.map(item => item
                                    .orders_count)
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                                toolbar: {
                                    show: true,
                                    tools: {
                                        download: false // <== line to add

                                    }
                                }
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 4,
                                    borderRadiusApplication: 'end',
                                    horizontal: true,
                                }
                            },
                            dataLabels: {
                                enabled: true
                            },
                            colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1',
                                '#A133FF', '#FF7F33', '#33FFCC', '#FF3333',
                                '#33FF66'
                            ],

                            xaxis: {

                                categories: data.top_product_order.map(item => item
                                    .title)
                            }
                        };

                        var chart = new ApexCharts(document.querySelector("#chart"),
                            options);
                        chart.render();
                    } else {
                        $('#chart').html(
                            '<div class="alert alert-info" role="alert">Aucune données n\'a été trouvé pour les dates choisis</div>'
                            );

                    }
                }
            });
        });
    });
</script>
