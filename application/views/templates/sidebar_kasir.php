<nav class="sidebar">
    <div class="sidebar-header">
        <h5>Menu Kasir</h5>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(2) == 'transaksi' || $this->uri->segment(2) == 'dashboard') ? 'active' : ''; ?>" href="<?php echo base_url('kasir/transaksi'); ?>">
                <i class="fas fa-cash-register"></i> Transaksi Penjualan
            </a>
        </li>
        <!-- Menu lain untuk kasir bisa ditambahkan di sini -->
        <!-- Contoh: Riwayat Transaksi Kasir (jika ada) -->
        <!--
        <li class="nav-item">
            <a class="nav-link <?php echo ($this->uri->segment(2) == 'riwayat') ? 'active' : ''; ?>" href="<?php echo base_url('kasir/riwayat'); ?>">
                <i class="fas fa-history"></i> Riwayat Transaksi Saya
            </a>
        </li>
        -->
        <?php if($this->session->userdata('level') == 'admin'): ?>
        <hr style="background-color: #6c757d; margin: 10px 15px;">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('admin/dashboard'); ?>">
                <i class="fas fa-arrow-left"></i> Kembali ke Admin
            </a>
        </li>
        <?php endif; ?>
    </ul>
</nav>
<div class="content-wrapper"> <!-- content-wrapper dibuka di sini, ditutup di footer -->
    <div class="container-fluid">
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
         <?php if($this->session->flashdata('success_cart')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('success_cart'); ?>
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
        <?php if($this->session->flashdata('error_cart')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('error_cart'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
         <?php if($this->session->flashdata('error_bayar')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error Pembayaran!</strong><br>
                <?php echo $this->session->flashdata('error_bayar'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <!-- End Pesan Flashdata -->
