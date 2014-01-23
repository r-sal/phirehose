<?php
/**
 * Proof of concept
 * 
 * issues/todo
 * - namespaceing phirehose
 * - make protocol/ports changeable in phirehose
 */

$ip = "127.0.0.1";
$port = "8000";
$file = "data/test.queue";

$server = stream_socket_server("tcp://{$ip}:{$port}", $errNo, $errMsg);

if ($server === false) {
    throw new UnexpectedValueException("Could not bind to socket: {$errMsg}");
}

$trimmed = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$threes = array_chunk($trimmed, 2);

for (;;) {
    $client = @stream_socket_accept($server);

    $received = stream_socket_recvfrom($client, 1500);
    
    if($received){
        echo "received data\n";
        echo "responding\n";
        fwrite($client, "HTTP/1.1 200 OK\n");
        fwrite($client, "Content-Type: application/json\n");
        fwrite($client, "Transfer-Encoding: chunked\n");
        fwrite($client, "\n");

        for (;;) {
            foreach($threes as $i=>$tweets){
                echo "sending tweet {$i}\n";

                fwrite($client, dechex(strlen($tweets[0]) + 2)."\n");
                fwrite($client, $tweets[0]."\r\n");
                fwrite($client, "\n");
                fwrite($client, "\n");
                fwrite($client, dechex(strlen($tweets[1]) + 2)."\n");
                fwrite($client, $tweets[1]."\r\n");
                fwrite($client, "\n");
                fwrite($client, "\n");
                usleep(500000);
            }
            echo "\n\nreseting\n";
        }
    }
}

