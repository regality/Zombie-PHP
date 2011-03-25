<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title>Zombie PHP</title>
   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
   <link rel="stylesheet" href="<?= $web_root ?>/css/main.css" type="text/css" />
   <link href='http://fonts.googleapis.com/css?family=Slackey' rel='stylesheet' type='text/css'>
   <script type="text/javascript" src="<?= $web_root ?>/js/jquery.min.js"></script>
   <script type="text/javascript" src="<?= $web_root ?>/js/sha1.js"></script>
   <script type="text/javascript" src="<?= $web_root ?>/js/main.js"></script>
   <script type="text/javascript">
   function setAjaxUrl() {
      $.ajaxSetup({
         "url":"<?= $web_root ?>/app.php",
      });
   }
   <?php foreach ($token_list as $token): ?>
   addToken("<?= $token ?>");
   <?php endforeach ?>
   </script>
</head>
<body>
   <div id="header">
      <h1>Zombie PHP</h1>
      <img src="<?= $web_root ?>/images/zombie.jpg" />
   </div>
   <div id="wrapper">
      <div id="main">
         <div id="sidenav">
            <?php $menu->run() ?>
         </div>
         <div id="content">
            <div id="app-console" class="app-content" style="display:none;">
               <h2>Console</h2><a href="javascript:void(0)" id="console-clear">Clear all</a>
               <div id="console-messages">
               </div>
            </div>
         </div>
      </div>
   </div>
   <? render_errors() ?>
</body>
</html>
