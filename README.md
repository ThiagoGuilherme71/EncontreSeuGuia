# 🥾 Trilhas — Encontre seu Guia

Plataforma social que conecta **trilheiros** a **guias locais** para explorar trilhas com segurança. O trilheiro descobre trilhas pela cidade, agenda com um guia, paga, conversa pelo chat e — depois da aventura — avalia o guia e posta as fotos do rolê, que viram um feed social na página da trilha.

> ⚠️ **Projeto em modo MVP/Sandbox**: aprovações de guias e trilhas são automáticas, o pagamento é simulado e as notificações são internas (sem push). Veja [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) para detalhes técnicos.

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
| Infra local | Docker Compose (PHP-FPM + Nginx + MySQL) |
| Ícones | Lucide React |

## 🚀 Como rodar

Pré-requisitos: Docker e Docker Compose.

```bash
# 1. Clone e configure
git clone <repo>
cd EncontreSeuGuia
cp .env.example .env   # confira as credenciais abaixo

# 2. Suba os containers (app + nginx + mysql)
docker compose up -d --build

# 3. Prepare a aplicação
docker exec laravel_app php artisan key:generate
docker exec laravel_app php artisan migrate --seed
docker exec laravel_app php artisan storage:link

# 4. Build do frontend
npm install
npm run build      # ou: npm run dev (para desenvolvimento com HMR)
```

App disponível em **http://localhost:8000**.

### Configuração do `.env` (banco via Docker)

```env
DB_CONNECTION=mysql
DB_HOST=db            # nome do serviço no docker-compose
DB_PORT=3306
DB_DATABASE=encontre_seu_guia
DB_USERNAME=user
DB_PASSWORD=secret
```

### 👤 Usuários de teste (seed)

| Papel | E-mail | Senha |
|---|---|---|
| Trilheiro | `teste@teste.com` | `123456` |
| Guia | `joao@guia.com` | `123456` |
| Guia | `maria@guia.com` | `123456` |

O seed cria 6 trilhas reais da Chapada Diamantina (BA), 6 guias com idiomas e os vínculos guia↔trilha.

## 📱 Mobile-first

Todo o layout foi construído mobile-first: bottom navigation no celular, formulários otimizados pra toque, upload de foto direto da câmera e imagens comprimidas server-side (pensado pra conexão fraca de área de trilha).

## 📄 Documentação

- [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) — arquitetura, modelo de dados, regras de negócio e decisões técnicas

## 📌 Status

MVP funcional em sandbox. Próximos passos planejados: gateway de pagamento real, aprovação manual de guias/trilhas, push notifications e moderação de conteúdo.
