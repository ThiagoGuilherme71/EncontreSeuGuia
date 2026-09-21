# 🥾 Trilhas — Encontre seu Guia

Plataforma social que conecta **trilheiros** a **guias locais** para explorar trilhas com segurança. O trilheiro descobre trilhas pela cidade, agenda com um guia, paga, conversa pelo chat e — depois da aventura — avalia o guia e posta as fotos do rolê, que viram um feed social na página da trilha.

> ⚠️ **Projeto em modo MVP/Sandbox**: aprovações de guias e trilhas são automáticas, o pagamento é simulado e as notificações são internas (sem push).

## ✨ Funcionalidades

### Para quem vai trilhar
- 🔍 Feed de trilhas com busca por nome e filtro por cidade (com paginação)
- 🏔️ Página da trilha com dificuldade, guias disponíveis (com avaliações e idiomas) e fotos de aventuras anteriores
- 📅 Agendamento por proposta: escolhe guia, data, nº de pessoas → o guia aceita ou rejeita
- 💳 Pagamento simulado com recibo imprimível
- 💬 Chat com o guia (liberado após o aceite, fecha 1 dia antes da trilha)
- ⭐ Avaliação do guia com estrelas + comentário após a trilha
- 📸 Postar fotos da aventura, que aparecem públicas na página da trilha
- 🔔 Notificações internas (proposta aceita/rejeitada, mensagens, cancelamentos)

### Para guias
- 🧭 Dashboard com 4 abas: **Propostas recebidas**, **Minhas trilhas**, **Trilhas que me cadastrei** e **Histórico**
- ✅ Aceitar/rejeitar propostas (com motivo opcional)
- 🗺️ Criar e editar trilhas (aprovação automática no sandbox)
- ❄️ Congelar/reativar inscrição em trilhas (sai da lista de guias disponíveis sem perder o vínculo)
- ⭐ Recebe avaliações e notificações de novas propostas
- 📸 Também posta fotos das aventuras que guiou

## 🛠️ Stack

| Camada | Tecnologia |
|---|---|
| Backend | Laravel 12 (PHP 8.2) |
| Frontend | React 18 + Inertia.js |
| Estilo | TailwindCSS 4 — design Zine/Screen-Print com doodles |
| Banco | MySQL 8 |
| Build | Vite |
| Infra local | Docker Compose (PHP-FPM + Nginx + MySQL + Vite), pronto na subida |
| Ícones | Lucide React |

## 🚀 Como rodar

Pré-requisito único: **Docker** (com Docker Compose). Não é preciso ter PHP, Composer, Node ou MySQL instalados na máquina.

```bash
git clone <repo>
cd EncontreSeuGuia
docker compose up -d --build
```

Pronto — app em **http://localhost:8000**.

O entrypoint do container faz o resto sozinho na subida: cria o `.env` a partir do `.env.example`, instala as dependências (`composer install` e `npm ci`), gera a `APP_KEY`, compila os assets, espera o MySQL aceitar conexões, roda as migrations, popula o banco e cria o link de `storage`. Tudo é idempotente e pula o que já está pronto, então as subidas seguintes levam poucos segundos.

### Serviços

| Serviço | Container | Porta | O que é |
|---|---|---|---|
| `nginx` | `laravel_nginx` | **8000** | Servidor web — é por aqui que você acessa o app |
| `app` | `laravel_app` | 9000 (interna) | PHP-FPM 8.2 + Node 22, roda o entrypoint |
| `db` | `laravel_db` | 3306 | MySQL 8 |
| `vite` | `laravel_vite` | 5173 | Dev server com HMR — **opcional**, só sobe com o profile `dev` |

O `nginx` só começa a aceitar requisições depois que o `app` sinaliza que terminou o preparo, e o `app` só começa depois que o MySQL está saudável — você não pega erro de "banco indisponível" no meio da subida.

### Desenvolvimento com HMR

Por padrão o app usa os assets compilados em `public/build`. Para desenvolver o frontend com hot reload:

```bash
docker compose --profile dev up -d
```

Enquanto o container `vite` estiver de pé, o Laravel serve os assets pelo dev server em `:5173`. Ao pará-lo (`docker compose stop vite`), o app volta sozinho ao build estático.

### Ajustando o comportamento da subida

Cada etapa do entrypoint tem um knob. `auto` faz a etapa só quando ela é necessária:

| Variável | Padrão | Efeito |
|---|---|---|
| `AUTO_INSTALL` | `true` | Instala `vendor/` e `node_modules/` quando faltam ou quando o lockfile mudou |
| `AUTO_BUILD` | `auto` | `auto` compila só se `public/build` não existir; `true` força; `false` pula |
| `AUTO_MIGRATE` | `true` | Roda `php artisan migrate --force` |
| `AUTO_SEED` | `auto` | `auto` popula só se o banco estiver vazio; `true` força; `false` pula |
| `DB_WAIT_TIMEOUT` | `90` | Segundos aguardando o MySQL antes de desistir |

```bash
# recompilar os assets e repopular o banco nesta subida
AUTO_BUILD=true AUTO_SEED=true docker compose up -d
```

### Comandos do dia a dia

```bash
docker compose logs -f app                      # acompanhar o preparo e os erros
docker exec -it laravel_app bash                # shell no container
docker exec laravel_app php artisan tinker      # console do Laravel
docker exec laravel_app php artisan migrate     # migrations manuais
docker compose down                             # derruba (mantém o banco)
docker compose down -v                          # derruba e apaga o banco
```

### Configuração do `.env`

O arquivo é criado automaticamente a partir do `.env.example`. Os valores do banco já vêm apontados para o container:

```env
DB_CONNECTION=mysql
DB_HOST=db            # nome do serviço no docker-compose
DB_PORT=3306
DB_DATABASE=encontre_seu_guia
DB_USERNAME=user
DB_PASSWORD=secret
```

Se você editar o `.env`, reinicie o app para recarregar: `docker compose restart app`.

### 👤 Usuários de teste (seed)

| Papel | E-mail | Senha |
|---|---|---|
| Trilheiro | `thiagoguilherme.barbosaa@gmail.com` | `123456` |
| Guia | `carlos.nascimento.guia@gmail.com` | `123456` |

O seed cria 2 trilhas reais da Chapada Diamantina (BA) — Cachoeira da Fumacinha e Vale do Pati —, 1 guia com idiomas, as 3 dificuldades, os 6 idiomas e os vínculos guia↔trilha.

> As fotos das trilhas só aparecem se existirem imagens em `storage/app/seeders/` (não versionadas). Sem elas o seed roda normalmente, apenas sem foto de capa.

## 📱 Mobile-first

Todo o layout foi construído mobile-first: bottom navigation no celular, formulários otimizados pra toque, upload de foto direto da câmera e imagens comprimidas server-side (pensado pra conexão fraca de área de trilha).

## 📌 Status

MVP funcional em sandbox. Próximos passos planejados: gateway de pagamento real, aprovação manual de guias/trilhas, push notifications e moderação de conteúdo.
