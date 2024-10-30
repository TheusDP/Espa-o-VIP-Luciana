<?php
session_start();
session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destroi a sessão
echo json_encode(["loggedOut" => true]);
?>