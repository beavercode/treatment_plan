<?php
/** @var \UTI\Lib\Data $data */

//todo v1, refactor this, make separate class for PDF
// $pdf = $data('pdf.object');
// $pdf->show();
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $data('pdf.name') . '"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
//@readfile($data('pdf.file'));
echo $data('pdf.body');

//todo v2
// Let the browser know that a PDF file is coming.
/*header("Content-type: application/pdf");
header("Content-Length: " . filesize($data('pdf.file')));
// Send the file to the browser.
readfile($data('pdf.file'));*/

//todo v3
/*if (file_exists($data('pdf.file'))) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Type: application/force-download");
    header('Content-Disposition: attachment; filename=' . urlencode(basename($data('pdf.file'))));
    // header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($data('pdf.file')));
    ob_clean();
    flush();
    readfile($file);
    exit;
} else {
    die('no such file ' . $data('pdf.file'));
}*/

//todo v4
/*header('Content-Type: application/pdf');
header('Content-Length: ' . strlen($data('pdf.body')));
header('Content-disposition: inline; filename="' . $data('pdf.name') . '"');
header('Cache-Control: public, must-revalidate, max-age=0');
header('Pragma: public');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

echo $data('pdf.body');*/

//todo v5
/*if (file_exists($file = $data('pdf.file'))) {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . urlencode($data('pdf.name')));
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/pdf");
    header("Content-Description: File Transfer");
    header("Content-Length: " . filesize($file));
    flush(); // this doesn't really matter.
    $fp = fopen($file, "r");
    while (!feof($fp))
    {
        echo fread($fp, 65536);
        flush(); // this is essential for large downloads
    }
    fclose($fp);
} else {
    die('wrong file name: ' .$file);
}*/

//todo v6
/*header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $data('pdf.name') . '"');
readfile($data('pdf.file'));*/