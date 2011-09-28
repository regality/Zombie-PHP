$(document).ready(function() {
   $(".<SLUG>-delete").live('click', function(e) {
      e.preventDefault();
      var row = $(this).parents("tr");
      $.ajax({"data":{"app":"<SLUG>",
                      "action":"delete",
                      "id":$(this).attr("<TABLE_NAME>_id")},
              "success":function(data) {
                  if (data.status == "success") {
                     row.remove();
                  } else {
                     undead.ui.error("Could not delete <TABLE_NAME>.");
                  }
              }
      });
   });

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
                  if (data.status == "success") {
                     undead.stack.pop("<SLUG>");
                     undead.stack.refresh("<SLUG>");
                  } else {
                     undead.ui.error("Could not create <TABLE_NAME>.");
                  }
              }
      });
      return false;
   });

   $(".<SLUG>-update").live('click', function() {
      var form = $(this).parents("form");
      if (!undead.ui.verifyForm(form)) {
         return false;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"<SLUG>",
<AJAX_COMMA_SEP_FIELDS_WID>
                      "action":"update"},
              "success":function(data) {
                  if (data.status == "success") {
                     undead.stack.pop("<SLUG>");
                     undead.stack.refresh("<SLUG>");
                  } else {
                     undead.ui.error("Could not update <TABLE_NAME>.");
                  }
              }
      });
      return false;
   });

});
