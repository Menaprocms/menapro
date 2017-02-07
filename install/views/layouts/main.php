<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo $baseDir;?>img/favicon.ico" type="image/x-icon" />
    <link href='https://fonts.googleapis.com/css?family=Lato:400,100' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <title><?php echo translate('Menapro installer')?></title>

    <link rel="stylesheet" href="install/css/bootstrap.css" type="text/css" media="all">
      <link rel="stylesheet" href="install/css/font-awesome-4.6.3/css/font-awesome.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="install/css/install.css" type="text/css" media="all">
    <link rel="stylesheet" href="install/css/select2.min.css" type="text/css" media="all">
    <script type="text/javascript" src="install/js/pwstrength-bootstrap.min.js"></script>
    <script type="text/javascript" src="install/js/select2.full.min.js"></script>
    <!--    <link rel="stylesheet" href="install/css/bootstrap-theme.min.css" type="text/css" media="all">-->
    <script type="text/javascript">
        var baseDir="<?php echo $baseDir;?>";
    </script>
    <script type="text/javascript" src="install/js/install.js"></script>

</head>
<body>

<div class="wrap">
        <div class="container">
          <?php
          include(__DIR__.'/../install/'.$view);

           ?>
         </div>
</div>

</body>

</html>

<?php

function translate($string){
    return $string;
}

?>