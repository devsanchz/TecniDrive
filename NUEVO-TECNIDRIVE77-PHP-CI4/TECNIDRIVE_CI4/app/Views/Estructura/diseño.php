<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina;?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?= $this->renderSection('css') ?>
    <style>
body {
  background-image: url("<?= base_url('assets/img/fondo.png') ?>");
   background-repeat: no-repeat;
      background-size: cover;
      background-attachment: fixed; 
      color: #0a0a0a;
}
</style>
</head>
<body>
   <?php echo $this->renderSection('Estructura/menu_admin'); ?>
   <?php echo $this->renderSection('Estructura/menu_pro'); ?>
   <?php echo $this->renderSection('Estructura/menu_mecanico'); ?>



 <?php echo $this->renderSection("contenido");?>



    

<?= $this->renderSection('scripts') ?>
</body>
</html>