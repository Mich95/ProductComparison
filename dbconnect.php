<?php

$oraUser="username";
$oraPass="password";
$oraDB="(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=animatrix.science.mq.edu.au)(PORT=1521)))(CONNECT_DATA=(SID=one)))";

 
 $conn = oci_connect($oraUser,$oraPass,$oraDB);
		
if (!$conn) 
{
 echo "fail";
}


?>