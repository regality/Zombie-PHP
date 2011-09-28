<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title>Zombie PHP</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
   <?php if ($config['env'] == 'dev'): ?>
      <link rel="stylesheet" href="/css/<?= ($this->is_mobile ? "mobile-" : "") ?>main.css" type="text/css" />
      <script type="text/javascript" src="/js/undead/jquery.js"></script>
      <script type="text/javascript" src="/js/undead/undead.js"></script>
      <script type="text/javascript" src="/js/home/main.js"></script>
   <?php elseif ($config['env'] == 'prod'): ?>
      <link rel="stylesheet" href="/build/<?= $version ?>/css/<?= ($this->is_mobile ? "mobile-" : "") ?>main.css" type="text/css" />
      <script type="text/javascript" src="/build/<?= $version ?>/js/main.js"></script>
   <?php endif ?>
   <script type="text/javascript">
   $(function() {
      undead.settings.baseUrl = "<?= $config['web_root'] ?>";
      undead.init.init();
      undead.token.set("<?= $token ?>");
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
         <img src="<?= $config['web_root'] ?>/images/zombie-glasses.png" alt="logo" />
         <h1>Zombie PHP</h1>
      </div>
      <div id="content">
         <div id="alerts"></div>
