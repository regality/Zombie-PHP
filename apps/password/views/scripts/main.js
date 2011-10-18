$(function() {
   $(".password-update").die('click').live('click', function() {
      var form = $(this).parents("div.form");
      var oldPassField = form.find("input[name=old_password]");
      var newPassFieldA = form.find("input[name=new_password_a]");
      var newPassFieldB = form.find("input[name=new_password_b]");
      if (!zs.ui.verifyForm(form)) {
         return false;
      }
      if (newPassFieldA.val() !== newPassFieldB.val()) {
         zs.ui.showFieldError(newPassFieldA, "match", "Passwords do not match.");
         return false;
      }
      var oldPass = zs.crypt.hash(oldPassField.val());
      var newPass = zs.crypt.hash(newPassFieldA.val());
      $.ajax({"url":"app.php",
              "data":{"app":"password",
                      "old_password":oldPass,
                      "new_password":newPass,
                      "action":"update"},
              "success":function(data) {
                  if (data.status === "success") {
                     zs.stack.pop("password");
                     zs.stack.push("password", "success");
                  } else if (data.reason == "wrong password") {
                     zs.ui.showFieldError(oldPassField, "wrong", "Wrong password.");
                  } else {
                     zs.ui.error("Could not update password.");
                  }
              }
      });
      return false;
   });
});
