   <tr>
      <td>
         <label><FIELD_NAME_NICE></label>
      </td>
      <td>
         <textarea <VALIDATE>name="<FIELD_NAME>"><?= (isset($<TABLE_NAME>['<FIELD_NAME>']) ? htmlentities($<TABLE_NAME>['<FIELD_NAME>']) : '') ?></textarea>
      </td>
   </tr>
