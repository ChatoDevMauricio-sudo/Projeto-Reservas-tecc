# 📅 Sistema de Reservas e Empréstimos

Sistema integrado **Web (PHP + MySQL) + Aplicativo Mobile (Android)** para gerenciamento e reserva de recursos escolares — laboratórios, projetores, salas e equipamentos — desenvolvido com a metodologia **Scrum**.

> **Disciplina:** Fábrica de Software
> **Projeto A:** Sistema de Reservas e Empréstimos
> **Cliente (Stakeholder):** Equipe B

---

## 📄 Sumário

- [Resumo Executivo](#-resumo-executivo)
- [Objetivo](#-objetivo)
- [Funcionalidades / Escopo Entregue](#-funcionalidades--escopo-entregue)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Modelagem](#-modelagem)
- [Como Rodar o Projeto](#-como-rodar-o-projeto)
- [Equipe (Papéis Scrum)](#-equipe-papéis-scrum)
- [Requisitos Levantados com o Cliente](#-requisitos-levantados-com-o-cliente)
- [Sprints Realizadas](#-sprints-realizadas)
- [Quadro Kanban](#-quadro-kanban)
- [Critério de Aceite (Sprint Review)](#-critério-de-aceite-sprint-review)
- [Conclusão](#-conclusão)

---

## 🧾 Resumo Executivo

O **Sistema de Reservas e Empréstimos** foi desenvolvido para resolver um problema real relatado pelo cliente: a dificuldade de saber se um recurso da escola (laboratório, projetor, sala, equipamento) está disponível para uso.

A solução é composta por três partes integradas:

- 🌐 **Sistema Web (PHP + MySQL):** painel administrativo para cadastro de recursos, categorias e setores, com upload de foto e controle de reservas.
- 📱 **Aplicativo Mobile (Android):** app para os alunos consultarem o catálogo, filtrarem por categoria e realizarem reservas por **data** e **turno** (manhã, tarde ou noite).
- 🔗 **Integração por API (JSON):** o Web e o Mobile compartilham o **mesmo banco de dados** — tudo que o admin cadastra aparece no app, e toda reserva feita no app é gravada no banco em tempo real.

A pedido do cliente, a reserva é **automática** — o administrador **não** precisa aprovar. O foco do projeto foi entregar uma solução **simples, funcional e eficiente**.

## 🎯 Objetivo

Desenvolver, em equipe, uma solução completa (Web + Mobile) integrada por API, permitindo o gerenciamento de recursos da escola e a realização de reservas de forma simples, rápida e organizada.

## ✅ Funcionalidades / Escopo Entregue

### Web (PHP)

| Módulo | Descrição |
|---|---|
| Login / Autenticação | Sessão PHP, senhas com `password_hash`. Perfis **admin** e **usuário**. |
| Dashboard | Contadores (recursos, reservas ativas, categorias, setores) e últimas reservas. |
| CRUD Categorias | Tabela auxiliar (ex.: Laboratórios, Projetores, Robótica, Quadras). |
| CRUD Setores | Tabela auxiliar (ex.: TI, Manutenção, Coordenação). |
| CRUD Recursos | Cadastro principal com **upload de foto** e chaves estrangeiras (categoria/setor). |
| Reservas | Usuário reserva por data + turno; admin visualiza todas as reservas (web e mobile). |
| Busca | Filtro de busca nas listagens (recursos, reservas, categorias, setores). |
| APIs JSON | Endpoints consumidos pelo app: recursos, categorias, login, reservar, histórico. |

### Mobile (Android — Java)

| Tela | Descrição |
|---|---|
| Login | Autenticação via API. |
| Principal (Lista) | RecyclerView com foto + nome; **filtro por categoria** e **busca**. |
| Detalhes | Foto ampliada, descrição, setor responsável e botão **Reservar** (data + turno). |
| Histórico | Lista das reservas do usuário (dados vindos do banco via API). |

## 🛠️ Tecnologias Utilizadas

- **Back-end / Web:** PHP 8 (PDO), MySQL/MariaDB, Apache (XAMPP)
- **Front-end Web:** HTML5, CSS3
- **Mobile:** Android (Java), Retrofit 2 (consumo de API), Gson, Glide (imagens), RecyclerView
- **Banco de Dados:** MySQL — 5 tabelas relacionadas por chaves estrangeiras
- **Gerência de Projeto:** Scrum + Kanban (Trello) + Git/GitHub

## 📁 Estrutura do Projeto

```
ProjetoSistemasReservaa-master/
├── sql/
│   ├── 01_estrutura.sql          # Criação das tabelas do banco
│   └── 02_dados_iniciais.sql     # Usuários e dados de teste
├── web/                          # Aplicação PHP (admin + usuário)
│   ├── api/                      # Endpoints JSON consumidos pelo app mobile
│   ├── assets/css/                # Estilos
│   ├── categorias/                # CRUD de categorias
│   ├── config/db.php              # Conexão com o banco (PDO)
│   ├── includes/                  # Header, footer, autenticação, funções
│   ├── recursos/                  # CRUD de recursos (com upload de foto)
│   ├── reservas/                  # Reservar / listar / histórico
│   ├── setores/                   # CRUD de setores
│   ├── uploads/                   # Fotos dos recursos
│   ├── index.php / login.php / cadastro.php / instalar.php
├── mobile/
│   └── ReservasApp/               # Projeto Android (Java, Android Studio)
└── docs/
    ├── COMO_RODAR.md               # Passo a passo detalhado de instalação
    ├── RELATORIO.md                 # Relatório completo do projeto
    ├── KANBAN.md                    # Quadro Kanban do time
    ├── DER.md                       # Diagrama Entidade-Relacionamento
    ├── diagrama_classes.md          # Diagrama de Classes
    └── diagrama_casos_de_uso.md     # Diagrama de Casos de Uso
```

## 🗺️ Modelagem

Os diagramas do projeto (renderizam em Mermaid, ex.: no GitHub/VS Code) estão em `docs/`:

- **[DER.md](docs/DER.md)** — Modelo Entidade-Relacionamento do banco `reservas_db`. Entidades: `USUARIOS`, `CATEGORIAS`, `SETORES`, `RECURSOS`, `RESERVAS`.
- **[diagrama_classes.md](docs/diagrama_classes.md)** — Diagrama de Classes (atributos e métodos): `Usuario`, `Categoria`, `Setor`, `Recurso`, `Reserva`, `ApiService`.
- **[diagrama_casos_de_uso.md](docs/diagrama_casos_de_uso.md)** — Casos de uso dos atores **Administrador** e **Usuário/Aluno**.


### 1. Banco de Dados + Web

1. Instale o **XAMPP** e inicie **Apache** e **MySQL**.
2. Copie a pasta `web/` para `C:\xampp\htdocs\` e renomeie para `reservas`.
3. No **phpMyAdmin** (`http://localhost/phpmyadmin`), execute em ordem:
   - `sql/01_estrutura.sql`
   - `sql/02_dados_iniciais.sql`
4. Acesse `http://localhost/reservas/login.php`.

**Contas de teste (já criadas pelo script de dados iniciais):**

| Perfil | E-mail | Senha |
|---|---|---|
| Admin | `admin@escola.com` | `123456` |
| Usuário | `aluno@escola.com` | `123456` |

### 2. Aplicativo Mobile (Android)

1. Abra a pasta `mobile/ReservasApp` no **Android Studio** e aguarde o Gradle sincronizar.
2. Configure o endereço do servidor em `app/src/main/java/com/escola/reservasapp/api/ApiConfig.java`:
   - Emulador: `http://10.0.2.2/reservas/api/`
   - Celular físico (mesma rede Wi-Fi): `http://SEU_IP_LOCAL/reservas/api/`
3. Rode o app (**Run ▶**) e faça login com `aluno@escola.com` / `123456`.

> Toda reserva feita pelo app aparece também no site (Admin > Reservas), marcada como **origem = mobile** — confirmando a integração Web ↔ Mobile ↔ Banco. ✅

## 👥 Equipe (Papéis Scrum)

O time foi organizado seguindo a metodologia **Scrum**, com divisão clara de responsabilidades.

| Papel | Responsável(is) | Função |
|---|---|---|
| **Scrum Master (SM)** | Davi Foppa | Organiza o fluxo de trabalho (Kanban), acompanha o progresso das tarefas, conduz as dailies e garante que a equipe siga a metodologia Scrum. |
| **Product Owner (PO)** | Gaby Mello | Mantém contato direto com o cliente (Equipe B), levanta e define os requisitos, prioriza as funcionalidades. |
| **Dev Team** | Brenda Matos, Camile, João Pedro, Nicolas, Guilherme, Pedro, Wesley | Desenvolvimento técnico do projeto — back-end, front-end web e aplicativo mobile. |
| **Stakeholder (Cliente)** | Equipe B | Fornece dados reais (recursos e fotos), testa e valida o sistema nas etapas finais. |

## 🗣️ Requisitos Levantados com o Cliente

Requisitos coletados junto à Equipe B durante a fase de entrevista:

- Reserva **simples**, sem necessidade de aprovação do admin.
- Reserva por **turno** (manhã, tarde, noite) e **data**.
- Somente o **admin** cadastra recursos, categorias e setores.
- O **usuário** apenas realiza reservas e consulta o próprio histórico.
- Cada recurso possui **foto, nome e descrição**.
- **Filtro de busca** eficiente no aplicativo.
- **Histórico de reservas**, tanto web quanto mobile, gravado no mesmo banco.
- Integração total por **API** entre Web, Mobile e Banco de Dados.

## 🏁 Sprints Realizadas

| Sprint | Objetivo | Entregas |
|---|---|---|
| **Sprint 1 — Base** | Banco e autenticação | Modelagem do banco (DER), criação das tabelas, login e dashboard. |
| **Sprint 2 — Core Web** | CRUDs | Categorias, Setores e Recursos (com upload de foto). |
| **Sprint 3 — Reservas + API** | Reservas e integração | Módulo de reservas e APIs JSON para o app. |
| **Sprint 4 — Mobile** | Aplicativo | Login, listagem, filtros, detalhes, reserva e histórico. |
| **Sprint 5 — Testes/Review** | Validação | QA, testes de aceite com o cliente e ajustes finais. |

## 🗂️ Quadro Kanban

> 🔗 Acesse o quadro no Trello: [Sistema de Reservas — Kanban](https://trello.com/invite/b/6a45cdfe093636a869fd80f2/ATTIa0562af0abd763a4bab07ddd99fa7dde4A58698E/sistema-reserva)
>
> Cards marcados com **[COM CLIENTE]** exigem alinhamento direto com a Equipe B (Stakeholder).

### ✅ DONE (Concluído)

| # | Card | Responsável | Sprint |
|---|---|---|---|
| 1 | [SM] Criar Banco de Dados (DER) e Repositório | SM | 1 |
| 2 | [SM] Configurar GitHub e Trello | SM | 1 |
| 3 | [Dev Back] Criar tabelas e conexão MySQL (PDO) | Dev Back | 1 |
| 4 | [PO] Tela de Login e sessão PHP | PO | 1 |
| 5 | [PO] Painel Dashboard com contadores | PO | 1 |
| 6 | [Dev Back] CRUD Categorias (tabela auxiliar) | Dev Back | 2 |
| 7 | [Dev Back] CRUD Setores (tabela auxiliar) | Dev Back | 2 |
| 8 | [Dev Back] CRUD Recursos com Upload de foto | Dev Back | 2 |
| 9 | [Dev Back] Módulo de Reservas (data + turno) | Dev Back | 3 |
| 10 | [Dev Back] APIs JSON (recursos, login, reservar, histórico) | Dev Back | 3 |
| 11 | [Dev Android] Tela principal (RecyclerView) com fotos | Dev Android | 4 |
| 12 | [Dev Android] Filtro por categoria + busca | Dev Android | 4 |
| 13 | [Dev Android] Tela de detalhes + reservar | Dev Android | 4 |
| 14 | [Dev Android] Tela de histórico | Dev Android | 4 |
| 15 | [PO] Teste de QA: encontrar bugs antes de mostrar ao cliente | PO | 5 |
| 16 | [SM] Atualizar status diário da equipe (Daily) | SM | 5 |
| 17 | [PO] **[COM CLIENTE]** Entrevista Inicial: definir campos do banco | PO | 1 |
| 18 | [PO] **[COM CLIENTE]** Coletar as fotos/imagens reais dos recursos | PO | 3 |
| 19 | [PO] **[COM CLIENTE]** Sprint Review: cliente testa e valida no celular | PO | 5 |
| 20 | [SM] Gerar apresentação final e entregar documentação | SM | 5 |

### 🔄 DOING / 📋 TO DO

Todas as tarefas planejadas para as 5 sprints foram concluídas até a entrega final. Novas demandas (correções, melhorias ou pedidos adicionais do cliente) devem ser registradas diretamente no quadro do Trello.

### 📌 Legenda de Papéis

- **PO** — Product Owner (negocia com o cliente)
- **SM** — Scrum Master (organiza e cobra prazos)
- **Dev Back** — Desenvolvedor Back-end (PHP/MySQL)
- **Dev Android** — Desenvolvedor Mobile

## 🎯 Critério de Aceite (Sprint Review)

Como critério de validação final, o cliente (Equipe B) utiliza o aplicativo mobile para buscar um recurso previamente cadastrado e efetuar uma reserva. Se o recurso for encontrado, a reserva for gravada no banco de dados e aparecer no painel administrativo do sistema web, o projeto é considerado **validado com sucesso**. ✅

## 🏆 Conclusão

O projeto atingiu todos os objetivos propostos, entregando uma solução completa, integrada e funcional — Web + Mobile + Banco de Dados. A separação em módulos permitiu que todos os integrantes da equipe programassem, e a metodologia **Scrum** garantiu uma melhor organização do time, divisão de tarefas e comunicação contínua com o cliente (Equipe B).

Como resultado, foi desenvolvido um sistema que atende às necessidades reais do usuário, com foco em **simplicidade, usabilidade e eficiência**.

---

📚 Para mais detalhes, consulte o relatório completo em **[docs/RELATORIO.md](docs/RELATORIO.md)**.
