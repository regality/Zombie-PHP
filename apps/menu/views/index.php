<div class="menu-title">
   <h2><?= $title ?></h2>
</div>

<?php foreach ($apps as $app => $settings): ?>
   <?php $is_active = ($active == $app ? 'active' : '') ?>
   <a href="/<?= $app ?>" class="item <?= $is_active ?>"><?= $settings['name'] ?></a>
<?php endforeach ?>

<?php if ($session->is_set("username")): ?>
   <a href="javascript:void(0)" class="item" id="logout">Logout</a>
<?php endif ?>
