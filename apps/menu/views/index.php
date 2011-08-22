<div class="menu-title">
   <h2><?= $title ?></h2>
</div>

<?php foreach ($apps as $app => $settings): ?>
   <?php $active = ($preload == $app ? ' active' : '') ?>
   <a href="#/<?= $app ?>/index" class="app item<?= $active ?>" app="<?= $app ?>"><?= $settings['name'] ?></a>
<?php endforeach ?>

<?php if (isset($preload)): ?>
   <script type="text/javascript">
   $(document).ready(function() {
      undead.loadApp("<?= $preload ?>");
   });
   </script>
<?php endif ?>

<?php if ($session->is_set("username")): ?>
   <a class="item" id="logout">
      Logout
   </div>
<?php endif ?>
