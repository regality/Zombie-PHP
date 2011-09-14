<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title>Zombie PHP</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
   <link rel="stylesheet" href="<?= $config['config']['web_root'] ?>/css/<?= ($this->is_mobile ? "mobile-" : "") ?>light.css" type="text/css" />
   <script type="text/javascript" src="<?= $config['config']['web_root'] ?>/js/jquery.min.js"></script>
   <script type="text/javascript" src="<?= $config['config']['web_root'] ?>/js/undead.js"></script>
   <script type="text/javascript" src="<?= $config['config']['web_root'] ?>/js/main.js"></script>
   <script type="text/javascript">
   $(document).ready(function() {
      undead.settings.baseUrl = "<?= $config['config']['web_root'] ?>";
      undead.token.set("<?= $token ?>");
      undead.init.init();
      undead.stack.loadDefault();
   });
   </script>
</head>
<body>
   <div id="main">
      <div id="sidenav">
         <?php $menu->run("index", array('active' => $action)) ?>
      </div>
      <div id="header">
         <img src="<?= $config['config']['web_root'] ?>/images/zombie-glasses.png" alt="logo" />
         <h1>Zombie PHP</h1>
      </div>
      <div id="content">
         <div id="alerts"></div>
