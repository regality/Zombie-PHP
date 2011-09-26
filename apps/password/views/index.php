<div class="form">
   <table>
      <tr>
         <th colspan="2">Changing Your Password</th>
      </tr>
      <tr>
         <td>
            <label>Old Password</label>
         </td>
         <td>
            <input class="required" type="password" name="old_password" value="" />
         </td>
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
         <td colspan="2">
            <button class="password-update">Update Password</button>
         </td>
      </tr>
   </table>
</div>
<script type="text/javascript">
undead.util.require("password/main");
</script>
