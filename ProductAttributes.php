<HTML>
<HEAD>
<TITLE> Comparison - Test view attributes page</TITLE>
<!-- // Load any stylesheets here
<LINK rel="stylesheet" type="text/css" HREF="./books.css">

</HEAD>
<BODY>
<!-- Variables are returned as $_GET["varname"] or $_POST["varname"] -->
<!-- Displaying the time at the server is useful to show the page was generated and not loaded from cache -->
<p>Local time is <?php echo  date("l dS o F Y h:i:s A")?><BR>

<?php
	#need to select products to compare first
	if(!$_POST || sizeof($_POST)==1){
      header("location:ProductsTest.php");
	}
	
	# First, include the common database access functions, if they're not already included
	require_once "./common_db.php";
	# Get a PDO database connection - see http://www.php.net/manual/en/book.pdo.php
	$dbo = db_connect();

	$prodIDs = array();
	$headers = array();
	$prices = array();
	
	#debugging - checking values of POST array
	print_r($_POST);
	
	# fill arrays using productIDs passed from checkboxes on Products page
	foreach ($_POST as $itemID){
			$prodIDs[] = $itemID;
			$headers[] = get_name($itemID);
			$prices[] = get_price($itemID);
	};

	# Construct an SQL query as multiple lines for readability
	$query = "SELECT DISTINCT SETATT_ID, SETATT_NAME";
	# Append to the $query string - note the required space
	$query .= " FROM PRODATTREL, SETATTVALUE, SETATTRIBUTE";
	# ID of product
	# 5 AND 7 NEED TO BE CHANGED TO PRODUCTS THAT USER SELECTED
	$query .= " WHERE PRODATTREL_PROD_ID IN ('".$prodIDs[0]."','".$prodIDs[1]."')";
	# Joining ProdAttRel table & SetAttValue table
	$query .= " AND PRODATTREL_SETATTVAL_ID = SETATTVALUE.SETATTVAL_ID";
	# Joining SetAttribute table & SetAttValue table
	$query .= " AND SETATTVAL_SETATT_ID = SETATTRIBUTE.SETATT_ID";
	# Order by ID
	$query .= " ORDER BY SETATT_ID";
		

	# Run the query, returning a PDOStatement object - see http://www.php.net/manual/en/class.pdostatement.php
	# Notice, this statement will throw a PDOException object if any problems - see http://www.php.net/manual/en/class.pdoexception.php
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
		$query1 = "SELECT PROD_NAME";
		# Append to the $query string - note the required space
		$query1 .= " FROM PRODUCT";
		# ID of product
		$query1 .= " WHERE PROD_ID = '".$prod_id."'";

		try {
			$statement1 = $dbo->query($query1);
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
		$query1 = "SELECT PRPR_PRICE";
		# Append to the $query string - note the required space
		$query1 .= " FROM PRODPRICES";
		# ID of product
		$query1 .= " WHERE PRPR_PROD_ID = '".$prod_id."'";

		try {
			$statement1 = $dbo->query($query1);
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
		$query1 = "SELECT SETATTVAL_VALUE";
		# Append to the $query string - note the required space
		$query1 .= " FROM PRODATTREL, SETATTVALUE, SETATTRIBUTE";
		# ID of product
		$query1 .= " WHERE PRODATTREL_PROD_ID = '".$prod_id."'";
		# Use attribute name to get value
		$query1 .= " AND SETATTRIBUTE.SETATT_NAME LIKE '".$attname."'";
		# Joining ProdAttRel table & SetAttValue table
		$query1 .= " AND PRODATTREL_SETATTVAL_ID = SETATTVALUE.SETATTVAL_ID";
		# Joining SetAttribute table & SetAttValue table
		$query1 .= " AND SETATTVAL_SETATT_ID = SETATTRIBUTE.SETATT_ID";

		try {
			$statement1 = $dbo->query($query1);
		}

		catch (PDOException $ex) {
			echo $ex->getMessage();
			die ("Invalid query");
		}
		
		$row1 = $statement1->fetch();
		return $row1[0];
	}

?>

<!-- Print the table headers, with 2px borders around cells so you can see the structure -->
<strong><H3>Product Comparison</H3></strong>
<br>
<TABLE BORDER=2>
	<!-- First row -->
	<TR>
		<!-- Print 'attributes' in first column-->
		<TH>Attributes</TH>
		<!-- Print names of products being compared -->
		<?php for($j=0; $j < sizeof($headers); $j++) { ?> <!-- see http://www.php.net/manual/en/pdostatement.columncount.php -->
			<TH><?php echo $headers[$j] ?></TH>
		<?php	} ?>
	</TR>
	
	<!-- Second row -->
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
    while($row = $statement->fetch()) { ?> <!-- see http://www.php.net/manual/en/pdostatement.fetch.php -->
		<TR>
			<?php		
				#attribute for the row
				#e.g. 'operating system'
				$attribute_name = $row[1];
			?>
			
			<!-- Fill cells with Attribute name, product1 value, product2 value etc.-->
			<!-- static - will always be this column -->
		    <TD><?php echo $attribute_name?></TD>	
			
			<!-- Loop through productIDs to get values for each column-->
			<?php for($j=0; $j < sizeof($prices); $j++) { ?> 
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
