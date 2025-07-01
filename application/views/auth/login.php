<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Aplikasi Kasir</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9; /* Warna latar belakang yang umum untuk halaman login */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            width: 360px;
            background: #fff;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .login-logo a {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }
        .login-logo a i {
            color: #007bff; /* Warna ikon */
        }
        .login-card-body .input-group .form-control {
            border-right: 0;
        }
        .login-card-body .input-group .input-group-text {
            background-color: transparent;
            border-left: 0;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-logo text-center mb-4">
            <a href="<?php echo base_url(); ?>"><i class="fas fa-cash-register"></i> Aplikasi Kasir</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg text-center">Silakan login untuk memulai sesi Anda</p>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
                <?php if(validation_errors()): ?>
                     <div class="alert alert-danger" role="alert">
                        <?php echo validation_errors(); ?>
                    </div>
                <?php endif; ?>


                <?php echo form_open('auth/login'); ?>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo set_value('username'); ?>" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Login <i class="fas fa-sign-in-alt"></i></button>
                        </div>
                    </div>
                <?php echo form_close(); ?>

                <!-- <p class="mt-3 mb-1 text-center">
                    <a href="#">Lupa password?</a>
                </p> -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
```
