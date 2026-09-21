#!/usr/bin/env bash
#
# Entrypoint do container "vite" (dev server com HMR, profile "dev").
#
# Usa a mesma imagem do app, para compartilhar o Node e a lib de permissoes:
# o dev server precisa escrever em node_modules/.vite com o mesmo dono que o
# container do PHP usa, senao um quebra o npm do outro.
#
set -euo pipefail

# shellcheck source=/var/www/docker/lib.sh
. /usr/local/lib/docker-app/lib.sh

LOG_TAG="vite"

VITE_PORT="${VITE_PORT:-5173}"

cd "$APP_DIR"

sync_app_user
prepare_app_home
install_node_deps

# Enquanto este container roda, o Laravel serve os assets pelo dev server
# (atraves do arquivo public/hot). Ao parar o container o Vite remove esse
# arquivo e a aplicacao volta a usar o build estatico de public/build.
log "subindo o dev server em http://localhost:${VITE_PORT}"

exec_as_app npm --prefix "$APP_DIR" run dev -- --host 0.0.0.0 --port "$VITE_PORT"
