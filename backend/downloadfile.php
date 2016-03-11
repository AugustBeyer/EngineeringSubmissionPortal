<?php

$team_id = htmlspecialchars($_GET["tid"]);
$file_name = htmlspecialchars($_GET["name"]);

echo "Preparing file for download\n";

$file = "/var/www/html/2016/" . $team_id . "/" . $file_name;

    if (file_exists($file)) 
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
   		ob_end_flush();
        readfile($file);
        exit;
    }

?>