<div class="form">
   <form>
      <table>
         <tr>
            <th colspan="2">
               <?php if ($form_action == "update"): ?>
                  Updating <TABLE_NAME> <?= $<TABLE_NAME>['id'] ?>
               <?php else: ?>
                  Creating New <TABLE_NAME>
               <?php endif ?>
            </th>
         </tr>
      <HTML_EDIT_FIELDS>
         <tr>
            <td>
               <button class="<SLUG>-<?= $form_action ?>"><?= ucwords($form_action) ?></button>
               <?php if (isset($<TABLE_NAME>['id'])): ?>
                  <input type="hidden" value="<?= $<TABLE_NAME>['id'] ?>" name="id" />
               <?php endif ?>
            </td>
            <td>
               <button class="pop-active">Cancel</button>
            </td>
         </tr>
      </table>
   </form>
</div>
<script type="text/javascript">
undead.util.require("<SLUG>/main");
</script>
