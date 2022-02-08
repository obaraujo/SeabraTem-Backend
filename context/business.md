# Negócio

## Requisitos

- [ ] Deve possível criar um negócio
- [ ] Deve ser possível editar um negócio
- [ ] Deve ser possível excluir um negócio

## Regras de Negócio

- [ ] Só é possível cadastrar um negócio se o usuário for **st-admin**
- [ ] Não é possível cadastrar mais negócios do que o plano do usuário permite
- [ ] Só é possível editar se o negócio estiver cadastrado
- [ ] Só é possível excluir se o negócio estiver cadastrado

## MÉTODOS E ROTAS

### **Cadastro**

_Requisição_

> POST `/wp-json/api/v1/business/create`

```json
{
  "post_author": "Nome",
  "post_content": "Sobrenome",
  "post_excerpt": "obaraujo",
  "post_status": "obaraujo",
  "post_type": "obaraujo",
  "comment_status": "obaraujo",
  "post_name": "obaraujo",
  "post_category": "obaraujo",
  "meta_input": ["obaraujo"]
}
```

_Resposta_

`Sucesso`
