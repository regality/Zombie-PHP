<div class="form">
   <table>
      <tr>
         <th colspan="2">
            Changing password for <?= $username ?>
         </th>
      </tr>
      <tr>
         <td>
            <label>Password</label>
         </td>
         <td>
            <input class="required" type="password" name="new_password_a" value="" />
         </td>
      </tr>
      <tr>
         <td>
            <label>Repeat Password</label>
         </td>
         <td>
            <input class="required" type="password" name="new_password_b" value="" />
         </td>
      </tr>
      <tr>
         <td>
            <button class="password-update">Update Password</button>
         </td>
         <td>
            <button class="pop-active">Cancel</button>
         </td>
      </tr>
   </table>
   <input type="hidden" name="id" value="<?= $request['id'] ?>" />
</div>
<script type="text/javascript">
undead.util.require("users/password");
</script>
