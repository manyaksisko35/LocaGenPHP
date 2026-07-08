<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocaGen Pro - AI Localization</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #05010a !important;
            color: #f0e6ff !important;
            font-family: 'Consolas', 'Courier New', monospace !important;
        }
        .cyber-nav {
            background-color: #0a0214 !important;
            border-bottom: 1px solid #450082 !important;
            box-shadow: 0 0 20px rgba(69, 0, 130, 0.4);
        }
        .cyber-brand {
            color: #b026ff !important;
            font-weight: bold;
            font-size: 1.5em;
            text-shadow: 0 0 10px #b026ff;
            letter-spacing: 2px;
            text-decoration: none;
        }
        .cyber-brand:hover {
            color: #00f0ff !important;
            text-shadow: 0 0 10px #00f0ff;
        }
        .user-text {
            color: #00f0ff !important;
            letter-spacing: 1px;
            font-weight: bold;
        }
        .btn-cyber-logout {
            background: transparent;
            color: #ff003c;
            border: 1px solid #ff003c;
            border-radius: 4px;
            font-family: 'Consolas', monospace;
            font-weight: bold;
            padding: 6px 15px;
            transition: 0.3s all;
            box-shadow: 0 0 10px rgba(255, 0, 60, 0.2);
            text-decoration: none;
            display: inline-block;
        }
        .btn-cyber-logout:hover {
            background: #ff003c;
            color: #000;
            box-shadow: 0 0 20px rgba(255, 0, 60, 0.6);
        }
        .btn-cyber-login {
            background: transparent;
            color: #00f0ff;
            border: 1px solid #00f0ff;
            border-radius: 4px;
            font-family: 'Consolas', monospace;
            font-weight: bold;
            padding: 6px 15px;
            transition: 0.3s all;
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.2);
            text-decoration: none;
            display: inline-block;
        }
        .btn-cyber-login:hover {
            background: #00f0ff;
            color: #000;
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.6);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg cyber-nav mb-4">
    <div class="container">
        <a class="navbar-brand cyber-brand" href="index.php">&gt;_ LOCAGEN_PRO</a>
        
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item me-3">
                        <span class="nav-link user-text">USR_NODE: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="btn-cyber-logout" href="logout.php">[ DISCONNECT ]</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn-cyber-login" href="login.php">[ AUTHENTICATE ]</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">