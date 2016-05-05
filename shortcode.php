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

$id=$jId;
?>

<div id="map_<?php echo $id; ?>" style="width:<?php echo $carta->width; ?>px;height:<?php echo $carta->height; ?>px"></div>


<?php if (!$notFirstmap) : ?>
<link rel="stylesheet" href="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.awesome-markers.css" />
<link rel="stylesheet" href="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" />

<script type="text/javascript" src="<?php echo admin_url("../") ?>plugins/Carta/js/modal.js"></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.2/js/bootstrap-dialog.js'></script>
<link href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.2/css/bootstrap-dialog.css' rel='stylesheet' />

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

<script src='https://cdn.mapfig.com/mapfig-cdn/leaflet.markercluster.js'></script>
<link href='https://cdn.mapfig.com/mapfig-cdn/MarkerCluster.css' rel='stylesheet' />
<link href='https://cdn.mapfig.com/mapfig-cdn/MarkerCluster.Default.css' rel='stylesheet' />

<link href='<?php echo admin_url("../") ?>plugins/Carta/css/carta.css' rel='stylesheet' />
<?php  endif; ?>

<textarea style="display:none;" id="geo_image_olverlays_<?php echo $id;?>"><?php echo ($carta->geo_image_olverlays) ? base64_decode($carta->geo_image_olverlays) : "[]"; ?></textarea>
<div style="display:none;" id="textarea_legend_content_<?php echo $id;?>"><?php echo ($carta->legend_content) ? base64_decode($carta->legend_content) : ""; ?></div>

<script>
var mbAttribution_<?php echo $id;?>=' contributors | <a href="https://www.mapfig.com" target="_blank">Mapfig</a>';var show_sidebar_<?php echo $id;?>=<?php echo ($carta->show_sidebar) ? "true" : "false"; ?>;var mapOverlays_<?php echo $id;?>=new L.LayerGroup();
var defaultLayer_<?php echo $id;?>=<?php echo $defaultLayer;?>;
var defaultLayerMiniMap_<?php echo $id;?>=<?php echo $defaultLayer;?>;
var featureGroup_<?php echo $id;?> = (<?PHP echo ($carta->show_cluster) ? "true" : "false"; ?>) ? new L.MarkerClusterGroup() : new L.FeatureGroup();
//var featureGroup_<?php echo $id;?>=new L.FeatureGroup();
var map_scale_<?php echo $id;?>=L.control.scale({position:'bottomleft',maxWidth:100,metric:true,imperial:true,updateWhenIdle:false});var baseLayers_<?php echo $id;?>={<?php echo $baseLayers?>};var overlays_<?php echo $id;?>={"Map Points":featureGroup_<?php echo $id;?>};var layerSelector_<?php echo $id;?>=L.control.layers(baseLayers_<?php echo $id;?>,overlays_<?php echo $id;?>);map_<?php echo $id;?>=new L.Map("map_<?php echo $id; ?>",{dragging:true,touchZoom:true,scrollWheelZoom:true,doubleClickZoom:true,boxzoom:true,trackResize:true,worldCopyJump:false,closePopupOnClick:true,keyboard:true,keyboardPanOffset:80,keyboardZoomOffset:1,inertia:true,inertiaDeceleration:3000,inertiaMaxSpeed:1500,zoomControl:true,crs:L.CRS.EPSG3857,layers:[defaultLayer_<?php echo $id;?>,featureGroup_<?php echo $id;?>]});map_<?php echo $id;?>.setView([<?php echo $first_lat;?>,<?php echo $first_lng;?>],<?php echo $carta->zoom;?>);L.control.fullscreen().addTo(map_<?php echo $id;?>);
L.control.locate({position:'bottomright',drawCircle:true,follow:true,setView:true,keepCurrentZoomLevel:true,remainActive:false,circleStyle:{},markerStyle:{},followCircleStyle:{},followMarkerStyle:{},icon:'icon-cross-hairs',circlePadding:[0,0],metric:true,showPopup:true,strings:{title:'I am Here',popup:'You are within {distance} {unit} from this point',outsideMapBoundsMsg:'You seem located outside the boundaries of the map'},locateOptions:{watch:true}}).addTo(map_<?php echo $id;?>);
featureGroup_<?php echo $id;?>.addTo(map_<?php echo $id;?>);
map_scale_<?php echo $id;?>.addTo(map_<?php echo $id;?>);
var jsonData_<?php echo $id;?>=L.geoJson(null,{style:function(feature){return{color:"#f06eaa","weight":4,"opacity":0.5,"fillOpacity":0.2};},onEachFeature:function(feature,layer){featureGroup_<?php echo $id;?>.addLayer(layer);
layer.on("click", function(){
	openPopup_<?php echo $id; ?>(layer);
});
properties1=feature.properties;var properties=new Array();for(var i=0;i<properties1.length;i++){row={};row['name']=properties1[i].name;row['value']=properties1[i].value;row['defaultProperty']=properties1[i].defaultProperty;properties.push(row);}
layerProperties_<?php echo $id; ?>.push(new Array(layer,properties));var style=feature.style;var cp=feature.customProperties;if(style){if(layer instanceof L.Marker){if(style.markerColor){layer.setIcon(L.AwesomeMarkers.icon(style));}}
else{layer.setStyle(style);}}
shapeStyles_<?php echo $id; ?>.push(style);shapeCustomProperties_<?php echo $id; ?>.push(cp);renderSideBar_<?php echo $id;?>(layer);bindPopup_<?php echo $id; ?>(layer);}})
jQuery('#map_<?php echo $id; ?> .leaflet-top.leaflet-left').append('<div id="sidebarhideshow_<?php echo $id; ?>" class="leaflet-control-sidebar leaflet-bar leaflet-control" style="z-index:10;">'+'<a class="leaflet-control-sidebar-button leaflet-bar-part" id="sidebar-button-reorder_<?php echo $id; ?>" href="#" onClick="return false;" title="Sidebar Toggle"><i class="fa fa-reorder"></i></a>'+'<div id="sidebar-buttons_<?php echo $id; ?>" class="sidebar-buttons" style="max-height: 300px; overflow: auto;">'+'<ul class="list-unstyled leaflet-sidebar">'+'</ul>'+'</div>'+'</div>');
jQuery('#map_<?php echo $id; ?> .leaflet-top.leaflet-left').append('<div id="edit_image_overlays_<?php echo $id; ?>" class="leaflet-control-sidebar leaflet-bar leaflet-control" style="z-index:10;"><a class="leaflet-control-sidebar-button leaflet-bar-part" href="#" onclick="return false;" title="Set Image Overlay Opacity"><i class="fa fa-image"></i></a></div>');
function renderSideBar_<?php echo $id;?>(layer){if(show_sidebar_<?php echo $id;?>)
jQuery('#sidebarhideshow_<?php echo $id; ?>').show();else{jQuery('#sidebarhideshow_<?php echo $id; ?>').hide();} if(!layer) return;
target=jQuery('#sidebar-buttons_<?php echo $id; ?> ul.leaflet-sidebar');currentIndex=getLayerIndex_<?php echo $id; ?>(layer);lable=layerProperties_<?php echo $id; ?>[currentIndex][1][0].value;if(lable==""){lable="No Location";}
target.append('<li><input type="checkbox" data-index="'+currentIndex+'" onClick="changeAddressCheckbox_<?php echo $id; ?>(this)" checked><a data-index="'+currentIndex+'" onClick="clickOnSidebarAddress_<?php echo $id; ?>(this)">'+lable+'</a><div class="clear"></div></li>');}
var animating=false;jQuery('#sidebar-button-reorder_<?php echo $id; ?>').click(function(){if(animating)return;var element=jQuery('#sidebar-buttons_<?php echo $id; ?>');animating=true;if(element.css('left')=='-50px'){element.show();element.animate({opacity:'1',left:'0px'},400,function(){animating=false;});}
else{element.animate({opacity:'0',left:'-50px'},400,function(){animating=false;element.hide();});}});function changeAddressCheckbox_<?php echo $id;?>(obj){var layers=getLayers_<?php echo $id; ?>();console.log(layers);index=jQuery(obj).attr("data-index");console.log(index);if(jQuery(obj).is(':checked')){featureGroup_<?php echo $id;?>.addLayer(layers[index]);}
else{featureGroup_<?php echo $id;?>.removeLayer(layers[index]);}}
function clickOnSidebarAddress_<?php echo $id;?>(obj){var layers=getLayers_<?php echo $id; ?>();index=jQuery(obj).attr("data-index");setTimeout(function(){layers[index].openPopup();},50);}

<?php if ($carta->show_minimap) : ?>
new L.Control.MiniMap(defaultLayerMiniMap_<?php echo $id;?>, {toggleDisplay: true}).addTo(map_<?php echo $id;?>)._minimize(true);
jQuery('.leaflet-control-minimap .leaflet-control-sidebar, .leaflet-control-minimap #edit_image_overlays_<?php echo $id; ?>').remove();
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
		renderSideBar_<?php echo $id;?>();
		
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
		
		if(!value.opacity) {
			value.opacity = 1;
		}
		jQuery(overlay._image).css('opacity', value.opacity);
		
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
setTimeout(function() {
	imageOverlaysUpdate_<?php echo $id;?>();
}, 500);




jQuery(document).ready(function($) {
	jQuery("#edit_image_overlays_<?php echo $id; ?>").click(function(e) {
		e.preventDefault();
		
		var overlays = '\
			<div class="table-responsive" id="image_overlays_modal_<?php echo $id; ?>">\
				<table class="table table-striped table-bordered table-hover">\
					<thead>\
						<tr>\
							<th style="display: none;">\
								Overlay Image Name\
							</th>\
							<th>\
								Overlay Image\
							</th>\
							<th>\
								Set Opacity\
							</th>\
							<th style="display: none;">\
								Bounds\
							</th>\
							<th style="display: none;">\
								Pop-Up Contents\
							</th>\
						</tr>\
					</thead>\
					<tbody>\
						\
					</tbody>\
				</table>\
				<div style="clear: both;"></div>\
			</div>\
		';
		
		BootstrapDialog.show({
			title: 'Set Image Overlays Opacity',
			message: overlays,
			closable: false,
			buttons: [{
				label: 'Save',
				icon: 'fa fa-check',
				cssClass: 'btn-primary',
				action: function(dialog) {
					imageOverlays_<?php echo $id; ?> = [];
					jQuery('#image_overlays_modal_<?php echo $id; ?> tbody tr').each(function(index, obj) {
						temp = {};
						temp['name'] = jQuery(this).find('.image_overlays_name').val();
						temp['src'] = jQuery(this).find('.image_overlays_img').attr('src');
						temp['opacity'] = jQuery(this).find('.image_overlays_opacity_input').val();
						temp['bounds'] = jQuery(this).find('.image_overlays_bounds_input').val();
						temp['popupcontent'] = jQuery(this).find('.image_overlays_popupcontent_input').html();
						
						imageOverlays_<?php echo $id; ?>.push(temp);
					});
					
					imageOverlaysUpdate_<?php echo $id; ?>(true);
					dialog.close();
					jQuery("body").removeClass("modal-open");
				}
			}, {
				label: 'Cancel',
				icon: 'fa fa-remove',
				cssClass: '',
				action: function(dialog) {
					dialog.close();
					jQuery("body").removeClass("modal-open");
				}
			}]
		});
		
		setTimeout(function(){
			jQuery.each(imageOverlays_<?php echo $id; ?>, function(key, value) {
				jQuery('#image_overlays_modal_<?php echo $id; ?> tbody').append('\
					<tr>\
						<td style="display: none;">\
							<input type="text" class="form-control image_overlays_name" value="'+value.name+'"/>\
						</td>\
						<td>\
							<img src="'+value.src+'" class="image_overlays_img" style="height:60px;width:100px" alt="'+value.name+'" title="'+value.name+'"/>\
						</td>\
						<td>\
							<select class="image_overlays_opacity_input">\
								<option value="0.05" '+ ((value.opacity == "0.05") ? "selected" : "") +'>0.05</option>\
								<option value="0.10" '+ ((value.opacity == "0.10") ? "selected" : "") +'>0.10</option>\
								<option value="0.15" '+ ((value.opacity == "0.15") ? "selected" : "") +'>0.15</option>\
								<option value="0.20" '+ ((value.opacity == "0.20") ? "selected" : "") +'>0.20</option>\
								<option value="0.25" '+ ((value.opacity == "0.25") ? "selected" : "") +'>0.25</option>\
								<option value="0.30" '+ ((value.opacity == "0.30") ? "selected" : "") +'>0.30</option>\
								<option value="0.35" '+ ((value.opacity == "0.35") ? "selected" : "") +'>0.35</option>\
								<option value="0.40" '+ ((value.opacity == "0.40") ? "selected" : "") +'>0.40</option>\
								<option value="0.45" '+ ((value.opacity == "0.45") ? "selected" : "") +'>0.45</option>\
								<option value="0.50" '+ ((value.opacity == "0.50") ? "selected" : "") +'>0.50</option>\
								<option value="0.55" '+ ((value.opacity == "0.55") ? "selected" : "") +'>0.55</option>\
								<option value="0.60" '+ ((value.opacity == "0.60") ? "selected" : "") +'>0.60</option>\
								<option value="0.65" '+ ((value.opacity == "0.65") ? "selected" : "") +'>0.65</option>\
								<option value="0.70" '+ ((value.opacity == "0.70") ? "selected" : "") +'>0.70</option>\
								<option value="0.75" '+ ((value.opacity == "0.75") ? "selected" : "") +'>0.75</option>\
								<option value="0.80" '+ ((value.opacity == "0.80") ? "selected" : "") +'>0.80</option>\
								<option value="0.85" '+ ((value.opacity == "0.85") ? "selected" : "") +'>0.85</option>\
								<option value="0.90" '+ ((value.opacity == "0.90") ? "selected" : "") +'>0.90</option>\
								<option value="0.95" '+ ((value.opacity == "0.95") ? "selected" : "") +'>0.95</option>\
								<option value="1" '+ ((value.opacity == "1") ? "selected" : "") +'>1</option>\
								<option></option>\
							</select>\
						</td>\
						<td style="display: none;">\
							<a href="#" onClick="return image_overlays_bounds_click(this)" class="btn btn-info"><i class="fa fa-edit"></i> Set Image Coordinates</a>\
							<input type="hidden" class="image_overlays_bounds_input" value="'+value.bounds+'"/>\
						</td>\
						<td style="display: none;">\
							<a href="#" onClick="return image_overlays_popupcontent_click(this)" class="btn btn-info"><i class="fa fa-edit"></i> Set Pop-Up Content</a>\
							<div style="display:none;" class="image_overlays_popupcontent_input">'+value.popupcontent+'</div>\
						</td>\
					</tr>\
				');
			});
		}, 200);
	});
});



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
#map_<?php echo $id;?> img {
	background: transparent !important;
}
</style>








<script>
	function staticSidebarPopupResize_<?php echo $id;?>() {
		m_height = jQuery("#map_<?php echo $id;?>").height();
		height = m_height-40;
		
		jQuery('#static-popup_<?php echo $id;?>').css('max-height',height).css('min-width',250);
	}
	
	jQuery(document).ready(function($) {
		jQuery("#map_<?php echo $id;?>").append('\
			<div class="bubble static bound selected" id="static-popup_<?php echo $id;?>" style="display: none; font-size: 14px;">\
				<a name="close" class="close" id="static-popup-close_<?php echo $id;?>" onClick="mapClosePopup_<?php echo $id;?>();"><i class="fa fa-close"></i></a>\
				<div class="content body" rv-html="record:body" rv-show="record:body" id="static-popup-content_<?php echo $id;?>"></div>\
			</div>\
		');
		staticSidebarPopupResize_<?php echo $id;?>();
	});
</script>
<style>
.close {
    float: right;
    font-size: 21px;
    font-weight: bold;
    line-height: 1;
    color: #000000;
    text-shadow: 0 1px 0 #ffffff;
    opacity: 0.2;
    filter: alpha(opacity=20);
}
.close:hover, .close:focus {
    color: #000000;
    text-decoration: none;
    cursor: pointer;
    opacity: 0.5;
    filter: alpha(opacity=50);
}
.bubble.static {
	z-index: 1005;
	overflow-y: auto;
	position: absolute;
	background: #fff;
	border-radius: 2px;
	color: #000;
	padding: 14px;
	max-height: 90%;
	max-width: 400px;
	left: 70px;
	top: 20px;
	opacity: .85;
}
.bubble.static.selected {
	opacity: .9;
}
.bubble.static.bound {
	display: block;
}
.bubble.static .title {
	display: inline;
	font-size: 16px;
	line-height: 1em;
}
.bubble.static .content {
	margin-top: .7em;
}
</style>











<script>
var layerProperties_<?php echo $id; ?> = new Array();
var shapeStyles_<?php echo $id; ?> = new Array();
var shapeCustomProperties_<?php echo $id; ?> = new Array();

function getPropertiesByLayer_<?php echo $id; ?>(layer) {
    for (i = 0; i < layerProperties_<?php echo $id; ?>.length; i++) {
        if (layerProperties_<?php echo $id; ?>[i][0] == layer) {
            return layerProperties_<?php echo $id; ?>[i][1];
        }
    }
    return {};
}

function bindPopup_<?php echo $id; ?>(layer) {
    popupContent = getPopupContent_<?php echo $id; ?>(layer)
    layer.bindPopup(popupContent);
}

function getPopupContent_<?php echo $id; ?>(layer) {
    popupContent = "";
    properties = getPropertiesByLayer_<?php echo $id; ?>(layer);
    customProperties = getCustomPropertiesByLayer_<?php echo $id; ?>(layer);
    jQuery.each(properties, function(index, property) {
        if (!customProperties.hide_label) {
            var label = property.name;
            if (property.name == "Name") {
                label = "Location";
            }
            popupContent += '<b>' + label + '</b> : ';
        }
        if (customProperties.show_address_on_popup) {
            if (property.name == "Name") {
				if(customProperties.bootstrap_popup) {
					popupContent += '<b class="title" id="static-popup-title">' + property.value + '</b><br/>';
				}
				else {
					popupContent += '<b>' + property.value + '</b><br/>';
				}
                return true;
            }
        }
        if(property.name != "Name") {
			popupContent += property.value+'<br/>';
		}
    });
    if (customProperties.get_direction) {
        jQuery.each(properties, function(index, property) {
            if (property.name == "Name") {
                address = property.value;
                popupContent += '<a href="https://www.google.com/maps/dir//' + address + '" target="_blank">Get Direction</a>';
                return false;
            }
        });
    }
    return popupContent;
}

function getCustomPropertiesByLayer_<?php echo $id; ?>(layer) {
    for (i = 0; i < layerProperties_<?php echo $id; ?>.length; i++) {
        if (layerProperties_<?php echo $id; ?>[i][0] == layer) {
            return shapeCustomProperties_<?php echo $id; ?>[i];
        }
    }
    return {};
}

function getLayerIndex_<?php echo $id; ?>(layer) {
    for (i = 0; i < layerProperties_<?php echo $id; ?>.length; i++) {
        if (layerProperties_<?php echo $id; ?>[i][0] == layer) {
            return i;
        }
    }
}

function getLayers_<?php echo $id; ?>() {
    layers = new Array();
    for (i = 0; i < layerProperties_<?php echo $id; ?>.length; i++) {
        layers.push(layerProperties_<?php echo $id; ?>[i][0]);
    }
    return layers;
}

function clickOnSidebarAddress_<?php echo $id; ?>(obj) {
    var layers = getLayers_<?php echo $id; ?>();
    index = jQuery(obj).parent().index();
    setTimeout(function() {
        layers[index].openPopup();
        openPopup_<?php echo $id; ?>(layers[index]);
    }, 50);
}

function openPopup_<?php echo $id; ?>(layer) {
	mapClosePopup_<?php echo $id; ?>();
    index = getLayerIndex_<?php echo $id; ?>(layer);
	
    if (shapeCustomProperties_<?php echo $id; ?>[index].bootstrap_popup) {
        setTimeout(function() {
            map_<?php echo $id; ?>.closePopup();
            mapOpenPopup_<?php echo $id; ?>(layer);
        }, 50);
    }
}

function mapClosePopup_<?php echo $id; ?>() {
	jQuery('#static-popup_<?php echo $id; ?>').fadeOut();
}
function mapOpenPopup_<?php echo $id; ?>(layer) {
	popupContent = getPopupContent_<?php echo $id; ?>(layer)
	jQuery('#static-popup-content_<?php echo $id; ?>').html(popupContent);
	jQuery('#static-popup_<?php echo $id; ?>').fadeIn();
}
</script>












<?php if (!$notFirstmap) :?>

<!-- <script type="text/javascript" src="<?php echo admin_url("../") ?>plugins/Carta/js/helper.js"></script>-->

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