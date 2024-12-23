<div class="container">
    <h2 class="text-center">Uji Performansi Algoritma Pencarian</h2>

    <div class="row">
        <div class="col-md-12">
            <h4>Hasil Pengujian</h4>
            @if (empty($results))
            <p>Hasil pengujian tidak tersedia.</p>
            @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ukuran Input</th>
                        <th>Waktu Eksekusi (Iteratif)</th>
                        <th>Waktu Eksekusi (Rekursif)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                    <tr>
                        <td>{{ $result['input_size'] }}</td>
                        <td>{{ number_format($result['iterative_time'], 6) }} detik</td>
                        <td>{{ number_format($result['recursive_time'], 6) }} detik</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            <h4>Grafik Performa</h4>
            <canvas id="performanceChart" width="400" height="200"></canvas>

            <form action="{{ route('export.csv') }}" method="POST">
                @csrf
                <input type="hidden" name="results" value="{{ json_encode($results) }}">
                <button type="submit" class="btn btn-primary mt-3">Ekspor ke CSV</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const chartData = {
            labels: @json(array_column($results, 'input_size')),
            datasets: [{
                    label: 'Iteratif',
                    data: @json(array_column($results, 'iterative_time')),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                },
                {
                    label: 'Rekursif',
                    data: @json(array_column($results, 'recursive_time')),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                }
            ]
        };

        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Ukuran Input'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Waktu Eksekusi (detik)'
                        }
                    }
                }
            }
        });
    });
</script>