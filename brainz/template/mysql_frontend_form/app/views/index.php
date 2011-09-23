<div class="form">
   <form>
      <table>
      <HTML_EDIT_FIELDS>
         <tr>
            <td colspan="2">
               <button class="<SLUG>-create">Submit</button>
            </td>
         </tr>
      </table>
   </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
   $(".<SLUG>-create").die('click').live('click', function() {
      var form = $(this).parents("form");
      if (!undead.ui.verifyForm(form)) {
         undead.ui.error("Some required fields are msising.");
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
</script>
