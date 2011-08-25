<?php if (isset($preload)): ?>
   <div class="app-stack" id="<?= $preload_app ?>-stack" app="<?= $preload_app ?>" active="true">
      <div class="app-content" action="<?= $preload_action ?>">
         <?php $preload->run($preload_action, $request) ?>
      </div>
   </div>
<?php endif ?>
