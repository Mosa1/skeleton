@extends('betterfly::admin.common.layout')

@section('content')
    <main class="main">
        <ol class="breadcrumb">
        </ol>
        <div class="container-fluid">
            <div id="ui-view">
                <div>
                    <div class="animated fadeIn">
                        <div class="card-columns cols-2">
                            <div class="card">
                                <div class="card-header">Doughnut Chart
                                    <div class="card-header-actions">
                                        <a class="card-header-action" href="http://www.chartjs.org" target="_blank">
                                            <small class="text-muted">docs</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-wrapper">
                                        <canvas id="canvas-3"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">Polar Area Chart
                                    <div class="card-header-actions">
                                        <a class="card-header-action" href="http://www.chartjs.org" target="_blank">
                                            <small class="text-muted">docs</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-wrapper">
                                        <canvas id="canvas-6"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">Radar Chart
                                    <div class="card-header-actions">
                                        <a class="card-header-action" href="http://www.chartjs.org" target="_blank">
                                            <small class="text-muted">docs</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-wrapper">
                                        <canvas id="canvas-4"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">Pie Chart
                                    <div class="card-header-actions">
                                        <a class="card-header-action" href="http://www.chartjs.org" target="_blank">
                                            <small class="text-muted">docs</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-wrapper">
                                        <canvas id="canvas-5"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/betterfly/js/Chart.bundle.js') }}"></script>
    <script>
      /* eslint-disable object-curly-newline */

      /* global Chart */

      /**
       * --------------------------------------------------------------------------
       * CoreUI Free Boostrap Admin Template (v2.1.12): main.js
       * Licensed under MIT (https://coreui.io/license)
       * --------------------------------------------------------------------------
       */

      /* eslint-disable no-magic-numbers */
      // random Numbers
      var random = function random() {
        return Math.round(Math.random() * 100);
      }; // eslint-disable-next-line no-unused-vars


      var radarChart = new Chart($('#canvas-4'), {
        type: 'radar',
        data: {
          labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
          datasets: [{
            label: 'My First dataset',
            backgroundColor: 'rgba(220, 220, 220, 0.2)',
            borderColor: 'rgba(220, 220, 220, 1)',
            pointBackgroundColor: 'rgba(220, 220, 220, 1)',
            pointBorderColor: '#fff',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(220, 220, 220, 1)',
            data: [65, 59, 90, 81, 56, 55, 40]
          }, {
            label: 'My Second dataset',
            backgroundColor: 'rgba(151, 187, 205, 0.2)',
            borderColor: 'rgba(151, 187, 205, 1)',
            pointBackgroundColor: 'rgba(151, 187, 205, 1)',
            pointBorderColor: '#fff',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(151, 187, 205, 1)',
            data: [28, 48, 40, 19, 96, 27, 100]
          }]
        },
        options: {
          responsive: true
        }
      }); // eslint-disable-next-line no-unused-vars


      var pieChart = new Chart($('#canvas-5'), {
        type: 'pie',
        data: {
          labels: ['Red', 'Green', 'Yellow'],
          datasets: [{
            data: [300, 50, 100],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
          }]
        },
        options: {
          responsive: true
        }
      }); // eslint-disable-next-line no-unused-vars


      var doughnutChart = new Chart($('#canvas-3'), {
        type: 'doughnut',
        data: {
          labels: ['Red', 'Green', 'Yellow'],
          datasets: [{
            data: [300, 50, 100],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
          }]
        },
        options: {
          responsive: true
        }
      }); // eslint-disable-next-line no-unused-vars

      var polarAreaChart = new Chart($('#canvas-6'), {
        type: 'polarArea',
        data: {
          labels: ['Red', 'Green', 'Yellow', 'Grey', 'Blue'],
          datasets: [{
            data: [11, 16, 7, 3, 14],
            backgroundColor: ['#FF6384', '#4BC0C0', '#FFCE56', '#E7E9ED', '#36A2EB']
          }]
        },
        options: {
          responsive: true
        }
      });
      //# sourceMappingURL=charts.js.map
    </script>
@endpush