<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aplikasi Kasir Sederhana">
    <meta name="author" content="Jules">
    <title><?php echo isset($title) ? $title . ' - Aplikasi Kasir' : 'Aplikasi Kasir'; ?></title>

    <!-- Load Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Load Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <!-- Custom styles untuk template ini (opsional) -->
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <!-- Load jQuery UI CSS (untuk autocomplete di kasir) -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .main-wrapper {
            display: flex;
            flex: 1;
        }
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40; /* Bootstrap dark background */
            color: #fff;
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: #adb5bd; /* Bootstrap muted color */
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: #495057; /* Bootstrap darker background */
        }
        .sidebar .sidebar-header {
            padding: 15px;
            background: #23272b; /* Slightly darker than sidebar */
            text-align: center;
        }
        .content-wrapper {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa; /* Light background for content */
        }
        .navbar-custom {
             background-color: #007bff; /* Primary color for navbar */
             color: white;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
            color: white;
        }
         /* Sticky footer styles */
        .footer {
            background-color: #f5f5f5;
            padding: 10px 0;
            text-align: center;
            font-size: 0.9em;
            color: #6c757d;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom navbar-dark sticky-top">
    <a class="navbar-brand" href="<?php echo base_url( $this->session->userdata('level') == 'admin' ? 'admin/dashboard' : 'kasir/dashboard' ); ?>">
        <i class="fas fa-cash-register"></i> Aplikasi Kasir
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">
            <?php if ($this->session->userdata('logged_in')): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i> <?php echo $this->session->userdata('nama_lengkap'); ?>
                        (<?php echo ucfirst($this->session->userdata('level')); ?>)
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <!-- <a class="dropdown-item" href="#">Profil</a> -->
                        <!-- <div class="dropdown-divider"></div> -->
                        <a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="main-wrapper">
    <?php // Sidebar akan diload di sini oleh controller ?>
    <?php // Konten utama akan dimulai setelah ini ?>
    <!-- .content-wrapper akan dibuka di view masing-masing atau di view yang memanggil header dan sidebar -->

    <!-- application/views/templates/header.php -->
    <!-- Pastikan ada direktori assets/css/style.css atau hapus link jika tidak digunakan -->
    <!-- Saya akan membuat file style.css kosong untuk menghindari error 404 -->
