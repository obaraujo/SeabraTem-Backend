# Regras gerais do funcionamento da Seabra Tem

- [ ] Deve ser possível usuários se cadastrarem [Requisitos e regras](./context/user.md).
- [ ] Deve ser possível realizar avaliações dos negócios [Requisitos e regras](./context/rating.md).
- [ ] O usuário deverá contratar um plano para se tornar **st-admin** [Requisitos e regras](./context/plans.md).
- [ ] Mesmo tendo um plano contratado específico, a empresa poderá fazer upgrades [Requisitos e regras](./context/upgrades-plans.md).
- [ ] Deve ser possível o usuário **st-admin** cadastrar negócios [Requisitos e regras](./context/business.md).
- [ ] Deve ser possível o usuário **st-admin** cadastrar produtos [Requisitos e regras](./context/products.md).
- [ ] Deve ser possível o usuário **st-admin** ver seus analytics [Requisitos e regras](./context/analytics.md).

# API Seabra Tem

## Negócios

## Produtos

### **Cadastro**

_Requisição_

`/wp-json/api/v1/product`

```json
{
  "name": "Nome",
  "description": "Sobrenome",
  "price": "15.99"
}
```

_Resposta_

```json
{
  "message": "mes"
}
```
