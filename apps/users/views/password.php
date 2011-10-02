<form>
   <div class="grid_7 form alpha omega form">
      <div class="grid_11 field">
         <h3>Changing password for <?= $username ?></h3>
      </div>

      <div class="grid_7 alpha omega">
         <hr />
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Password</label>
         </div>
         <div class="grid_4">
            <input validate="required,minlen=8" type="password" name="new_password_a" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Repeat Password</label>
         </div>
         <div class="grid_4">
            <input validate="required" type="password" name="new_password_b" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_1 prefix_2 suffix_1 alpha">
            <button class="password-update">Update Password</button>
         </div>

         <div class="grid_1 suffix_6 omega">
            <button class="pop-active">Cancel</button>
         </div>
      </div>

      <input type="hidden" name="id" value="<?= $id ?>" />
   </div>
</form>
<script type="text/javascript">
zs.util.require("users/password");
</script>
