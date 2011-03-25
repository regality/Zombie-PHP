<div class="close-button" id="close-users"></div>
<div class="form">
<table>
   <?php if (isset($users['id'])): ?>
      <input type="hidden" value="<?= $users['id'] ?>" name="id" />
   <?php endif ?>
   <tr>
      <td>
         <label>Username</label>
      </td>
      <td>
         <input class="required" type="text" name="username" value="<?= (isset($users['username']) ? htmlentities($users['username']) : '') ?>" />
      </td>
   </tr>
   <tr>
      <td>
         <label>Firstname</label>
      </td>
      <td>
         <input  type="text" name="firstname" value="<?= (isset($users['firstname']) ? htmlentities($users['firstname']) : '') ?>" />
      </td>
   </tr>
   <tr>
      <td>
         <label>Lastname</label>
      </td>
      <td>
         <input class="required" type="text" name="lastname" value="<?= (isset($users['lastname']) ? htmlentities($users['lastname']) : '') ?>" />
      </td>
   </tr>
   <?php if ($form_action == 'create'): ?>
   <tr>
      <td>
         <label>Password</label>
      </td>
      <td>
         <input class="required" type="password" name="password1" value="" />
      </td>
   </tr>
   <tr>
      <td>
         <label>Confirm Password</label>
      </td>
      <td>
         <input class="required" type="password" name="password2" value="" />
      </td>
   </tr>
   <?php endif ?>
   <tr>
      <td>
         <label>Groups</label>
      </td>
      <td>
         <?php foreach ($groups as $group): ?>
            <input  type="checkbox" name="groups[]" value="<?= $group['name'] ?>" <?= (isset($users['groups']) && in_array($group['name'], $users['groups']) ? 'checked' : '') ?> />
            <?= $group['name'] ?><br />
         <?php endforeach ?>
      </td>
   </tr>
   <tr>
      <td colspan='2'>
         <button class="users-<?= $form_action ?>"><?= $form_action ?></button>
      </td>
   </tr>
</table>
</div>
