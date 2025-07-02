<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-box-open"></i> <?php echo $title; ?></h3>

<div class="mb-3">
    <a href="<?php echo base_url('admin/produk/tambah'); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

<div class="card">
    <div class="card-header">
        Daftar Produk
    </div>
    <div class="card-body">
        <?php if (empty($produk_list)): ?>
            <div class="alert alert-info">Belum ada data produk.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" style="width: 5%;">No</th>
                            <th scope="col" style="width: 10%;">Gambar</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Kategori</th>
                            <th scope="col" class="text-right">Harga (Rp)</th>
                            <th scope="col" class="text-center">Stok</th>
                            <th scope="col" style="width: 15%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($produk_list as $produk): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <?php if ($produk->gambar_produk && file_exists('./uploads/produk/' . $produk->gambar_produk)): ?>
                                    <img src="<?php echo base_url('uploads/produk/' . $produk->gambar_produk); ?>" alt="<?php echo htmlspecialchars($produk->nama_produk); ?>" style="width: 70px; height: auto; object-fit: cover;">
                                <?php else: ?>
                                    <img src="<?php echo base_url('uploads/produk/default.jpg'); ?>" alt="Default Image" style="width: 70px; height: auto;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($produk->nama_produk); ?></td>
                            <td><?php echo htmlspecialchars($produk->nama_kategori); ?></td>
                            <td class="text-right"><?php echo number_format($produk->harga, 0, ',', '.'); ?></td>
                            <td class="text-center"><?php echo $produk->stok; ?></td>
                            <td class="text-center action-buttons">
                                <a href="<?php echo base_url('admin/produk/edit/' . $produk->id_produk); ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo base_url('admin/produk/hapus/' . $produk->id_produk); ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
