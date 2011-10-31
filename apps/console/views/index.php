<div>
   <input type="text" id="console-run" />
   <table id="console-messages" width="500">
      <tr>
         <th>
            <h2>Console</h2><a href="javascript:void(0)" id="console-clear">Clear all</a>
         </th>
      </tr>
   </table>

   <?php if ($env == 'dev'): ?>
      <div>
         <a class="session" href="#">Session Variables</a>
         <pre class="session console-server"><?php print_r($_SESSION) ?></pre>
      </div>
      <div>
         <a class="cookie" href="#">Cookies</a>
         <pre class="cookie console-server"><?php print_r($_COOKIE) ?></pre>
      </div>
      <div>
         <a class="server" href="#">$_SERVER</a>
         <pre class="server console-server"><?php print_r($_SERVER) ?></pre>
      </div>
   <?php endif ?>
</div>
<script type="text/javascript">
zs.util.require("console/main");
</script>
