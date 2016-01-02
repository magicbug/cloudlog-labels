<?php
require_once('fpdf.php');
require('PDF_Label.php');

/*------------------------------------------------
To create the object, 2 possibilities:
either pass a custom format via an array
or use a built-in AVERY name
------------------------------------------------*/

// Example of custom format
// $pdf = new PDF_Label(array('paper-size'=>'A4', 'metric'=>'mm', 'marginLeft'=>1, 'marginTop'=>1, 'NX'=>2, 'NY'=>7, 'SpaceX'=>0, 'SpaceY'=>0, 'width'=>99, 'height'=>38, 'font-size'=>14));

// Standard format
$pdf = new PDF_Label('3422');

$pdf->AddPage();

$con = mysqli_connect("localhost","","","");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


	$sql = "SELECT COL_PRIMARY_KEY, COL_CALL, COL_TIME_ON, COL_MODE, COL_BAND, COL_RST_SENT, COL_SAT_NAME, COL_SAT_MODE FROM TABLE_HRD_CONTACTS_V01 WHERE COL_QSL_SENT LIKE 'R' ORDER BY COL_COUNTRY";

	$result = $con->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {

				$time = strtotime($row["COL_TIME_ON"]);
				$myFormatForView = date("d/m/y H:i", $time);
					if($row["COL_SAT_NAME"] != "") {
						$text = sprintf("%s\n\n%s %s\n%s %s \n\n%s", 'To: '.$row["COL_CALL"], $myFormatForView, 'on '.$row["COL_BAND"].' 2x'.$row["COL_MODE"].' RST '.$row["COL_RST_SENT"].'', 'Satellite: '.$row["COL_SAT_NAME"].' Mode: '.strtoupper($row["COL_SAT_MODE"]).' ', '', 'Thanks for QSO.');
					} else {
						$text = sprintf("%s\n\n%s %s\n%s %s \n\n%s", 'To: '.$row["COL_CALL"], $myFormatForView, 'on '.$row["COL_BAND"].' 2x'.$row["COL_MODE"].' RST '.$row["COL_RST_SENT"].'', '', '', 'Thanks for QSO.');
					}

					$pdf->Add_Label($text);
	    }
	} else {
	    echo "0 results";
	}

$pdf->Output();
?>
