<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalasi Aplikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .error-message {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            border: 1px solid #ebccd1;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }
        .validation-errors p {
            color: #a94442;
            margin: 0 0 5px 0;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Konfigurasi Aplikasi</h2>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (validation_errors()): ?>
            <div class="error-message validation-errors">
                <?php echo validation_errors('<p>', '</p>'); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo isset($form_action) ? htmlspecialchars($form_action, ENT_QUOTES, 'UTF-8') : ''; ?>">

            <div class="form-group">
                <label for="base_url">Base URL Aplikasi</label>
                <input type="text" name="base_url" id="base_url" value="<?php echo set_value('base_url', isset($suggested_base_url) ? htmlspecialchars($suggested_base_url, ENT_QUOTES, 'UTF-8') : ''); ?>" required>
                <small>Contoh: http://localhost/nama_folder_aplikasi/</small>
            </div>

            <hr style="margin: 20px 0;">
            <h4>Konfigurasi Database</h4>

            <div class="form-group">
                <label for="db_host">Host Database</label>
                <input type="text" name="db_host" id="db_host" value="<?php echo set_value('db_host', 'localhost'); ?>" required>
            </div>

            <div class="form-group">
                <label for="db_name">Nama Database</label>
                <input type="text" name="db_name" id="db_name" value="<?php echo set_value('db_name'); ?>" required>
            </div>

            <div class="form-group">
                <label for="db_user">User Database</label>
                <input type="text" name="db_user" id="db_user" value="<?php echo set_value('db_user'); ?>" required>
            </div>

            <div class="form-group">
                <label for="db_pass">Password Database</label>
                <input type="password" name="db_pass" id="db_pass" value="<?php echo set_value('db_pass'); ?>">
                <small>Kosongkan jika tidak ada password.</small>
            </div>

            <div class="form-group">
                <input type="submit" value="Simpan Konfigurasi & Install">
            </div>

        <?php echo form_close(); ?>
    </div>
</body>
</html>
