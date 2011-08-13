<div class="form">
<table>
   <tr>
      <td>
         <label>Name</label>
      </td>
      <td>
         <input class="" type="text" name="name" value="<?= (isset($groups['name']) ? htmlentities($groups['name']) : '') ?>" />
      </td>
   </tr>
   <tr>
      <td colspan='2'>
         <button class="groups-<?= $form_action ?>"><?= $form_action ?></button>
         <?php if (isset($groups['id'])): ?>
            <input type="hidden" value="<?= $groups['id'] ?>" name="id" />
         <?php endif ?>
      </td>
   </tr>
</table>
</div>
