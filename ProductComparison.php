<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>COMP 344</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/shop-homepage.css" rel="stylesheet"> 

</head>

<body>

<?php
	include 'Functions.php';
	
	#need to select products to compare first
	if(!$_POST || sizeof($_POST)==1){
      header("location:catalogue.php");
	}
	
	# create arrays of category IDs and names
	$catIDs = get_catIDs();
	$categories = array();

	# fill category name array
	for($j=0; $j < sizeof($catIDs); $j++) {
			$categories[] = get_catname($catIDs[$j]);
	};
	
	# create arrays
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

	# Get attributes
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
?>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">COMP344</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-2">
                <p class="lead">Comparison</p>
                              
				<!-- Categories menu -->
                <div class="list-group">
					<?php for($j=0; $j < sizeof($categories); $j++) { ?>
						<form method="post" action="catalogue.php" id="form1">
							<input type="hidden" name="catID" value=<?php echo $catIDs[$j] ?> />
							<input type="submit" value="<?php echo $categories[$j] ?>"></button>
						</form>
					<?php	} ?>
                </div>
            </div>

            <div class="col-md-9">
             <p><button type="button" class="btn btn-success" onclick="location.href = 'catalogue.php';">Products</button></p>
                
                <div class="row">
                                   
				<!-- Print the table headers, with 2px borders around cells so you can see the structure -->
				<TABLE class="table table-hover">
					<thead>
					<!-- First row -->
					<TR>
						<!-- Print 'attributes' in first column-->
						<TH>Attributes</TH>
						
						<!-- Print names of products being compared -->
						<?php for($j=0; $j < sizeof($headers); $j++) { ?> 
							<TH><?php echo $headers[$j] ?></TH>
						<?php	} ?>
					</TR>
					</thead>
					
					<tbody>
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
						</tbody>
				<?php	}

					# Drop the reference to the database
					$dbo = null;
				?>
				</TABLE>
                                                                            
                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->

    <div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; COMP344 Assignment 2</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
