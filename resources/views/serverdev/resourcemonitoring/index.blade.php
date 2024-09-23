@extends('layout.app')

@section('content')
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Multipurpose</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Dashboards</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card card-bordered">
                    <div class="card-body">
                        <!-- Label di atas chart -->
                        <h3 class="text-center mb-4">Monitoring Server Dev GCP TS</h3>

                        <!-- Button to refresh the chart -->
                        <button id="refreshChart" class="btn btn-primary mb-3">Refresh Chart</button>

                        <!-- Chart container -->
                        <div id="kt_amcharts_5" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
@endsection

@push('scripts')
    <script type="text/javascript">
        am5.ready(function() {
            var root = am5.Root.new("kt_amcharts_5");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            var chart = root.container.children.push(am5radar.RadarChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "panX",
                wheelY: "zoomX",
                innerRadius: am5.percent(20),
                startAngle: -90,
                endAngle: 180
            }));

            var cursor = chart.set("cursor", am5radar.RadarCursor.new(root, {
                behavior: "zoomX"
            }));

            cursor.lineY.set("visible", false);

            var xRenderer = am5radar.AxisRendererCircular.new(root, {});

            xRenderer.labels.template.setAll({
                radius: 10
            });

            xRenderer.grid.template.setAll({
                forceHidden: true
            });

            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                renderer: xRenderer,
                min: 0,
                max: 100,
                strictMinMax: true,
                numberFormat: "#'%'",
                tooltip: am5.Tooltip.new(root, {})
            }));

            var yRenderer = am5radar.AxisRendererRadial.new(root, {
                minGridDistance: 20
            });

            yRenderer.labels.template.setAll({
                centerX: am5.p100,
                fontWeight: "500",
                fontSize: 18,
                templateField: "columnSettings"
            });

            yRenderer.grid.template.setAll({
                forceHidden: true
            });

            var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "category",
                renderer: yRenderer
            }));

            var series1, series2;

            function updateChartData(data) {
                yAxis.data.setAll(data);

                // Hapus seri lama jika ada
                if (series1) {
                    chart.series.removeValue(series1);
                }
                if (series2) {
                    chart.series.removeValue(series2);
                }

                // Tambahkan seri baru
                series1 = chart.series.push(am5radar.RadarColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    clustered: false,
                    valueXField: "full",
                    categoryYField: "category",
                    fill: root.interfaceColors.get("alternativeBackground")
                }));

                series1.columns.template.setAll({
                    width: am5.p100,
                    fillOpacity: 0.08,
                    strokeOpacity: 0,
                    cornerRadius: 20
                });

                series1.data.setAll(data);

                series2 = chart.series.push(am5radar.RadarColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    clustered: false,
                    valueXField: "value",
                    categoryYField: "category"
                }));

                series2.columns.template.setAll({
                    width: am5.p100,
                    strokeOpacity: 0,
                    tooltipText: "{category}: {valueX}%",
                    cornerRadius: 20,
                    templateField: "columnSettings"
                });

                series2.data.setAll(data);

                series1.appear(1000);
                series2.appear(1000);
                chart.appear(1000, 100);
            }

            function fetchData() {
                $.ajax({
                    url: 'https://api.divops.devtechnos.com/api/resource', // Ganti dengan endpoint API Python Anda
                    method: 'GET',
                    success: function(response) {
                        updateChartData(response
                            .data); // Anggap API mengembalikan response dengan key "data"
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            // Ambil data pertama kali
            fetchData();

            // Handle button click untuk refresh chart
            $('#refreshChart').on('click', function() {
                fetchData();
            });
        });
    </script>
@endpush
