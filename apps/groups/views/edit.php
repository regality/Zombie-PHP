<form>
   <div class="grid_6 alpha omega form">
      <div class="grid_11 field">
         <?php if ($form_action == "update"): ?>
            <h3>Updating Group <?= $groups['name'] ?></h3>
         <?php else: ?>
            <h3>Creating New Group</h3>
         <?php endif ?>
      </div>

      <div class="grid_5 alpha omega">
         <hr />
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_2 alpha">
            <label>Name</label>
         </div>
         <div class="grid_4">
            <input validate="required,minlen=3,maxlen=50" type="text" name="name" value="<?= (isset($groups['name']) ? htmlentities($groups['name']) : '') ?>" />
         </div>
         <div class="grid_5 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_1 prefix_1 alpha">
            <button class="groups-<?= $form_action ?>"><?= ucwords($form_action) ?></button>
         </div>

         <div class="grid_1 suffix_8 omega">
            <button class="pop-active">Cancel</button>
         </div>
      </div>

      <?php if (isset($groups['id'])): ?>
         <input type="hidden" value="<?= $groups['id'] ?>" name="id" />
      <?php endif ?>
   </div>
</form>
<script type="text/javascript">
undead.util.require('groups/main');
</script>
