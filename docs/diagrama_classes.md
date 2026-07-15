# 🧩 Diagrama de Classes

Classes do sistema com **atributos** e **métodos**. (Mermaid)

```mermaid
classDiagram
    class Usuario {
        - int id
        - String nome
        - String email
        - String senha
        - String tipo
        + autenticar(email, senha) bool
        + ehAdmin() bool
    }

    class Categoria {
        - int id
        - String nome
        - String descricao
        + salvar() void
        + excluir() void
        + listar() List
    }

    class Setor {
        - int id
        - String nome
        - String responsavel
        + salvar() void
        + excluir() void
        + listar() List
    }

    class Recurso {
        - int id
        - String nome
        - String descricao
        - String foto
        - int categoria_id
        - int setor_id
        - bool ativo
        + salvar() void
        + excluir() void
        + listar(filtro) List
        + uploadFoto(arquivo) String
    }

    class Reserva {
        - int id
        - int recurso_id
        - int usuario_id
        - Date data_reserva
        - String turno
        - String origem
        - String status
        + criar() void
        + cancelar() void
        + historicoPorUsuario(usuario_id) List
    }

    class ApiService {
        + login(email, senha) LoginResponse
        + listarRecursos(categoria, busca) List~Recurso~
        + listarCategorias() List~Categoria~
        + reservar(ReservaRequest) SimpleResponse
        + historico(usuario_id) List~Reserva~
    }

    Usuario "1" --> "0..*" Reserva : faz
    Recurso "1" --> "0..*" Reserva : possui
    Categoria "1" --> "0..*" Recurso : classifica
    Setor "1" --> "0..*" Recurso : responsavel
    ApiService ..> Recurso : consome
    ApiService ..> Reserva : consome
    ApiService ..> Usuario : autentica
```

## Observações
- As classes **Usuario, Categoria, Setor, Recurso e Reserva** representam as entidades do banco
  (mapeadas no Web em PHP e no Mobile como POJOs Java).
- **ApiService** é a interface Retrofit no app Android que consome os endpoints JSON do servidor.
