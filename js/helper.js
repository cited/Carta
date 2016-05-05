var layerProperties = new Array();
var shapeStyles = new Array();
var shapeCustomProperties = new Array();
var publicLayer = null;
var editMode = false;
var currentLayer = '';
var getShapes = function(drawnItems) {
    var shapes = [];
    drawnItems.eachLayer(function(layer) {
        if (layer instanceof L.Polyline || layer instanceof L.Rectangle || layer instanceof L.Circle || layer instanceof L.Marker) {
            shapes.push(layer);
        }
    });
    return shapes;
};

function setPropertiesByLayer(layer, properties) {
    for (i = 0; i < layerProperties.length; i++) {
        if (layerProperties[i][0] == layer) {
            layerProperties[i][1] = properties;
            return;
        }
    }
}

function getPropertiesByLayer(layer) {
    for (i = 0; i < layerProperties.length; i++) {
        if (layerProperties[i][0] == layer) {
            return layerProperties[i][1];
        }
    }
    return {};
}

function bindPopup(layer) {
    popupContent = getPopupContent(layer)
    layer.bindPopup(popupContent);
}

function getPopupContent(layer) {
    popupContent = "";
    properties = getPropertiesByLayer(layer);
    customProperties = getCustomPropertiesByLayer(layer);
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

function getCustomPropertiesByLayer(layer) {
    for (i = 0; i < layerProperties.length; i++) {
        if (layerProperties[i][0] == layer) {
            return shapeCustomProperties[i];
        }
    }
    return {};
}

function getLayerIndex(layer) {
    for (i = 0; i < layerProperties.length; i++) {
        if (layerProperties[i][0] == layer) {
            return i;
        }
    }
}

function getLayers() {
    layers = new Array();
    for (i = 0; i < layerProperties.length; i++) {
        layers.push(layerProperties[i][0]);
    }
    return layers;
}

function clickOnSidebarAddress(obj) {
    var layers = getLayers();
    index = jQuery(obj).parent().index();
    setTimeout(function() {
        layers[index].openPopup();
        openPopup(layers[index]);
    }, 50);
}

function openPopup(layer) {
	mapClosePopup();
    index = getLayerIndex(layer);
	
    if (shapeCustomProperties[index].bootstrap_popup) {
        setTimeout(function() {
            map.closePopup();
            mapOpenPopup(layer);
        }, 50);
    }
}

function mapClosePopup() {
	jQuery('#static-popup').fadeOut();
}
function mapOpenPopup(layer) {
	popupContent = getPopupContent(layer)
	jQuery('#static-popup-content').html(popupContent);
	jQuery('#static-popup').fadeIn();
}

function cartaPopupCentralized() {
    width = jQuery(window).width();
    w = 600;
    margin_left = (width - w) / 2;
    $dialog = jQuery('.carta-modal-dialog');
    $dialog.css('margin-top', 150).css('width', w).css('margin-left', margin_left);
}