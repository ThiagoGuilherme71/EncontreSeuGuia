# Encontre seu Guia

Plataforma que conecta trilheiros a guias locais credenciados. O trilheiro descobre trilhas por cidade, envia uma proposta de agendamento a um guia, negocia pelo chat, paga e — depois da trilha — avalia o guia e publica as fotos, que compõem um feed público na página da trilha.

Projeto pessoal, desenvolvido para exercitar um domínio completo de ponta a ponta: autenticação multi-perfil, máquina de estados de agendamento, upload e processamento de mídia, e infraestrutura reproduzível em Docker.

> **Escopo — MVP em modo sandbox.** O pagamento é simulado, a aprovação de guias e trilhas é automática e as notificações são internas (sem push ou e-mail). As decisões de produto que ficaram fora do escopo estão listadas em [Limitações conhecidas](#limitações-conhecidas).

---

## Sumário

- [Domínio](#domínio)
- [Decisões de arquitetura](#decisões-de-arquitetura)
- [Stack](#stack)
- [Execução local](#execução-local)
- [Desenvolvimento](#desenvolvimento)
- [Estrutura do projeto](#estrutura-do-projeto)
- [Modelo de dados](#modelo-de-dados)
- [Testes](#testes)
- [Limitações conhecidas](#limitações-conhecidas)
- [Licença](#licença)

---

## Domínio

O sistema tem dois perfis de usuário, com tabelas e sessões independentes.

**Trilheiro**

- Feed de trilhas paginado, com busca por nome e filtro por cidade
- Página da trilha com dificuldade, distância, ponto de encontro em mapa, guias disponíveis (preço, idiomas e média de avaliações) e o feed de aventuras anteriores
- Agendamento por proposta: escolhe guia, data e número de pessoas; o guia aceita ou recusa
- Pagamento simulado, com recibo imprimível
- Chat com o guia, liberado após o aceite e encerrado um dia antes da trilha
- Avaliação com nota e comentário após a conclusão
- Publicação de fotos da aventura, exibidas publicamente na página da trilha

**Guia**

- Painel com propostas recebidas, trilhas próprias, trilhas em que se inscreveu e histórico
- Aceite ou recusa de propostas, com motivo opcional
- Cadastro e edição de trilhas
- Preço por pessoa definido por inscrição, não por trilha: o mesmo percurso pode ter preços diferentes conforme o guia
- Congelamento da inscrição, que retira o guia da lista de disponíveis sem desfazer o vínculo nem o histórico

### Ciclo de vida do agendamento

```
pending ──aceite──> accepted ──pagamento──> accepted (pago) ──data passada──> completed ──> avaliação + fotos
   │                    │
   └──recusa──> rejected└──cancelamento──> cancelled
```

O cancelamento é permitido a qualquer uma das partes até o fim do dia anterior à trilha. A transição para `completed` ocorre quando a data já passou.

---

## Decisões de arquitetura

**Dois guards de autenticação em vez de um campo `role`.** Trilheiros e guias têm atributos quase disjuntos — o guia tem CEP, endereço, anos de experiência, documentos e idiomas; o trilheiro não. Modelar os dois na mesma tabela produziria uma tabela majoritariamente nula. O projeto usa dois guards de sessão (`web` e `guia`) sobre providers distintos, e as rotas que servem aos dois perfis declaram `auth:web,guia`.

**Autorização resolvida por participação, não por papel.** Agendamento, chat e fotos pertencem a exatamente duas pessoas. Cada controller resolve o ator a partir do guard autenticado e confronta com os donos do registro, abortando com 403 quando não há correspondência — em vez de confiar no identificador enviado pelo cliente.

**Detecção de conflito de agenda por sobreposição de intervalos.** Uma proposta é recusada quando o intervalo `[início, início + duração estimada da trilha)` colide com outro agendamento pendente ou aceito do mesmo guia no dia. A mensagem de erro devolve o horário em que o guia volta a ficar livre. Os horários já ocupados também são enviados ao formulário, para o cliente sinalizar o conflito antes do envio.

**Processamento de imagem no servidor.** Fotos de celular chegam com 5–12 MB e o uso previsto é em área de trilha, com conexão ruim. Todo upload passa por `App\Support\ImageResizer`: é decodificado via GD, tem a orientação EXIF corrigida, é redimensionado a uma largura máxima, reescrito como JPEG de qualidade 80 e salvo com nome UUID. Fotos de aventura ganham também uma miniatura de 480 px para o feed. O reencode tem o efeito colateral desejável de descartar qualquer conteúdo que não seja imagem.

**Ambiente reproduzível por entrypoint idempotente.** O único pré-requisito é Docker. O entrypoint do container `app` cria o `.env`, instala dependências, gera a `APP_KEY`, compila os assets, aguarda o MySQL, roda migrations e seeds e cria o link de storage — pulando cada etapa já concluída. Os healthchecks são encadeados: o `nginx` só aceita requisições depois que o `app` sinaliza prontidão, e o `app` só inicia com o banco saudável.

---

## Stack

| Camada | Tecnologia |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | React 19 + Inertia.js (SPA sem API separada) |
| Estilo | Tailwind CSS 4 |
| Banco | MySQL 8 |
| Build | Vite 6 |
| Mapas | Leaflet / React Leaflet |
| Ícones | Lucide React |
| Infraestrutura | Docker Compose — PHP-FPM, Nginx, MySQL, Vite |

---

## Execução local

Pré-requisito único: Docker com Compose. Não é necessário ter PHP, Composer, Node ou MySQL instalados.

```bash
git clone git@github.com:ThiagoGuilherme71/EncontreSeuGuia.git
cd EncontreSeuGuia
docker compose up -d --build
```

A aplicação sobe em **http://localhost:8000**. A primeira subida leva alguns minutos (build da imagem, `composer install`, `npm ci` e compilação dos assets); as seguintes levam segundos.

Acompanhe o preparo com `docker compose logs -f app`.

### Serviços

| Serviço | Container | Porta | Função |
|---|---|---|---|
| `nginx` | `laravel_nginx` | 8000 | Servidor web — ponto de entrada |
| `app` | `laravel_app` | 9000 (interna) | PHP-FPM 8.2 e Node 22 |
| `db` | `laravel_db` | 3306 | MySQL 8 |
| `vite` | `laravel_vite` | 5173 | Dev server com HMR (profile `dev`) |

### Dados de exemplo

O seed cria duas trilhas reais da Chapada Diamantina (Cachoeira da Fumacinha e Vale do Pati), um guia com três idiomas, as dificuldades, os idiomas e os vínculos guia–trilha.

Contas de demonstração:

| Perfil | E-mail | Senha |
|---|---|---|
| Trilheiro | `trilheiro@exemplo.test` | `123456` |
| Guia | `guia@exemplo.test` | `123456` |

As fotos de capa das trilhas dependem de imagens em `storage/app/seeders/`, que não são versionadas. Sem elas o seed roda normalmente, apenas sem capa.

---

## Desenvolvimento

### Hot Module Replacement

Por padrão a aplicação serve os assets compilados em `public/build`. Para desenvolver o frontend com recarga automática:

```bash
docker compose --profile dev up -d
```

Com o container `vite` no ar, o Laravel passa a servir os assets pelo dev server. Ao pará-lo (`docker compose stop vite`), volta sozinho ao build estático.

### Controle da subida

Cada etapa do entrypoint tem uma variável de ambiente. O valor `auto` executa a etapa apenas quando necessária.

| Variável | Padrão | Efeito |
|---|---|---|
| `AUTO_INSTALL` | `true` | Instala `vendor/` e `node_modules/` quando faltam ou quando o lockfile mudou |
| `AUTO_BUILD` | `auto` | Compila os assets apenas se `public/build` não existir |
| `AUTO_MIGRATE` | `true` | Executa `php artisan migrate --force` |
| `AUTO_SEED` | `auto` | Popula o banco apenas se estiver vazio |
| `DB_WAIT_TIMEOUT` | `90` | Segundos de espera pelo MySQL antes de desistir |

```bash
# forçar recompilação dos assets e repopulação do banco nesta subida
AUTO_BUILD=true AUTO_SEED=true docker compose up -d
```

### Comandos frequentes

```bash
docker compose logs -f app                 # logs do preparo e da aplicação
docker exec -it laravel_app bash           # shell no container
docker exec laravel_app php artisan tinker # console do Laravel
docker exec laravel_app php artisan test   # suíte de testes
docker compose down                        # derruba, preservando o banco
docker compose down -v                     # derruba e apaga o volume do banco
```

### Configuração

O `.env` é criado automaticamente a partir do `.env.example`, já apontado para os containers. Após editá-lo, recarregue com `docker compose restart app`.

---

## Estrutura do projeto

```
app/
├── Http/Controllers/     Agendamento, Avaliacao, Chat, Cliente, FotoAventura,
│                         Guia, Notificacao, Perfil, Trilha e auth/
├── Http/Middleware/      HandleInertiaRequests (props compartilhadas)
├── Models/               Agendamento, Avaliacao, ChatMessage, Dificuldade,
│                         FotoAventura, Guia, Idioma, Notificacao, Trilha, User
└── Support/              ImageResizer (pipeline de upload de imagem)

resources/js/
├── Pages/                16 páginas Inertia, uma por rota renderizada
├── Components/           domain/, layout/ e ui/
├── Layouts/              AuthLayout e layout público
├── hooks/  lib/          hooks e utilidades compartilhadas

database/
├── migrations/           23 migrations
├── seeders/              dados de demonstração
└── factories/

docker/
├── nginx/                virtual host
├── php/                  limites de upload e checagem de banco
├── entrypoint.sh         preparo do container app
├── vite-entrypoint.sh    dev server
└── lib.sh                funções compartilhadas pelos entrypoints
```

---

## Modelo de dados

| Tabela | Papel |
|---|---|
| `users` | Trilheiros |
| `guias` | Guias, com documentos e dados de credenciamento |
| `trilhas` | Trilhas, com dificuldade, geolocalização do ponto de encontro e lista do que levar |
| `trilhas_guias` | Inscrição guia–trilha, com `preco_por_pessoa` e `congelada` no pivô |
| `idiomas` / `idiomas_guias` | Idiomas falados por cada guia |
| `dificuldades` | Níveis de dificuldade das trilhas |
| `agendamentos` | Propostas e seu ciclo de vida, incluindo pagamento |
| `avaliacoes` | Uma avaliação por agendamento concluído |
| `chat_messages` | Conversa por agendamento, com marcação de leitura |
| `fotos_aventura` | Fotos publicadas por trilheiro ou guia, com miniatura |
| `notificacoes` | Notificações internas polimórficas, por perfil |

---

## Testes

São 22 testes de feature cobrindo os fluxos de ponta a ponta: login nos dois guards, cadastro de trilheiro, criação e edição de trilhas, proposta e conflito de agenda, aceite e recusa, pagamento, chat, avaliação, upload de fotos e as regras de autorização de cada um — inclusive os casos negativos, em que um terceiro tenta acessar agendamento, chat, foto ou notificação alheios.

```bash
docker exec laravel_app php artisan test
```

Os testes rodam sob `DatabaseTransactions`: cada caso é revertido ao final, preservando os dados de seed.

`test_guia_nao_criador_nao_deveria_editar_trilha_alheia` falha por construção. Ele documenta a edição colaborativa de trilhas descrita em [Limitações conhecidas](#limitações-conhecidas): a asserção descreve o comportamento desejado em produção, não o atual.

---

## Limitações conhecidas

O projeto é um MVP e deixa explicitamente fora de escopo:

- **Pagamento simulado.** Não há integração com gateway; `pago_em` é apenas registrado.
- **Aprovação automática.** Guias e trilhas entram no ar sem curadoria; a moderação de conteúdo publicado também não existe.
- **Notificações internas.** Não há push nem e-mail; o `MAIL_MAILER` padrão é `log`.
- **Conclusão por data.** Agendamentos aceitos com data passada são marcados como concluídos na abertura do painel, sem job agendado.
- **Edição colaborativa de trilhas.** Qualquer guia inscrito pode editar a trilha, não apenas quem a criou — decisão de sandbox, que em produção exigiria fluxo de sugestão e aprovação.

Próximos passos previstos: gateway de pagamento real, credenciamento manual de guias, notificações push e moderação de conteúdo.

---

## Licença

Projeto pessoal, publicado para fins de estudo e portfólio.
