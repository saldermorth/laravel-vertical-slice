// Save as test.php
<?php
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "Socket created successfully.\n";
    $result = socket_bind($socket, '127.0.0.1', 9876);
    if ($result === false) {
        echo "socket_bind() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
    } else {
        echo "Socket bound to 127.0.0.1:9876 successfully.\n";
    }
    socket_close($socket);
}
