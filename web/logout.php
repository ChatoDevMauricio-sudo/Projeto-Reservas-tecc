<?php
require_once __DIR__ . '/includes/funcoes.php';
session_destroy();
redirect('login.php');
