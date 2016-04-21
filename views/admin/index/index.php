<?php

echo head(array('title'=>'Carta', 'bodyclass'=>'carta browse'));

?>
<link href='<?php echo admin_url("../") ?>plugins/Carta/css/carta.css' rel='stylesheet' />

<div class="carta-img"></div>
<div id='primary'>	  	
    <p>To add maps to your Pages, Exhibits or Items, simply paste the short code [carta id="XXX"] where XXX is your MapID.</p>
</div>

<a href="<?php echo url("carta/index/add"); ?>" class="big green add button">Add Map</a>
<p>&nbsp;</p>
<p>&nbsp;</p>

	<table>
		<tr>
			<td>Carta</td>
			<td>Name</td>
			<td>Action</td>
		</tr>
		<?php  $flag = 0; foreach ($carta as $f) : $flag = 1; ?>
			<tr>
				<td>[carta id="<?php echo $f->id; ?>"]</td>
				<td><?php echo $f->name; ?></td>
				<td>
					<a href="<?php echo url("carta/index/edit/" . $f->id); ?>" class="btn button green">Edit</a>
					<a href="<?php echo url("carta/index/downloadjson/" .  $f->id); ?>" class="btn button green">JSON</a>
					<a href="<?php echo url("carta/index/delete/" .  $f->id); ?>" class="btn button red">Delete</a>
				</td>		
			</tr>
			
		<?php endforeach; ?>

		<?php if (empty($flag)): ?>
			<tr>
				<td colspan="3">There are not map.</td>
			</tr>
		<?php endif; ?>
		
	</table>

<?php include("layergroup.php"); ?>

<?php include("baselayerapi.php"); ?>
<?php echo foot(); ?>