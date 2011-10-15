<div class="menu-title">
   <h2><?= $title ?></h2>
</div>
<?php foreach ($apps as $app => $settings): ?>
   <?php $is_active = ($active == $app ? 'active' : '') ?>
   <?php $refresh = (isset($settings['refresh']) ? 'refresh="' . $settings['refresh'] . '"' : '') ?>
   <a href="/<?= $app ?>" <?= $refresh ?> class="item <?= $is_active ?>"><?= $settings['name'] ?></a>
<?php endforeach ?>
<?php if ($logged_in): ?>
   <a href="javascript:void(0)" class="item" id="logout">Logout</a>
<?php endif ?>
<script type="text/javascript">
zs.util.require("menu/main");
</script>
