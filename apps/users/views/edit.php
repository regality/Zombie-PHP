<div class="form">
   <form>
      <table>
         <tr>
            <th colspan="2">
               <?php if ($form_action == "update"): ?>
                  Updating User <?= $users['username'] ?>
               <?php else: ?>
                  Creating New User
               <?php endif ?>
            </th>
         </tr>
         <tr>
            <td>
               <label>Username</label>
            </td>
            <td>
               <input validate="required,maxlen=100" type="text" name="username" value="<?= (isset($users['username']) ? $users['username'] : '') ?>" />
            </td>
         </tr>
         <tr>
            <td>
               <label>Firstname</label>
            </td>
            <td>
               <input validate="required,maxlen=100" type="text" name="firstname" value="<?= (isset($users['firstname']) ? $users['firstname'] : '') ?>" />
            </td>
         </tr>
         <tr>
            <td>
               <label>Lastname</label>
            </td>
            <td>
               <input validate="required,maxlen=100" type="text" name="lastname" value="<?= (isset($users['lastname']) ? $users['lastname'] : '') ?>" />
            </td>
         </tr>
         <?php if ($form_action == 'create'): ?>
         <tr>
            <td>
               <label>Password</label>
            </td>
            <td>
               <input validate="required,minlen=8" type="password" name="password1" value="" />
            </td>
         </tr>
         <tr>
            <td>
               <label>Confirm Password</label>
            </td>
            <td>
               <input validate="required" type="password" name="password2" value="" />
            </td>
         </tr>
         <?php endif ?>
         <tr>
            <td>
               <label>Groups</label>
            </td>
            <td>
               <?php foreach ($groups as $group): ?>
                  <?php $checked = (isset($users['groups']) && in_array($group['name'], $users['groups'])
                                   ? 'checked' : '') ?>
                  <input type="checkbox" name="groups[]" value="<?= $group['id'] ?>" <?= $checked ?> />
                  <?= $group['name'] ?><br />
               <?php endforeach ?>
            </td>
         </tr>
         <tr>
            <td>
               <button class="users-<?= $form_action ?>"><?= $form_action ?></button>
               <?php if (isset($users['id'])): ?>
                  <input type="hidden" value="<?= $users['id'] ?>" name="id" />
               <?php endif ?>
            </td>
            <td>
               <button class="pop-active">Cancel</button>
            </td>
         </tr>
      </table>
   </form>
</div>
