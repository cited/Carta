<?php 
	if (!empty($carta_item_id)){
		$cartaItem = get_db()->getTable('CartaItem')->getByItemId($carta_item_id);	
	}
	
?>
<style>
	#carta-metadata h2{
		display: none;
	}
</style>
<link href='<?php echo admin_url("../") ?>plugins/Carta/css/carta.css' rel='stylesheet' />

<div class="carta-img"></div>

<p>To add Carta map, Just copy and paste desired shortcode(s) below:</p>

<textarea id="carta_content" name="carta_content" rows="10" cols="10"><?php echo isset($cartaItem) ? $cartaItem->content : ''; ?></textarea>
<input type="hidden" name="carta_item_id" value="<?php echo isset($cartaItem) ? $cartaItem->id : '';?>">
<?php 
	
	$carta = get_db()->getTable('Carta')->getByType("map");
       
?>
<p>&nbsp;</p>
<table>
		<tr>
			<td>Carta</td>
			<td>Name</td>			
		</tr>
		<?php  $flag = 0; foreach ($carta as $f) : $flag = 1; ?>
			
			<tr>
				<td>[carta id="<?php echo $f->id; ?>"]</td>
				<td><?php echo $f->name; ?></td>					
			</tr>
			
		<?php endforeach; ?>

		<?php if (empty($flag)): ?>
			<tr>
				<td colspan="2">There are not map.</td>
			</tr>
		<?php endif; ?>
		
	</table>