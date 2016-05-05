jQuery(document).ready(function($) {
    tinymce.init({
        selector: "#description",
        theme: "modern",
        plugins: ["advlist autolink lists link image charmap print preview hr anchor pagebreak", "searchreplace wordcount visualblocks visualchars code fullscreen", "insertdatetime media nonbreaking save table contextmenu directionality", "emoticons template paste textcolor colorpicker textpattern"],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        autosave_ask_before_unload: false,
        relative_urls: false
    });
});
map.on('draw:created', function(e) {
    var type = e.layerType;
    var layer = e.layer;
    featureGroup.addLayer(layer);
    currentLayer = e.layer;
    layer.on("click", function() {
        if (editMode) {
            showModal("edit", layer);
            setTimeout(function() {
                map.closePopup();
            }, 50);
        }
		else {
			openPopup(layer);
		}
    });
    layerProperties.push(new Array(layer, new Array()));
    cp = {};
    cp['get_direction'] = false;
    cp['bootstrap_popup'] = false;
    cp['show_address_on_popup'] = true;
    cp['hide_label'] = true;
    shapeCustomProperties.push(cp);
    if (layer instanceof L.Marker) {
        style = {};
        style['icon'] = '';
        style['prefix'] = 'fa';
        style['markerColor'] = '';
    } else if (layer instanceof L.Polyline || layer instanceof L.Rectangle || layer instanceof L.Circle) {
        style = {};
        style['color'] = '#ff0000';
        style['opacity'] = 0.5;
        style['weight'] = 5;
        style['fillColor'] = '#ff0000';
        style['fillOpacity'] = 0.2;
        layer.setStyle(style);
    }
    shapeStyles.push(style);
    properties = new Array();
    row = {};
    row['name'] = 'Name';
    row['value'] = '';
    row['defaultProperty'] = true;
    properties.push(row);
    row = {};
    row['name'] = 'Description';
    row['value'] = '';
    row['defaultProperty'] = false;
    properties.push(row);
    setPropertiesByLayer(layer, properties);
    showModal("add", layer);
});
map.on('draw:deleted', function(e) {
    var layers = e.layers;
    layers.eachLayer(function(layer) {
        if (layer instanceof L.Polyline || layer instanceof L.Rectangle || layer instanceof L.Circle || layer instanceof L.Marker) {
            deleteLayer(layer);
        }
    });
});
map.on('draw:editstart', function(e) {
    setTimeout(function() {
        var layers = featureGroup.getLayers();
        jQuery.each(layers, function(index, layer) {
            index = getLayerIndex(layer);
            if (layer instanceof L.Marker) {} else if (layer instanceof L.Polyline || layer instanceof L.Polygon || layer instanceof L.Rectangle || layer instanceof L.Circle) {
                layer.setStyle(shapeStyles[index]);
            }
        });
    }, 50);
    editMode = true;
});
map.on('draw:editstop', function(e) {
    setTimeout(function() {
        var layers = featureGroup.getLayers();
        jQuery.each(layers, function(index, layer) {
            index = getLayerIndex(layer);
            if (layer instanceof L.Marker) {} else if (layer instanceof L.Polyline || layer instanceof L.Polygon || layer instanceof L.Rectangle || layer instanceof L.Circle) {
                layer.setStyle(shapeStyles[index]);
            }
        });
    }, 50);
    editMode = false;
});

function deleteLayer(layer) {
    index = getLayerIndex(layer);
    layerProperties.splice(index, 1);
    shapeStyles.splice(index, 1);
    shapeCustomProperties.splice(index, 1);
}

function showModal(type, layer) {
    currentLayer = layer;
    if (type == "add") {
        jQuery('#autoFillAddress').val("");
        tinyMCE.get('description').setContent("");
    } else {
        currentLayer = layer;
        properties = getPropertiesByLayer(layer);
        jQuery('#autoFillAddress').val(properties[0].value);
        jQuery('#description').val(properties[1].value);
        tinyMCE.get('description').setContent(properties[1].value);
    }
    jQuery('#carta_myModal').modal("show");
    if (layer instanceof L.Marker) {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById("autoFillAddress")), {
            types: ["geocode"]
        });
        google.maps.event.addListener(autocomplete, "place_changed", function() {
            lat = autocomplete.getPlace().geometry.location.lat();
            lng = autocomplete.getPlace().geometry.location.lng();
            index = getLayerIndex(layer);
            layerProperties[index][0].setLatLng([lat, lng]);
            map.setView([lat, lng], map.getZoom());
        });
    } else {
        google.maps.event.clearInstanceListeners(document.getElementById("autoFillAddress"));
    }
    renderStyleOnPopup(layer);
}

function renderStyleOnPopup(layer) {
    index = getLayerIndex(layer);
    style = shapeStyles[index];
    customProperties = shapeCustomProperties[index];
    if (layer instanceof L.Marker) {
        jQuery('#menuStyle tbody').html('   <tr>    <td>     Icon    </td>    <td>     <select id="icon" class="form-control">      <option value="">Select Marker Icon</option>      <option value="adjust">Adjust</option>      <option value="anchor">Anchor</option>      <option value="archive">Archive</option>      <option value="area-chart">Area Chart</option>      <option value="arrows">Arrows</option>      <option value="arrows-h">Arrows H</option>      <option value="arrows-v">Arrows V</option>      <option value="asterisk">Asterisk</option>      <option value="at">At</option>      <option value="automobile">Automobile</option>      <option value="ban">Ban</option>      <option value="bank">Bank</option>      <option value="bar-chart">Bar Chart</option>      <option value="bar-chart-o">Bar Chart O</option>      <option value="barcode">Barcode</option>      <option value="bars">Bars</option>      <option value="bed">Bed</option>      <option value="beer">Beer</option>      <option value="bell">Bell</option>      <option value="bell-o">Bell O</option>      <option value="bell-slash">Bell Slash</option>      <option value="bell-slash-o">Bell Slash O</option>      <option value="bicycle">Bicycle</option>      <option value="binoculars">Binoculars</option>      <option value="birthday-cake">Birthday Cake</option>      <option value="bolt">Bolt</option>      <option value="bomb">Bomb</option>      <option value="book">Book</option>      <option value="bookmark">Bookmark</option>      <option value="bookmark-o">Bookmark O</option>      <option value="briefcase">Briefcase</option>      <option value="bug">Bug</option>      <option value="building">Building</option>      <option value="building-o">Building O</option>      <option value="bullhorn">Bullhorn</option>      <option value="bullseye">Bullseye</option>      <option value="bus">Bus</option>      <option value="cab">Cab (Alias)</option>      <option value="calculator">Calculator</option>      <option value="calendar">Calendar</option>      <option value="calendar-o">Calendar O</option>      <option value="camera">Camera</option>      <option value="camera-retro">Camera Retro</option>      <option value="car">Car</option>      <option value="caret-square-o-down">Caret Square O Down</option>      <option value="caret-square-o-left">Caret Square O Left</option>      <option value="caret-square-o-right">Caret Square O Right</option>      <option value="caret-square-o-up">Caret Square O Up</option>      <option value="cart-arrow-down">Cart Arrow Down</option>      <option value="cart-plus">Cart Plus</option>      <option value="cc">CC</option>      <option value="certificate">Certificate</option>      <option value="check">Check</option>      <option value="check-circle">Check Circle</option>      <option value="check-circle-o">Check Circle O</option>      <option value="check-square">Check Square</option>      <option value="check-square-o">Check Square O</option>      <option value="child">Child</option>      <option value="circle">Circle</option>      <option value="circle-o">Circle O</option>      <option value="circle-o-notch">Circle O Notch</option>      <option value="circle-thin">Circle Thin</option>      <option value="clock-o">Clock O</option>      <option value="close">Close (Alias)</option>      <option value="cloud">Cloud</option>      <option value="cloud-download">Cloud Download</option>      <option value="cloud-upload">Cloud Upload</option>      <option value="code">Code</option>      <option value="code-fork">Code Fork</option>      <option value="coffee">Coffee</option>      <option value="cog">Cog</option>      <option value="cogs">Cogs</option>      <option value="comment">Comment</option>      <option value="comment-o">Comment O</option>      <option value="comments">Comments</option>      <option value="comments-o">Comments O</option>      <option value="compass">Compass</option>      <option value="copyright">Copyright</option>      <option value="credit-card">Credit Card</option>      <option value="crop">Crop</option>      <option value="crosshairs">Crosshairs</option>      <option value="cube">Cube</option>      <option value="cubes">Cubes</option>      <option value="cutlery">Cutlery</option>      <option value="dashboard">Dashboard</option>      <option value="database">Database</option>      <option value="desktop">Desktop</option>      <option value="diamond">Diamond</option>      <option value="dot-circle-o">Dot Circle O</option>      <option value="download">Download</option>      <option value="edit">Edit</option>      <option value="ellipsis-h">Ellipsis H</option>      <option value="ellipsis-v">Ellipsis V</option>      <option value="envelope">Envelope</option>      <option value="envelope-o">Envelope O</option>      <option value="envelope-square">Envelope Square</option>      <option value="eraser">Eraser</option>      <option value="exchange">Exchange</option>      <option value="exclamation">Exclamation</option>      <option value="exclamation-circle">Exclamation Circle</option>      <option value="exclamation-triangle">Exclamation Triangle</option>      <option value="external-link">External Link</option>      <option value="external-link-square">External Link Square</option>      <option value="eye">Eye</option>      <option value="eye-slash">Eye Slash</option>      <option value="eyedropper">Eye Dropper</option>      <option value="fax">Fax</option>      <option value="female">Female</option>      <option value="fighter-jet">Fighter Jet</option>      <option value="file-archive-o">File Archive O</option>      <option value="file-audio-o">File Audio O</option>      <option value="file-code-o">File Code O</option>      <option value="file-excel-o">File Excel O</option>      <option value="file-image-o">File Image O</option>      <option value="file-movie-o">File Movie O</option>      <option value="pdf-o">Pdf O</option>      <option value="file-photo-o">File Photo O</option>      <option value="file-picture-o">File Picture O</option>      <option value="file-powerpoint-o">File Powerpoint O</option>      <option value="file-sound-o">File Sound O</option>      <option value="file-video-o">File Video O</option>      <option value="file-word-o">File Word O</option>      <option value="file-zip-o">File Zip O</option>      <option value="film">Film</option>      <option value="filter">Filter</option>      <option value="fire">Fire</option>      <option value="fire-extinguisher">Fire Extinguisher</option>      <option value="flag">Flag</option>      <option value="flag-checkered">Flag Checkered</option>      <option value="flag-o">Flag O</option>      <option value="flash">Flash</option>      <option value="flask">Flask</option>      <option value="folder">Folder</option>      <option value="folder-o">Folder O</option>      <option value="folder-open">Folder Open</option>      <option value="folder-open-o">Folder Open O</option>      <option value="frown-o">Frown O</option>      <option value="futbol-o">Futbol O</option>      <option value="gamepad">Game Pad</option>      <option value="gavel">Gavel</option>      <option value="gear">Gear</option>      <option value="gears">Gears</option>      <option value="genderless">Gender Less</option>      <option value="gift">Gift</option>      <option value="glass">Glass</option>      <option value="globe">Globe</option>      <option value="Graduation Cap">Graduation Cap</option>      <option value="group">Group</option>      <option value="hdd-o">Hdd O</option>      <option value="headphones">Head Phones</option>      <option value="heart">Heart</option>      <option value="heart-o">Heart O</option>      <option value="heartbeat">Heartbeat</option>      <option value="history">History</option>      <option value="home">Home</option>      <option value="hotel">Hotel</option>      <option value="image">Image</option>      <option value="inbox">Inbox</option>      <option value="info">Info</option>      <option value="info-circle">Info Circle</option>      <option value="institution">Institution</option>      <option value="key">Key</option>      <option value="keyboard-o">Keyboard O</option>      <option value="language">Language</option>      <option value="laptop">Laptop</option>      <option value="leaf">Leaf</option>      <option value="legal">Legal</option>      <option value="lemon-o">Lemon O</option>      <option value="level-down">Level Down</option>      <option value="level-up">Level Up</option>      <option value="life-bouy">Life Bouy</option>      <option value="life-buoy">Life Buoy</option>      <option value="life-ring">Life Ring</option>      <option value="life-saver">Life Saver</option>      <option value="lightbulb-o">Light bulb O</option>      <option value="line-chart">Line Chart</option>      <option value="location-arrow">Location Arrow</option>      <option value="lock">Lock</option>      <option value="magic">Magic</option>      <option value="magnet">Magnet</option>      <option value="mail-forward">Mail Forward</option>      <option value="mail-reply">Mail Reply</option>      <option value="mail-reply-all">Mail Reply All</option>      <option value="male">Male</option>      <option value="map-marker">Map Marker</option>      <option value="meh-o">Meh O</option>      <option value="microphone">Microphone</option>      <option value="microphone-slash">Microphone Slash</option>      <option value="minus">Minus</option>      <option value="minus-circle">Minus Circle</option>      <option value="minus-square">Minus Square</option>      <option value="minus-square-o">Minus Square O</option>      <option value="mobile">Mobile</option>      <option value="mobile-phone">Mobile Phone</option>      <option value="money">Money</option>      <option value="moon-o">Moon O</option>      <option value="mortar-board">Mortar Board</option>      <option value="motorcycle">Motorcycle</option>      <option value="music">Music</option>      <option value="navicon">NavIcon</option>      <option value="newspaper-o">Newspaper O</option>      <option value="paint-brush">Paint Brush</option>      <option value="paper-plane">Paper Plane</option>      <option value="paper-plane-o">Paper Plane O</option>      <option value="paw">Paw</option>      <option value="pencil">Pencil</option>      <option value="pencil-square">Pencil Square</option>      <option value="pencil-square-o">Pencil Square O</option>      <option value="phone">Phone</option>      <option value="phone-square">Phone Square</option>      <option value="photo">Photo</option>      <option value="picture-o">Picture O</option>      <option value="pie-chart">Pie Chart</option>      <option value="plane">Plane</option>      <option value="plug">Plug</option>      <option value="plus">Plus</option>      <option value="plus-circle">Plus Circle</option>      <option value="plus-square">Plus Square</option>      <option value="plus-square-o">Plus Square O</option>      <option value="power-off">Power Off</option>      <option value="print">Print</option>      <option value="puzzle-piece">Puzzle Piece</option>      <option value="qrcode">QR Code</option>      <option value="question">Question</option>      <option value="question-circle">Question Circle</option>      <option value="quote-left">Quote Left</option>      <option value="quote-right">Quote Right</option>      <option value="random">Random</option>      <option value="recycle">Recycle</option>      <option value="refresh">Refresh</option>      <option value="remove">Remove</option>      <option value="reorder">Reorder</option>      <option value="reply">Reply</option>      <option value="reply-all">Reply All</option>      <option value="retweet">Re Tweet</option>      <option value="road">Road</option>      <option value="rocket">Rocket</option>      <option value="rss">RSS</option>      <option value="rss-square">RSS Square</option>      <option value="search">Search</option>      <option value="search-minus">Search Minus</option>      <option value="search-plus">Search Plus</option>      <option value="send">Send</option>      <option value="send-o">Send O</option>      <option value="server">Server</option>      <option value="share">Share</option>      <option value="share-alt">Share ALT</option>      <option value="share-alt-square">Share ALT Square</option>      <option value="share-square">Share Square</option>      <option value="share-square-o">Share Square O</option>      <option value="shield">Shield</option>      <option value="ship">Ship</option>      <option value="shopping-cart">Shopping Cart</option>      <option value="sign-in">Sign In</option>      <option value="sign-out">Sign Out</option>      <option value="signal">Signal</option>      <option value="sitemap">Sitemap</option>      <option value="sliders">Sliders</option>      <option value="smile-o">Smile O</option>      <option value="soccer-ball-o">Soccer Ball O</option>      <option value="sort">Sort</option>      <option value="sort-alpha-asc">Sort Alpha Asc</option>      <option value="sort-alpha-desc">Sort Alpha Desc</option>      <option value="sort-amount-asc">Sort Amount Asc</option>      <option value="sort-amount-desc">Sort Amount Desc</option>      <option value="sort-asc">Sort Asc</option>      <option value="sort-desc">Sort Desc</option>      <option value="sort-down">Sort Down</option>      <option value="sort-numeric-asc">Sort Numeric Asc</option>      <option value="sort-numeric-desc">Sort Numeric Desc</option>      <option value="sort-up">Sort Up</option>      <option value="space-shuttle">Space Shuttle</option>      <option value="spinner">Spinner</option>      <option value="spoon">Spoon</option>      <option value="square">Square</option>      <option value="square-o">Square O</option>      <option value="star">Star</option>      <option value="star-half">Star Half</option>      <option value="star-half-empty">Star Half Empty</option>      <option value="star-half-full">Star Half Full</option>      <option value="star-half-o">Star Half O</option>      <option value="star-o">Star O</option>      <option value="street-view">Street View</option>      <option value="suitcase">Suitcase</option>      <option value="sun-o">Sun O</option>      <option value="support">Support</option>      <option value="tablet">Tablet</option>      <option value="tachometer">Tachometer</option>      <option value="tag">Tag</option>      <option value="tags">Tags</option>      <option value="tasks">Tasks</option>      <option value="taxi">Taxi</option>      <option value="terminal">Terminal</option>      <option value="thumb-tack">Thumb Tack</option>      <option value="thumb-down">Thumb Down</option>      <option value="thumb-o-down">Thumb O Down</option>      <option value="thumb-o-up">Thumb Down Up</option>      <option value="ticket">Ticket</option>      <option value="times">Times</option>      <option value="times-circle">Times Circle</option>      <option value="times-circle-o">Times Circle O</option>      <option value="tint">Tint</option>      <option value="toggle-down">Toggle Down</option>      <option value="toggle-left">Toggle left</option>      <option value="toggle-off">Toggle Off</option>      <option value="toggle-on">Toggle On</option>      <option value="toggle-right">Toggle Right</option>      <option value="toggle-up">Toggle Up</option>      <option value="trash">Trash</option>      <option value="trash-o">Trash O</option>      <option value="tree">Tree</option>      <option value="trophy">Trophy</option>      <option value="truck">Truck</option>      <option value="tty">TTY</option>      <option value="umbrella">Umbrella</option>      <option value="university">University</option>      <option value="unlock">Unlock</option>      <option value="unlock-alt">Unlock ALT</option>      <option value="unsorted">Unsorted</option>      <option value="upload">Upload</option>      <option value="user">User</option>      <option value="user-plus">User Plus</option>      <option value="user-secret">User Secret</option>      <option value="user-times">User Times</option>      <option value="users">Users</option>      <option value="video-camera">Video Camera</option>      <option value="volume-down">Volume Down</option>      <option value="volume-off">Volume Off</option>      <option value="volume-up">Volume Up</option>      <option value="warning">Warning</option>      <option value="wheelchair">Wheelchair</option>      <option value="wifi">Wifi</option>      <option value="wrench">Wrench</option>     </select>    </td>   </tr>   <tr>    <td>     Marker Color    </td>    <td>     <input type="hidden" id="prefix" value="' + style.prefix + '"/>     <select class="form-control" id="markerColor">      <option value="">Select Marker Colour</option>      <option value="red">Red</option>      <option value="blue">Blue</option>      <option value="green">Green</option>      <option value="purple">Purple</option>      <option value="orange">Orange</option>      <option value="darkred">Darkred</option>      <option value="lightred">Lightred</option>      <option value="beige">Beige</option>      <option value="darkblue">Darkblue</option>      <option value="darkpurple">Darkpurple</option>      <option value="white">White</option>      <option value="pink">Pink</option>      <option value="lightblue">Lightblue</option>      <option value="lightgreen">Lightgreen</option>      <option value="gray">Gray</option>      <option value="black">Black</option>      <option value="cadetblue">Cadet Blue</option>      <option value="brown">Brown</option>      <option value="lightgray">Lightgray</option>     </select>    </td>   </tr>  ');
        jQuery('#icon').val(style.icon);
        jQuery('#markerColor').val(style.markerColor);
    } else if (layer instanceof L.Polyline || layer instanceof L.Rectangle || layer instanceof L.Circle) {
        jQuery('#menuStyle tbody').html('   <tr>    <td>     Color    </td>    <td>     <input type="text" id="color" readonly class="form-control" placeholder="Stroke Color" value="' + style.color + '"/>    </td>   </tr>   <tr>    <td>     Opacity (range: 0-1)    </td>    <td>     <input type="number" id="opacity" step="0.1" class="form-control" placeholder="Stroke Opacity (range 0 to 1)" value="' + style.opacity + '"/>    </td>   </tr>   <tr>    <td>     Weight    </td>    <td>     <input type="number" id="weight" class="form-control" placeholder="Stroke Weight" value="' + style.weight + '"/>    </td>   </tr>   <tr>    <td>     Fill Color    </td>    <td>     <input type="text" id="fillColor" readonly class="form-control" placeholder="Fill color" value="' + style.fillColor + '"/>    </td>   </tr>   <tr>    <td>     Fill Opacity (range: 0-1)    </td>    <td>     <input type="number" id="fillOpacity" step="0.1" class="form-control" placeholder="Fill Opacity (range 0 to 1)" value="' + style.fillOpacity + '"/>    </td>   </tr>  ');
        setTimeout(function() {
            jQuery('#color').colpick({
                layout: 'rgbhex',
                submit: 0,
                color: jQuery('#color').val().replace('#', ''),
                onChange: function(hsb, hex, rgb, el, bySetColor) {
                    jQuery(el).css('border-color', '#' + hex);
                    if (!bySetColor) jQuery(el).val('#' + hex);
                }
            });
            jQuery('#fillColor').colpick({
                layout: 'rgbhex',
                submit: 0,
                color: jQuery('#fillColor').val().replace('#', ''),
                onChange: function(hsb, hex, rgb, el, bySetColor) {
                    jQuery(el).css('border-color', '#' + hex);
                    if (!bySetColor) jQuery(el).val('#' + hex);
                }
            });
            jQuery('#get_direction, #show_address_on_popup').closest('tr').hide();
        }, 50);
    }
    jQuery('#menuCustomProperties tbody').html('  <tr>   <td>    Add Google Maps Directions Link   </td>   <td>    <input type="checkbox" id="get_direction" ' + ((customProperties.get_direction == true) ? "checked" : "") + '/>   </td>  </tr>  <tr>   <td>    Display Modal InfoBox   </td>   <td>    <input type="checkbox" id="bootstrap_popup" ' + ((customProperties.bootstrap_popup == true) ? "checked" : "") + '/>   </td>  </tr>  <tr>   <td>    Include Location on Popup   </td>   <td>    <input type="checkbox" id="show_address_on_popup" ' + ((customProperties.show_address_on_popup == true) ? "checked" : "") + '/>   </td>  </tr>  <tr>   <td>    Hide Label From Popup   </td>   <td>    <input type="checkbox" id="hide_label" ' + ((customProperties.hide_label == true) ? "checked" : "") + '/>   </td>  </tr> ');
}

function reRenderShapeStylesOnMap(layer) {
    for (i = 0; i < layerProperties.length; i++) {
        if (layerProperties[i][0] == layer) {
            if (layer instanceof L.Marker) {
                if (shapeStyles[i].markerColor) {
                    layer.setIcon(L.AwesomeMarkers.icon(shapeStyles[i]));
                }
            } else if (layer instanceof L.Polyline || layer instanceof L.Rectangle || layer instanceof L.Circle) {
                layer.setStyle(shapeStyles[i]);
            }
            return true;
        }
    }
    return false;
}