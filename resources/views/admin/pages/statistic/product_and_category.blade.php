<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts">


<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
    <div class="card">
        <div class="card-header">
            <h4>Produits les plus commandés</h4>
            <!-- two date for get product between date-->
        </div>
        <form id="dateRangeForm" class="m-auto">
            <label for="start_date">Debut:</label>
            <input type="date" id="start_date" name="start_date">
            <label for="end_date">Fin:</label>
            <input type="date" id="end_date" name="end_date">
            <button type="submit" class="bg-primary text-white border-0">Obtenir les données</button>
        </form>

        {{-- <div class="row m-auto">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date">Date debut</label>
                    <input type="date" name="date_debut" id="dateStart" value="{{ request('date_debut') }}"
                        class="form-control" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="date">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" id="dateEnd"
                        class="form-control" required>
                </div>
            </div>
        </div> --}}
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
                            xaxis: {

                                categories: data.top_product_order.map(item => item
                                    .title)
                            }
                        };

                        var chart = new ApexCharts(document.querySelector("#chart"),
                            options);
                        chart.render();
                    }else{
                      $('#chart').html('<div class="alert alert-info" role="alert">Aucune données n\'a été trouvé pour les dates choisis</div>');
                       
                    }
                }
            });
        });
    });
</script>
