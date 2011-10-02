$(function() {
   $(".password-update").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!zs.ui.verifyForm($form)) {
         return false;
      }
      if ($form.find("input[name=new_password_a").val() !==
          $form.find("input[name=new_password_b").val()) {
         zs.ui.error("Passwords do not match.");
         return false;
      }
      oldPass = zs.crypt.hash($form.find("input[name=old_password]").val());
      newPass = zs.crypt.hash($form.find("input[name=new_password_a]").val());
      $.ajax({"url":"app.php",
              "data":{"app":"password",
                      "old_password":oldPass,
                      "new_password":newPass,
                      "action":"update"},
              "success":function(data) {
                  if (data.status === "success") {
                     zs.stack.pop("password");
                     zs.stack.push("password", "success");
                  }
              }
      });
      return false;
   });
});
