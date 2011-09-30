<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title>Zombie PHP</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
   <?php if ($env == 'dev'): ?>
      <link rel="stylesheet" href="/css/<?= ($is_mobile ? "mobile-" : "") ?>main.css" type="text/css" />
   <?php elseif ($env == 'prod'): ?>
      <link rel="stylesheet" href="/build/<?= $version ?>/css/<?= ($is_mobile ? "mobile-" : "") ?>main.css" type="text/css" />
   <?php endif ?>
</head>
<body>
   <?php if ($env == 'dev'): ?>
      <script type="text/javascript" src="/js/undead/jquery.js"></script>
      <script type="text/javascript" src="/js/undead/undead.js"></script>
      <script type="text/javascript" src="/js/home/main.js"></script>
   <?php elseif ($env == 'prod'): ?>
      <script type="text/javascript" src="/build/<?= $version ?>/js/main.js"></script>
   <?php endif ?>
   <script type="text/javascript">
   $(function() {
      undead.settings.baseUrl = "<?= $web_root ?>";
      undead.init.init();
      undead.token.set("<?= $token ?>");
      undead.stack.loadDefault();
   });
   </script>
   <div id="main" class="container_16">
      <div id="header" class="grid_11 prefix_5">
         <h1><span class="h1x">Z</span><span class="h1u">ombie</span><span class="h1x">PHP</span></h1>
      </div>
      <div id="sidenav-grid" class="grid_5">
         <div id="sidenav">
            <?php $menu->run("index", array('active' => $action)) ?>
         </div>
      </div>
      <div id="content-grid" class="grid_11">
         <div id="content">
            <div id="alerts"></div>
