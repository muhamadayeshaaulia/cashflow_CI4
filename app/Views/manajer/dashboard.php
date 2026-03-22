<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <h1>Selamat Datang di Dashboard!</h1>
    <p>Anda login sebagai: <strong><?= session()->get('username') ?></strong> (Role: <?= session()->get('role') ?>)</p>

    <a href="<?= base_url('/logout') ?>" class="btn btn-danger">Logout</a>

</body>
</html>