<div class="form">
   <form>
      <table>
         <tr>
            <td>
               <label>Name</label>
            </td>
            <td>
               <input validate="required,length={1,50}" type="text" name="name" value="<?= (isset($groups['name']) ? htmlentities($groups['name']) : '') ?>" />
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
