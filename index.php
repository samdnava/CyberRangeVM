<?php
session_start();

if(!isset($_SESSION['last_login'])) {
    $_SESSION['last_login'] = date('Y-m-d H:i:s');
}

// Our "Database" of users
$users = [
    'admin_boss' => ['pw' => 'SuperSecure123', 'role' => 'admin', 'msg' => 'Welcome back, Commander.'],
    'intern_greg' => ['pw' => 'dont_fire_me', 'role' => 'low-level', 'msg' => 'Greg, did you finish that spreadsheet?'],
    'shadow_board' => ['pw' => 'OldGuard2026', 'role' => 'alumni', 'msg' => 'Welcome, Shadow. The tradition continues.']
];

// Login Logic
if (isset($_POST['login'])) {
    $u = $_POST['user'];
    $p = $_POST['pass'];
    
    if (isset($users[$u]) && $users[$u]['pw'] === $p) {
        $_SESSION['user'] = $u;
        $_SESSION['role'] = $users[$u]['role'];
    } else {
        $error = "Access Denied. Your failure has been logged.";
    }
}

// Logout Logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CR-Ubuntu | Secure Portal</title>
    <style>
        body { font-family: 'Courier New', monospace; background: #0e1621; color: #58a6ff; text-align: center; padding-top: 50px; }
        .box { background: #17212b; padding: 30px; border-radius: 10px; border: 1px solid #30363d; display: inline-block; min-width: 350px; }
        input { width: 80%; padding: 10px; margin: 10px 0; background: #0d1117; border: 1px solid #30363d; color: white; }
        button { background: #238636; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
        .admin { border-top: 5px solid #4cd964; }
        .intern { border-top: 5px solid #f1c40f; }
        .alumni { border-top: 5px solid #0088cc; }
    </style>
</head>
<body>
    <div class="box">
        <?php if (!isset($_SESSION['user'])): ?>
            <h1>[ NODE LOGIN ]</h1>
            <?php if (isset($error)) echo "<p style='color:#ff4d4d'>$error</p>"; ?>
            <form method="post">
                <input type="text" name="user" placeholder="Username" required><br>
                <input type="password" name="pass" placeholder="Password" required><br>
                <button type="submit" name="login">AUTHENTICATE</button>
            </form>
        <?php else: ?>
            <div class="<?php echo ($_SESSION['role'] == 'admin') ? 'admin' : (($_SESSION['role'] == 'alumni') ? 'alumni' : 'intern'); ?>">
                <h1><?php echo $users[$_SESSION['user']]['msg']; ?></h1>
                <p>Status: <b style="color:white"><?php echo strtoupper($_SESSION['role']); ?> ACCESS GRANTED</b></p>
                <hr style="border-color:#30363d">
                
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <h3>Admin Controls</h3>
                    <p>🚀 [ NUKE PROMPT ] - Ready</p>
                    <p>🛡️ [ HARDENING ] - 100% Complete</p>
                    <p><i>"The board is impressed. Mostly."</i></p>
                    <p>🕒 Last Secure Session: <?php echo $_SESSION['last_login']; ?></p>
                <?php elseif ($_SESSION['role'] == 'alumni'): ?>
                    <h3>Alumni Archive Access</h3>
                    <p>📜 Records: [UNMODIFIED]</p>
                    <p>🏛️ Status: Legacy Systems Maintained</p>
                    <p><i>"We haven't touched the coffee machine since the incident in '18."</i></p>

                <?php else: ?>
                    <h3>Intern View</h3>
                    <p>☕ Coffee Machine Status: EMPTY</p>
                    <p>🗑️ Tasks: Re-alphabetize the server rack cables.</p>
                    <p><i>"Please stop touching things, Greg."</i></p>
                <?php endif; ?>
                
                <br><a href="?logout=1" style="color:#8b949e; text-decoration:none;">[ SECURE LOGOUT ]</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
