@extends('templates.app', ['title' => 'Dashboard | Diagram Laporan'])

@section('dynamic-contents')
    <div class="container mt-5">
        <h3 class="text-center mb-3">Jumlah Laporan dan Tanggapan</h3>
        <canvas id="reportsChart" style="max-height: 500px;"></canvas>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil data laporan dari backend
            fetch('{{ route("head_staff.reports.by.province") }}')
                .then(response => response.json())
                .then(data => {
                    // Parse data untuk Chart.js
                    const labels = data.map(item => item.province_name); // Ambil nama provinsi
                    const totalReports = data.map(item => item.total_reports); // Jumlah laporan
                    const doneCount = data.map(item => item.done_count); // Jumlah DONE
                    const onProcessCount = data.map(item => item.on_process_count); // Jumlah ON_PROCESS
                    const rejectCount = data.map(item => item.reject_count); // Jumlah REJECT

                    // Konfigurasi Chart.js
                    const ctx = document.getElementById('reportsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Jumlah Laporan',
                                    data: totalReports,
                                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Tanggapan DONE',
                                    data: doneCount,
                                    backgroundColor: 'rgba(0, 255, 0, 0.6)',
                                    borderColor: 'rgba(0, 255, 0, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Tanggapan ON PROCESS',
                                    data: onProcessCount,
                                    backgroundColor: 'rgba(255, 165, 0, 0.6)',
                                    borderColor: 'rgba(255, 165, 0, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Tanggapan REJECTED',
                                    data: rejectCount,
                                    backgroundColor: 'rgba(255, 0, 0, 0.6)',
                                    borderColor: 'rgba(255, 0, 0, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching chart data:', error));
        });
    </script>
@endpush
