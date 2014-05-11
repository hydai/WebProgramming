<?php
    $filename = "photo";
    if ($_GET['filename'] == null) {
        $filename .= "1.jpg";
    } else {
        $filename .= $_GET['filename'].".jpg";
    }
    header('Content-type: application');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Content-Length: ' . filesize('./fileArea/'.$filename));
    ob_end_flush();
    readfile('./fileArea/'.$filename);
?>
