      </div>
   </div>
   <div id="footer">
      <div id="footer-content">
         Copyright zombiephp.com<br />
         <?php if ($is_mobile): ?>
            <a href="http://<?= $domain ?>/?mobile=0">View full site</a>
         <?php elseif ($mcookie == 'o'): ?>
            <a href="http://<?= $domain ?>/?mobile=1">View mobile site</a>
         <?php endif ?>
      </div>
   </div>
   <? renderErrorsJs() ?>
</body>
</html>
