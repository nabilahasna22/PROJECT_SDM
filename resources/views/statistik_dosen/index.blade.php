@extends('layouts.template')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
    <style>
        /* Card and Table Styling */
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            margin: 20px;
        }

        .card-header {
            background-color: #ffc107;
            color: #fff;
            padding: 20px;
            font-size: 1.5rem;
            border-radius: 12px 12px 0 0;
        }

        .table td canvas {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}

        .table th, .table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #e0e0e0;
            font-size: 1rem;
        }

        .table th {
            background-color: #f7f7f7;
            color: #555;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #fffbec;
            cursor: pointer;
        }

        /* Highlight Bobot Column */
        .table td {
            background-color: #fff3cd;
        }

        .table td canvas {
            margin: auto;
        }

        /* Button for Actions */
        .text-info {
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
        }

        .text-info:hover {
            color: #0056b3;
        }

        /* Chart Styles */
        .chart-container {
            width: 100%;
            height: 150px;
        }

        /* Responsive Styling for Smaller Screens */
        @media (max-width: 768px) {
            .table th, .table td {
                padding: 8px;
                font-size: 0.9rem;
            }

            .card-header {
                font-size: 1.2rem;
                padding: 15px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Statistik Dosen</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_statistik_dosen">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dosen</th>
                        <th>Total Kegiatan</th>
                        <th>Terprogram</th>
                        <th>Non Program</th>
                        <th>Non JTI</th>
                        <th>Total Bobot</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        var dataStatistikDosen = $('#table_statistik_dosen').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('statistik_dosen.list') }}",
                type: "POST",
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    alert('Terjadi kesalahan: ' + error);
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nama_dosen", className: "text-center" },
                { data: "total_kegiatan", className: "text-center" },
                { data: "terprogram", className: "text-center" },
                { data: "non_program", className: "text-center" },
                { data: "non_jti", className: "text-center" },
                { data: "total_bobot", className: "text-center", render: function(data, type, row) {
                    // Create a canvas for the chart
                    var canvasId = 'chart-bobot-' + row.id;
                    setTimeout(function() {
                        createDoughnutChart(canvasId, row.total_bobot);  // Create doughnut chart
                    }, 10);
                    return `<canvas id="${canvasId}" width="100%" height="150"></canvas>`;
                }},
                { 
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<a href="{{ url('statistik_dosen/detail') }}/${row.id}" class="text-info">[Detail]</a>`;
                    }
                }
            ],
            order: [[1, 'asc']]
        });

        function createDoughnutChart(canvasId, bobotData) {
            var ctx = document.getElementById(canvasId).getContext('2d');
            var chartData = {
                labels: ['Total Bobot', 'Remaining'],  // Labeling the sections
                datasets: [{
                    label: 'Total Bobot',
                    data: [bobotData, 100 - bobotData],  // Total Bobot and Remaining part
                    backgroundColor: ['#ffc107', '#e0e0e0'],  // Yellow for the data, gray for remaining
                    borderColor: ['#ffc107', '#e0e0e0'],
                    borderWidth: 1
                }]
            };

            new Chart(ctx, {
                type: 'doughnut',  // Doughnut chart type
                data: chartData,
                options: {
                    responsive: true,  // Make it responsive
                    cutoutPercentage: 70,  // Make it a ring
                    plugins: {
                        legend: {
                            display: false  // Hide the legend if not needed
                        }
                    }
                }
            });
        }
    });
</script>


@endpush
