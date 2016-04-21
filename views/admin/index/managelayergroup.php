<?php
echo head(array('title'=>'Carta', 'bodyclass'=>'carta browse'));
?>

<form action="<?php echo url("carta/index/layergroupsave"); ?>" id="save_map_form" method="post">
	

    <input type="hidden" value="<?php echo $layer_group_id; ?>" name="layer_group_id">
    <p>
        <label for="">Name</label> 
        <input type="text" name="layer_group_name" value="<?php echo (isset($layer_group->name)) ? $layer_group->name : 'Group Name'; ?>">
    </p>
    <p>
		<label for="">BaseLayers</label> 
        <select name="baselayer[]" multiple="multiple"> 
        	<?php foreach ($baselayer as $b) : if (!isset($b->is_deleted)) : ?>
        		<option value="<?php echo $b->id; ?>" <?php echo (in_array($b->id, $selected_baselayer)) ? 'selected="selected"': ''; ?> ><?php echo $b->name; ?></option>
        	<?php endif; endforeach; ?>
        </select>   	
    </p>
    <p>
    	<button class="btn button green">Save</button>
    </p>
</form>
