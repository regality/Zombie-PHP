<div class="item"><h2><?= $title ?></h2></div>
<?php foreach ($apps as $app): ?>
   <div app="<?= $app['app'] ?>" 
    class="item app<?= (isset($app['active']) ? " active" : "") ?>"
    cache="<?= ($app['cache'] ? "1" : "0") ?>" >
      <?= $app['name'] ?>
   </div>
<?php endforeach ?>

<?php if ($session->is_set("username")): ?>
   <div class="item app" id="logout">
      Logout
   </div>
<?php endif ?>
