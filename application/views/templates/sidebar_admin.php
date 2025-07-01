<nav class="sidebar">
    <div class="sidebar-header">
        <h5>Menu Admin</h5>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(2) == 'dashboard' || $this->uri->segment(2) == '') ? 'active' : ''; ?>" href="<?php echo base_url('admin/dashboard'); ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(2) == 'kategori') ? 'active' : ''; ?>" href="<?php echo base_url('admin/kategori'); ?>">
                <i class="fas fa-tags"></i> Manajemen Kategori
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(2) == 'produk') ? 'active' : ''; ?>" href="<?php echo base_url('admin/produk'); ?>">
                <i class="fas fa-box-open"></i> Manajemen Produk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(2) == 'users') ? 'active' : ''; ?>" href="<?php echo base_url('admin/users'); ?>">
                <i class="fas fa-users-cog"></i> Manajemen Pengguna
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(2) == 'laporan') ? 'active' : ''; ?>" href="#laporanSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-chart-line"></i> Laporan <i class="fas fa-caret-down float-right mt-1"></i>
            </a>
            <ul class="collapse list-unstyled <?php echo ($this->uri->segment(2) == 'laporan') ? 'show' : ''; ?>" id="laporanSubmenu">
                <li>
                    <a class="nav-link <?php echo ($this->uri->segment(3) == 'penjualan') ? 'active' : ''; ?>" href="<?php echo base_url('admin/laporan/penjualan'); ?>"><i class="fas fa-file-invoice-dollar"></i> Penjualan</a>
                </li>
                <li>
                    <a class="nav-link <?php echo ($this->uri->segment(3) == 'produk_terlaris') ? 'active' : ''; ?>" href="<?php echo base_url('admin/laporan/produk_terlaris'); ?>"><i class="fas fa-star"></i> Produk Terlaris</a>
                </li>
            </ul>
        </li>
        <hr style="background-color: #6c757d; margin: 10px 15px;">
        <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(1) == 'kasir' && $this->uri->segment(2) == 'transaksi') ? 'active' : ''; ?>" href="<?php echo base_url('kasir/transaksi'); ?>">
                <i class="fas fa-cash-register"></i> Halaman Kasir
            </a>
        </li>
    </ul>
</nav>
<div class="content-wrapper"> <!-- content-wrapper dibuka di sini, ditutup di footer -->
    <div class="container-fluid">
        <!-- Breadcrumb atau judul halaman bisa ditaruh di sini jika seragam -->
        <!-- Atau di masing-masing view utama -->
        <?php if(isset($title)): ?>
            <!-- <h2 class="mt-0 mb-3"><?php echo $title; ?></h2> -->
        <?php endif; ?>

        <!-- Pesan Flashdata -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('warning'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('info'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <!-- End Pesan Flashdata -->

        <!-- Konten utama dari view spesifik akan dimuat di sini -->
```
