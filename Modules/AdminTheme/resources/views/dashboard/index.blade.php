@extends('admintheme::layouts.main')
@section('title', 'Dashboard')
@section('content')
<style>
    canvas {
        width: 95% !important;
        height: 95% !important;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
<div class="card box-primary">
    <div class="row">
        <div class="col-12">
            <div class="card-header">
                <div class="form-group row">
                    <div class="col-md-2 d-flex align-items-center" style="width: max-content">
                        <label for="chart-type">Choose chart:</label>
                    </div>
                    <div class="col-sm-2">
                        <select id="chart-type" class="form-control">
                            <option value="order-overview">Order Overview</option>
                            <option value="sell-history">Sell History</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-center" style="width: max-content; margin-left: auto">
                        <label for="payment-method">Payment Method:</label>
                    </div>
                    <div class="col-sm-2">
                        <select id="payment-method" class="form-control">
                            <option value="all">All</option>
                            <option value="PAYPAL">PayPal</option>
                            <option value="CREDIT_CARD">Stripe</option>
                            <option value="CREDIT_CARD_2">AirWallet</option>
                        </select>
                    </div>
                    <div class="col-sm-1 d-flex align-items-center" style="width: max-content">
                        <label for="date-range">Date Range:</label>
                    </div>
                    <div class="col-sm-2">
                        <select id="date-range" class="form-control">
                            <option value="week">Week</option>
                            <option value="month">Month</option>
                            <option value="year">Year</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="myChart1"></canvas>
                </div>
                <div class="chart">
                    <canvas id="myChart2"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var myChart1 = document.getElementById('myChart1').getContext('2d');
    Chart.defaults.global.defaultFontFamily = 'Lato';
    Chart.defaults.global.defaultFontSize = 18;
    Chart.defaults.global.defaultFontColor = '#777';

    var massPopChart = new Chart(myChart1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: {!! json_encode($chartData['datasets']) !!}
        },
        options: {
            title: {
                display: true,
                text: 'Order Overview',
                fontSize: 25,
                responsive: true
            },
            legend: {
                display: true,
                position: 'right',
                labels: {
                    fontColor: '#000'
                }
            },
            layout: {
                padding: {
                    left: 50,
                    right: 0,
                    bottom: 0,
                    top: 0
                }
            },
            tooltips: {
                enabled: true
            },
            scales: {
                yAxes: [{
                    id: 'y-axis-1',
                    type: 'linear',
                    position: 'left',
                    ticks: {
                        beginAtZero: true
                    }
                }, {
                    id: 'y-axis-2',
                    type: 'linear',
                    position: 'right',
                    ticks: {
                        beginAtZero: true,
                    },
                    gridLines: {
                        drawOnChartArea: false,
                        color: '#0099cc'
                    }
                }]
            }
        }
    });
    var myChart2 = document.getElementById('myChart2').getContext('2d');
    var massPopChart2 = new Chart(myChart2, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Money sold',
                    data: {!! json_encode($chartData2['datasets'][0]['data']) !!},
                    backgroundColor: '#ff9900',
                    borderColor: '#ff9900',
                    borderWidth: 1
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Sell History Overview',
                fontSize: 25,
                responsive: true
            },
            legend: {
                display: true,
                position: 'right',
                labels: {
                    fontColor: '#000'
                }
            },
            layout: {
                padding: {
                    left: 50,
                    right: 0,
                    bottom: 0,
                    top: 0
                }
            },
            tooltips: {
                enabled: true
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    document.getElementById('date-range').addEventListener('change', function () {
        var selectedDateRange = this.value;
        var labels = [];
        var chartData = [];

        switch (selectedDateRange) {
            case 'week':
                labels = {!! json_encode($daysOfWeek) !!};
                break;
            case 'month':
                labels = {!! json_encode($daysOfMonth) !!};
                break;
            case 'year':
                labels = {!! json_encode($daysOfYear) !!};
                break;
        }

        $.ajax({
            type: 'GET',
            url: 'admin/get-chart-data',
            data: { dateRange: selectedDateRange },
            success: function (data) {
                updateCharts(data);
            }
        });
    });
    document.getElementById('payment-method').addEventListener('change', function () {
        var selectedPaymentMethod = this.value;
        var dateRange = document.getElementById('date-range').value;

        $.ajax({
            type: 'GET',
            url: 'admin/get-chart-data',
            data: { dateRange: dateRange, paymentMethod: selectedPaymentMethod === 'all' ? null : selectedPaymentMethod },
            success: function (data) {
                updateCharts(data);
            }
        });
    });
    document.getElementById('chart-type').addEventListener('change', function () {
        var selectedChartType = this.value;
        var dateRange = document.getElementById('date-range').value;
        var paymentMethod = document.getElementById('payment-method').value;

        $.ajax({
            type: 'GET',
            url: 'admin/get-chart-data',
            data: { dateRange: dateRange, paymentMethod: paymentMethod === 'all' ? null : paymentMethod, chartType: selectedChartType },
            success: function (data) {
                updateCharts(data);
            }
        });
    });
    function updateCharts(data) {
        massPopChart.data.labels = data.chartData1.labels;
        massPopChart.data.datasets[0].data = data.chartData1.datasets[0].data;
        massPopChart.data.datasets[1].data = data.chartData1.datasets[1].data;
        massPopChart.update();

        massPopChart2.data.labels = data.chartData2.labels;
        massPopChart2.data.datasets[0].data = data.chartData2.datasets[0].data;
        massPopChart2.update();
    };
    document.getElementById('chart-type').addEventListener('change', function () {
        var selectedChartType = this.value;
        var dateRange = document.getElementById('date-range').value;
        var paymentMethod = document.getElementById('payment-method').value;

        $.ajax({
            type: 'GET',
            url: 'admin/get-chart-data',
            data: { dateRange: dateRange, paymentMethod: paymentMethod === 'all' ? null : paymentMethod, chartType: selectedChartType },
            success: function (data) {
                updateCharts(data);
            }
        });

        if (selectedChartType === 'order-overview') {
            document.getElementById('myChart1').style.display = 'block';
            document.getElementById('myChart2').style.display = 'none';
        } else if (selectedChartType === 'sell-history') {
            document.getElementById('myChart1').style.display = 'none';
            document.getElementById('myChart2').style.display = 'block';
        }
    });

    document.getElementById('myChart2').style.display = 'none';
</script>
@endsection