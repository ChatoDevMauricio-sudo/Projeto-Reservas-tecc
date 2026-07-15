# 📄 Relatório do Projeto — Sistema de Reservas e Empréstimos

**Disciplina:** Fábrica de Software
**Metodologia:** Scrum
**Projeto A:** Sistema de Reservas e Empréstimos
**Cliente (Stakeholder):** Equipe B

---

## 1. Resumo Executivo
O **Sistema de Reservas e Empréstimos** foi desenvolvido para resolver um problema real relatado
pelo cliente: a dificuldade de saber se um recurso da escola (laboratório, projetor, sala,
equipamento) está disponível para uso. A solução é composta por:

- **Sistema Web (PHP + MySQL):** painel administrativo para cadastro de recursos, categorias e
  setores, com upload de foto e controle de reservas.
- **Aplicativo Mobile (Android):** app para os alunos consultarem o catálogo, filtrarem por
  categoria e realizarem reservas por **data** e **turno** (manhã, tarde ou noite).
- **Integração por API (JSON):** o Web e o Mobile compartilham o **mesmo banco de dados**; tudo
  que o admin cadastra aparece no app, e toda reserva feita no app é gravada no banco.

A pedido do cliente, a reserva é **automática** — o administrador **não** precisa aprovar. O foco
foi entregar algo **simples e prático**.

---

## 2. Objetivo
Desenvolver, em equipe, uma solução completa (Web + Mobile) integrada por API, permitindo o
gerenciamento de recursos da escola e a realização de reservas de forma simples e direta.

---

## 3. Escopo Entregue

### Web (PHP)
| Módulo | Descrição |
|---|---|
| Login / Autenticação | Sessão PHP, senhas com `password_hash`. Perfis **admin** e **usuário**. |
| Dashboard | Contadores (recursos, reservas ativas, categorias, setores) e últimas reservas. |
| CRUD Categorias | Tabela auxiliar (ex: Laboratórios, Projetores, Robótica, Quadras). |
| CRUD Setores | Tabela auxiliar (ex: TI, Manutenção, Coordenação). |
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

---

## 4. Papéis da Equipe (Scrum)

| Papel | Responsável | Função |
|---|---|---|
| **Product Owner (PO)** | *(preencher)* | Negocia com o cliente, define e prioriza requisitos. |
| **Scrum Master (SM)** | *(preencher)* | Organiza o Kanban, cobra prazos, conduz as Dailies. |
| **Dev Team** | *(preencher)* | Desenvolvimento Back-end, Front-end e Mobile. |
| **Stakeholder (Cliente)** | Equipe B | Fornece dados reais (10 recursos/fotos), testa e valida a entrega. |

---

## 5. Requisitos levantados com o Cliente
Requisitos coletados na **Entrevista de Requisitos** com a Equipe B:
- Reserva **simples**, sem necessidade de aprovação do admin.
- Reserva por **turno** (manhã, tarde, noite) e **data**.
- Somente o **admin** cadastra recursos, categorias e setores.
- O **usuário** apenas realiza reservas e vê o próprio histórico.
- Cada recurso possui **foto, nome e descrição**.
- **Filtro de busca** no app.
- **Histórico de reservas** (tanto web quanto mobile gravados no banco).
- Integração total por **API** entre Web, Mobile e Banco.

---

## 6. Tecnologias Utilizadas
- **Back-end / Web:** PHP 8 (PDO), MySQL/MariaDB, Apache (XAMPP).
- **Front-end Web:** HTML5, CSS3.
- **Mobile:** Android (Java), Retrofit 2 (consumo de API), Gson, Glide (imagens), RecyclerView.
- **Banco de Dados:** MySQL (5 tabelas relacionadas com chaves estrangeiras).
- **Gerência:** Scrum + Kanban + Git/GitHub.

---

## 7. Sprints Realizadas

| Sprint | Objetivo | Entregas |
|---|---|---|
| **Sprint 1 — Base** | Banco e autenticação | Modelagem do banco (DER), tabelas, login e dashboard. |
| **Sprint 2 — Core Web** | CRUDs | Categorias, Setores e Recursos (com upload de foto). |
| **Sprint 3 — Reservas + API** | Reservas e integração | Módulo de reservas e APIs JSON. |
| **Sprint 4 — Mobile** | Aplicativo | Login, lista, filtro, detalhes, reserva e histórico. |
| **Sprint 5 — Testes/Review** | Validação | QA, testes de aceite com o cliente e ajustes finais. |

---

## 8. Modelagem
Os diagramas do projeto estão nos arquivos:
- **`DER.md`** — Modelo Entidade-Relacionamento do banco.
- **`diagrama_classes.md`** — Diagrama de Classes (atributos e métodos).
- **`diagrama_casos_de_uso.md`** — Diagrama de Casos de Uso.

---

## 9. Critério de Aceite (Sprint Review)
Na entrega final, o cliente (Equipe B) pega o celular, procura um recurso que ele havia solicitado
no cadastro e realiza uma reserva. Se o recurso for encontrado, a reserva for gravada no banco e
aparecer no painel web do admin, o projeto é **VALIDADO**. ✅

---

## 10. Conclusão
O projeto atendeu a todos os requisitos definidos pelo cliente, entregando uma solução integrada
Web + Mobile + Banco de forma simples e funcional. A separação em módulos permitiu que todos os
integrantes programassem, e a metodologia Scrum garantiu a comunicação contínua com o Stakeholder.
