#!/usr/bin/env bash
#
# Funcoes compartilhadas pelos entrypoints dos containers "app" e "vite".
# Ambos rodam sobre o mesmo bind mount, entao precisam escrever com o mesmo
# dono -- senao um container cria arquivos que o outro nao consegue remover.
#

APP_DIR="${APP_DIR:-/var/www}"
APP_USER="${APP_USER:-www-data}"
APP_HOME="${APP_HOME:-/tmp/app-home}"

log()  { printf '\033[1;36m[%s]\033[0m %s\n' "${LOG_TAG:-entrypoint}" "$*"; }
warn() { printf '\033[1;33m[%s]\033[0m %s\n' "${LOG_TAG:-entrypoint}" "$*" >&2; }

# Roda um comando como o usuario da aplicacao, para que os arquivos criados no
# volume montado pertencam ao dono do projeto no host (e nao ao root).
run_as_app() {
    runuser -u "$APP_USER" -- env \
        HOME="$APP_HOME" \
        COMPOSER_HOME="$APP_HOME/composer" \
        COMPOSER_ALLOW_SUPERUSER=1 \
        npm_config_cache="$APP_HOME/npm" \
        npm_config_update_notifier=false \
        npm_config_fund=false \
        "$@"
}

# Igual ao run_as_app, mas substitui o shell pelo processo -- para que o
# processo final do container receba os sinais do docker diretamente.
exec_as_app() {
    exec runuser -u "$APP_USER" -- env \
        HOME="$APP_HOME" \
        COMPOSER_HOME="$APP_HOME/composer" \
        COMPOSER_ALLOW_SUPERUSER=1 \
        npm_config_cache="$APP_HOME/npm" \
        npm_config_update_notifier=false \
        npm_config_fund=false \
        "$@"
}

# O bind mount traz os arquivos com o UID/GID do host. Alinhamos o usuario da
# aplicacao a eles para que o PHP-FPM e o Vite consigam escrever no projeto e
# os arquivos gerados fiquem com o dono certo dos dois lados.
sync_app_user() {
    local host_uid host_gid current_uid current_gid

    host_uid="$(stat -c '%u' "$APP_DIR")"
    host_gid="$(stat -c '%g' "$APP_DIR")"

    if [ "$host_uid" = "0" ]; then
        return 0
    fi

    current_uid="$(id -u "$APP_USER")"
    current_gid="$(id -g "$APP_USER")"

    if [ "$current_gid" != "$host_gid" ]; then
        groupmod -o -g "$host_gid" "$APP_USER"
    fi

    if [ "$current_uid" != "$host_uid" ]; then
        usermod -o -u "$host_uid" -g "$host_gid" "$APP_USER"
        log "usuario $APP_USER alinhado ao dono do projeto (${host_uid}:${host_gid})"
    fi
}

prepare_app_home() {
    mkdir -p "$APP_HOME"
    chown -R "$APP_USER:$APP_USER" "$APP_HOME"
}

# Instala node_modules/ quando faltar ou quando o package-lock.json for mais
# novo que a ultima instalacao. O marcador e proprio porque o npm ci recria o
# diretorio inteiro.
install_node_deps() {
    local stamp="$APP_DIR/node_modules/.entrypoint-install"

    if [ -d "$APP_DIR/node_modules" ] && [ -f "$stamp" ] && [ "$APP_DIR/package-lock.json" -ot "$stamp" ]; then
        return 0
    fi

    # Resquicios de root (ex.: node_modules/.vite escrito por outro container)
    # fariam o npm falhar com EACCES ao limpar o diretorio.
    if [ -d "$APP_DIR/node_modules" ]; then
        chown -R "$APP_USER:$APP_USER" "$APP_DIR/node_modules" 2>/dev/null || true
    fi

    if [ -f "$APP_DIR/package-lock.json" ]; then
        log "instalando dependencias do frontend (npm ci)..."
        run_as_app npm --prefix "$APP_DIR" ci --no-audit --no-fund
    else
        log "instalando dependencias do frontend (npm install)..."
        run_as_app npm --prefix "$APP_DIR" install --no-audit --no-fund
    fi

    run_as_app touch "$stamp"
}
