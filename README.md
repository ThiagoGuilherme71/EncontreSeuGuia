comandos pra executar as migrations:
php artisan migrate --path=database/migrations/0001_01_01_000000_create_users_table.php
php artisan migrate --path=database/migrations/2025_03_26_130911_create_guias_table.php
php artisan migrate --path=database/migrations/2025_04_14_171240_create_dificuldades_table.php
php artisan migrate --path=database/migrations/2025_04_14_171240_create_trilhas_table.php
php artisan migrate --path=database/migrations/2025_04_14_171240_create_agendamentos_table.php
php artisan migrate --path=database/migrations/2025_04_14_171240_create_avaliacoes_table.php
php artisan migrate --path=database/migrations/2025_04_14_171240_create_guia_sessions_table.php
php artisan migrate --path=database/migrations/2025_05_08_120731_create_idiomas_table.php
php artisan migrate --path=database/migrations/2025_05_06_131023_create_trilhas_guias_table.php
php artisan migrate --path=database/migrations/2025_05_08_120832_create_idiomas_guias_table.php

comandos pra executar o seed:
php artisan db:seed
