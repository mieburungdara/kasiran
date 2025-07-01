<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-tachometer-alt"></i> <?php echo $title; ?></h3>

<div class="row">
    <!-- Card Total Penjualan Hari Ini -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4 dashboard-card">
            <div class="card-body">
                <div>
                    <div class="text-value">Rp <?php echo number_format($total_penjualan_hari_ini, 0, ',', '.'); ?></div>
                    <div>Total Penjualan Hari Ini</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url('admin/laporan/penjualan?start_date='.date('Y-m-d').'&end_date='.date('Y-m-d')); ?>">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>

    <!-- Card Jumlah Transaksi Hari Ini -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white mb-4 dashboard-card">
            <div class="card-body">
                <div>
                    <div class="text-value"><?php echo $jumlah_transaksi_hari_ini; ?></div>
                    <div>Transaksi Hari Ini</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                 <a class="small text-white stretched-link" href="<?php echo base_url('admin/laporan/penjualan?start_date='.date('Y-m-d').'&end_date='.date('Y-m-d')); ?>">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>

    <!-- Card Jumlah Produk -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4 dashboard-card">
            <div class="card-body">
                <div>
                    <div class="text-value"><?php echo $jumlah_produk; ?></div>
                    <div>Total Produk</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url('admin/produk'); ?>">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>

    <!-- Card Jumlah User -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4 dashboard-card">
            <div class="card-body">
                <div>
                    <div class="text-value"><?php echo $jumlah_user; ?></div>
                    <div>Total Pengguna</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?php echo base_url('admin/users'); ?>">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area mr-1"></i>
                Grafik Penjualan (7 Hari Terakhir)
            </div>
            <div class="card-body"><canvas id="salesChart" width="100%" height="40"></canvas></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-star mr-1"></i>
                Produk Terlaris (Bulan Ini)
            </div>
            <div class="card-body">
                <?php if (!empty($produk_terlaris)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($produk_terlaris as $pt): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($pt->nama_produk); ?>
                                <span class="badge badge-primary badge-pill"><?php echo $pt->total_terjual; ?> terjual</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-center text-muted">Belum ada data produk terlaris bulan ini.</p>
                <?php endif; ?>
            </div>
             <div class="card-footer text-center">
                <a class="small" href="<?php echo base_url('admin/laporan/produk_terlaris'); ?>">Lihat Semua Produk Terlaris</a>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Sales Chart
    var ctx = document.getElementById("salesChart");
    if (ctx) {
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($sales_chart_data['labels']); ?>,
                datasets: [{
                    label: "Pendapatan (Rp)",
                    lineTension: 0.3,
                    backgroundColor: "rgba(2,117,216,0.2)",
                    borderColor: "rgba(2,117,216,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(2,117,216,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(2,117,216,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: <?php echo json_encode($sales_chart_data['values']); ?>,
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            //max: 40000, // Atur Max sesuai kebutuhan atau biarkan otomatis
                            maxTicksLimit: 5,
                            callback: function(value, index, values) {
                                return 'Rp ' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgba(0, 0, 0, .125)",
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + number_format(tooltipItem.yLabel);
                            return label;
                        }
                    }
                }
            }
        });
    }
});

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep, // Mengubah default pemisah ribuan
        dec = (typeof dec_point === 'undefined') ? ',' : dec_point, // Mengubah default pemisah desimal
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
</script>
```
