$(function() {
   $(".password-update").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.ui.verifyForm($form)) {
         return false;
      }
      if ($form.find("input[name=new_password_a").val() !==
          $form.find("input[name=new_password_b").val()) {
         undead.ui.error("Passwords do not match.");
         return false;
      }
      oldPass = undead.crypt.hash($form.find("input[name=old_password]").val());
      newPass = undead.crypt.hash($form.find("input[name=new_password_a]").val());
      $.ajax({"url":"app.php",
              "data":{"app":"password",
                      "old_password":oldPass,
                      "new_password":newPass,
                      "action":"update"},
              "success":function(data) {
                  if (data.status === "success") {
                     undead.stack.pop("password");
                     undead.stack.push("password", "success");
                  }
              }
      });
      return false;
   });
});
