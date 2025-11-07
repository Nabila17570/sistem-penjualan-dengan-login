<?php
// Commit 3: Proses Session
session_start();

// Commit 4: Saat logout ditekan, hapus session
session_unset();
session_destroy();

// Commit 4: arahkan kembali ke index.php.
header("location: index.php");
exit;
?>