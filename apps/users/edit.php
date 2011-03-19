<style>
.edit-users label {
height: 100%;
width: 100px;
display: inline-block;
}

.edit-users input[type=text],
.edit-users .foo {
width: 120px;
text-align: left;
}

.edit-users .foo {
display: inline-block;
}

.field {
padding: 2px;
margin: 2px 20px;
border-bottom: 1px solid #aaa;
}

.close-button {
height: 30px;
width: 30px;
background-image: url('/images/close-button.png');
float: right;
}
</style>
<div class="users-edit">
   <div class="close-button" id="close-users"></div>
   <h3>Editing User <i><?= $user['username'] ?></i></h3><br />
   <div class="field">
      <label>Username</label>
      <input type="text" value="<?= $user['username'] ?>">
   </div>
   <div class="field">
      <label>Firstname</label>
      <input type="text" value="<?= $user['firstname'] ?>">
   </div>
   <div class="field">
      <label>Lastname</label>
      <input type="text" value="<?= $user['lastname'] ?>">
   </div>
   <div class="field">
      <label>Groups</label>
      <div class="foo">
         <?php foreach ($groups as $group): ?>
            <input type="checkbox" <?= (in_array($group['name'], $user['groups']) ? "checked" : "") ?>>
            <?= $group['name'] ?><br />
         <?php endforeach ?>
      </div>
   </div>
      <button>Save</button>
</div>
