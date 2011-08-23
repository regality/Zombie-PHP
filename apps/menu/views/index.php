<div class="menu-title">
   <h2><?= $title ?></h2>
</div>

<?php foreach ($apps as $app => $settings): ?>
   <?php $active = ($preload == $app ? 'active' : '') ?>
   <a href="#/<?= $app ?>" class="item <?= $active ?>"><?= $settings['name'] ?></a>
<?php endforeach ?>

<?php if ($session->is_set("username")): ?>
   <a class="item" id="logout">
      Logout
   </a>
<?php endif ?>
