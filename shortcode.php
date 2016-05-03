<?php 
global $id;

$notFirstmap = false;
if ($id != ""){
    $notFirstmap = true;
}

$id = $args['id'];
$jId = uniqid();

if (!$notFirstmap){
    
    function getJsonData($id){
        
        $carta = get_db()->getTable('Carta')->getById($id);
        return $carta;
    }    
}


$carta = getJsonData($id);

if (count($carta) > 0 ) :
$defaultLayer =  get_db()->getTable('CartaLayer')->getById($carta->baselayer);;
$layerGroup = getJsonData("layergroup");

$defaultLayer = "L.tileLayer('".$defaultLayer->url."', {maxZoom: 18, id: '". $defaultLayer->key . "', token: '". $defaultLayer->accesstoken . "', attribution:'" . html_entity_decode($defaultLayer->attribution) . "' + mbAttribution_" . $jId ."})";

$baseLayers = array();
$layerGroup = array();
if (!empty($carta->layergroup)){
    $layerGroup = get_db()->getTable('CartaGroup')->getById($carta->layergroup);
}

$rws = array();
if (count($layerGroup) > 0){    
    $rws = explode(',', $layerGroup->layer_id);
}

foreach($rws as $r) {

    $r = get_db()->getTable('CartaLayer')->getById($r);;

    if (count($r) > 0){
       $baseLayers[] = "'".$r->name."': L.tileLayer('".$r->url."', {maxZoom: 18, id: '".$r->key."', token: '". $r->accesstoken . "', attribution:'" . html_entity_decode($r->attribution) . "' + mbAttribution_" . $jId . "})";
    }  
}

$baseLayers = implode(",", $baseLayers);
  
$first_lat = $carta->latitude;
$first_lng = $carta->longitude;
/*
if (!empty($carta->pointers)) {
    $parseData = json_decode(unserialize(base64_decode($carta->pointers)));
    
    //$first_lat = $parseData[0]->geometry->coordinates[0];
    //$first_lng = $parseData[0]->geometry->coordinates[1];
    $coordinates = json_encode($parseData[0]->geometry->coordinates);
    if(preg_match_all("/(\-?\d+(\.\d+)?),(\-?\d+(\.\d+)?)/", $coordinates, $matches)) {
        $match = $matches[0][0];
        $match = str_replace(array("[","]"), "", $match);
        
        $match = explode(",", $match);
        $first_lat = $match[0];
        $first_lng = $match[1];
    }
    else {
        $first_lat = 0;
        $first_lng = 0;
    }
}
*/
$id=$jId;
?>

<div id="map_<?php echo $id; ?>" style="width:<?php echo $carta->width; ?>px;height:<?php echo $carta->height; ?>px"></div>


<?php if (!$notFirstmap) : ?>
<link rel="stylesheet" href="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.awesome-markers.css" />
<link rel="stylesheet" href="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" />

<script src="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.js"></script>

<script src="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.awesome-markers.js"></script>
 
<link href='https://cdn.mapfig.com/mapfig-cdn/leaflet.draw.css' rel='stylesheet' />
<script src='https://cdn.mapfig.com/mapfig-cdn/leaflet.draw.js'></script>

<script src='https://cdn.mapfig.com/mapfig-cdn/Leaflet.fullscreen.min.js'></script>
<link href='https://cdn.mapfig.com/mapfig-cdn/leaflet.fullscreen.css' rel='stylesheet' />

<script src='https://cdn.mapfig.com/mapfig-cdn/L.Control.Locate.js'></script>
<link href='https://cdn.mapfig.com/mapfig-cdn/L.Control.Locate.css' rel='stylesheet' />

<link href="https://cdn.mapfig.com/mapfig-cdn/Control.MiniMap.css" rel="stylesheet">
<script src='https://cdn.mapfig.com/mapfig-cdn/Control.MiniMap.js'></script>

<link href="https://cdn.mapfig.com/mapfig-cdn/leaflet.measurecontrol.css" rel="stylesheet">
<script src='https://cdn.mapfig.com/mapfig-cdn/leaflet.measurecontrol.js'></script>

<script src='https://cdn.mapfig.com/mapfig-cdn/Leaflet.StaticSidebar.js'></script>
<!--[if lt IE 9]>
  <link href='https://cdn.mapfig.com/mapfig-cdn/L.Control.Locate.ie.css' rel='stylesheet' />
<![endif]-->


<link href='<?php echo admin_url("../") ?>plugins/Carta/css/carta.css' rel='stylesheet' />
<script type="text/javascript" src="<?php echo admin_url("../") ?>plugins/Carta/js/modal.js"></script>
<?php  endif; ?>

<textarea style="display:none;" id="geo_image_olverlays_<?php echo $id;?>"><?php echo ($carta->geo_image_olverlays) ? base64_decode($carta->geo_image_olverlays) : "[]"; ?></textarea>
<div style="display:none;" id="textarea_legend_content_<?php echo $id;?>"><?php echo ($carta->legend_content) ? base64_decode($carta->legend_content) : ""; ?></div>

<script>
var mbAttribution_<?php echo $id;?>=' contributors | <a href="https://www.mapfig.com" target="_blank">Mapfig</a>';var show_sidebar_<?php echo $id;?>=<?php echo ($carta->show_sidebar) ? "true" : "false"; ?>;var mapOverlays_<?php echo $id;?>=new L.LayerGroup();
var defaultLayer_<?php echo $id;?>=<?php echo $defaultLayer;?>;
var defaultLayerMiniMap_<?php echo $id;?>=<?php echo $defaultLayer;?>;
var featureGroup_<?php echo $id;?>=new L.FeatureGroup();var map_scale_<?php echo $id;?>=L.control.scale({position:'bottomleft',maxWidth:100,metric:true,imperial:true,updateWhenIdle:false});var baseLayers_<?php echo $id;?>={<?php echo $baseLayers?>};var overlays_<?php echo $id;?>={"Map Points":featureGroup_<?php echo $id;?>};var layerSelector_<?php echo $id;?>=L.control.layers(baseLayers_<?php echo $id;?>,overlays_<?php echo $id;?>);map_<?php echo $id;?>=new L.Map("map_<?php echo $id; ?>",{dragging:true,touchZoom:true,scrollWheelZoom:true,doubleClickZoom:true,boxzoom:true,trackResize:true,worldCopyJump:false,closePopupOnClick:true,keyboard:true,keyboardPanOffset:80,keyboardZoomOffset:1,inertia:true,inertiaDeceleration:3000,inertiaMaxSpeed:1500,zoomControl:true,crs:L.CRS.EPSG3857,layers:[defaultLayer_<?php echo $id;?>,featureGroup_<?php echo $id;?>]});map_<?php echo $id;?>.setView([<?php echo $first_lat;?>,<?php echo $first_lng;?>],<?php echo $carta->zoom;?>);L.control.fullscreen().addTo(map_<?php echo $id;?>);
L.control.locate({position:'bottomright',drawCircle:true,follow:true,setView:true,keepCurrentZoomLevel:true,remainActive:false,circleStyle:{},markerStyle:{},followCircleStyle:{},followMarkerStyle:{},icon:'icon-cross-hairs',circlePadding:[0,0],metric:true,showPopup:true,strings:{title:'I am Here',popup:'You are within {distance} {unit} from this point',outsideMapBoundsMsg:'You seem located outside the boundaries of the map'},locateOptions:{watch:true}}).addTo(map_<?php echo $id;?>);
featureGroup_<?php echo $id;?>.addTo(map_<?php echo $id;?>);
map_scale_<?php echo $id;?>.addTo(map_<?php echo $id;?>);
var jsonData_<?php echo $id;?>=L.geoJson(null,{style:function(feature){return{color:"#f06eaa","weight":4,"opacity":0.5,"fillOpacity":0.2};},onEachFeature:function(feature,layer){featureGroup_<?php echo $id;?>.addLayer(layer);properties1=feature.properties;var properties=new Array();for(var i=0;i<properties1.length;i++){row={};row['name']=properties1[i].name;row['value']=properties1[i].value;row['defaultProperty']=properties1[i].defaultProperty;properties.push(row);}
layerProperties.push(new Array(layer,properties));console.log(properties);var style=feature.style;var cp=feature.customProperties;if(style){if(layer instanceof L.Marker){if(style.markerColor){layer.setIcon(L.AwesomeMarkers.icon(style));}}
else{layer.setStyle(style);}}
shapeStyles.push(style);shapeCustomProperties.push(cp);renderSideBar_<?php echo $id;?>(layer);bindPopup(layer);}})
jQuery('#map_<?php echo $id; ?> .leaflet-top.leaflet-left').append('<div id="sidebarhideshow_<?php echo $id; ?>" class="leaflet-control-sidebar leaflet-bar leaflet-control" style="z-index:10;">'+'<a class="leaflet-control-sidebar-button leaflet-bar-part" id="sidebar-button-reorder_<?php echo $id; ?>" href="#" onClick="return false;" title="Sidebar Toggle"><i class="fa fa-reorder"></i></a>'+'<div id="sidebar-buttons_<?php echo $id; ?>" class="sidebar-buttons" style="max-height: 300px; overflow: auto;">'+'<ul class="list-unstyled leaflet-sidebar">'+'</ul>'+'</div>'+'</div>');function renderSideBar_<?php echo $id;?>(layer){if(show_sidebar_<?php echo $id;?>)
jQuery('#sidebarhideshow_<?php echo $id; ?>').show();else{jQuery('#sidebarhideshow_<?php echo $id; ?>').hide();}
target=jQuery('#sidebar-buttons_<?php echo $id; ?> ul.leaflet-sidebar');currentIndex=getLayerIndex(layer);lable=layerProperties[currentIndex][1][0].value;if(lable==""){lable="No Location";}
target.append('<li><input type="checkbox" data-index="'+currentIndex+'" onClick="changeAddressCheckbox_<?php echo $id; ?>(this)" checked><a data-index="'+currentIndex+'" onClick="clickOnSidebarAddress_<?php echo $id; ?>(this)">'+lable+'</a><div class="clear"></div></li>');}
var animating=false;jQuery('#sidebar-button-reorder_<?php echo $id; ?>').click(function(){if(animating)return;var element=jQuery('#sidebar-buttons_<?php echo $id; ?>');animating=true;if(element.css('left')=='-50px'){element.show();element.animate({opacity:'1',left:'0px'},400,function(){animating=false;});}
else{element.animate({opacity:'0',left:'-50px'},400,function(){animating=false;element.hide();});}});function changeAddressCheckbox_<?php echo $id;?>(obj){var layers=getLayers();console.log(layers);index=jQuery(obj).attr("data-index");console.log(index);if(jQuery(obj).is(':checked')){featureGroup_<?php echo $id;?>.addLayer(layers[index]);}
else{featureGroup_<?php echo $id;?>.removeLayer(layers[index]);}}
function clickOnSidebarAddress_<?php echo $id;?>(obj){var layers=getLayers();index=jQuery(obj).attr("data-index");setTimeout(function(){layers[index].openPopup();},50);}

<?php if ($carta->show_minimap) : ?>
new L.Control.MiniMap(defaultLayerMiniMap_<?php echo $id;?>, {toggleDisplay: true}).addTo(map_<?php echo $id;?>)._minimize(true);
jQuery('.leaflet-control-minimap .leaflet-control-sidebar, .leaflet-control-minimap #edit_image_overlays').remove();
<?php endif; ?>

<?php if ($carta->show_measure) : ?>
setTimeout(function() {
	map_<?php echo $id;?>.addControl(L.Control.measureControl({position:'topright'}));
}, 500);
<?php endif; ?>

/*<!-- Legend -->*/
	var isLegendEnable_<?php echo $id;?>  = <?PHP echo ($carta->show_legend) ? "true" : "false" ?>;
	var legendContent_<?php echo $id;?>   = jQuery("#textarea_legend_content_<?php echo $id;?>").html();
	
	jQuery(document).ready(function() {
		jQuery('.leaflet-objects-pane img').css('background', 'transparent');
		
		jQuery('#map_<?php echo $id;?>').append('\
			<div class="overlay_container" style="z-index: 2; position: absolute; right: 0; left: 0; bottom: 10px;">\
				<div id="pnlLegend_<?php echo $id;?>" class="panel" style="margin-bottom:0; display: none; font-size: 14px;">\
					<div id="legendContainer_<?php echo $id;?>">\
						\
					</div>\
				</div>\
				<a id="trgrLegend_<?php echo $id;?>" class="trigger" href="#">Legend</a>\
			</div>\
		');
		
		legendUpdate_<?php echo $id;?>();
		
		jQuery('#trgrLegend_<?php echo $id;?>').click(function(){
			jQuery('#pnlLegend_<?php echo $id;?>').toggle('fast');
			jQuery(this).toggleClass('active');
			return false;
		});
	});
	
	function legendUpdate_<?php echo $id;?>() {
		if(isLegendEnable_<?php echo $id;?>) {
			jQuery('#trgrLegend_<?php echo $id;?>').show();
		}
		else {
			jQuery('#trgrLegend_<?php echo $id;?>, #pnlLegend_<?php echo $id;?>').hide();
		}
		
		jQuery('#legendContainer_<?php echo $id;?>').html(jQuery('<textarea/>').html(legendContent_<?php echo $id;?>).val());
	}


var imageOverlays_<?php echo $id;?>   = JSON.parse(jQuery("#geo_image_olverlays_<?php echo $id;?>").val());
var imageOverlaysLayers_<?php echo $id;?> = [];
var imageOverlaysPopups_<?php echo $id;?> = [];
var globalTempI = 0;

function imageOverlaysUpdate_<?php echo $id;?>() {
	var imageBounds = null;
	
	jQuery.each(imageOverlaysLayers_<?php echo $id;?>, function(key, value) {
		map_<?php echo $id;?>.removeLayer(value);
	});
	
	imageOverlaysLayers_<?php echo $id;?> = [];
	imageOverlaysPopups_<?php echo $id;?> = [];
	
	jQuery.each(imageOverlays_<?php echo $id;?>, function(key, value) {
		var imageUrl = value.src;
		var pcon = value.popupcontent;
		// This is the trickiest part - you'll need accurate coordinates for the
		// corners of the image. You can find and create appropriate values at
		// http://maps.nypl.org/warper/ or
		// http://www.georeferencer.org/
		imageBounds = L.latLngBounds(JSON.parse(value.bounds));
		
		// See full documentation for the ImageOverlay type:
		// http://leafletjs.com/reference.html#imageoverlay
		var overlay = L.imageOverlay(imageUrl, imageBounds)
			.addTo(map_<?php echo $id;?>);
		
		var popup = L.popup().setContent(pcon);
		popup.setLatLng([imageBounds.getCenter().lat,imageBounds.getCenter().lng]);
		imageOverlaysPopups_<?php echo $id;?>.push(popup);
		
		L.DomEvent.on(overlay._image, 'click', function(e) {
			globalTempI = 0;
			var dis = this;
			jQuery.each(imageOverlaysLayers_<?php echo $id;?>, function(k, v) {
				if(dis == v._image) {
					setTimeout(function(){
						imageOverlaysPopups_<?php echo $id;?>[globalTempI].addTo(map_<?php echo $id;?>);
					}, 100);
					return false;
				}
				globalTempI++;
			});
		});
		
		imageOverlaysLayers_<?php echo $id;?>.push(overlay);
	});
}
imageOverlaysUpdate_<?php echo $id;?>();
</script>
<style>
.panel#pnlLegend_<?php echo $id;?> {
    z-index: 10;
	font-size: 14px !important;
    bottom: 60px;
    position: absolute;
    right: 0;
    display: none;
    color: #FFF;
    background: #000;
    border: 4px solid #676767;
    border-right: 0;
    -moz-border-radius-topleft: 20px;
    -webkit-border-top-left-radius: 20px;
    -moz-border-radius-bottomleft: 20px;
    -webkit-border-bottom-left-radius: 20px;
    width: 270px;
    height: auto;
    padding: 8px 0px 60px 20px;
    filter: alpha(opacity=95);
    opacity: .95;
    cursor: default;
}
a.trigger#trgrLegend_<?php echo $id;?> {
    bottom: 60px;
    right: 0px;
}
a.trigger {
    z-index: 11;
    position: absolute;
    text-decoration: none;
    font-size: 17px;
    line-height: 16px;
    color: #FFF;
    padding: 12px 7px 12px 13px;
    font-weight: 700;
    background: #676767;
    border: 4px solid #676767;
    border-right: 0;
    -moz-border-radius-topleft: 20px;
    -webkit-border-top-left-radius: 20px;
    -moz-border-radius-bottomleft: 20px;
    -webkit-border-bottom-left-radius: 20px;
    display: block;
}
a.trigger:hover {
    position: absolute;
    text-decoration: none;
    font-size: 17px;
    color: #FFF;
    padding: 12px 12px 12px 13px;
    font-weight: 700;
    background: #676767;
    border: 4 solid #676767;
    border-right: 0;
    -moz-border-radius-topleft: 20px;
    -webkit-border-top-left-radius: 20px;
    -moz-border-radius-bottomleft: 20px;
    -webkit-border-bottom-left-radius: 20px;
    display: block;
}
div.overlay_container {
    opacity: 0.75;
}
</style>



<?php if (!$notFirstmap) :?>

<script type="text/javascript" src="<?php echo admin_url("../") ?>plugins/Carta/js/helper.js"></script>

<?php endif; ?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        layerSelector_<?php echo $id; ?>.addTo(map_<?php echo $id; ?>);
        jQuery('#map_<?php echo $id; ?> .leaflet-control-layers form.leaflet-control-layers-list input[type=radio]').click(function(){
                map_<?php echo $id; ?>.removeLayer(defaultLayer_<?php echo $id; ?>);
            });
    });
 <?php if (isset($carta) && count($carta->pointers) > 0) : ?>
    var data_<?php echo $id; ?> = <?php echo unserialize(base64_decode($carta->pointers)); ?>;
    jsonData_<?php echo $id; ?>.addData(data_<?php echo $id; ?>); 

<?php endif; ?>
</script>

<?php else : ?>
    <p>Carta shortcode not exists.</p>
<?php endif; ?>