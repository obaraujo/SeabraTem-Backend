# Categorias

Essa parte da API foi projetada para suportar qualquer tipo das chamadas taxonomias.

## Requisitos

- [x] Deve ser possível criar categorias
- [x] Deve ser possível editar categorias
- [x] Deve ser possível resgatar uma Categoria
- [x] Deve ser possível resgatar todas as Categorias
- [x] Deve ser possível apagar categorias

## Regras de Negócio

- [x] Para executar qualquer comando usuário deve estar logado
- [x] A categoria criada por um usuário só é visível para ele

## MÉTODOS E ROTAS

### **Cadastro**

_Requisição_

> POST `/wp-json/api/v1/category/create`

```json
{
  "term": "Mercado",
  "taxonomy": "category_business",
  "description": "Isso é um exemplo de categoria criada pela API Seabra Tem",
  "parent": 0
}
```

_Resposta_

`Sucesso`

```json
{
  "term_id": 55,
  "term": "Alimentício",
  "taxonomy": "category_business",
  "description": "Ramo de alimentos",
  "parent": 0
}
```

`Erro: A categoria já existe`

```json
{
  "code": "term_exists",
  "message": "Um termo com o nome fornecido já existe com este ascendente.",
  "data": 40
}
```

`Erro: A taxonomia é inválida`

```json
{
  "code": "taxonomy_not_valid",
  "message": "A taxonomia não existe :(",
  "data": { "status": 401 }
}
```

`Erro: Nome da categoria vazio`

```json
{
  "code": "term_invalid",
  "message": "O valor enviado está vazio!",
  "data": { "status": 401 }
}
```

`Erro: O nome passado para a categoria não se adéqua ao critério`

```json
{
  "code": "term_invalid",
  "message": "O valor enviado é curto demais!",
  "data": { "status": 401, "min": 3, "max": 20 }
}
```

```json
{
  "code": "term_invalid",
  "message": "O valor enviado é longo demais!",
  "data": { "status": 401, "min": 3, "max": 20 }
}
```

### **Buscar todas as categorias que pertencem ao usuário**

_Requisição_

> GET `/wp-json/api/v1/category/{tipo da categoria}`

_Resposta_
`Sucesso`

```json
{
  "categoria-270": {
    "term_id": 41,
    "name": "Categoria",
    "slug": "categoria-270",
    "description": "",
    "parent": 0,
    "count": 0
  },
  "categoria2-270": {
    "term_id": 42,
    "name": "Categoria 2",
    "slug": "categoria2-270",
    "description": "",
    "parent": 0,
    "count": 0
  },
  "categoria3-270": {
    "term_id": 43,
    "name": "Categoria 3",
    "slug": "categoria3-270",
    "description": "",
    "parent": 0,
    "count": 0
  }
}
```

`Erro: A taxonomia é inválida`

```json
{
  "code": "taxonomy_not_valid",
  "message": "A taxonomia não existe :(",
  "data": { "status": 401 }
}
```

### **Atualização**

_Requisição_

> POST `/wp-json/api/v1/category`

```json
{
  "term_id": 40,
  "term": "Categoria",
  "taxonomy": "category_business",
  "description": "Isso é um exemplo de categoria criada pela API Seabra Tem",
  "parent": 0
}
```

_Resposta_

`Sucesso`

```json
{
  "term_id": 40,
  "name": "Categoria",
  "slug": "categoria-270",
  "description": "Isso é um exemplo de categoria criada pela API Seabra Tem",
  "parent": 0,
  "count": 0
}
```

`Erro: A taxonomia é inválida`

```json
{
  "code": "taxonomy_not_valid",
  "message": "A taxonomia não existe :(",
  "data": { "status": 401 }
}
```

`Erro: Nome da categoria vazio`

```json
{
  "code": "term_invalid",
  "message": "O valor enviado está vazio!",
  "data": { "status": 401 }
}
```

`Erro: O nome passado para a categoria não se adéqua ao critério`

```json
{
  "code": "term_invalid",
  "message": "O valor enviado é curto demais!",
  "data": { "status": 401, "min": 3, "max": 20 }
}
```

```json
{
  "code": "term_invalid",
  "message": "O valor enviado é longo demais!",
  "data": { "status": 401, "min": 3, "max": 20 }
}
```
