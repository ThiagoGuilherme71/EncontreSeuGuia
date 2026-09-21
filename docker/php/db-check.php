<?php

/**
 * Helper usado pelo entrypoint do container.
 *
 *   php db-check.php wait       <app_dir> [timeout]  -> aguarda o banco aceitar conexões
 *   php db-check.php needs-seed <app_dir>            -> exit 0 se o banco está vazio (precisa de seed)
 *
 * Lê a configuração do .env do projeto respeitando a mesma precedência do
 * Laravel: variáveis de ambiente reais ganham do que está escrito no arquivo.
 */

$command = $argv[1] ?? '';
$appDir  = rtrim($argv[2] ?? '/var/www', '/');
$timeout = (int) ($argv[3] ?? 60);

$fileEnv = [];
$envPath = $appDir . '/.env';

if (is_readable($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $fileEnv[trim($key)] = trim(trim($value), "\"'");
    }
}

$env = function (string $key, ?string $default = null) use ($fileEnv): ?string {
    $value = getenv($key);

    if ($value !== false && $value !== '') {
        return $value;
    }

    return $fileEnv[$key] ?? $default;
};

$connection = $env('DB_CONNECTION', 'mysql');

// Só faz sentido esperar por um banco que roda em outro container.
if (!in_array($connection, ['mysql', 'mariadb', 'pgsql'], true)) {
    exit(0);
}

$driver = $connection === 'pgsql' ? 'pgsql' : 'mysql';
$dsn    = sprintf(
    '%s:host=%s;port=%d;dbname=%s',
    $driver,
    $env('DB_HOST', 'db'),
    (int) $env('DB_PORT', $driver === 'pgsql' ? '5432' : '3306'),
    $env('DB_DATABASE', 'laravel'),
);

$user    = $env('DB_USERNAME', 'root');
$pass    = $env('DB_PASSWORD', '');
$options = [PDO::ATTR_TIMEOUT => 3, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

$connect = fn (): PDO => new PDO($dsn, $user, $pass, $options);

switch ($command) {
    case 'wait':
        $deadline = time() + $timeout;

        while (true) {
            try {
                $connect();
                exit(0);
            } catch (PDOException $e) {
                if (time() >= $deadline) {
                    fwrite(STDERR, "Banco nao respondeu em {$timeout}s: {$e->getMessage()}\n");
                    exit(1);
                }

                sleep(2);
            }
        }

    case 'needs-seed':
        try {
            $count = (int) $connect()->query('SELECT COUNT(*) FROM users')->fetchColumn();
        } catch (PDOException $e) {
            // Tabela ainda nao existe: trate como banco vazio.
            exit(0);
        }

        exit($count > 0 ? 1 : 0);

    default:
        fwrite(STDERR, "Uso: db-check.php {wait|needs-seed} <app_dir> [timeout]\n");
        exit(2);
}
