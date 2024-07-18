<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts">


<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
    <div class="card">
        <div class="card-header">
            <h4>Commandes par mois</h4>
            <!-- two date for get product between date-->
            <form id="dateRangeForm" class="m-auto">
                <select name="year" id="year"></select>
                {{-- <button type="submit" class="bg-primary text-white border-0">Obtenir les données</button> --}}
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


<script>
    $(document).ready(function() {
        // without rangetime
        //get years
        function getYears(startYear, endYear) {
            let years = [];
            for (let year = startYear; year <= endYear; year++) {
                years.push(year);
            }
            return years;
        }

        let currentYear = new Date().getFullYear();
        let yearsList = getYears(currentYear - 1, currentYear);
        //put in my select option
        $('#year').append('<option disabled selected value>' + 'Choisir une année' + '</option>');

        $.each(yearsList, function(index, value) {
            $('#year').append('<option value="' + value + '">' + value + '</option>');
        });



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
        $('#year').change(function(e) {
            e.preventDefault();
            var year = $(this).val();

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
</script>
