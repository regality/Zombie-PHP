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
$(document).ready(function() {
   $(".password-update").die('click').live('click', function() {
      var $form = $(this).parents("div.form");
      if (!undead.ui.verifyForm($form)) {
         undead.ui.error("Some required fields are msising.");
         return;
      }
      if ($form.find("input[name=new_password_a").val() !==
          $form.find("input[name=new_password_b").val()) {
         undead.ui.error("Passwords do not match.");
         return;
      }
      var newPass = undead.crypt.hash($form.find("input[name=new_password_a]").val());
      var id = $form.find("input[name=id]").val();
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":id,
                      "new_password":newPass,
                      "action":"password_update"},
              "success":function(data) {
                  if (data.status === "success") {
                     undead.stack.pop("users");
                     undead.stack.refresh("users");
                  }
              }
      });
   });

});
</script>
