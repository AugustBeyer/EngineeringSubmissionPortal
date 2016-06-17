<?php
require "system_config.php";
$team_id = htmlspecialchars($_GET["tid"]);
$file_name = htmlspecialchars($_GET["name"]);

$file = $current_year_path . $team_id . "/" . $file_name;
echo "Preparing file for download:\t" . $file ."\n";

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