
<?php


function createPDF($result)
{	
	require('fpdf.php');
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',12);		
	$pdf->MultiCell(0,12,"My Bookmarks",1,'C');

	foreach($result as $row) {	
		$pdf->Ln();
			//foreach($row as $column)
		$pdf->SetFont('Arial','B',12);
		$pdf->MultiCell(0,12,$row["word"],1,'J');
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(0,12,$row["definition"],1,'J');
	}
	$pdf->Output("bookmarks.pdf","F");
	$url = "backend/bookmarks.pdf";
	$data["url"] = $url;
	ignore_user_abort(true);
	if (connection_aborted()) {
		unlink($f);
	}
	return $data;
}	
?>