<div class="item"><h2><?= $title ?></h2></div>
<?php foreach ($apps as $app => $settings): ?>
   <?php if (isset($settings['name'])): ?>
      <div app="<?= $app ?>" 
       class="item app<?= (isset($settings['active']) ? " active" : "") ?>"
       cache="<?= ($settings['cache'] ? "1" : "0") ?>" >
         <?= $settings['name'] ?>
      </div>
   <?php elseif (isset($settings['active'])): ?>
      <script type="text/javascript">
      $(document).ready(function() {
         undead.loadApp("<?= $app ?>");
      });
      </script>
   <?php endif ?>
<?php endforeach ?>

<?php if ($session->is_set("username")): ?>
   <div class="item app" id="logout">
      Logout
   </div>
<?php endif ?>
