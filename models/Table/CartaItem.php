<?php 

class Table_CartaItem extends Omeka_Db_Table
{
	function getAll(){

		$db = get_db();
       
        $select = $db->select()->from($db->CartaItem);
       
        $carta = $this->fetchObjects($select);        
        
        return $carta;
	}

    function getById($id){

        $db = get_db();
       
        $select = $db->select()->from($db->CartaItem);
        
        $select->where("{$db->CartaItem}.id = ?", $id);
        
        $carta = $this->fetchObject($select);
        
        return $carta;
    }

    function getByItemId($id){

        $db = get_db();
       
        $select = $db->select()->from($db->CartaItem);
        
        $select->where("{$db->CartaItem}.item_id = ?", $id);
       
        $carta = $this->fetchObject($select);
        
        return $carta;
    }

	function insert($cartadata){       
                
        $db = get_db();
        $sql = $this->makeInsertQuery($cartadata);
        $sql = "INSERT INTO $db->CartaItem " . $sql;

        $db->query($sql);
    }

    function update($cartadata, $id){
       
       
        $sql = $this->makeUpdateQuery($cartadata);
        
        $db = get_db();
        $sql = "Update $db->CartaItem " . $sql . " where id={$id}";
       
        $db->query($sql);
        return ;
    }

    function delete($id){

        $db = get_db();
        $sql = '';
        
        $sql = "DELETE from $db->CartaItem where id='{$id}'";        

        $db->query($sql);

    }

    
    function makeInsertQuery($cartaData){

        $keyvalues=''; $values='';       
        
        foreach($cartaData as $key=>$value){
            $keyvalues .= $key.',';
            $values .= "'" . $value . "',";
        }

        $keyvalues=substr($keyvalues,0,strlen($keyvalues)-1);
        
        $values=substr($values,0,strlen($values)-1);
        
        $query = "($keyvalues) values ($values)";
        return $query;
    }

    
    function makeUpdateQuery($cartadata){

        $keyvalues='';
        
        foreach($cartadata as $key=>$value){
            $keyvalues .= $key ."='". $value . "',";
        }
        
        $keyvalues = substr($keyvalues,0,strlen($keyvalues)-1);
           
        $query = "SET $keyvalues";          
    
        return $query;    
    }

}