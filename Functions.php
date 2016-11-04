<?php
	
	# Include the common database access functions
	require_once "./common_db.php";
	
	# Get a PDO database connection
	$dbo = db_connect();
	
	#function to get prodIDs in a category
	function get_IDs($cat_id){
		global $dbo;
		
		# Construct an SQL query as multiple lines for readability
		$query = "SELECT CGPR_PROD_ID";
		$query .= " FROM CGPRREL";
		$query .= " WHERE CGPR_CAT_ID = ".$cat_id;

		try {
			$statement = $dbo->query($query);
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$IDs = array();
		
		while($row = $statement->fetch()) {
			for($j=0; $j < $statement->columnCount(); $j++) {			
				$IDs[] = $row[$j];
			}
		}
		
		return $IDs;
	}
	
	#function to get prodIDs in a category
	function get_catname($cat_id){
		global $dbo;
		
		# Construct an SQL query as multiple lines for readability
		$query = "SELECT CAT_NAME";
		$query .= " FROM CATEGORY";
		$query .= " WHERE CAT_ID = '".$cat_id."'";

		try {
			$statement1 = $dbo->query($query);
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$row = $statement1->fetch();
		return $row[0];
	}
	
	#function to get prodIDs in a category
	function get_catIDs(){
		global $dbo;
		
		# Construct an SQL query as multiple lines for readability
		$query = "SELECT CAT_ID";
		$query .= " FROM CATEGORY";

		try {
			$statement = $dbo->query($query);
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$cats = array();
		
		while($row = $statement->fetch()) {
			for($j=0; $j < $statement->columnCount(); $j++) {			
				$cats[] = $row[$j];
			}
		}
		
		return $cats;
	}
	
	#function to get name of a product
	function get_name($prod_id){
		global $dbo;
		
		# Construct an SQL query as multiple lines for readability
		$query = "SELECT PROD_NAME";
		$query .= " FROM PRODUCT";
		$query .= " WHERE PROD_ID = '".$prod_id."'";

		try {
			$statement1 = $dbo->query($query);
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$row = $statement1->fetch();
		return $row[0];
	}
	
	#function to get price for a product
	function get_price($prod_id){
		global $dbo;
		
		# Construct an SQL query as multiple lines for readability
		$query = "SELECT PRPR_PRICE";
		$query .= " FROM PRODPRICES";
		$query .= " WHERE PRPR_PROD_ID = '".$prod_id."'";

		try {
			$statement1 = $dbo->query($query);
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$row1 = $statement1->fetch();
		return $row1[0];
	}
	
	#function to get price for a product
	function get_SKU($prod_id){
		global $dbo;
		
		# Construct an SQL query as multiple lines for readability
		$query = "SELECT PROD_SKU";
		$query .= " FROM PRODUCT";
		$query .= " WHERE PROD_ID = '".$prod_id."'";

		try {
			$statement1 = $dbo->query($query);
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$row1 = $statement1->fetch();
		return $row1[0];
	}
	
	#function to get img of a product
	function get_img($prod_id){
		global $dbo;
		
		# Construct an SQL query as multiple lines for readability
		$query = "SELECT PROD_IMG_URL";
		$query .= " FROM PRODUCT";
		$query .= " WHERE PROD_ID = '".$prod_id."'";

		try {
			$statement1 = $dbo->query($query);
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$row1 = $statement1->fetch();
		return $row1[0];
	}
	
	#function to get description of a product
	function get_desc($prod_id){
		global $dbo;
		
		# Construct an SQL query as multiple lines for readability
		$query = "SELECT PROD_LONG_DESC";
		$query .= " FROM PRODUCT";
		$query .= " WHERE PROD_ID = '".$prod_id."'";

		try {
			$statement1 = $dbo->query($query);
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$row1 = $statement1->fetch();
		return $row1[0];
	}
	
	#function to get attribute values for a product
	function get_value($attname,$prod_id){
		global $dbo;
		
		# Construct an SQL query as multiple lines for readability
		$query = "SELECT ATTRVAL_VALUE";
		$query .= " FROM ATTRIBUTE, ATTRIBUTEVALUE";
		$query .= " WHERE ID = ATTRVAL_ATTR_ID";
		$query .= " AND ATTRIBUTE.PRODUCT_PROD_ID = '".$prod_id."'";
		$query .= " AND NAME = '".$attname."'";

		try {
			$statement1 = $dbo->query($query);
		}

		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$row1 = $statement1->fetch();
		return $row1[0];
	}
?>