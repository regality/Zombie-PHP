<form>
   <div class="grid_7 form alpha omega form">
      <div class="grid_11">
         <h3>Changing Your Password</h3>
      </div>

      <div class="grid_7 alpha"><hr /></div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Old Password</label>
         </div>
         <div class="grid_4">
            <input validate="required" type="password" name="old_password" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>New Password</label>
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
            <input validate="required,minlen=8" type="password" name="new_password_b" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_10 prefix_1 alpha omega field">
         <button class="password-update">Update Password</button>
      </div>
   </div>
</form>
<script type="text/javascript">
undead.util.require("password/main");
</script>
