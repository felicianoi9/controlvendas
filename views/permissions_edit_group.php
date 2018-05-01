<h1>Grupo de Permissões - Editar</h1>
<form method="POST">
	<label for="name">Nome do Grupo</label><br/>
	<input type="text" name="name" value="<?php echo $group_info['name_group'];?>"/><br/><br/>

	<label >Permissões:</label><br/>
	<?php foreach ($permissions_list as $p) :?>
	<div class="p_item">
		<input type="checkbox" name="permissions[]" value="<?php echo $p['id'];?>" id="p_<?php echo $p['id'];?>"    <?php echo (in_array($p['id'], $group_info['params']))?'checked = "checked"':'';?> />
		<label for="p_<?php echo $p['id'];?>"><?php echo $p['name'];?></label>
	</div>
	<?php endforeach;?>
	<br/><br/>

	<input type="submit" value="Alterar" />

</form>