   <tr>
      <td>
         <label><FIELD_NAME_NICE></label>
      </td>
      <td>
         <select <VALIDATE>name="<FIELD_NAME>">
            <option value=""></option>
<ENUM_OPTIONS>
         </select>
      </td>
   </tr>
   <?php if (isset($<TABLE_NAME>)): ?>
      <script type="text/javascript">
      $("select[name='<FIELD_NAME>'] option[value='<?= $<TABLE_NAME>['<FIELD_NAME>'] ?>']").attr("selected","selected");
      </script>
   <?php endif ?>
