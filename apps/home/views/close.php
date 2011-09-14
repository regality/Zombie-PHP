      </div>
   </div>
   <div id="footer">
      <div id="footer-content">
         Copyright zombiephp.com<br />
         <?php if ($is_mobile): ?>
            <a href="http://<?= $config['config']['domain'] ?>/?mobile=0">View full site</a>
         <?php elseif ($_COOKIE['m'] == 'o'): ?>
            <a href="http://<?= $config['config']['domain'] ?>/?mobile=1">View mobile site</a>
         <?php endif ?>
      </div>
   </div>
   <? render_errors_js() ?>
</body>
</html>
