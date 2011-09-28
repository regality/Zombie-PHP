<div class="form">
   <form>
      <table>
         <tr>
            <th colspan="2">
               <?php if ($form_action == "update"): ?>
                  Updating Group <?= $groups['name'] ?>
               <?php else: ?>
                  Creating New Group
               <?php endif ?>
            </th>
         </tr>
         <tr>
            <td>
               <label>Name</label>
            </td>
            <td>
               <input validate="required,maxlength=50" type="text" name="name" value="<?= (isset($groups['name']) ? htmlentities($groups['name']) : '') ?>" />
            </td>
         </tr>
         <tr>
            <td>
               <button class="groups-<?= $form_action ?>"><?= ucwords($form_action) ?></button>
               <?php if (isset($groups['id'])): ?>
                  <input type="hidden" value="<?= $groups['id'] ?>" name="id" />
               <?php endif ?>
            </td>
            <td>
               <button class="pop-active">Cancel</button>
            </td>
         </tr>
      </table>
   </form>
</div>
