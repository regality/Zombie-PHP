$(function() {
   $(".users-delete").click(function(e) {
      e.preventDefault();
      var row = $(this).parents("tr");
      $.ajax({"data":{"app":"users",
                      "action":"delete",
                      "id":$(this).attr("users_id")},
              "success":function(data) {
                  row.remove();
              }
      });
   });

   $(".users-create").live('click', function() {
      var form = $(this).parents("div.form");
      if (!undead.ui.verifyForm(form)) {
         undead.ui.error("Some required fields are msising.");
         return;
      }
      var groups = [];
      $.each(form.find("input[name='groups[]']:checked"), function() {
         groups.push($(this).val());
      });
      if (groups.length == 0) {
         undead.ui.error("You must select at least one group.");
         return
      }
      var pw1 = form.find("input[name=password1]").val();
      var pw2 = form.find("input[name=password2]").val();
      if (pw1 != pw2) {
         undead.ui.error('Passwords do not match');
         retun;
      }
      var hex_pass = undead.crypt.hash(pw1);
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":form.find("input[name=id]").val(),
                      "username":form.find("input[name=username]").val(),
                      "firstname":form.find("input[name=firstname]").val(),
                      "lastname":form.find("input[name=lastname]").val(),
                      "password":hex_pass,
                      "groups":groups,
                      "action":"create"},
              "success":function(data) {
                  undead.stack.pop("users");
                  undead.stack.refresh("users");
              }
      });
   });

   $(".users-update").live('click', function() {
      var form = $(this).parents("div.form");
      if (!undead.ui.verifyForm(form)) {
         undead.ui.error("Some required fields are msising.");
         return;
      }
      var groups = [];
      $.each(form.find("input[name='groups[]']:checked"), function() {
         groups.push($(this).val());
      });
      if (groups.length == 0) {
         undead.ui.error("You must select at least one group.");
         return
      }
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":form.find("input[name=id]").val(),
                      "username":form.find("input[name=username]").val(),
                      "firstname":form.find("input[name=firstname]").val(),
                      "lastname":form.find("input[name=lastname]").val(),
                      "groups":groups,
                      "action":"update"},
              "success":function(data) {
                  $("#users-modal").fadeOut();
                  undead.stack.pop("users");
                  undead.stack.refresh("users");
              }
      });
   });

});
