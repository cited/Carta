<?php

/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Carta
 */

if (!defined('Carta_PLUGIN_DIR')) {
    define('Carta_PLUGIN_DIR', dirname(__FILE__));
}

if (!defined('Carta_JSON_DIR')) {
    define('Carta_JSON_DIR', dirname(__FILE__) . '/json');
}

class CartaPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'install', 
        'uninstall', 
        'define_routes',
        'define_acl',
        'public_items_show',
        'admin_items_show_sidebar',
        'admin_items_browse_detailed_each',
        'initialize',
        'after_save_item' ,  
        'public_head',
        'admin_head'                           
    );

    
    protected $_filters = array(
        'admin_navigation_main',
        'exhibit_layouts',         // Added by David  
        'admin_items_form_tabs'   // Added by David   

    );

    public function hookInstall(){  
        include("install.php");

    }
    
    public function hookUninstall(){ 
        $db = get_db();
        $db->query("DROP TABLE IF EXISTS `$db->Carta`");
        $db->query("DROP TABLE IF EXISTS `$db->CartaGroup`");
        $db->query("DROP TABLE IF EXISTS `$db->CartaLayer`");
        $db->query("DROP TABLE IF EXISTS `$db->CartaItem`");
		$db->query("DELETE FROM `$db->ElementSets` WHERE name = '_carta_version'");
    }
        
    public function hookInitialize() {
		$CurrentVersion = 6;
		$elementSet = get_db()->getTable('ElementSet')->findBySql("name = '_carta_version'");
		$db = get_db();
		
		if(!$elementSet) {
			$db->query("INSERT INTO `$db->ElementSets` (name, description) VALUES ('_carta_version', '{$CurrentVersion}')");
			$elementSet = get_db()->getTable('ElementSet')->findBySql("name = '_carta_version'");
		}
		
		if((int) $elementSet[0]->description <= 0) {
			$db->query("ALTER TABLE `$db->Carta` ADD COLUMN geo_image_olverlays LONGTEXT NOT NULL");
		}
		if((int) $elementSet[0]->description <= 1) {
			$db->query("ALTER TABLE `$db->Carta` ADD COLUMN show_sidebar BOOLEAN NOT NULL DEFAULT TRUE");
			$db->query("ALTER TABLE `$db->Carta` ADD COLUMN show_minimap BOOLEAN NOT NULL DEFAULT TRUE");
			$db->query("ALTER TABLE `$db->Carta` ADD COLUMN show_measure BOOLEAN NOT NULL DEFAULT TRUE");
		}
		if((int) $elementSet[0]->description <= 2) {
			$db->query("ALTER TABLE `$db->Carta` ADD COLUMN show_legend BOOLEAN NOT NULL DEFAULT FALSE");
			$db->query("ALTER TABLE `$db->Carta` ADD COLUMN legend_content longtext NOT NULL");
		}
		if((int) $elementSet[0]->description <= 3) {
			$db->query("ALTER TABLE `$db->Carta` ADD COLUMN latitude varchar(100) NOT NULL DEFAULT '0'");
			$db->query("ALTER TABLE `$db->Carta` ADD COLUMN longitude varchar(100) NOT NULL DEFAULT '0'");
		}
		if((int) $elementSet[0]->description <= 4) {
			$db->query("ALTER TABLE `$db->CartaLayer` ALTER COLUMN `name` varchar(300) NOT NULL");
			$db->query("ALTER TABLE `$db->CartaLayer` ALTER COLUMN `url` varchar(300) NOT NULL");
			$db->query("ALTER TABLE `$db->CartaLayer` ALTER COLUMN `key` varchar(300) NOT NULL");
			$db->query("ALTER TABLE `$db->CartaLayer` ALTER COLUMN `accesstoken` varchar(300) NOT NULL");
			$db->query("ALTER TABLE `$db->CartaLayer` ALTER COLUMN `attribution` varchar(300) NOT NULL");
		}
		if((int) $elementSet[0]->description <= 5) {
			$db->query("ALTER TABLE `$db->Carta` ADD COLUMN show_cluster BOOLEAN NOT NULL DEFAULT FALSE");
		}
		
		$db->query("UPDATE `$db->ElementSets` SET description = '{$CurrentVersion}' WHERE name = '_carta_version'");
		add_shortcode ('carta', array($this,'cartaShortcode'));
    }
    
    public function cartaShortcode($args){        
       include("shortcode.php");
    }


    
    public function hookPublicHead($args){
    }



     public function hookAdminHead($args)
    {

    }


    public function hookPublicItemsShow($args){

        $item = $args['item'];
        $id = $item->id; 


        $cartaItem = get_db()->getTable('CartaItem')->getByItemId($item->id);
           
           
        if(count($cartaItem) > 0){
            $tobj = new Omeka_View_Helper_Shortcodes;
        
            echo $tobj->shortcodes($cartaItem->content);
            
        }

        
    }
    
    public function hookDefineAcl($args){
        $acl = $args['acl'];
        $acl->addResource('Cartas');
        $acl->allow(null, 'Cartas');
    }

    
    public function hookAdminItemsBrowseDetailedEach($args){       
    }
    
    public function hookAdminItemsShowSidebar($args){       
    }
    
    public function hookDefineRoutes($array){
    }
    
    public function filterAdminNavigationMain($navArray){
        $navArray['Carta'] = array('label'=>__("Carta"), 'uri'=>url('carta'));
        return $navArray;
    }


    // Added by David  - Add Carta Layout 
    public function filterExhibitLayouts($layouts){
        $layouts['carta'] = array(
            'name' => __('Carta Map'),
            'description' => __('Display a Carta Map')
        );
        return $layouts;
    }

    // Added by David - add Carta to 'Add an Item' Tab
    public function filterAdminItemsFormTabs($tabs, $args){
        // insert the carta tab before the Miscellaneous tab
        $item = $args['item'];
        $tabs['Carta'] = $this->_cartaForm($item);

        return $tabs;
    }

    public function hookAfterSaveItem($args){

        if (!($post = $args['post'])) {
            return;
        }

        $item = $args['record'];
        
        // If we don't have the geolocation form on the page, don't do anything!
        if (!isset($post['carta_content'])) {
            return;
        }

        $carta_item_id = $post['carta_item_id'];
        $cartaItem = get_db()->getTable('CartaItem');
        
        $itemsJson = array(
            "item_id"=>$item->id,
            "content"=>$post['carta_content']
        );

        if(empty($carta_item_id)){
            $cartaItem->insert($itemsJson);
        }else{
            $cartaItem->update($itemsJson, $carta_item_id);
        }
        
    }

    // Start of Added by David: this is to add Carta Map as an Item

    protected function _cartaForm($item, $post = null)
    {
              
        $carta_item_id = $item->id;
        
     
        ob_start();
        include("cartaItemForm.php");
        $string = ob_get_contents();
        ob_end_clean();
        
        return $string;       
    }
    // End of Added by David 

}