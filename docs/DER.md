# 🗄️ DER — Diagrama Entidade-Relacionamento

Modelo do banco de dados `reservas_db`. (Renderiza no GitHub / VS Code com extensão Mermaid.)

```mermaid
erDiagram
    USUARIOS ||--o{ RESERVAS : "faz"
    RECURSOS ||--o{ RESERVAS : "é reservado em"
    CATEGORIAS ||--o{ RECURSOS : "classifica"
    SETORES ||--o{ RECURSOS : "é responsável por"

    USUARIOS {
        int id PK
        varchar nome
        varchar email
        varchar senha
        enum tipo "admin/user"
        timestamp criado_em
    }

    CATEGORIAS {
        int id PK
        varchar nome
        varchar descricao
    }

    SETORES {
        int id PK
        varchar nome
        varchar responsavel
    }

    RECURSOS {
        int id PK
        varchar nome
        text descricao
        varchar foto
        int categoria_id FK
        int setor_id FK
        tinyint ativo
        timestamp criado_em
    }

    RESERVAS {
        int id PK
        int recurso_id FK
        int usuario_id FK
        date data_reserva
        enum turno "manha/tarde/noite"
        enum origem "web/mobile"
        enum status "ativa/cancelada"
        timestamp criado_em
    }
```

## Relacionamentos
- Um **usuário** faz muitas **reservas** (1:N).
- Um **recurso** pode ter muitas **reservas** (1:N).
- Uma **categoria** classifica muitos **recursos** (1:N).
- Um **setor** é responsável por muitos **recursos** (1:N).

## Regras importantes
- `reservas` tem **UNIQUE (recurso_id, data_reserva, turno)** → evita reservar o mesmo recurso no
  mesmo dia e turno duas vezes.
- `origem` registra se a reserva veio do **web** ou do **mobile**.
