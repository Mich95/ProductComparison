<?php
//COMP344 Assignment 1, 2016. Michelle Runham. 43664717.
$oraUser="43664717";
$oraPass="murcielago97";
$oraDB="(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=animatrix.science.mq.edu.au)(PORT=1521)))(CONNECT_DATA=(SID=one)))";

 
 $conn = oci_connect($oraUser,$oraPass,$oraDB);
		
if (!$conn) 
{
 echo "fail";
}


?>