$(document).ready(function() {
   $(".<SLUG>-create").live('click', function() {
      var form = $(this).parents("form");
      if (!undead.ui.verifyForm(form)) {
         return false;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"<SLUG>",
<AJAX_COMMA_SEP_FIELDS>
                      "action":"create"},
              "success":function(data) {
                  if (data.status = "success") {
                     undead.stack.pop("<SLUG>");
                     undead.stack.push("<SLUG>", "success");
                  } else {
                     undead.ui.error("Could not submit form.");
                  }
              }
      });
      return false;
   });

});
