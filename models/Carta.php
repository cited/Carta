<?php

class Carta extends Omeka_Record_AbstractRecord
{
    public $id;
    public $name;
    public $width;
    public $height;
    public $baselayer;
    public $layergroup;
    public $zoom;   
    public $pointers;
    public $geo_image_olverlays;
	
    public $show_sidebar;
    public $show_minimap;
    public $show_measure;
    
	public $show_legend;
	public $legend_content;
	
	public $latitude;
	public $longitude;
}