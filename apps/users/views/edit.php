<form>
   <div class="grid_7 form alpha omega form">
      <div class="grid_11 field">
         <?php if ($form_action == "update"): ?>
            <h3>Updating User <?= $users['username'] ?></h3>
         <?php else: ?>
            <h3>Creating New User</h3>
         <?php endif ?>
      </div>

      <div class="grid_7 alpha omega">
         <hr />
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Username</label>
         </div>
         <div class="grid_4">
            <input validate="required,maxlen=100" type="text" name="username" value="<?= (isset($users['username']) ? $users['username'] : '') ?>" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Firstname</label>
         </div>
         <div class="grid_4">
            <input validate="required,maxlen=100" type="text" name="firstname" value="<?= (isset($users['firstname']) ? $users['firstname'] : '') ?>" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Lastname</label>
         </div>
         <div class="grid_4">
            <input validate="required,maxlen=100" type="text" name="lastname" value="<?= (isset($users['lastname']) ? $users['lastname'] : '') ?>" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <?php if ($form_action == 'create'): ?>
         <div class="grid_11 alpha omega field">
            <div class="grid_3 alpha">
               <label>Password</label>
            </div>
            <div class="grid_4">
               <input validate="required,minlen=8" type="password" name="password1" value="" />
            </div>
            <div class="grid_4 error omega">
            </div>
         </div>

         <div class="grid_11 alpha omega field">
            <div class="grid_3 alpha">
               <label>Confirm Password</label>
            </div>
            <div class="grid_4">
               <input validate="required" type="password" name="password2" value="" />
            </div>
            <div class="grid_4 error omega">
            </div>
         </div>
      <?php endif ?>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Groups</label>
         </div>
         <div class="grid_4">
            <?php foreach ($groups as $group): ?>
               <?php $checked = (isset($users['groups']) && in_array($group['name'], $users['groups'])
                                ? 'checked' : '') ?>
               <input type="checkbox" name="groups[]" value="<?= $group['id'] ?>" <?= $checked ?> />
               <?= $group['name'] ?><br />
            <?php endforeach ?>
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_1 prefix_2 suffix_1 alpha">
            <button class="users-<?= $form_action ?>"><?= ucwords($form_action) ?></button>
         </div>

         <div class="grid_1 suffix_6 omega">
            <button class="pop-active">Cancel</button>
         </div>
      </div>

      <?php if (isset($users['id'])): ?>
         <input type="hidden" value="<?= $users['id'] ?>" name="id" />
      <?php endif ?>
   </div>
</form>
<script type="text/javascript">
zs.util.require("users/main");
</script>
