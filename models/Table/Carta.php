<?php

class Table_Carta extends Omeka_Db_Table
{
	public function getByType(){

		$db = get_db();
       
        $select = $db->select()->from($db->Carta);
       
        $carta = $this->fetchObjects($select);        
        
        return $carta;
	}

    function getById($id){

        $db = get_db();
       
        $select = $db->select()->from($db->Carta);
        
        $select->where("{$db->Carta}.id = ?", $id);
        
        $carta = $this->fetchObject($select);
        
        return $carta;
    }

    function insert($cartadata){       
                
        $db = get_db();
        $sql = $this->makeInsertQuery($cartadata);
        $sql = "INSERT INTO $db->Carta " . $sql;

        $db->query($sql);
    }

    function update($cartadata, $id){
       
       
        $sql = $this->makeUpdateQuery($cartadata);
        
        $db = get_db();
        $sql = "Update $db->Carta " . $sql . " where id={$id}";
       
        $db->query($sql);
        return ;
    }

    function delete($id){

        $db = get_db();
        $sql = '';
		$sql = "DELETE from $db->Carta where id='{$id}'";

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