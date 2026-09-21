#!/usr/bin/env bash
#
# Entrypoint do container "app" (PHP-FPM).
#
# Deixa o projeto pronto para uso na subida do container: cria o .env, instala
# as dependencias no volume montado, gera a APP_KEY, compila os assets, espera
# o MySQL, roda migrations/seed, cria o link de storage e so entao sobe o
# PHP-FPM. Tudo e idempotente: pode subir e descer o container a vontade.
#
# Knobs (definidos no docker-compose.yml ou no ambiente do `docker compose`):
#   AUTO_INSTALL      true|false        instala vendor/ e node_modules/ quando faltarem
#                                       ou quando o lockfile for mais novo que eles
#   AUTO_BUILD        auto|true|false   "auto" = so builda se public/build nao existir
#   AUTO_MIGRATE      true|false        roda php artisan migrate --force
#   AUTO_SEED         auto|true|false   "auto" = so semeia se o banco estiver vazio
#   DB_WAIT_TIMEOUT   segundos          tempo maximo esperando o banco (padrao 90)
#
set -euo pipefail

# shellcheck source=/var/www/docker/lib.sh
. /usr/local/lib/docker-app/lib.sh

LOG_TAG="entrypoint"

AUTO_INSTALL="${AUTO_INSTALL:-true}"
AUTO_BUILD="${AUTO_BUILD:-auto}"
AUTO_MIGRATE="${AUTO_MIGRATE:-true}"
AUTO_SEED="${AUTO_SEED:-auto}"
DB_WAIT_TIMEOUT="${DB_WAIT_TIMEOUT:-90}"

HELPER_DIR="/usr/local/bin"

artisan() { run_as_app php "$APP_DIR/artisan" "$@"; }

ensure_env_file() {
    if [ -f "$APP_DIR/.env" ]; then
        return 0
    fi

    if [ ! -f "$APP_DIR/.env.example" ]; then
        warn "nem .env nem .env.example encontrados em $APP_DIR"
        exit 1
    fi

    cp "$APP_DIR/.env.example" "$APP_DIR/.env"
    chown "$APP_USER:$APP_USER" "$APP_DIR/.env"
    log ".env criado a partir do .env.example"
}

ensure_writable_dirs() {
    mkdir -p \
        "$APP_DIR/storage/framework/cache/data" \
        "$APP_DIR/storage/framework/sessions" \
        "$APP_DIR/storage/framework/views" \
        "$APP_DIR/storage/logs" \
        "$APP_DIR/storage/app/public" \
        "$APP_DIR/bootstrap/cache"

    chown -R "$APP_USER:$APP_USER" "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
    chmod -R ug+rwX "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
}

install_php_deps() {
    local stamp="$APP_DIR/vendor/.entrypoint-install"

    if [ "$AUTO_INSTALL" != "true" ]; then
        return 0
    fi

    # Reinstala tambem quando o composer.lock for mais novo que o vendor/:
    # sinal de que as dependencias mudaram desde a ultima subida. O marcador e
    # proprio porque o composer nao atualiza o mtime do autoload.php quando nao
    # ha nada a instalar.
    if [ -f "$APP_DIR/vendor/autoload.php" ] && [ -f "$stamp" ] && [ "$APP_DIR/composer.lock" -ot "$stamp" ]; then
        return 0
    fi

    log "instalando dependencias PHP (composer install)..."
    run_as_app composer install --working-dir="$APP_DIR" --no-interaction --prefer-dist
    run_as_app touch "$stamp"
}

maybe_install_node_deps() {
    if [ "$AUTO_INSTALL" != "true" ] || [ "$AUTO_BUILD" = "false" ]; then
        return 0
    fi

    install_node_deps
}

build_assets() {
    case "$AUTO_BUILD" in
        false)
            return 0
            ;;
        auto)
            if [ -f "$APP_DIR/public/build/manifest.json" ]; then
                log "assets ja compilados (public/build); pulando o build"
                return 0
            fi
            ;;
    esac

    log "compilando assets (npm run build)..."
    run_as_app npm --prefix "$APP_DIR" run build
}

ensure_app_key() {
    if grep -qE '^APP_KEY=.+$' "$APP_DIR/.env"; then
        return 0
    fi

    log "gerando APP_KEY..."
    artisan key:generate --force --ansi
}

wait_for_database() {
    log "aguardando o banco de dados..."
    run_as_app php "$HELPER_DIR/db-check.php" wait "$APP_DIR" "$DB_WAIT_TIMEOUT"
    log "banco de dados disponivel"
}

run_migrations() {
    if [ "$AUTO_MIGRATE" != "true" ]; then
        return 0
    fi

    log "rodando migrations..."
    artisan migrate --force --ansi
}

run_seeders() {
    case "$AUTO_SEED" in
        false)
            return 0
            ;;
        auto)
            if ! run_as_app php "$HELPER_DIR/db-check.php" needs-seed "$APP_DIR"; then
                log "banco ja populado; pulando o seed"
                return 0
            fi
            ;;
    esac

    log "populando o banco (db:seed)..."
    artisan db:seed --force --ansi
}

link_storage() {
    # --force recria o link caso ele aponte para um caminho antigo.
    artisan storage:link --force --quiet
}

clear_caches() {
    # Caches de config/rota/view podem ter ficado de uma execucao anterior com
    # outro .env. cache:clear fica de fora de proposito: o store e o banco, que
    # ainda pode nao estar migrado neste ponto.
    artisan config:clear --quiet
    artisan route:clear --quiet
    artisan view:clear --quiet
}

main() {
    cd "$APP_DIR"

    # Evita o aviso "dubious ownership" do git em cima do volume montado.
    git config --global --add safe.directory "$APP_DIR" 2>/dev/null || true

    sync_app_user
    prepare_app_home

    ensure_env_file
    ensure_writable_dirs
    install_php_deps
    clear_caches
    ensure_app_key
    maybe_install_node_deps
    build_assets
    wait_for_database
    run_migrations
    run_seeders
    link_storage

    log "aplicacao pronta em http://localhost:8000"
    log "subindo o PHP-FPM..."

    exec "$@"
}

main "$@"
