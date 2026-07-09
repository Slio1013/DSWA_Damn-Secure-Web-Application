<?php
/**
 * VULNERABLE APP - DB CONNECTION
 * ------------------------------------------------------------------
 * NOTE ON DATABASE ENGINE:
 * The task asked for a "db connection". In this sandbox there is no
 * MySQL server available, so SQLite is used as a stand-in (same PHP
 * PDO logic applies). To point this at real MySQL, swap this block:
 *
 *   $pdo = new PDO('mysql:host=localhost;dbname=vulnapp', 'root', '');
 *
 * Everything else (queries, vulnerabilities, fixes) is identical
 * between MySQL and SQLite for the purposes of this exercise.
 * ------------------------------------------------------------------
 * INTENTIONAL WEAKNESSES IN THIS FILE (v1 - do not use in production):
 *  - Plaintext passwords stored and compared directly
 *  - No least-privilege DB user
 *  - Errors/exceptions will leak stack traces (no error handling)
 */

$dbFile = __DIR__ . '/../data/vulnapp.sqlite';
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); // intentionally NOT exception mode

function seed_database($pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT,
        password TEXT,
        role TEXT
    )");

    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($count == 0) {
        // Plaintext passwords - intentional vulnerability
        $pdo->exec("INSERT INTO users (username, password, role) VALUES
            ('admin', 'SuperSecret123', 'admin'),
            ('john', 'password1', 'user'),
            ('mary', 'letmein', 'user')");
    }
}
seed_database($pdo);
