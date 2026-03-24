<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts">


<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
    <div class="card">
        <div class="card-header">
            <h4>Commandes par mois</h4>
            <!-- two date for get product between date-->
            <form id="dateRangeYear" class="m-auto">
                <select name="year" id="year">
                    <option disabled selected value>Choir une année</option>
                    @for ($i = 2023; $i <= date('Y'); $i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-primary text-white border-0">Obtenir les données</button>
            </form>
        </div>
        

        <div class="card-body">
            <div class="">
                <div id="chart_month"></div>
            </div>
        </div>
    </div>
</div>






<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

{{-- 
<script>
    $(document).ready(function() {
      

        $.ajax({
            url: "{{ route('dashboard.order-period') }}",
            method: 'GET',

            success: function(data) {
                //map

                var options = {
                    series: [{
                        name: 'Nombre de commande',
                        data: data.orders_by_month.map(item => item.count)
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
                            horizontal: false,
                        }
                    },
                    dataLabels: {
                        enabled: true,
                    },
                    xaxis: {

                        categories: data.orders_by_month.map(item => item.month_name + '-' +
                            item
                            .year)

                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart_month"), options);
                chart.render();
            }
        });


        //get data if select year
        $('#dateRangeYear').submit(function(e) {
            e.preventDefault();
            var year = $('#year option:selected').val();
            $.ajax({
                url: "{{ route('dashboard.order-period') }}",
                method: 'GET',
                data: {
                    year: year,
                },
                success: function(data) {
                    //map
                    if (data.orders_by_month.length > 0) {
                        var options = {
                            series: [{
                                name: 'Nombre de commande',
                                data: data.orders_by_month.map(item => item
                                    .count)
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
                                    horizontal: false,
                                }
                            },
                            dataLabels: {
                                enabled: true,
                            },
                            xaxis: {

                                categories: data.orders_by_month.map(item => item
                                    .month_name + '-' +
                                    item
                                    .year)

                            }
                        };

                        var chart = new ApexCharts(document.querySelector("#chart_month"),
                            options);
                        chart.render();

                    } else {
                        $('#chart_month').html(
                            '<div class="alert alert-info" role="alert">Aucune données n\'a été trouvé pour les dates choisis</div>'
                        );

                    }
                }
            });
        });










    });
</script> --}}



<script>
    $(document).ready(function () {

        var chart = null; // 👈 variable globale pour stocker le graphique

        // Options de base du graphique
        function getChartOptions(data) {
            return {
                series: [{
                    name: 'Nombre de commandes',
                    data: data.map(item => item.count)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: true,
                        tools: { download: false }
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        borderRadiusApplication: 'end',
                        horizontal: false,
                    }
                },
                dataLabels: { enabled: true },
                xaxis: {
                    categories: data.map(item => item.month_name + ' ' + item.year)
                },
                noData: {
                    text: 'Aucune donnée disponible',
                    align: 'center',
                    verticalAlign: 'middle',
                }
            };
        }

        // Fonction pour rendre ou mettre à jour le graphique
        function renderChart(data) {
            if (data.orders_by_month.length === 0) {
                $('#chart_month').html(
                    '<div class="alert alert-info">Aucune donnée trouvée pour la période choisie.</div>'
                );
                return;
            }

            if (chart) {
                // 👇 Mettre à jour sans recréer
                chart.updateOptions(getChartOptions(data.orders_by_month));
            } else {
                // 👇 Créer seulement la première fois
                $('#chart_month').html(''); // nettoyer le conteneur
                chart = new ApexCharts(document.querySelector("#chart_month"), getChartOptions(data.orders_by_month));
                chart.render();
            }
        }

        // Chargement initial
        function loadData(year = null) {
            $.ajax({
                url: "{{ route('dashboard.order-period') }}",
                method: 'GET',
                data: year ? { year: year } : {},
                beforeSend: function () {
                    $('#chart_month').html('<div class="text-center py-4"><i class="fa fa-spinner fa-spin"></i> Chargement...</div>');
                    chart = null; // 👈 reset pour recréer proprement après le message
                },
                success: function (data) {
                    renderChart(data);
                },
                error: function () {
                    $('#chart_month').html(
                        '<div class="alert alert-danger">Erreur lors du chargement des données.</div>'
                    );
                }
            });
        }

        // Appel initial sans filtre
        loadData();

        // Soumission du formulaire
        $('#dateRangeYear').submit(function (e) {
            e.preventDefault();
            var year = $('#year').val();

            if (!year) {
                alert('Veuillez choisir une année.');
                return;
            }

            loadData(year);
        });

    });
</script>
