<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts">


<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
    <div class="card">
        <div class="card-header">
            <h4>Produits les plus commandés</h4>
            <!-- two date for get product between date-->
        </div>

        <div class="row m-auto">
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
        </div>
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
        $.ajax({
            url: "{{ route('dashboard.product-statistic') }}",
            method: 'GET',
            success: function(data) {
              //map
              // console.log(data.top_product_order.map(item => item.title));

                var options = {
                    series: [{
                      name: 'Total Orders',
                      data: data.top_product_order.map(item => item.orders_count)
                    }],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            borderRadiusApplication: 'end',
                            horizontal: true,
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                      categories: data.top_product_order.map(item => item.title)
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            }
        });
    });
</script>
