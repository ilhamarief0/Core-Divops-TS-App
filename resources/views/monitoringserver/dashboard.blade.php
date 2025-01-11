<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technos Studio : Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .status-bar {
            display: flex;
            gap: 1px;
            width: 100%;
        }
        .status-bar div {
            flex: 1;
            height: 20px;
            background-color: green;
            cursor: pointer;
            position: relative;
        }
        .status-bar .down {
            background-color: red;
        }
        .tooltip-custom {
            position: absolute;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            white-space: nowrap;
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="container my-4">

        <h1 class="mb-4">Server Monitoring Technos Studio</h1>

        @foreach ($servers as $server)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ $server->name }}</strong> - Port : {{ $server->port }}
            </div>
            <div class="card-body">
                <div class="status-bar mt-3">
                    @foreach ($server->logs as $log)
                        <div
                            class="{{ strtolower($log->status) === 'down' ? 'down' : '' }}"
                            data-date="{{ $log->checked_at }}"
                            data-status="{{ $log->status }}">
                            <div class="tooltip-custom">
                                {{ strtolower($log->status) === 'down' ? 'Downtime detected on ' . $log->checked_at : $log->checked_at . ' No downtime recorded on this day' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <script>
        // Tunggu sampai DOM selesai dimuat
        document.addEventListener('DOMContentLoaded', () => {
            const statusBars = document.querySelectorAll('.status-bar div');

            statusBars.forEach(bar => {
                // Tampilkan tooltip saat mouse masuk
                bar.addEventListener('mouseenter', function () {
                    const tooltip = this.querySelector('.tooltip-custom');
                    if (tooltip) tooltip.style.display = 'block';
                });

                // Sembunyikan tooltip saat mouse keluar
                bar.addEventListener('mouseleave', function () {
                    const tooltip = this.querySelector('.tooltip-custom');
                    if (tooltip) tooltip.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>
