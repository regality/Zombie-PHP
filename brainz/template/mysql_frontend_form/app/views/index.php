<div class="form">
   <table>
   <HTML_EDIT_FIELDS>
      <tr>
         <td colspan="2">
            <button class="<SLUG>-create">Submit</button>
         </td>
      </tr>
   </table>
</div>
<script type="text/javascript">
$(document).ready(function() {
   $(".<SLUG>-create").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.verify_form($form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"<SLUG>",
<AJAX_COMMA_SEP_FIELDS>
                      "action":"create"},
              "success":function(data) {
                  undead.popStack("<SLUG>");
                  undead.pushStack("<SLUG>", "success");
              }
      });
   });

});
</script>
