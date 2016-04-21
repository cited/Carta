<?php
echo head(array('title'=>'Carta', 'bodyclass'=>'carta browse'));
?>
<link href='<?php echo admin_url("../") ?>plugins/Carta/css/carta.css' rel='stylesheet' />
<form action="<?php echo url("carta/index/baselayerapisave"); ?>" id="save_map_form" method="post">
	

    <input type="hidden" value="<?php echo $baselayer_id; ?>" name="layer_id">

    <p>
        <label for="">Layer Name</label> 
        <input type="text" name="layer_name" value="<?php echo (isset($baselayer->name)) ? $baselayer->name : 'Layer Name'; ?>">
    </p>
	
	<p>
        <label for="">Layer URL</label> 
        <input type="text" name="layer_url" value="<?php echo (isset($baselayer->url)) ? $baselayer->url : ''; ?>">
    </p>

    <p>
        <label for="">key</label> 
        <input type="text" name="layer_key" value="<?php echo (isset($baselayer->key)) ? $baselayer->key : ''; ?>">
    </p>

    <p>
        <label for="">Access Token</label> 
        <input type="text" name="layer_accesstoken" value="<?php echo (isset($baselayer->accesstoken)) ? $baselayer->accesstoken : ''; ?>">
    </p>

    <p>
        <label for="">Attribution</label> 
        <input type="text" name="attribution" value="<?php echo (isset($baselayer->attribution)) ? $baselayer->attribution : ''; ?>">
    </p>


    <p>
    	<button class="btn button green">Save</button>
    </p>
</form>
