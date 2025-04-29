<?php
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', 0);
    require_once('config.php');
    require_once('db.php');
    $logDir = __DIR__ . "/logs";
    $time = strval(time());
    $rs_success = 'false';
    $rs_message = 'fields';

    $jarr = array(
        'data' => array(
            array(
                'cardid' => $_GET['cardid'],
                'cjihao' => $_GET['cjihao'],
                'mjihao' => (int)$_GET['mjihao'],
                'status'=>1,
                'time'=>$time,
                'output'=>0
            ),
        ),
        'code' => 0,
        'message' => 'success'
    );

    header('Content-type: application/json');
    header("Connection: close");
   
    try {
        $sendUrl = "http://" . SERVER_HOST . ":" . SERVER_PORT . SERVER_PATH . "?cardid=" . $_GET['cardid'] . "&cjihao=" . $_GET['cjihao'] . "&mjihao=" . $_GET['mjihao'];     
        $opts = array('http' => array('timeout' => 2.0));
        $context = stream_context_create($opts);
        $response = @file_get_contents($sendUrl, false, $context);       
        
        if ($response === false) {
            $logMessage = "Error Response Cardid : ".$_GET['cardid']. PHP_EOL;
            $logMessage .= "Error Response DateTime : ".date("Y-m-d H:i:s"). PHP_EOL;
            $logMessage .= "Error Response DateTime : ไม่สามารถติดต่อ Node Server ได้". PHP_EOL;
            $logMessage .= "=============================================================". PHP_EOL;
        
            if (!file_exists($logDir)) { // สร้างโฟลเดอร์หากยังไม่มี
                mkdir($logDir, 0777, true);
            }
        
            $logFile = $logDir . "/" . date("Y-m-d") . ".txt";    
            file_put_contents($logFile, $logMessage, FILE_APPEND);
        }
        else{
            $response = json_decode($response);   
            $rs_success = ($response->success == 1 ) ? 'true' : 'false';
            $rs_message = $response->message ? $response->message : 'fields';        
        }

    } catch (Exception $e) {
        $logMessage = "Error Cardid : ".$_GET['cardid']. PHP_EOL;
        $logMessage .= "Error DateTime : ".date("Y-m-d H:i:s"). PHP_EOL;
        $logMessage .= "Error DateTime : " . $e->getMessage() . PHP_EOL;
        $logMessage .= "=============================================================". PHP_EOL;

        if (!file_exists($logDir)) { // สร้างโฟลเดอร์หากยังไม่มี
            mkdir($logDir, 0777, true);
        }
    
        $logFile = $logDir . "/" . date("Y-m-d") . ".txt";    
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
    // echo "http://" . SERVER_HOST . ":" . SERVER_PORT . SERVER_PATH . "?cardid=" . $_GET['cardid']; die;
    $cardid =  $_GET['cardid'] ? $_GET['cardid'] : '0';
    $cjihao = $_GET['cjihao'] ? $_GET['cjihao'] : '0';
    $mjihao = (int)$_GET['mjihao'] ? (int)$_GET['mjihao'] : 0;
    $status = 1;
    $time = $time ? $time : strval(time());
    $output = 0;
    $code = 0;
    $message = 'success';

    $sql = "INSERT INTO scan_logs (cardid, cjihao, mjihao, status, time, output, code, message, rs_success, rs_message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    // s = string, i = integer
    $stmt->bind_param("ssiisiisss", 
        $cardid, 
        $cjihao, 
        $mjihao, 
        $status, 
        $time, 
        $output, 
        $code, 
        $message, 
        $rs_success, 
        $rs_message
    );

    $stmt->execute();
    $stmt->close();
    $mysqli->close();

    // Echo the JSON response, regardless of success or error
    echo json_encode($jarr);
    ob_flush();
    flush();
?>
