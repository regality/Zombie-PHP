   <tr>
      <td>
         <label><FIELD_NAME_NICE></label>
      </td>
      <td>
         <select <VALIDATE>name="<FIELD_NAME>">
            <option value=''></option>
            <?php foreach ($<OTHER_TABLE_NAME> as $option): ?>
               <?php $selected = ((isset($<TABLE_NAME>) && $option['id'] == $<TABLE_NAME>['<OTHER_TABLE_NAME>_id']) ? "selected" : "") ?>
               <option value="<?= $option['id'] ?>" <?= $selected ?>><?= $option['<JOIN_FIELD>'] ?></option>
            <?php endforeach ?>
         </select>
      </td>
   </tr>
