<?php
session_start();
session_destroy(); // Remove todas as variáveis da sessão
header("Location: login_conta.html"); // Redireciona para a página de login
exit();
