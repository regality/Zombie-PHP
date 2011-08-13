<div class="form">
<table>
<HTML_EDIT_FIELDS>
   <tr>
      <td colspan='2'>
         <button class="<SLUG>-<?= $form_action ?>"><?= $form_action ?></button>
         <?php if (isset($<SLUG>['id'])): ?>
            <input type="hidden" value="<?= $<SLUG>['id'] ?>" name="id" />
         <?php endif ?>
      </td>
   </tr>
</table>
</div>
