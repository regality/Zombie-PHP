<div class="app-stack" id="<?= $action ?>-stack" app="<?= $action ?>" active="true">
   <div class="app-content" action="<?= $preload_action ?>">
      <?php $preload->run($preload_action, $request) ?>
   </div>
</div>
<?php if ($action != 'console' && $config['env'] == 'dev'): ?>
   <div class="app-stack" id="console-stack" app="console" style="display:none;">
      <div class="app-content" action="index">
         <?php $console->run("index") ?>
      </div>
   </div>
<?php endif ?>
