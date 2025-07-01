<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-users-cog"></i> <?php echo $title; ?></h3>

<div class="mb-3">
    <a href="<?php echo base_url('admin/users/tambah'); ?>" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Tambah Pengguna
    </a>
</div>

<div class="card">
    <div class="card-header">
        Daftar Pengguna Sistem
    </div>
    <div class="card-body">
        <?php if (empty($users_list)): ?>
            <div class="alert alert-info">Belum ada data pengguna.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" style="width: 5%;">No</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Username</th>
                            <th scope="col" class="text-center">Level</th>
                            <th scope="col" class="text-center">Tanggal Dibuat</th>
                            <th scope="col" style="width: 15%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($users_list as $user_item): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($user_item->nama_lengkap); ?></td>
                            <td><?php echo htmlspecialchars($user_item->username); ?></td>
                            <td class="text-center">
                                <?php if ($user_item->level == 'admin'): ?>
                                    <span class="badge badge-danger"><?php echo ucfirst($user_item->level); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?php echo ucfirst($user_item->level); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo date('d/m/Y H:i', strtotime($user_item->created_at)); ?></td>
                            <td class="text-center action-buttons">
                                <?php if ($user_item->id_user != $this->session->userdata('id_user')): // Tombol edit & hapus tidak muncul untuk diri sendiri ?>
                                <a href="<?php echo base_url('admin/users/edit/' . $user_item->id_user); ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                <a href="<?php echo base_url('admin/users/hapus/' . $user_item->id_user); ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                    <i class="fas fa-user-times"></i>
                                </a>
                                <?php else: ?>
                                    <span class="text-muted small">(Akun Anda)</span>
                                <?php endif; ?>
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
