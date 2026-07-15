# 🎭 Diagrama de Casos de Uso

Atores: **Administrador** e **Usuário (Aluno)**. (Mermaid)

```mermaid
flowchart LR
    admin([👤 Administrador])
    user([👤 Usuário / Aluno])

    subgraph Sistema de Reservas e Empréstimos
        UC1((Fazer Login))
        UC2((Gerenciar Categorias))
        UC3((Gerenciar Setores))
        UC4((Gerenciar Recursos + Foto))
        UC5((Ver Dashboard))
        UC6((Ver Todas as Reservas))
        UC7((Consultar Catálogo))
        UC8((Filtrar / Buscar Recurso))
        UC9((Fazer Reserva))
        UC10((Ver Histórico))
    end

    admin --> UC1
    admin --> UC2
    admin --> UC3
    admin --> UC4
    admin --> UC5
    admin --> UC6

    user --> UC1
    user --> UC7
    user --> UC8
    user --> UC9
    user --> UC10
```

## Descrição dos Casos de Uso

| Caso de Uso | Ator | Descrição |
|---|---|---|
| Fazer Login | Ambos | Autentica no sistema (web e mobile). |
| Gerenciar Categorias | Admin | Criar, editar e excluir categorias. |
| Gerenciar Setores | Admin | Criar, editar e excluir setores. |
| Gerenciar Recursos | Admin | Cadastrar recursos com foto, categoria e setor. |
| Ver Dashboard | Admin | Visualizar contadores e últimas reservas. |
| Ver Todas as Reservas | Admin | Listar reservas de todos os usuários (web e mobile). |
| Consultar Catálogo | Usuário | Ver a lista de recursos disponíveis. |
| Filtrar / Buscar | Usuário | Filtrar por categoria ou buscar por nome. |
| Fazer Reserva | Usuário | Reservar um recurso por data e turno (sem aprovação). |
| Ver Histórico | Usuário | Consultar as próprias reservas. |
