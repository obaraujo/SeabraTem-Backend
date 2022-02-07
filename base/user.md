# Usuário

## Requisitos

- [] Deve possível cadastrar um usuário

---

- [] Deve ser possível fazer login
- [] Deve ser possível fazer login com o Google

---

- [] Ao adquirir um plano o usuário se torna **st-admin-`x`**
  > O `x` representa o plano que ele escolheu

---

- [] Deve ser possível editar seu perfil
  > ***

## Regras de Negócio

- [] Ao se cadastrar o usuário se torna um **inscrito**
- [] Não deve ser possível cadastrar um usuário com um endereço de email já cadastrado
- [] Não deve ser possível cadastrar um usuário com um nome de usuário já cadastrado

---

- [] O usuário precisa já possuir cadastro na plataforma

---

> ---

## Informações importantes

### Estrutura JSON dos dados do usuário

```Json
{
  "ID": "1",
  "first_name": "Nome",
  "last_name": "Sobrenome",
  "username": "obaraujo",
  "nickname": "Apelido",
  "user_email": "seu@email.aqui",
  "user_pass": "senha",
  "description": "A descrição vai aqui",
  "role": "subscriber"
}
```
