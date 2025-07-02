<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-star"></i> <?php echo $title; ?></h3>

<div class="card mb-4">
    <div class="card-header">
        Filter Laporan Produk Terlaris
    </div>
    <div class="card-body">
        <?php echo form_open('admin/laporan/produk_terlaris', ['method' => 'get']); ?>
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
                        <label for="limit">Jumlah Top</label>
                        <input type="number" class="form-control" id="limit" name="limit" value="<?php echo $limit; ?>" min="1" max="100">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filter</button>
                    </div>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Data Produk Terlaris (<?php echo date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date)); ?>) - Top <?php echo $limit; ?>
    </div>
    <div class="card-body">
        <?php if (empty($produk_terlaris)): ?>
            <div class="alert alert-info">Tidak ada data produk terjual pada periode ini.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" style="width: 5%;">Rank</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col" class="text-center">Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; foreach ($produk_terlaris as $produk): ?>
                        <tr>
                            <td class="text-center"><?php echo $rank++; ?></td>
                            <td><?php echo htmlspecialchars($produk->nama_produk); ?></td>
                            <td class="text-center"><?php echo $produk->total_terjual; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
