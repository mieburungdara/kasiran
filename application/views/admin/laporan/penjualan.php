<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-file-invoice-dollar"></i> <?php echo $title; ?></h3>

<div class="card mb-4">
    <div class="card-header">
        Filter Laporan Penjualan
    </div>
    <div class="card-body">
        <?php echo form_open('admin/laporan/penjualan', ['method' => 'get']); ?>
            <div class="form-row align-items-end">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filter</button>
                    </div>
                </div>
                 <div class="col-md-2">
                    <div class="form-group">
                        <a href="<?php echo base_url('admin/laporan/penjualan'); ?>" class="btn btn-secondary btn-block"><i class="fas fa-sync-alt"></i> Reset</a>
                    </div>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Data Laporan Penjualan (<?php echo date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date)); ?>)
    </div>
    <div class="card-body">
        <?php if (empty($laporan_penjualan)): ?>
            <div class="alert alert-info">Tidak ada data penjualan pada periode ini.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">ID Transaksi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Kasir</th>
                            <th scope="col" class="text-center">Total Item</th>
                            <th scope="col" class="text-right">Total Harga (Rp)</th>
                            <th scope="col" class="text-right">Bayar (Rp)</th>
                            <th scope="col" class="text-right">Kembali (Rp)</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; $grand_total = 0; foreach ($laporan_penjualan as $laporan): $grand_total += $laporan->total_harga; ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($laporan->id_transaksi); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($laporan->tanggal_transaksi)); ?></td>
                            <td><?php echo htmlspecialchars($laporan->nama_kasir); ?></td>
                            <td class="text-center"><?php echo $laporan->total_item; ?></td>
                            <td class="text-right"><?php echo number_format($laporan->total_harga, 0, ',', '.'); ?></td>
                            <td class="text-right"><?php echo number_format($laporan->bayar, 0, ',', '.'); ?></td>
                            <td class="text-right"><?php echo number_format($laporan->kembali, 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url('admin/laporan/detail_transaksi/' . $laporan->id_transaksi); ?>" class="btn btn-info btn-sm" title="Lihat Struk">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="font-weight-bold">
                        <tr>
                            <td colspan="5" class="text-right">Total Pendapatan:</td>
                            <td class="text-right bg-light">Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
```
