@extends('monitoringweb.website.clientsite')

@section('customer_site_content')
    <div class="pt-0">
        <div id="chart_timeline_{{ $customerSite->id }}"></div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [{
                    name: 'Response time (ms)',
                    data: {!! json_encode($chartData) !!} || [],
                }],
                chart: {
                    id: 'line-datetime',
                    type: 'line',
                    height: 400,
                    zoom: {
                        autoScaleYaxis: true
                    }
                },
                annotations: {
                    yaxis: [{
                        y: {{ $customerSite->warning_threshold ?? 500 }},
                        borderColor: 'orange',
                        label: {
                            show: true,
                            text: 'Threshold',
                            style: {
                                color: "#fff",
                                background: 'orange'
                            }
                        }
                    }, {
                        y: {{ $customerSite->down_threshold ?? 1000 }},
                        borderColor: 'red',
                        label: {
                            show: true,
                            text: 'Down',
                            style: {
                                color: "#fff",
                                background: 'red'
                            }
                        }
                    }]
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    type: 'datetime',
                    min: new Date("{{ $startTime->format('Y-m-d H:i:s') }}").getTime(),
                    max: new Date("{{ $endTime->format('Y-m-d H:i:s') }}").getTime(),
                    labels: {
                        datetimeUTC: false,
                    },
                    title: {
                        text: 'Datetime',
                    },
                },
                yaxis: {
                    tickAmount: {{ $customerSite->y_axis_tick_amount ?? 5 }},
                    title: {
                        text: 'Milliseconds',
                    },
                    max: {{ $customerSite->y_axis_max ?? 1000 }},
                    min: 0,
                },
                stroke: {
                    width: [2]
                },
                tooltip: {
                    x: {
                        format: 'dd MMM HH:mm:ss'
                    }
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart_timeline_{{ $customerSite->id }}"), options);
            chart.render().catch(err => console.error("Chart render error:", err));
        });
    </script>

@endpush
