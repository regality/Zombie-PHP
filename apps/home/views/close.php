         </div>
      </div>
      <div class="clear"></div>
      <div class="grid_11 prefix_5">
         <div id="footer">
            Copyright zombiephp.com<br />
            <?php if ($is_mobile): ?>
               <a href="http://<?= $domain ?><?= $web_root ?>/?mobile=0">View full site</a>
            <?php elseif ($mcookie == 'o'): ?>
               <a href="http://<?= $domain ?><?= $web_root ?>/?mobile=1">View mobile site</a>
            <?php endif ?>
         </div>
      </div>
   </div>
   <? renderErrorsJs() ?>
</body>
</html>
