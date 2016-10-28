<HTML>
<HEAD>
<TITLE> Comparison - Test view attributes page</TITLE>
<!-- // Load any stylesheets here
<LINK rel="stylesheet" type="text/css" HREF="./books.css">
-->
</HEAD>
<BODY>

<?php
	#need to select products to compare first
	if(!$_POST || sizeof($_POST)==1){
      header("location:catalogue.php");
	}
	
	# Include the common database access functions
	require_once "./common_db.php";
	
	# Get a PDO database connection
	$dbo = db_connect();

	$prodIDs = array();
	$headers = array();
	$prices = array();
	$images = array();
	
	# fill arrays using productIDs passed from checkboxes on Products page
	foreach ($_POST as $itemID){
			$prodIDs[] = $itemID;
			$headers[] = get_name($itemID);
			$prices[] = get_price($itemID);
			$images[] = get_img($itemID);
	};

	# Construct an SQL query as multiple lines for readability
	$query = "SELECT DISTINCT NAME";
	$query .= " FROM ATTRIBUTE";
	$query .= " WHERE PRODUCT_PROD_ID IN ('".$prodIDs[0]."','".$prodIDs[1]."')";
		

	# Run the query, returning a PDOStatement object 
	# Notice, this statement will throw a PDOException object if any problems
	try {
		$statement = $dbo->query($query);
	}
	
	# Provide the exception handler - in this case, just print an error message and die,
	# but see the provided default exception handler in common_db.php, which logs to the Apache error log
	catch (PDOException $ex) {
		echo $ex->getMessage();
		die ("Invalid query");
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
		
		$row1 = $statement1->fetch();
		return $row1[0];
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


<strong><H3>Product Comparison</H3></strong>
<br>

<!-- Print the table headers, with 2px borders around cells so you can see the structure -->
<TABLE BORDER=2>
	<!-- First row -->
	<TR>
		<!-- Print 'attributes' in first column-->
		<TH>Attributes</TH>
		
		<!-- Print names of products being compared -->
		<?php for($j=0; $j < sizeof($headers); $j++) { ?> 
			<TH><?php echo $headers[$j] ?></TH>
		<?php	} ?>
	</TR>
	
	<!-- Second row -->
	<TR>
		<!-- Print 'image' under attribute-->
		<TH>Image</TH>
		
		<!-- Display images -->
		<?php for($j=0; $j < sizeof($images); $j++) { ?> 
			<TH><img src="img\<?php echo $images[$j]?>"></TH>
		<?php	} ?>
	</TR>
	
	<!-- Third row -->
	<TR>
		<!-- Print 'price' under attribute-->
		<TH>Price</TH>
		
		<!-- Print prices -->
		<?php for($j=0; $j < sizeof($prices); $j++) { ?> 
			<TH>$<?php echo $prices[$j]?></TH>
		<?php	} ?>
	</TR>
   
   
<?php
	# Print the rest of the table
	# fetch() returns an array (by default, both indexed and name-associated) of result values for the row
    while($row = $statement->fetch()) { ?>
		<TR>
			<?php		
				#attribute for the row
				#e.g. 'operating system'
				$attribute_name = $row[0];
			?>
			
			<!-- Fill cells with Attribute name, product1 value, product2 value etc.-->
			<!-- static - will always be this column -->
		    <TD><?php echo $attribute_name?></TD>	
			
			<!-- Loop through productIDs to get values for the other columns-->
			<?php for($j=0; $j < sizeof($prodIDs); $j++) { ?> 
				<TD>
					<?php 
					# get value of an attribute for each product being compared e.g. operating system of each product
					$val = get_value($attribute_name,$prodIDs[$j]);
					
					# check product has attribute, otherwise indicate it does not
					if($val!=""){
						echo $val;
					} else {
						echo "----";
					};
					?>
				</TD>
			<?php	} ?>
			
		</TR>
<?php	}

	# Drop the reference to the database
	$dbo = null;
?>
</TABLE>

</BODY>
</HTML>
