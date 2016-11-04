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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    
    

</head>

<body>

<?php
	include 'Functions.php';
	
	
	if (!$_POST){
		$category = 9;
	} else {
		$category = $_POST["catID"];
	}
	
	$catIDs = get_catIDs();
	
	$categories = array();
	
	
	for($j=0; $j < sizeof($catIDs); $j++) {	
			$categories[] = get_catname($catIDs[$j]);
	};
	
	#needs to be changed to be dynamic
	$prodIDs = get_IDs($category);
	
	$headers = array();
	$prices = array();
	$images = array();
	$descs = array();
	$SKUs = array();
	
	# fill arrays using productIDs passed from checkboxes on Products page
	foreach ($prodIDs as $itemID){
			$headers[] = get_name($itemID);
			$prices[] = get_price($itemID);
			$images[] = get_img($itemID);
			$descs[] = get_desc($itemID);
			$SKUs[] = get_SKU($itemID);
	};
	
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
                <p class="lead">Products</p>
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
				

				
				<FORM METHOD="post" onsubmit="checkboxes()" ACTION="ProductComparison.php">
				
				<p><button type="submit" class="btn btn-success">Compare</button>  Select up to three products to compare!</p>
                <div class="row carousel-holder"></div>
				
                <div class="row">
					<?php for($j=0; $j < sizeof($prodIDs); $j++) { ?> 
						<div class="col-sm-4 col-lg-4 col-md-4">
							<div class="thumbnail">
								<img src="img\<?php echo $images[$j]?>">
								<div class="caption">
									<h4 class="pull-right">$<?php echo $prices[$j] ?></h4>
									<h4><a href="#"><?php echo $headers[$j] ?></a>
									</h4>
									<p><?php echo $descs[$j] ?></p>
								</div>
								<div class="ratings">
									<p class="pull-right"><input type="checkbox" name=<?php echo $SKUs[$j] ?> value=<?php echo $prodIDs[$j] ?> />    Compare</p>
									<p>
										<span class="glyphicon glyphicon-star"></span>
										<span class="glyphicon glyphicon-star"></span>
										<span class="glyphicon glyphicon-star"></span>
										<span class="glyphicon glyphicon-star"></span>
										<span class="glyphicon glyphicon-star"></span>
									</p>
								</div>
							</div>
						</div>
					<?php	} ?>    
                </div>
				</FORM>
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
	
	<script type="text/javascript">
		//from http://stackoverflow.com/questions/19001844/how-to-limit-the-number-of-selected-checkboxes
		var limit = 3;
		//when a product is selected
		$('input[type=checkbox]').on('change', function (e) {
			//check how many other boxes are selected
			if ($('input[type=checkbox]:checked').length > limit) {
				//if three products have already been selected, alert user and do not check box
			   this.checked = false;
			   alert("You can only compare up to three products!");
		   };
		});	 
		
		//from http://stackoverflow.com/questions/22938341/count-the-number-of-checked-checkboxes-in-html
		function checkboxes(){
			//count the number of products selected
			var checkedboxes = document.querySelectorAll('input[type="checkbox"]:checked').length;
			//if 0 or 1, ask user to select at least one more
			if (checkedboxes < 2){
				alert("Please select more than one product to compare!")
			}
		};
	</script>

</body>

</html>
