<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-tags"></i> <?php echo $title; ?></h3>

<div class="mb-3">
    <a href="<?php echo base_url('admin/kategori/tambah'); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kategori
    </a>
</div>

<div class="card">
    <div class="card-header">
        Daftar Kategori Produk
    </div>
    <div class="card-body">
        <?php if (empty($kategori_list)): ?>
            <div class="alert alert-info">Belum ada data kategori produk.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" style="width: 5%;">No</th>
                            <th scope="col">Nama Kategori</th>
                            <th scope="col" style="width: 15%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($kategori_list as $kategori): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($kategori->nama_kategori); ?></td>
                            <td class="text-center action-buttons">
                                <a href="<?php echo base_url('admin/kategori/edit/' . $kategori->id_kategori); ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo base_url('admin/kategori/hapus/' . $kategori->id_kategori); ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Produk terkait (jika ada) tidak akan terhapus otomatis namun akan kehilangan relasi kategori.');">
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
```
