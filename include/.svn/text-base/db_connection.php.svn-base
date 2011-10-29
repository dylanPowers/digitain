<?php

/*
 * This is a very handy class. It handles all the database functionality. The great thing about it
 * is that it can autogenerate query statements without having to worry about escaping strings beforehand 
 * as it is all done automatically.
 */
class dbConnection{
    private $mysqli_connection;
   
	public function create_connection(){
		$this->mysqli_connection =  new mysqli('localhost', 'digitain', 'G0_Cou6s!', 'digitain');
		if (mysqli_connect_errno()){
			return false;
		}
		else{
			return true;
		}
	}
	public function __get($name){
		if ($name == 'mysqli_connection'){
			return $this->$name;	
		}
		elseif ($name == 'insert_id'){
			return $this->mysqli_connection->insert_id;	
		}
	}
	public function close_connection(){
		$this->mysqli_connection->close();
	}
	
	//A function for selecting from the database.
	//mixed query_select(string $table, array $match [, string $select [, string $order [, bool $r_array]]])
	public function query_select($table, $match, $select='*', $order=false, $r_array=false){
		$table = $this->mysqli_connection->real_escape_string($table);
        $select = $this->mysqli_connection->real_escape_string($select);
        if ($match){
            $match = $this->create_equiv_statement($match);
            $query = 'select '.$select.' from '.$table.' where '.$match;
        }
		else{
           $query = 'select '.$select.' from '.$table; 
        }
		
		if ($order){
			$query .= ' order by '.$this->mysqli_connection->real_escape_string($order);	
		}
		$result = $this->query($query);
		
		//If there are greater than 5 rows of data returned
		//this could be the sign of an sql attack, as I don't have any functions that require more than
        //5 rows to be returned.
//		if ($result->num_rows > 5){
//			return false;	
//		}
//		else{
//			return $result;
//		}
        //I do now...
        if ($r_array && $result){
            $query_data = array();
            while ($row = $result->fetch_assoc()){
                $query_data[] = $row;
            }
            return $query_data;
        }
        else{
            return $result;
        }
	}
	//query_insert(string $table, array $data)
	public function query_insert($table, $data){
		$table = $this->mysqli_connection->real_escape_string($table);
		$columns = '';
		$values = '';
		$i = 0;
		foreach ($data as $key=>$value){
			if ($i == 0){
				$columns .= $this->mysqli_connection->real_escape_string($key);
				$values .= "'".$this->mysqli_connection->real_escape_string($value)."'";
			}
			else{
				$columns .= ', '.$this->mysqli_connection->real_escape_string($key);
				$values .= ", '".$this->mysqli_connection->real_escape_string($value)."'";	
			}
			++$i;
		}
		$query = 'INSERT into '.$table.' ('.$columns.') values ('.$values.')';
		$success = $this->query($query);
        return $success;
	}
	/*
     * bool query_update(string $table, array $data, array $match)
     */
	public function query_update($table, $data, $match){
		$table = $this->mysqli_connection->real_escape_string($table);
		$data = $this->create_equiv_statement($data, true);
		$match = $this->create_equiv_statement($match);
		$query = 'UPDATE '.$table.' SET '.$data.' WHERE '.$match;
		$success = $this->query($query);
		return $success;		
	}
	
	private function query($query){
        $result = $this->mysqli_connection->query($query);
        if (DEBUG){
            debug($query);
            debug($this->mysqli_connection->error);
        }
		return $result;	
	}
	
	/* 
     * This is a function that will create comparison type strings.
     * To make this array to string parser more broad in usefullness, there's
     * the option to use commas or ANDs for a seperator.
     * string create_equiv_statement(array $match, bool $commas)
     */
	private function create_equiv_statement($match, $commas=false){
		$num_items = count($match);
		if ($commas){
			$seperator = ',';			
		}
		else{
			$seperator = 'AND';
		}
		if ($num_items == 1){
			foreach ($match as $key=>$value){
				$key = $this->mysqli_connection->real_escape_string($key);
				$value = $this->mysqli_connection->real_escape_string($value);
				$match_statement = $key."='".$value."'";
			}
		}
		else{
			$match_statement = '';
			$i = 0;
			foreach ($match as $key=>$value){
				$key = $this->mysqli_connection->real_escape_string($key);
				$value = $this->mysqli_connection->real_escape_string($value);
				if ($i == $num_items-1){
					$match_statement .= $key."='".$value."' ";						
				}
				else{
					$match_statement .= $key."='".$value."' ".$seperator." ";
				}
                //I see a lot of php scripts out there with i++, but after a little
                //research, I've found that ++i is a more effecient method.
				++$i;
			}					
		}
		return $match_statement;	
	}
}
?>