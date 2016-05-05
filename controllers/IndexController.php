<?php

class Carta_IndexController extends Omeka_Controller_AbstractActionController
{
 	private $cartaObj = null;     

    public function init(){
    	$this->_helper->db->setDefaultModelName('Carta');
    	$this->cartaObj = $this->_helper->db->getTable('Carta');
    	$this->groupObj = $this->_helper->db->getTable('CartaGroup');
    	$this->layerObj = $this->_helper->db->getTable('CartaLayer');
    }
    
    public function indexAction(){ 
    	
    	$cartaArr = $this->cartaObj->getByType();

    	$this->view->carta = $cartaArr;

		$this->view->layer_group = $this->groupObj->getAll();
		$this->view->baselayer = $this->layerObj->getAll();
		
    }
    
    public function saveAction() {
    	$cartaDetail = array();
	    $cartaDetail['name'] = $_POST['carta_name'];
	    $cartaDetail['width'] = $_POST['carta_width'];
	    $cartaDetail['height'] = $_POST['carta_height'];
	    $cartaDetail['zoom'] = $_POST['carta_zoom'];
	    $cartaDetail['baselayer'] = $_POST['baselayer'];
	    $cartaDetail['layergroup'] = $_POST['layer_group'];
	    $cartaDetail['geo_image_olverlays'] = base64_encode(str_replace("\n", "<br>", str_replace("\r", "", $_POST['geo_image_olverlays'])));
	    $cartaDetail['pointers'] = base64_encode(serialize($_POST['geo_json_str']));
	    
		$cartaDetail['show_sidebar'] = isset($_POST['show_sidebar']);
		$cartaDetail['show_measure'] = isset($_POST['show_measure']);
		$cartaDetail['show_minimap'] = isset($_POST['show_minimap']);
		$cartaDetail['show_cluster'] = isset($_POST['show_cluster']);
		
		$cartaDetail['show_legend'] = isset($_POST['show_legend']);
		$cartaDetail['legend_content'] = base64_encode($_POST['legend_content']);
		
		$cartaDetail['latitude'] = $_POST['latitude'];
		$cartaDetail['longitude'] = $_POST['longitude'];

 	    $carta_id = $_POST['carta_id'];

	    if (empty($carta_id)){
	    	$this->cartaObj->insert($cartaDetail);
	    }else{
	    	$this->cartaObj->update($cartaDetail,$carta_id);
	    
	    }
		
		$type = $_POST['carta_type'];
		
		if ($type == "manage"){
			header("location:" . url("carta") . "/index/edit/" . $carta_id);
			exit();	
		}	   
	    header("location:" . url("carta"));

	    exit;
    }

	public function addAction(){
		
		
		$this->view->carta_id = count($carta);
		
		$this->view->height = 500;
		$this->view->width = 600;
		$this->view->zoom = 2;
		$this->view->first_lat = 0;
		$this->view->first_lng = 0;

		$this->view->defaultlayer = array(); 	
		$this->view->baselayer = $this->layerObj->getAll();
		if (count($this->view->baselayer)){
			$defaultlayer =  $this->view->baselayer[0];
		}

		$this->view->defaultLayer = $defaultlayer;
		$this->view->cartalayergroup = ''; 

    	$this->view->layerGroup = $this->groupObj->getAll();
		$this->render("manage");
    }    

    public function editAction(){
	
		$id = basename($_SERVER['REQUEST_URI']);
		
    	$cartaData = $this->cartaObj->getById($id);

		$this->view->baselayer = $this->layerObj->getAll();
    	$this->view->layerGroup = $this->groupObj->getAll();

		if (count($cartaData) > 0){		
			
			$this->view->carta = $cartaData;			

			$this->view->carta_id = $cartaData->id;
			
			$this->view->height = $cartaData->height;
			$this->view->width = $cartaData->width;
			$this->view->zoom = $cartaData->zoom;
			$this->view->first_lat = 0;
			$this->view->first_lng = 0;

			$defaultLayer = array(); 	
			$this->view->baselayer = $this->layerObj->getAll();

			if (!empty($cartaData->baselayer)){
				$defaultlayer =  $this->layerObj->getById($cartaData->baselayer);
			}else{
				$defaultlayer =  $this->view->baselayer[0];
			}			

			$this->view->defaultLayer = $defaultlayer;		


			$baseLayers = array();
			$layerGroup = array();
			if (!empty($cartaData->layergroup)){
			    $layerGroup = get_db()->getTable('CartaGroup')->getById($cartaData->layergroup);
			}

			$rws = array();
			if (count($layerGroup) > 0){    
			    $rws = explode(',', $layerGroup->layer_id);
			}

			foreach($rws as $r) {

			    $r = get_db()->getTable('CartaLayer')->getById($r);;

			    if (count($r) > 0){
			       $baseLayers[] = "'".$r->name."': L.tileLayer('".$r->url."', {maxZoom: 18, id: '".$r->key."', token: '".$r->accesstoken."', attribution:'" . html_entity_decode($r->attribution) . "' + mbAttribution})";
			    }  
			}

			$baseLayers = implode(",", $baseLayers);

			$this->view->cartalayergroup = $baseLayers;
			$this->render("manage");

		} else {
			header("location:" . url("carta"));
	    	exit;		
		}

    }   

    public function deleteAction(){
		$id = basename($_SERVER['REQUEST_URI']);
		$this->cartaObj->delete($id, "map");
		header("location:" . url("carta"));
		exit;
    }


    public function addlayergroupAction(){
	$this->view->baselayer = $this->layerObj->getAll();  
    	$this->view->selected_baselayer = array();
    	$this->view->layer_group_id = "";
    	$this->render("managelayergroup");    
    }
	
	   

	public function layergroupeditAction(){

		$id = basename($_SERVER['REQUEST_URI']);

    	$groupData = $this->groupObj->getById($id);

		$this->view->baselayer = $this->layerObj->getAll();  
	
		if (count($groupData) > 0){		
			
			$this->view->layer_group = $groupData;		
			$this->view->layer_group_id = $groupData->id;

			$selected_baselayer = array();
			
			if (!empty($groupData->layer_id)){
				$selected_baselayer = explode(',', $groupData->layer_id);
			}
			$this->view->selected_baselayer = $selected_baselayer;

			$this->render("managelayergroup");

		} else {
			header("location:" . url("carta"));
	    	exit;		
		}

    
    } 

    public function layergroupsaveAction() {   
		
		$layergroup = array();	
    	$layergroup['name'] = $_POST['layer_group_name'];
    	
    	$b = $_POST['baselayer'];
    	$layergroup['layer_id'] = "";
	    if (is_array($b)){
	    	$layergroup['layer_id'] = implode(',', $b);
	    }

    	$layer_group_id = $_POST['layer_group_id'];

    	if (empty($layer_group_id)){
    		$this->groupObj->insert($layergroup);	    
    	}else{
    		$this->groupObj->update($layergroup, $layer_group_id);	    
    	}
	    
	    header("location:" . url("carta"));
	    exit;
    }

    public function layergroupdeleteAction(){

	    $id = basename($_SERVER['REQUEST_URI']);
		
		$this->groupObj->delete($id);
		

		header("location:" . url("carta"));
	    exit;

    }

    public function addbaselayerapiAction(){		    	
    	
    	$this->view->baselayer_id = "";
    	$this->render("managebaselayerapi");    
    }
	
	   

	public function baselayerapieditAction(){

		$id = basename($_SERVER['REQUEST_URI']);

    	$layerData = $this->layerObj->getById($id);

		if (count($layerData) > 0){		
			
			$this->view->baselayer = $layerData;
			$this->view->baselayer_id = $layerData->id;
			
			$this->render("managebaselayerapi");

		} else {
			header("location:" . url("carta"));
	    	exit;		
		}
    
    } 

    public function baselayerapisaveAction() {   
		
    	$layer = array();	
    	$layer['name'] = $_POST['layer_name'];
    	$layer['url'] = $_POST['layer_url'];
	    $layer['`key`'] = $_POST['layer_key'];
	    $layer['accesstoken'] = $_POST['layer_accesstoken'];
	    $layer['attribution'] = htmlentities($_POST['attribution']);
		
    	$layer_id = $_POST['layer_id'];
    	if (empty($layer_id)){
    		$this->layerObj->insert($layer);	    
    	}else{
    		$this->layerObj->update($layer, $layer_id);	    
    	}
	    
	    header("location:" . url("carta"));

	    exit;
    }

    public function baselayerapideleteAction(){

	   

		$id = basename($_SERVER['REQUEST_URI']);
		
		$this->layerObj->delete($id);
		

		header("location:" . url("carta"));
		exit;

    }


    public function jsonAction(){

    	$id = basename($_SERVER['REQUEST_URI']);
		
		$cartaData = $this->cartaObj->getById($id);
		/*
		$pointers = json_decode($cartaData->pointers);

		foreach ($pointers as &$p) {
			$p->properties[1]->value = html_entity_decode($p->properties[1]->value);
		}
*/
		header("Content-Type: application/json", true);
		echo $cartaData->pointers;
		exit;

		header("Content-Disposition: attachment; filename=map.json");
		header("Content-Length: ". strlen($carta));
		echo $cartaData->pointers;

		exit;

    }

    public function downloadjsonAction(){

    	$id = basename($_SERVER['REQUEST_URI']);

		$cartaData = $this->cartaObj->getById($id);
		
		$carta = json_decode(unserialize(base64_decode($cartaData->pointers)));
		
		$mapData = array();
		$mapData['type'] = "FeatureCollection";
		$mapData['feature'] = $carta;
		
		
		$carta = json_encode($mapData);

		header("Content-Disposition: attachment; filename=map.json");
		header("Content-Length: ". strlen($carta));
		echo $carta;

		exit;

    }

    function getJsonData($type){

    	$json_string = json_encode($lGroupDetail);		
		$db = get_db();
        $sql = "SELECT * FROM $db->Carta where type='{$type}'"; 
	   echo $sql;

	    $db->getSelectForFindBy(array());

	   	$res = $db->query($sql);
	   	echo "<pre>"; print_r($res); echo "</pre>";
	   	exit;

		if (empty($content)){
			return array();
		}else{
			return json_decode($content);
		}
	}
	
	function jsonlayerAction() {
		$layers_id = (int) $_POST['layers_id'];
		
		$defaulLayer = $this->layerObj->getById($layers_id);
		$defaulLayer = array('url' => $defaulLayer['url'], 'key' => $defaulLayer['key'], 'token' => $defaulLayer['accesstoken'], 'attribution' => $defaulLayer['attribution']);
		
		echo json_encode($defaulLayer);
		exit;
	}
	
	function jsongroupAction() {
		$baseLayers = array();
		
		$groups_id = (int) $_REQUEST['groups_id'];
		$layers = $this->groupObj->getById($groups_id);
		$layers = explode(',', $layers['layer_id']);
		
		foreach($layers as $layers_id) {
			$layers_id = (int)$layers_id;
			$data = $this->layerObj->getById($layers_id);
			$baseLayers[] = array('name' => $data['name'], 'url' => $data['url'], 'key' => $data['key'], 'token' => $data['accesstoken'], 'attribution' => $data['attribution']);
		}
		
		echo json_encode($baseLayers);
		exit;
	}
	
	function jsonuploadfileAction() {
		$response = array();
		
		$rand = time();
		$image_url = admin_url("../")."files/image_overlays_".$rand.'/';
		$target_dir = dirname(__FILE__)."/../../../files/image_overlays_".$rand;
		@mkdir($target_dir);
		
		$target_file = $target_dir .'/'. basename($_FILES["image_overlays_file"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["image_overlays_file"]["tmp_name"]);
			if($check !== false) {
				$uploadOk = 1;
			} else {
				$response = array('success' => false, 'message' => 'File is not an image');
				$uploadOk = 0;
			}
		}
		// Check if file already exists
		if (file_exists($target_file)) {
			$response = array('success' => false, 'message' => 'Sorry, file already exists');
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["image_overlays_file"]["size"] > 500000) {
			$response = array('success' => false, 'message' => 'Sorry, your file is too large');
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$response = array('success' => false, 'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed');
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$response = array('success' => false, 'message' => 'Sorry, your file was not uploaded');
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["image_overlays_file"]["tmp_name"], $target_file)) {
				$response = array('success' => true, 'src' => $image_url.$_FILES["image_overlays_file"]["name"], 'name' => $_FILES["image_overlays_file"]["name"]);
			} else {
				$response = array('success' => false, 'message' => 'Sorry, there was an error uploading your file');
			}
		}
		
		echo json_encode($response);
		exit;
	}
	
	public function getrecordsAction() {
		$db = get_db();
		$prefix = $db->prefix;
		$db = $this->_helper->db;
		
		$type = $_POST['type'];
		if($type != "item" && $type != "collection") {
			exit;
		}
		
		$sql = "SELECT 
					oi.id as item_id,
					'Title' as `name`,
					case 
						when oe.id is not null then oet.`text` else '[Untitled]' end as `text`
				FROM {$prefix}element_texts oet
				JOIN {$prefix}{$type}s oi on oi.id = oet.record_id
				LEFT JOIN {$prefix}elements oe on oe.id = oet.element_id and oe.name='Title'
				JOIN (
				  SELECT MIN(id) AS id, record_id from omeka_element_texts
				  group by record_id
				)oet2 on oet2.id = oet.id
				WHERE oet.record_type = '".ucwords($type)."'";
		$res = $db->fetchObjects($sql);
		
		$output = '<option value="" selected>[Select '.ucwords($type).'s]</option>';
		foreach($res as $r) {
			$data = array('item_id' => $r->item_id, 'name' => $r->name, 'text' => $r->text);
			
			$output .= "<option value='$r->item_id'>".ucwords($type)." # $r->item_id". (($r->text) ? ' : ' . $r->text : '') . "</option>" ;
		}
		
		$responsive = array(
			'success' => true,
			'output' => $output
		);
		
		header("Content-Type: application/json");
		echo json_encode($responsive);
		exit;
    }
	
	public function getelementsAction() {
		$db = get_db();
		$prefix = $db->prefix;
		$db = $this->_helper->db;
		
		$item_id = (int)$_POST['id'];
		
		$sql = "SELECT oet.id as element_text_id, oet.element_id, oe.name, SUBSTR(oet.text, 1, 20) as text
				FROM {$prefix}element_texts oet 
				INNER JOIN {$prefix}elements oe ON oet.element_id = oe.id
				WHERE oet.record_id = $item_id";
		$res = $db->fetchObjects($sql);
		
		//$data = array();
		echo '<option value="" selected>[Select Column]</option>';
		foreach($res as $r) {
			$data = array('element_text_id' => $r->element_text_id, 'element_id' => $r->element_id, 'name' => $r->name, 'text' => $r->text);
			
			echo "<option value='$r->element_text_id'>$r->name ". (($r->text) ? ' : ' . $r->text : '') . "</option>";
		}
		exit;
    }
	
	public function getelementtextAction() {
		$db = get_db();
		$prefix = $db->prefix;
		$db = $this->_helper->db;
		
		$id = (int)$_POST['id'];
		
		$sql = "SELECT oet.text
				FROM {$prefix}element_texts oet
				WHERE oet.id = $id";
		$res = $db->fetchObjects($sql);
		
		if(count($res)> 0 ) {
			echo $res[0]->text;
		}
		exit;
    }
	
	public function getexhibitsAction() {
		$db = get_db();
		$prefix = $db->prefix;
		$db = $this->_helper->db;
		
		$sql = "SELECT oe.id, oe.title
				FROM {$prefix}exhibits oe
				WHERE oe.public = true";
		$res = $db->fetchObjects($sql);
		
		$output = '<option value="" selected>[Select Exhibits]</option>';
		foreach($res as $r) {
			$output .= "<option value='$r->id'>Title ". (($r->title) ? ' : ' . $r->title : '') . "</option>";
		}
		$responsive = array(
			'success' => true,
			'output' => $output
		);
		
		header("Content-Type: application/json");
		echo json_encode($responsive);
		exit;
    }
	
	public function getexhibitcolumnsAction() {
		$db = get_db();
		$prefix = $db->prefix;
		$db = $this->_helper->db;
		
		$sql = "SELECT oe.title, oe.description
				FROM {$prefix}exhibits oe
				WHERE oe.public = true";
		$res = $db->fetchObjects($sql);
		
		if(count($res)> 0 ) {
			echo '<option value="" selected>[Select Columns]</option>';
			echo "<option value='title'>Title</option>";
			echo "<option value='description'>Description</option>";
		}
		
		exit;
    }
	
	public function getexhibittextAction() {
		$db = get_db();
		$prefix = $db->prefix;
		$db = $this->_helper->db;
		
		$column = $_POST['id'];
		
		$sql = "SELECT oe.{$column}
				FROM {$prefix}exhibits oe
				WHERE oe.public = true";
		
		$res = $db->fetchObjects($sql);
		if(count($res)> 0 ) {
			echo $res[0]->$column;
		}
		
		exit;
    }
}