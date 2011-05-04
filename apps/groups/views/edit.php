<div class="close-button" id="close-groups"></div>
<div class="form">
<table>
   <?php if (isset($groups['id'])): ?>
      <input type="hidden" value="<?= $groups['id'] ?>" name="id" />
   <?php endif ?>
   <tr>
      <td>
         <label>Name</label>
      </td>
      <td>
         <input class="required" type="text" name="name" value="<?= (isset($groups['name']) ? htmlentities($groups['name']) : '') ?>" />
      </td>
   </tr>
   <tr>
      <td colspan='2'>
         <button class="groups-<?= $form_action ?>"><?= $form_action ?></button>
      </td>
   </tr>
</table>
</div>
