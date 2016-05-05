<?php
echo head(array('title'=>'Carta', 'bodyclass'=>'carta browse'));

if (count($defaultLayer) > 0){
    $defaultLayer = "L.tileLayer('" . $defaultLayer->url ."', {maxZoom: 18, id: '". $defaultLayer->key . "', attribution:'" . html_entity_decode($defaultLayer->attribution) . "' + mbAttribution})";
}

$first_lat = ($carta->latitude) ? $carta->latitude : 0;
$first_lng = ($carta->longitude) ? $carta->longitude : 0;

if(!$carta) {
	$carta = (object) array();
}

if(!isset($carta->show_measure)) {
	$carta->show_measure = true;
}
if(!isset($carta->show_sidebar)) {
	$carta->show_sidebar = true;
}
if(!isset($carta->show_minimap)) {
	$carta->show_minimap = true;
}
if(!isset($carta->show_cluster)) {
	$carta->show_cluster = false;
}
?>
<script src="<?php echo admin_url("../") ?>plugins/Carta/js/tinymce/js/tinymce/tinymce.min.js"></script>

<link rel="stylesheet" href="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.css" />
<script src="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.js"></script>

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

<link href="https://cdn.mapfig.com/mapfig-cdn/global-custom.css" rel="stylesheet">

<link href='<?php echo admin_url("../") ?>plugins/Carta/css/carta.css' rel='stylesheet' />
<script type="text/javascript" src="<?php echo admin_url("../") ?>plugins/Carta/js/modal.js"></script>
<script type="text/javascript" src="<?php echo admin_url("../") ?>plugins/Carta/js/tab.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" />

<link rel="stylesheet" href="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.awesome-markers.css" />
<script src="<?php echo admin_url("../") ?>plugins/Carta/leaflet/dist/leaflet.awesome-markers.js"></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.2/js/bootstrap-dialog.js'></script>
<link href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.2/css/bootstrap-dialog.css' rel='stylesheet' />

<script src="<?php echo admin_url("../") ?>plugins/Carta/js/tinymce/js/tinymce/tinymce.min.js"></script>

<link href='<?php echo admin_url("../") ?>plugins/Carta/css/colpick.css' rel='stylesheet' />
<script type="text/javascript" src="<?php echo admin_url("../") ?>plugins/Carta/js/colpick.js"></script>
<form action="<?php echo url("carta/index/save"); ?>" id="save_map_form" method="post">
    <input type="hidden" value="" id="geo_json_str" name="geo_json_str">
    <input type="hidden" name="geo_image_olverlays">
    <textarea style="display:none;" id="geo_image_olverlays" ><?php echo ($carta->geo_image_olverlays) ? base64_decode($carta->geo_image_olverlays) : "[]"; ?></textarea>
	<input type="hidden" value="<?php echo $carta_id; ?>" name="carta_id">
    <input type="hidden" id="carta_type" name="carta_type">
    <p>
        <label for="">Name</label> 
        <input type="text" name="carta_name" value="<?php echo (isset($carta->name)) ? $carta->name : 'carta name'; ?>">
    </p>
    <div class="carta_slider_container">
        <label for="">Width</label> 
        <input type="hidden" name="carta_width" id="carta_width" value="<?php echo (isset($carta->width)) ? $carta->width : '600'; ?>">
        <div id="carta_width_slider" class="slider"></div> 
        <div id="carta_width_value"><?php echo (isset($carta->width)) ? $carta->width : '600'; ?></div>
    </div>
    <div class="carta_slider_container">
        <label for="">Height</label> 
        <input type="hidden" name="carta_height" id="carta_height" value="<?php echo (isset($carta->height)) ? $carta->height : '500'; ?>">
        <div id="carta_height_slider" class="slider" style=""></div> 
        <div id="carta_height_value" style=""><?php echo (isset($carta->height)) ? $carta->height : '500'; ?></div>
    </div>    
    <div class="carta_slider_container">
        <label for="">Zoom</label> 
        <input type="hidden" name="carta_zoom"  id="carta_zoom"  value="<?php echo (isset($carta->zoom)) ? $carta->zoom : '2'; ?>">
        <div id="carta_zoom_slider" class="slider"></div> 
        <div id="carta_zoom_value"><?php echo (isset($carta->zoom)) ? $carta->zoom : '2'; ?></div>
    </div>
    
    <div>
        <label for="">Show Sidebar</label> 
        <input type="checkbox" name="show_sidebar"  id="show_sidebar"  <?php echo ($carta->show_sidebar) ? 'checked' : ''; ?> style="width: auto;">
    </div>
	<br>
    <div>
        <label for="">Show Mini Map</label> 
        <input type="checkbox" name="show_minimap"  id="show_minimap"  <?php echo ($carta->show_minimap) ? 'checked' : ''; ?> style="width: auto;">
    </div>
	<br>
    <div>
        <label for="">Show Distance Measure</label> 
        <input type="checkbox" name="show_measure"  id="show_measure"  <?php echo ($carta->show_measure) ? 'checked' : ''; ?> style="width: auto;">
    </div>
	<br>
    <div>
        <label for="">Show Clusters</label> 
        <input type="checkbox" name="show_cluster"  id="show_cluster"  <?php echo ($carta->show_cluster) ? 'checked' : ''; ?> style="width: auto;">
    </div>
	<br>
    <div>
        <label for="">Show Legend</label> 
        <input type="checkbox" name="show_legend"  id="show_legend"  <?php echo ($carta->show_legend) ? 'checked' : ''; ?> style="width: auto;">
		<a href="#" class="btn btn-info btn-sm" id="edit_legend"><i class="fa fa-edit"></i> Edit Legend Content</a>
        <input type="hidden" name="legend_content" id="legend_content">
    </div>
    <br>
     <p>
        <label for="">Baselayer</label> 
         <select name="baselayer" id="">
            <?php foreach ($baselayer  as $b) : if (!isset($b->is_deleted)) :  ?>
                <option value="<?php echo $b->id; ?>" <?php if (isset($carta) && $carta->baselayer == $b->id) echo 'selected="selected"';  ?>><?php echo $b->name; ?></option>
            <?php endif; endforeach; ?>
        </select>
    </p>

    <p>
        <label for="">Layer group</label> 
        <select name="layer_group" id="" >
            <option value="">Select</option>
            <?php foreach ($layerGroup  as $l) : if (!isset($l->is_deleted)) :  ?>
                <option value="<?php echo $l->id; ?>" <?php if (isset($carta) && $carta->layergroup == $l->id) echo 'selected="selected"'; ?>><?php echo $l->name; ?></option>
            <?php endif; endforeach; ?>
        </select>
    </p>    
	
	<input type="hidden" name="latitude" id="first_lat" value="<?PHP echo $first_lat; ?>">
	<input type="hidden" name="longitude" id="first_lng" value="<?PHP echo $first_lng; ?>">
</form>

<div id='map' style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px; margin-bottom:10px;"></div>
<button  id="geo_json" data-type="index" class="btn btn-primary">Save</button>

<div class="modal fade" style="display:none;z-index:1000;" id="carta_myModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title">Add/Edit Properties And Styles</h4>
      </div>
      <div class="modal-body">
            
            <div role="tabpanel">

              <!-- Nav tabs -->
              <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#properties" aria-controls="properties" role="tab" data-toggle="tab">Properties</a></li>
                <li role="presentation"><a href="#advanced" aria-controls="advanced" role="tab" data-toggle="tab">Advanced</a></li>
                <li role="presentation"><a href="#style" aria-controls="style" role="tab" data-toggle="tab">Styles</a></li>
                
              </ul>

              <!-- Tab panes -->
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="properties"> 
                                     
                    <table style="border:0" id="menuBasic">
                        <tbody>
                            <tr>
                                <td>
									<label for="">Name</label><br><br>
									<button class="OmekaFetcher" data-target="autoFillAddress" class="btn btn-success">Fetch From Omeka</button>
								</td>
                                <td><input type="text" id="autoFillAddress" ></td>
                            </tr>
                            <tr>
                                <td>
									<label for="">Description</label><br><br>
									<button class="OmekaFetcher" data-target="description" class="btn btn-success">Fetch From Omeka</button>
								</td>
                                <td><textarea id="description"></textarea></td>
                            </tr>   
                        </tbody>                                             
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="advanced">

                    <table id="menuCustomProperties"><tbody></tbody></table>
                </div>
                <div role="tabpanel" class="tab-pane" id="style">
                    <table id="menuStyle"><tbody></tbody></table>
                </div>         

              </div>

            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit_modal">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<div class="modal fade" style="display:none;z-index:1000;" id="carta_omeka_fetcher">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title">Fetch data From Omeka</h4>
      </div>
      <div class="modal-body">
            
			  <ul class="nav nav-tabs nav-justified">
				<li class="active"><a data-toggle="tab" href="#items">Fetch Items</a></li>
				<li><a data-toggle="tab" href="#collections">Fetch Collections</a></li>
				<li><a data-toggle="tab" href="#exhibits">Fetch Exhibits</a></li>
			  </ul>
			  <div class="clearfix"></div>

			  <div class="tab-content">
				<div id="items" class="tab-pane fade in active">
					<h3>Fetch Omeka Items</h3>
					<div class="container">
						<select id="select-items" class="form-control"><option value="" selected>[Select Items]</option></select><br><br>
						<select id="select-items-column" class="form-control"><option value="" selected>[Select Column]</option></select>
					</div>
				</div>
				<div id="collections" class="tab-pane fade in">
					<h3>Fetch Omeka Collections</h3>
					<div class="container">
						<select id="select-collections" class="form-control"><option value="" selected>[Select Collections]</option></select><br><br>
						<select id="select-collections-column" class="form-control"><option value="" selected>[Select Column]</option></select>
					</div>
				</div>
				<div id="exhibits" class="tab-pane fade in">
					<h3>Fetch Omeka Exhibits</h3>
					<div class="container">
						<select id="select-exhibits" class="form-control"><option value="" selected>[Select Exhibits]</option></select><br><br>
						<select id="select-exhibits-column" class="form-control"><option value="" selected>[Select Column]</option></select>
					</div>
				</div>
			  </div>
			  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="fetcher_close_modal">Close</button>
        <button type="button" class="btn btn-primary" id="fetcher_insert_modal">Insert</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	var fetcherTarget = "";
	jQuery(document).ready(function($) {
		$.ajax({
			type: "POST",
			data: {type: 'item'},
			dataType: "json",
			url: '<?php echo url("carta/index/getrecords"); ?>',
			success: function(data) {
				$('#items .container select#select-items').html(data.output);
			}
		});
		$.ajax({
			type: "POST",
			data: {type: 'collection'},
			dataType: "json",
			url: '<?php echo url("carta/index/getrecords"); ?>',
			success: function(data) {
				$('#collections .container select#select-collections').html(data.output);
			}
		});
		$.ajax({
			type: "GET",
			url: '<?php echo url("carta/index/getexhibits"); ?>',
			dataType: "json",
			success: function(data) {
				$('#exhibits .container select#select-exhibits').html(data.output);
			}
		});
		
		$('.OmekaFetcher').click(function() {
			fetcherTarget = $(this).attr('data-target');
			$('#carta_omeka_fetcher').modal("show");
			reset_fetcher_form();
		});
		$('#fetcher_close_modal').click(function() {
			$('#carta_omeka_fetcher').modal("hide");
		});
		
		$('#fetcher_insert_modal').click(function() {
			var activeTab = $('#carta_omeka_fetcher .tab-content > .tab-pane.active');
			
			if(activeTab.attr('id') == "exhibits") {
				var url = '<?php echo url("carta/index/getexhibittext"); ?>';
			}
			else {
				var url = '<?php echo url("carta/index/getelementtext"); ?>';
			}
			
			$.ajax({
				type: "POST",
				url: url,
				data: {id: $('#'+activeTab.attr('id')+' .container select#select-'+activeTab.attr('id')+'-column').val()},
				success: function(data) {
					if(tinyMCE.get(fetcherTarget)) {
						tinyMCE.get(fetcherTarget).setContent(tinyMCE.get(fetcherTarget).getContent() + " " + data);
					}
					else {
						$("#"+fetcherTarget).val($("#"+fetcherTarget).val() + " " + data);
					}
					$('#fetcher_close_modal').click();
				}
			});
		});
		
		$('#items .container select#select-items').change(function() {
			$.ajax({
				type: "POST",
				url: '<?php echo url("carta/index/getelements"); ?>',
				data: {id: $(this).val()},
				success: function(data) {
					$('#items .container select#select-items-column').html(data);
				}
			});
		});
		
		$('#collections .container select#select-collections').change(function() {
			$.ajax({
				type: "POST",
				url: '<?php echo url("carta/index/getelements"); ?>',
				data: {id: $(this).val()},
				success: function(data) {
					$('#collections .container select#select-collections-column').html(data);
				}
			});
		});
		
		$('#exhibits .container select#select-exhibits').change(function() {
			$.ajax({
				type: "POST",
				url: '<?php echo url("carta/index/getexhibitcolumns"); ?>',
				data: {id: $(this).val()},
				success: function(data) {
					$('#exhibits .container select#select-exhibits-column').html(data);
				}
			});
		});
		
		function reset_fetcher_form() {
			$('#items .container select#select-items, #collections .container select#select-collections, #exhibits .container select#select-exhibits').val("");
			$('#items .container select#select-items-column, #collections .container select#select-collections-column, #exhibits .container select#select-exhibits-column').val("").html('<option value="" selected>[Select Column]</option>');
		}
	});
</script>









<script>

var featureGroup = L.featureGroup();
var show_sidebar = true;
var mbAttribution = ' contributors | <a href="https://www.mapfig.com" target="_blank">Mapfig</a>'; 
var defaultLayer = <?php echo $defaultLayer; ?>;

var baseLayers = { <?php echo $cartalayergroup; ?>
};
var overlays = {
    "Map Points": featureGroup
};
var layerSelector = L.control.layers(baseLayers, overlays);

var map = L.map('map', { dragging: true, touchZoom: true, scrollWheelZoom: true, doubleClickZoom: true, boxzoom: true, trackResize: true, worldCopyJump: false, closePopupOnClick: true, keyboard: true, keyboardPanOffset: 80, keyboardZoomOffset: 1, inertia: true, inertiaDeceleration: 3000, inertiaMaxSpeed: 1500, zoomControl: true, crs: L.CRS.EPSG3857, layers: [defaultLayer, featureGroup] });
map.setView([<?php echo $first_lat;?>,<?php echo $first_lng; ?>], <?php echo $zoom; ?>);

 
jQuery('#map .leaflet-top.leaflet-left').append('<div id="sidebarhideshow" class="leaflet-control-sidebar leaflet-bar leaflet-control" style="z-index:11;">' + '<a class="leaflet-control-sidebar-button leaflet-bar-part" id="sidebar-button-reorder" href="#" onClick="return false;" title="Sidebar Toggle"><i class="fa fa-reorder"></i></a>' + '<div id="sidebar-buttons" class="sidebar-buttons" style="max-height: 300px; overflow: auto;">' + '<ul class="list-unstyled leaflet-sidebar">' + '</ul>' + '</div>' + '</div>');
jQuery('#map .leaflet-top.leaflet-left').append('<div id="edit_image_overlays" class="leaflet-control-sidebar leaflet-bar leaflet-control" style="z-index:10;"><a class="leaflet-control-sidebar-button leaflet-bar-part" href="#" onclick="return false;"><i class="fa fa-image"></i></a></div>');

var jsonData = L.geoJson(null, {

        style: function (feature) {
            return {color: "#f06eaa",  "weight": 4, "opacity": 0.5, "fillOpacity": 0.2};
        },
        onEachFeature: function (feature, layer) {
            featureGroup.addLayer(layer);
            
            layer.on("click", function(){
                if(editMode) {
                    showModal("edit", layer);
                    setTimeout(function(){
                        map.closePopup();
                    },50);
                }
				else {
					openPopup(layer);
				}
            });            

            properties1 = feature.properties;
            var properties = new Array();
            for(var i=0; i<properties1.length; i++){
                row = {};
                row['name']  = properties1[i].name;
                row['value'] = properties1[i].value;
                row['defaultProperty'] = properties1[i].defaultProperty;
                properties.push(row);
            }
            
            layerProperties.push(new Array(layer, properties));
            
            var style = feature.style;
            var cp    = feature.customProperties;

            if(style) {
                if(layer instanceof L.Marker) {
                    if(style.markerColor) {
                        layer.setIcon(L.AwesomeMarkers.icon(style));
                    }
                }
                else {
                    layer.setStyle(style);
                }
            }

            shapeStyles.push(style); //styles is JSON Object
            shapeCustomProperties.push(cp);
            bindPopup(layer);

            renderSideBar(layer);
        }
    });


  var drawControl = new L.Control.Draw({
    draw : {        
        circle : false
    },
    edit: {
      featureGroup: featureGroup
    }
  }).addTo(map);

jQuery(document).ready(function($) {
    imageOverlaysUpdate();
	jQuery('#geo_json, #save_json').click(function(){
        var type = jQuery(this).attr("data-type");
        jQuery('#carta_type').val(type);

        var finalShapeData = new Array();
                
        var shapes = getShapes(featureGroup);
        
        jQuery.each(shapes, function(index, shape) {
            properties = getPropertiesByLayer(shape);

            var index = getLayerIndex(shape);
            shpJson = shape.toGeoJSON();
            shpJson.properties = properties;
            shpJson.customProperties = shapeCustomProperties[index];
            shpJson.style = shapeStyles[index];
            finalShapeData.push(shpJson);
        });

        finalShapeData = JSON.stringify(finalShapeData);
        
        jQuery("#geo_json_str").val(finalShapeData);
        jQuery('#save_map_form').submit();       
    
    })

    jQuery('#submit_modal').click(function(){

        properties = new Array();
        var name = jQuery('#autoFillAddress').val();
        var description = tinyMCE.get('description').getContent();
                console.log(description);            
        row = {};
        row['name']            = "Name";
        row['value']           = name;
        row['defaultProperty'] = true;
        
        properties.push(row);

        row = {};
        row['name']     = "Description";
        row['value']    = description;
        row['defaultProperty'] = true;
        
        properties.push(row);


        stl = {};
        jQuery('#menuStyle tbody tr input, #menuStyle tbody tr select').each(function(){
            name  = $(this).attr('id');
            value = $(this).val();
            
            stl[name]  = value;
        });
        
        cp = {};
        jQuery('#menuCustomProperties tbody tr input[type=checkbox]').each(function(){
            name  = $(this).attr('id');
            value = $(this).is(':checked');
            
            cp[name]  = value;
        });

        for(i=0; i<layerProperties.length; i++) {
            if(layerProperties[i][0] == currentLayer) {
                layerProperties[i][1] = properties;
                shapeStyles[i] = stl;
                shapeCustomProperties[i] = cp;
                break;
            }
        }
        bindPopup(currentLayer);
        reRenderShapeStylesOnMap(currentLayer);
        renderSideBar(currentLayer);
        jQuery('#carta_myModal').modal("hide");
    })   
   
     var animating = false;
    jQuery('#sidebar-button-reorder').click(function() {
        if (animating) return;
        var element = jQuery('#sidebar-buttons');
        animating = true;
        if (element.css('left') == '-50px') {
            element.show();
            element.animate({
                opacity: '1',
                left: '0px'
            }, 400, function() {
                animating = false;
            });
        } else {
            element.animate({
                opacity: '0',
                left: '-50px'
            }, 400, function() {
                animating = false;
                element.hide();
            });
        }
    });
});

function renderSideBar(layer) {
        if (show_sidebar )
            jQuery('#sidebarhideshow').show();
        else {
            jQuery('#sidebarhideshow').hide();
        }
        if(layer) {
			target = jQuery('#sidebar-buttons ul.leaflet-sidebar');
			currentIndex = getLayerIndex(layer);
			
			lable = layerProperties[currentIndex][1][0].value;
			if (lable == "") {
				lable = "No Location";
			}
			target.append('<li><input type="checkbox" data-index="' + currentIndex + '" onClick="changeAddressCheckbox(this)" checked><a data-index="' + currentIndex + '" onClick="clickOnSidebarAddress(this)">' + lable + '</a><div class="clear"></div></li>');
		}
    }

    function changeAddressCheckbox(obj) {
        var layers = getLayers();
        
        index = jQuery(obj).attr("data-index");
        
        if (jQuery(obj).is(':checked')) {
            featureGroup.addLayer(layers[index]);
        } else {
            featureGroup.removeLayer(layers[index]);
        }
    }

function clickOnSidebarAddress(obj) {
    var layers = getLayers();
    index = jQuery(obj).attr("data-index");
    setTimeout(function() {
        layers[index].openPopup();
    }, 50);
}
</script>





<!-- Distance Measure -->
<script>
	var isShowMeasure = <?PHP echo ($carta->show_measure) ? "true" : "false" ?>;
	jQuery(document).ready(function($){
		setTimeout(function() {
			map.addControl(L.Control.measureControl({position:'topright'}));
			if(!isShowMeasure){
				jQuery('.leaflet-control-draw-measure').hide();
			}
		}, 500);
		
		jQuery("input[name=show_measure]").change(function() {
			isShowMeasure = !isShowMeasure;
			showMeasureUpdate();
		});
	});
	
	function showMeasureUpdate() {
		if(isShowMeasure){
			jQuery('.leaflet-control-draw-measure').show();
		}
		else {
			jQuery('.leaflet-control-draw-measure').hide();
		}
	}
</script>





<!-- Mini Map -->
<script>
	var isShowMinimap = <?PHP echo ($carta->show_minimap) ? "true" : "false" ?>;
	jQuery(document).ready(function($){
		setTimeout(function() {
			var defaultLayerMiniMap = <?php echo $defaultLayer; ?>;
			
			new L.Control.MiniMap(defaultLayerMiniMap, {toggleDisplay: true}).addTo(map)._minimize(true);
			jQuery('.leaflet-control-minimap .leaflet-control-sidebar, .leaflet-control-minimap #edit_image_overlays').remove();
			
			if(!isShowMinimap){
				jQuery('.leaflet-control-minimap').hide();
			}
		}, 500);
		
		jQuery("input[name=show_minimap]").change(function() {
			isShowMinimap = !isShowMinimap;
			showMiniMapUpdate();
		});
	});
	
	function showMiniMapUpdate() {
		if(isShowMinimap){
			jQuery('.leaflet-control-minimap').show();
		}
		else {
			jQuery('.leaflet-control-minimap').hide();
		}
	}
</script>






<!-- Map Center and zoomed -->
<script>
	function updateMapCenter() {
		canter = map.getCenter();
		jQuery('input[name=latitude]').val(canter.lat);
		jQuery('input[name=longitude]').val(canter.lng);
	}
	
	map.on('moveend', function(e) {
		updateMapCenter();
	});
	
	map.on('zoomend', function() {
		jQuery('input[name=zoom]').val(map.getZoom());
		jQuery('#carta_zoom_slider').slider({value: map.getZoom()})
	});
</script>





<!-- Sidebar -->
<script>
	var show_sidebar = <?PHP echo ($carta->show_sidebar) ? "true" : "false" ?>;
	jQuery(document).ready(function() {
		renderSideBar();
		jQuery("input[name=show_sidebar]").change(function() {
			show_sidebar = !show_sidebar;
			renderSideBar();
		});
	});
</script>







<script>
	function staticSidebarPopupResize() {
		m_height = jQuery("#map").height();
		height = m_height-40;
		
		jQuery('#static-popup').css('max-height',height).css('min-width',250);
	}
	
	jQuery(document).ready(function($) {
		jQuery("#map").append('\
			<div class="bubble static bound selected" id="static-popup" style="display: none;">\
				<a name="close" class="close" id="static-popup-close" onClick="mapClosePopup();"><i class="fa fa-close"></i></a>\
				<div class="content body" rv-html="record:body" rv-show="record:body" id="static-popup-content"></div>\
			</div>\
		');
		staticSidebarPopupResize();
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
	padding: 14px;
}
</style>







<!-- Legend -->
<div style="display:none;" id="textarea_legend_content"><?php echo ($carta->legend_content) ? base64_decode($carta->legend_content) : ""; ?></div>
<script>
	var isLegendEnable  = <?PHP echo ($carta->show_legend) ? "true" : "false" ?>;
	var legendContent   = jQuery("#textarea_legend_content").html();
	
	jQuery(document).ready(function() {
		jQuery('#map').append('\
			<div class="overlay_container" style="z-index: 2; position: absolute; right: 0; left: 0; bottom: 10px;">\
				<div id="pnlLegend" class="panel" style="margin-bottom: 0; display: none; font-size: 14px;">\
					<div id="legendContainer">\
						\
					</div>\
				</div>\
				<a id="trgrLegend" class="trigger" href="#">Legend</a>\
			</div>\
		');
		
		legendUpdate();
		jQuery("input[name=show_legend]").change(function() {
			isLegendEnable = !isLegendEnable;
			legendUpdate();
		});
		
		jQuery('#trgrLegend').click(function(){
			jQuery('#pnlLegend').toggle('fast');
			jQuery(this).toggleClass('active');
			return false;
		});
		
		jQuery('#edit_legend').click(function(e){
			e.preventDefault()
			var legend = '\
				<textarea class="form-control" style="min-height:100px;" id="edit_legend_content"></textarea>\
			';
			var dd = BootstrapDialog.show({
				title: 'Update Legend',
				message: legend,
				closable: false,
				buttons: [{
					label: '',
					icon: 'fa fa-check',
					cssClass: 'btn-primary',
					action: function(dialog) {
						legendContent = tinyMCE.get('edit_legend_content').getContent();
						legendUpdate();
						dialog.close();
						jQuery("body").removeClass("modal-open");
					}
				}, {
					label: '',
					icon: 'fa fa-remove',
					cssClass: '',
					action: function(dialog) {
						dialog.close();
						jQuery("body").removeClass("modal-open");
					}
				}]
			});
			
			setTimeout(function(){
				tinyMCEInit();
				setTimeout(function(){
					tinyMCE.get('edit_legend_content').setContent(jQuery('<textarea/>').html(legendContent).val());
				}, 500);
			}, 300);
			
			dd.getModal().removeAttr('tabindex');
		});
	});
	
	function legendUpdate() {
		if(isLegendEnable) {
			jQuery('#trgrLegend').show();
			jQuery('#edit_legend').fadeIn();
		}
		else {
			jQuery('#trgrLegend, #pnlLegend').hide();
			jQuery('#edit_legend').fadeOut();
		}
		
		jQuery('#legendContainer').html(jQuery('<textarea/>').html(legendContent).val());
		jQuery('input[name=legend_content]').val(jQuery('#legendContainer').html());
	}
</script>







<style>
	.pac-container.pac-logo {
		z-index: 10000;
	}
	#map a:visited {
		color: #000 !important;
	}
</style>
<script>
	var imageOverlays   = JSON.parse(jQuery("#geo_image_olverlays").val());
	var imageOverlaysLayers = [];
	var imageOverlaysPopups = [];
	var globalTempI = 0;
	
	jQuery('input[name=geo_image_olverlays]').val(imageOverlays);
	
	function imageOverlaysUpdate(update) {
		var imageBounds = null;
		
		jQuery.each(imageOverlaysLayers, function(key, value) {
			map.removeLayer(value);
		});
		
		imageOverlaysLayers = [];
		imageOverlaysPopups = [];
		
		jQuery.each(imageOverlays, function(key, value) {
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
				.addTo(map);
			
			var popup = L.popup().setContent(pcon);
			popup.setLatLng([imageBounds.getCenter().lat,imageBounds.getCenter().lng]);
			imageOverlaysPopups.push(popup);
			
			if(!value.opacity) {
				value.opacity = 1;
			}
			jQuery(overlay._image).css('opacity', value.opacity);
			
			L.DomEvent.on(overlay._image, 'click', function(e) {
				globalTempI = 0;
				var dis = this;
				jQuery.each(imageOverlaysLayers, function(k, v) {
					if(dis == v._image) {
						setTimeout(function(){
							imageOverlaysPopups[globalTempI].addTo(map);
						}, 100);
						return false;
					}
					globalTempI++;
				});
			});
			
			imageOverlaysLayers.push(overlay);
		});
		
		if(update && imageOverlaysLayers.length > 0) {
			map.fitBounds(imageBounds);
		}
		
		jQuery('#geo_image_olverlays, input[name=geo_image_olverlays]').val(JSON.stringify(imageOverlays));
	}
	
	function Alert(message, type) {
		title = ""
		if(!message)
			message = "";
		switch(type) {
			case "default":
				type = BootstrapDialog.TYPE_DEFAULT;
				title = "Information!";
				break;
			case "info":
				type = BootstrapDialog.TYPE_INFO;
				title = "Information!";
				break;
			case "primary":
				type = BootstrapDialog.TYPE_PRIMARY;
				title = "Information!";
				break;
			case "notify":
				type = BootstrapDialog.TYPE_PRIMARY;
				title = "Notification!";
				break;
			case "success":
				type = BootstrapDialog.TYPE_SUCCESS;
				title = "Success!";
				break;
			case "warning":
				type = BootstrapDialog.TYPE_WARNING;
				title = "Warning!";
				break;
			case "danger":
			case "error":
				type = BootstrapDialog.TYPE_DANGER;
				title = "Error!";
				break;
			default :
				type = BootstrapDialog.TYPE_DEFAULT;
				title = "Information!";
		}
		bd = BootstrapDialog.alert(message);
		bd.setType(type);
		bd.setTitle(title);
	}
	
	function tinyMCEInit(textarea) {
		if(textarea) {
			tinyMCE.remove('#'+textarea);
		}
		tinyMCE.remove('#imageOverlayPopupContentInput');
		tinyMCE.remove('#modalPropertyValue');
		tinyMCE.remove('#edit_overlay_content');
		tinyMCE.remove('#edit_legend_content');
		tinymce.init({
			selector: "textarea",
			theme: "modern",
			plugins: [
				"advlist autolink lists link charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen",
				"insertdatetime media nonbreaking save table contextmenu directionality",
				"emoticons template paste textcolor colorpicker textpattern"
			],
			toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
			toolbar2: "print preview media | forecolor backcolor emoticons",
			image_advtab: true,
			relative_urls: false,
			remove_script_host : false,
			convert_urls : true,
			autosave_ask_before_unload: false,
			extended_valid_elements : "a[onclick|style|href|title|id|class|target]"
		});
	}
	
	var progressBar = null;
	function createProgressBar() {
		var dialogInstance = new BootstrapDialog();
		dialogInstance.setTitle(null);
		dialogInstance.setMessage('\
			<div id="importProgressBar">\
				<div class="progress">\
					<div class="progress-bar progress-bar-striped active" role="progressbar"\
						aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">\
						0%\
					</div>\
				</div>\
			</div>\
		');
		dialogInstance.setType(BootstrapDialog.TYPE_SUCCESS);
		dialogInstance.setClosable(false);
		dialogInstance.open();
		
		dialogInstance.getModalHeader().hide();
		dialogInstance.getModalFooter().hide();
		
		return dialogInstance;
	}
	
	function updateProgressBar(dialogInstance, percentage) { // progress in percentage
		if(percentage == 100) {
			dialogInstance.close();
		}
		
		body = dialogInstance.getModalBody();
		obj  = body.find('#importProgressBar div.progress-bar.progress-bar-striped.active');
		
		obj.attr('aria-valuenow',percentage);
		obj.css('width',percentage+'%');
		obj.text(percentage+'% Completed');
	}
	
	var globalTempBoundsObj = null;
	var globalTempPopupcontentObj = null;
	var image_overlays_get_coordinatesObj = null;
	var image_overlays_lat_lng = [];
	
	function image_overlays_popupcontent_click(obj) {
		globalTempPopupcontentObj = obj;
		var content = '\
			<div class="table-responsive" id="image_overlays_modal">\
				<table class="table table-striped table-bordered table-hover">\
					<tr>\
						<td>\
							<textarea id="imageOverlayPopupContentInput"></textarea>\
						</td>\
					</tr>\
				</table>\
			</div>\
		';
		
		var dd = BootstrapDialog.show({
			title: 'Enter the Popup Content',
			message: content,
			closable: false,
			buttons: [{
				label: 'Save',
				icon: 'fa fa-check',
				cssClass: 'btn-primary',
				action: function(dialog) {
					con = jQuery(globalTempPopupcontentObj).parent().find('.image_overlays_popupcontent_input');
					jQuery(con).html(tinyMCE.get('imageOverlayPopupContentInput').getContent());
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
		dd.getModal().removeAttr('tabindex');
		
		setTimeout(function(){
			con = jQuery(globalTempPopupcontentObj).parent().find('.image_overlays_popupcontent_input').html();
			jQuery('#imageOverlayPopupContentInput').html(con);
			
			tinyMCEInit();
		}, 300);
	}
	
	function image_overlays_bounds_click(obj) {
		globalTempBoundsObj = obj;
		var bounds = '\
			<div class="table-responsive" id="image_overlays_modal">\
				<table class="table table-striped table-bordered table-hover">\
					<tr>\
						<th>\
							South West Coordinates\
						</th>\
						<td>\
							<input type="text" id="latitudeBL" placeholder="Latitude" class="form-control"/>\
						</td>\
						<td>\
							<input type="text" id="longitudeBL" placeholder="Longitude" class="form-control"/>\
						</td>\
						<td>\
							<a href="#" onClick="return false;" class="btn btn-info btn-xs image_overlays_get_coordinates">Populate Coordinates</a>\
						</td>\
					</tr>\
					<tr>\
						<th>\
							North East Coordinates\
						</th>\
						<td>\
							<input type="text" id="latitudeUR" placeholder="Latitude" class="form-control"/>\
						</td>\
						<td>\
							<input type="text" id="longitudeUR" placeholder="Longitude" class="form-control"/>\
						</td>\
						<td>\
							<a href="#" onClick="return false;" class="btn btn-info btn-xs image_overlays_get_coordinates">Populate Coordinates</a>\
						</td>\
					</tr>\
				</table>\
			</div>\
		';
		
		BootstrapDialog.show({
			title: 'Enter the Image Coordinates',
			message: bounds,
			closable: false,
			size: BootstrapDialog.SIZE_WIDE,
			buttons: [{
				label: 'Save',
				icon: 'fa fa-check',
				cssClass: 'btn-primary',
				action: function(dialog) {
					if(!parseFloat(jQuery('#latitudeUR').val()) ||
					!parseFloat(jQuery('#longitudeUR').val()) ||
					!parseFloat(jQuery('#latitudeBL').val()) ||
					!parseFloat(jQuery('#longitudeBL').val())) {
						Alert("Please Enter the correct Coordinates", "error");
					}
					else{
						bnds = [];
						bnds.push(new Array(parseFloat(jQuery('#latitudeBL').val()),parseFloat(jQuery('#longitudeBL').val())));
						bnds.push(new Array(parseFloat(jQuery('#latitudeUR').val()),parseFloat(jQuery('#longitudeUR').val())));
						
						jQuery(globalTempBoundsObj).parent().find('.image_overlays_bounds_input').val(JSON.stringify(bnds));
						dialog.close();
						jQuery("body").removeClass("modal-open");
					}
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
			val = jQuery(globalTempBoundsObj).parent().find('.image_overlays_bounds_input').val();
			if(val) {
				val = JSON.parse(val);
				jQuery('#latitudeUR').val(val[1][0]);
				jQuery('#longitudeUR').val(val[1][1]);
				
				jQuery('#latitudeBL').val(val[0][0]);
				jQuery('#longitudeBL').val(val[0][1]);
			}
			
			jQuery('.image_overlays_get_coordinates').click(function() {
				image_overlays_get_coordinatesObj = jQuery(this);
				BootstrapDialog.show({
					title: 'Enter the Address to get coordinates',
					message: '<input id="image_overlays_get_coordinates_get_coordinates" class="form-control"/>',
					closable: false,
					buttons: [{
						label: '',
						icon: 'fa fa-check',
						cssClass: 'btn-primary',
						action: function(dialog) {
							jQuery(jQuery(image_overlays_get_coordinatesObj).parent().parent().find('input')[0]).val(image_overlays_lat_lng[0]);
							jQuery(jQuery(image_overlays_get_coordinatesObj).parent().parent().find('input')[1]).val(image_overlays_lat_lng[1]);
							
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
					autocomplete = new google.maps.places.Autocomplete((document.getElementById("image_overlays_get_coordinates_get_coordinates")),{ types: ["geocode"] });
					google.maps.event.addListener(autocomplete, "place_changed", function() {
						lat = autocomplete.getPlace().geometry.location.lat();
						lng = autocomplete.getPlace().geometry.location.lng();
						
						image_overlays_lat_lng = [];
						
						image_overlays_lat_lng.push(lat);
						image_overlays_lat_lng.push(lng);
					});
				}, 200);
			});
		}, 200);
		
		return false;
	}
	
	jQuery(document).ready(function($) {
		jQuery("#edit_image_overlays").click(function(e) {
			e.preventDefault();
			
			var overlays = '\
				<div class="table-responsive" id="image_overlays_modal">\
					<table class="table table-striped table-bordered table-hover">\
						<thead>\
							<tr>\
								<th>\
									Overlay Name\
								</th>\
								<th>\
									Selected Image\
								</th>\
								<th>\
									Set Opacity\
								</th>\
								<th>\
									Bounds\
								</th>\
								<th>\
									Pop-Up Contents\
								</th>\
								<th>\
									Remove\
								</th>\
							</tr>\
						</thead>\
						<tbody>\
							\
						</tbody>\
					</table>\
					<button type="button" onClick="jQuery(\'#image_overlays_upload\').click(); return false;" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Overlay</button>\
					<div style="clear: both;"></div>\
					<form method="post" id="image-overlays-form" style="display:none;" enctype="multipart/form-data"><input type="file" name="image_overlays_file" id="image_overlays_upload" accept="image/*"/></form>\
				</div>\
			';
			
			BootstrapDialog.show({
				title: 'Add/Remove Image Overlays',
				message: overlays,
				closable: false,
				size: BootstrapDialog.SIZE_WIDE,
				buttons: [{
					label: 'Save',
					icon: 'fa fa-check',
					cssClass: 'btn-primary',
					action: function(dialog) {
						isOk = true;
						isOkOpacity = true;
						jQuery('#image_overlays_modal tbody tr').each(function(index, obj) {
							if(jQuery(this).find('.image_overlays_bounds_input').val() == '') {
								isOk = false;
								jQuery(this).css('boder','2px solid #f05050;');
							}
							else {
								jQuery(this).css('boder','');
							}
							
							if(parseFloat(jQuery(this).find('.image_overlays_opacity_input').val()) > 1 || parseFloat(jQuery(this).find('.image_overlays_opacity_input').val()) < 0) {
								isOkOpacity = false;
							}
						});
						
						if(!isOk) {
							Alert("Please Select the Coordinates for all the overlays", "error");
							return false;
						}
						if(!isOkOpacity) {
							Alert("Opacity should be between 0.0 and 1.0", "error");
							return false;
						}
						
						imageOverlays = [];
						jQuery('#image_overlays_modal tbody tr').each(function(index, obj) {
							temp = {};
							temp['name'] = jQuery(this).find('.image_overlays_name').val();
							temp['src'] = jQuery(this).find('.image_overlays_img').attr('src');
							temp['opacity'] = jQuery(this).find('.image_overlays_opacity_input').val();
							temp['bounds'] = jQuery(this).find('.image_overlays_bounds_input').val();
							temp['popupcontent'] = jQuery(this).find('.image_overlays_popupcontent_input').html();
							
							imageOverlays.push(temp);
						});
						
						imageOverlaysUpdate(true);
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
				jQuery.each(imageOverlays, function(key, value) {
					jQuery('#image_overlays_modal tbody').append('\
						<tr>\
							<td>\
								<input type="text" class="form-control image_overlays_name" value="'+value.name+'"/>\
							</td>\
							<td>\
								<img src="'+value.src+'" class="image_overlays_img" style="height:60px;width:100px" alt="'+value.name+'" title="'+value.name+'"/>\
							</td>\
							<td>\
								<input type="text" class="image_overlays_opacity_input" value="'+ ((value.opacity) ? value.opacity : 1) +'" style="width: 90px;"/>\
							</td>\
							<td>\
								<a href="#" onClick="return image_overlays_bounds_click(this)" class="btn btn-info"><i class="fa fa-edit"></i> Set Image Coordinates</a>\
								<input type="hidden" class="image_overlays_bounds_input" value="'+value.bounds+'"/>\
							</td>\
							<td>\
								<a href="#" onClick="return image_overlays_popupcontent_click(this)" class="btn btn-info"><i class="fa fa-edit"></i> Set Pop-Up Content</a>\
								<div style="display:none;" class="image_overlays_popupcontent_input">'+value.popupcontent+'</div>\
							</td>\
							<td>\
								<button class="btn btn-danger" onClick="jQuery(this).parent().parent().remove();"><i class="fa fa-remove"></i></button>\
							</td>\
						</tr>\
					');
				});
				
				jQuery('#image_overlays_upload').change(function(){
					if(jQuery(this).val() != "") {
						progressBar = createProgressBar();
						var formData = new FormData(jQuery('form#image-overlays-form')[0]);
						jQuery.ajax({
							url: '<?php echo url("carta/index/jsonuploadfile"); ?>',  //Server script to process data
							type: 'POST',
							xhr: function() {  // Custom XMLHttpRequest
								var myXhr = jQuery.ajaxSettings.xhr();
								if(myXhr.upload){ // Check if upload property exists
									myXhr.upload.addEventListener('progress',function(e){
										if(e.lengthComputable){
											updateProgressBar(progressBar, Math.floor((e.loaded/e.total)*100));
										}
									}, false); // For handling the progress of the upload
								}
								return myXhr;
							},
							success: function(data) {
								data = JSON.parse(data);
								if(data.success) {
									jQuery('#image_overlays_modal tbody').append('\
										<tr>\
											<td>\
												<input type="text" class="form-control image_overlays_name" value="'+data.name+'"/>\
											</td>\
											<td>\
												<img src="'+data.src+'" class="image_overlays_img" style="height:60px;width:100px" alt="'+data.name+'" title="'+data.name+'"/>\
											</td>\
											<td>\
												<input type="text" class="image_overlays_opacity_input" value="1" style="width: 90px;"/>\
											</td>\
											<td>\
												<a href="#" onClick="return image_overlays_bounds_click(this)" class="btn btn-info"><i class="fa fa-edit"></i> Set Image Coordinates</a>\
												<input type="hidden" class="image_overlays_bounds_input" value=""/>\
											</td>\
											<td>\
												<a href="#" onClick="return image_overlays_popupcontent_click(this)" class="btn btn-info"><i class="fa fa-edit"></i> Set Pop-Up Content</a>\
												<div style="display:none;" class="image_overlays_popupcontent_input"></div>\
											</td>\
											<td>\
												<button class="btn btn-danger" onClick="jQuery(this).parent().parent().remove();"><i class="fa fa-remove"></i></button>\
											</td>\
										</tr>\
									').hide().slideDown();
								}
								else {
									Alert(data.message);
								}
							},
							error: function(e){
								Alert("Error While Uploading the file", "error");
								progressBar.close();
							},
							// Form data
							data: formData,
							//Options to tell jQuery not to process data or worry about content-type.
							cache: false,
							contentType: false,
							processData: false
						});
					}
					
					jQuery(this).val('');
				});
			}, 200);
		});
	});
</script>









<script type="text/javascript" src="<?php echo admin_url("../") ?>plugins/Carta/js/helper.js"></script>

<script type="text/javascript" src="<?php echo admin_url("../") ?>plugins/Carta/js/carta.js"></script>

<script type="text/javascript">

jQuery(document).ready(function($) {
        layerSelector.addTo(map);
        jQuery('#map .leaflet-control-layers form.leaflet-control-layers-list input[type=radio]').click(function(){
                map.removeLayer(defaultLayer);
            });
    });

 <?php if (isset($carta->pointers) && count($carta->pointers) > 0) : ?>
    
    var data =<?php echo unserialize(base64_decode($carta->pointers)); ?>;
    jsonData.addData(data); 
    
<?php endif; ?>

</script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        jQuery('#carta_zoom_slider').slider({
            value: <?php echo (isset($carta->zoom)) ? $carta->zoom : 2; ?>,
            min: 0,
            max: 18,
            step: 1,
            slide: function (event, ui){

                var valA = jQuery('#carta_zoom_slider').slider("value");
                
                jQuery('#carta_zoom').val(valA);
                jQuery('#carta_zoom_value').html(valA);  
            },
            change  : function( event, ui ) { 
                var valA = jQuery('#carta_zoom_slider').slider("value");
                
                jQuery('#carta_zoom').val(valA);
                jQuery('#carta_zoom_value').html(valA);
				
				map.setZoom(valA);
            }

        });


        jQuery('#carta_width_slider').slider({
            value: <?php echo (isset($carta->width)) ? $carta->width : 600; ?>,
            min: 100,
            max: 1000,
            step: 10,
            slide: function (event, ui){

                var valA = jQuery('#carta_width_slider').slider("value");
                
                jQuery('#carta_width').val(valA);
                jQuery('#carta_width_value').html(valA);  
            },
            change: function (event, ui){

                var valA = jQuery('#carta_width_slider').slider("value");
                
                jQuery('#carta_width').val(valA);
                jQuery('#carta_width_value').html(valA);
				
				map._size.x = valA;
				jQuery('#map').css('width',valA);
            }

        });


        jQuery('#carta_height_slider').slider({
            value: <?php echo (isset($carta->height)) ? $carta->height : 500; ?>,
            min: 100,
            max: 1000,
            step: 10,
            slide: function (event, ui){

                var valA = jQuery('#carta_height_slider').slider("value");
                
                jQuery('#carta_height').val(valA);
                jQuery('#carta_height_value').html(valA);  
            },
            change: function (event, ui){

                var valA = jQuery('#carta_height_slider').slider("value");
                
                jQuery('#carta_height').val(valA);
                jQuery('#carta_height_value').html(valA);
				
				map._size.y = valA;
				jQuery('#map').css('height',valA);
            }

        });
		
		$ = jQuery;
		
		$('select[name=baselayer]').change(function(e){
			var posting = $.post('<?php echo url("carta/index/jsonlayer"); ?>', { layers_id: $(this).val() });
			
			posting.done(function(data) {
				map.removeLayer(defaultLayer);
				
				data = jQuery.parseJSON(data);
				defaultLayer = new L.TileLayer(data.url, {'id' : data.key, token: data.accesstoken, maxZoom: 18, attribution: '© '+$('<div/>').html(data.attribution).text()+mbAttribution});
				
				map.addLayer(defaultLayer);
			});
		});
		
		$('select[name=layer_group]').change(function(e){
			var posting = $.post('<?php echo url("carta/index/jsongroup"); ?>', { groups_id: $(this).val() });
			
			posting.done(function(data) {
				map.removeControl(layerSelector);
				
				data = jQuery.parseJSON(data);
				
				baseLayers = {}
				$.each(data, function(idx, obj) {
					if(obj.name) {
						baseLayers[obj.name] = new L.TileLayer(obj.url, {maxZoom: 18, id: obj.key, token: obj.accesstoken, attribution: '© '+$('<div/>').html(obj.attribution).text()+mbAttribution});
					}
				});
				
				layerSelector = L.control.layers(baseLayers, overlays)
				map.addControl(layerSelector);
				
				setTimeout(function(){
					$('.leaflet-control-layers form.leaflet-control-layers-list input[type=radio]').click(function(){
						map.removeLayer(defaultLayer);
					});
				},200);
			});
		});
		
		setTimeout(function(){
			$('.leaflet-control-layers form.leaflet-control-layers-list input[type=radio]').click(function(){
				map.removeLayer(defaultLayer);
			});
		},200);
    });
</script>
<?php echo foot(); ?>