$(function() {
   $(".password-update").live('click', function() {
      var form = $(this).parents("div.form");
      if (!zs.ui.verifyForm(form)) {
         return false;
      }
      pwf1 = form.find("input[name=new_password_a]");
      pwf2 = form.find("input[name=new_password_b]");
      if (pwf1.val() !== pwf2.val()) {
         zs.ui.showFieldError(pwf1, "match", "Passwords do not match.");
         return false;
      }
      var newPass = zs.crypt.hash(pwf1.val());
      var id = form.find("input[name=id]").val();
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":id,
                      "new_password":newPass,
                      "action":"password_update"},
              "success":function(data) {
                  if (data.status === "success") {
                     zs.stack.pop("users");
                     zs.stack.refresh("users");
                  } else {
                     zs.ui.error("Could not update password.");
                  }
              }
      });
      return false;
   });

});
