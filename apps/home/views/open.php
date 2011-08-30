<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title>Zombie PHP</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
   <link rel="stylesheet" href="<?= $web_root ?>/css/<?= ($this->is_mobile ? "mobile-" : "") ?>dark.css" type="text/css" />
   <script type="text/javascript" src="<?= $web_root ?>/js/jquery.min.js"></script>
   <script type="text/javascript" src="<?= $web_root ?>/js/sha1.js"></script>
   <script type="text/javascript" src="<?= $web_root ?>/js/sha256.min.js"></script>
   <script type="text/javascript" src="<?= $web_root ?>/js/undead.js"></script>
   <script type="text/javascript" src="<?= $web_root ?>/js/main.js"></script>
   <script type="text/javascript" src="<?= $web_root ?>/js/json2.js"></script>
   <script type="text/javascript">
   function setAjaxUrl() {
      $.ajaxSetup({
         "url":"<?= $web_root ?>/app.php"
      });
   }
   undead.token.set("<?= $token ?>");
   </script>
</head>
<body>
   <div id="main">
      <div id="header">
         <img src="<?= $web_root ?>/images/zombie-glasses.png" alt="logo" />
         <h1>Zombie PHP</h1>
      </div>
      <div id="sidenav">
         <?php $menu->run("index", array('active' => $action)) ?>
      </div>
      <div id="content">
