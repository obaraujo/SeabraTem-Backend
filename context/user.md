# Usuário

## Requisitos

- [x] Deve possível cadastrar um usuário
- [x] Deve ser possível fazer login
- [x] Deve ser possível editar seu perfil
- [x] Deve ser possível alterar a senha
- [x] Deve ser possível excluir um usuário
- [ ] Ao adquirir um plano o usuário deve se tornar um **st-admin-`x`**
  > O `x` representa o plano que ele escolheu

## Regras de Negócio

- [x] Ao se cadastrar o usuário se torna um **subscriber**
- [x] Não deve ser possível cadastrar um usuário com um endereço de email já cadastrado
- [x] Não deve ser possível cadastrar um usuário com um nome de usuário já cadastrado
- [x] A senha não pode está vazia
- [x] A senha não pode ter menos que 6 caracteres nem mais que 14
- [x] O usuário precisa já possuir cadastro na plataforma
- [x] O usuário pode editar todas as suas informações (Nome, sobrenome, apelido, email, descrição), **exceto o nome de usuário**

## Informações importantes

### Estrutura JSON dos dados do usuário

```Json
{
  "ID": "1",
  "id_image": "233",
  "first_name": "Nome",
  "last_name": "Sobrenome",
  "username": "obaraujo",
  "nickname": "Apelido",
  "user_email": "seu@email.aqui",
  "user_pass": "senha",
  "description": "A descrição vai aqui",
  "role": "subscriber",
  "upgrade_plan": null
}
```

> O `upgrade_plan` é o campo responsável por armazenar os pacotes que a pessoa comprou para otimizar seu plano. Começa com `null` mas quando ela fizer o upgrade nele terá um array com os IDs dos upgrades que ele fez. Esse campo terá relação com a tabela `package_upgrade`, onde ficará armazenada os dados sobre o upgrade.

## MÉTODOS E ROTAS

### **Cadastro**

_Requisição_

> POST `/wp-json/api/v1/user/create`

```json
{
  "first_name": "Vinícius",
  "last_name": "Araújo",
  "username": "obaraujo",
  "user_pass": "obaraujo",
  "user_email": "baraujo@stagon.in"
}
```

_Resposta_

`Sucesso`

```json
{
  "success": true,
  "message": "Pronto Vinícius, a sua conta foi criada!",
  "data": {
    "user_id": 18,
    "user_name": "obaraujo",
    "display_name": "Vinícius Araújo"
  }
}
```

`Erro: nome de usuário já cadastrado`

```json
{
  "code": "existing_user_login",
  "message": "Este nome de usuário já existe!",
  "data": null
}
```

`Erro: nome de usuário já cadastrado`

```json
{
  "code": "existing_user_email",
  "message": "Desculpe, mas este e-mail já está em uso!",
  "data": null
}
```

`Erro: Senha enviada está vazia`

```json
{
  "code": "user_pass_empty",
  "message": "A senha enviada está vazia!",
  "data": { "status": 401 }
}
```

`Erro: Senha enviada longa demais`

```json
{
  "code": "user_pass_long",
  "message": "A senha enviada é longa demais!",
  "data": {
    "status": 401,
    "min": 6,
    "max": 14
  }
}
```

`Erro: Senha enviada curta demais`

```json
{
  "code": "user_pass_shirt",
  "message": "A senha enviada é curta demais!",
  "data": {
    "status": 401,
    "min": 6,
    "max": 14
  }
}
```

### **Login**

_Requisição_

> POST `/wp-json/jwt-auth/v1/token`

```json
{
  "username": "obaraujo",
  "password": "obaraujo"
}
```

_Resposta_

`Sucesso`

```json
{
  "success": true,
  "statusCode": 200,
  "code": "jwt_auth_valid_credential",
  "message": "Credential is valid",
  "data": {
    "token": "code_token",
    "id": 24,
    "email": "baraujo@stagon.in",
    "nicename": "obaraujo",
    "firstName": "Nome",
    "lastName": "Sobrenome",
    "displayName": "Nome Sobrenome"
  }
}
```

`Erro: Nome de usuário inválido`

```json
{
  "success": false,
  "statusCode": 403,
  "code": "invalid_username",
  "message": "Erro: O usuário obaraujo não está registrado neste site. Se você não está certo de seu nome de usuário, experimente o endereço de e-mail.",
  "data": []
}
```

`Erro: Email inválido`

```json
{
  "success": false,
  "statusCode": 403,
  "code": "invalid_email",
  "message": "Endereço de e-mail desconhecido. Verifique novamente ou tente seu nome de usuário.",
  "data": []
}
```

`Erro: Senha inválida`

```json
{
  "success": false,
  "statusCode": 403,
  "code": "incorrect_password",
  "message": "Erro: A senha informada para o usuário obaraujo está incorreta. Perdeu a senha?",
  "data": []
}
```

### **Mostrar dados do usuário**

_Requisição_

> GET `/wp-json/api/v1/user`

```js
const headers = new Headers({
  Authorization: `Bearer ${token}`,
});
```

_Resposta_

`Sucesso`

```json
{
  "ID": 24,
  "first_name": "Nome",
  "last_name": "Sobrenome",
  "nickname": "obaraujo",
  "description": "",
  "user_email": "baraujo@stagon.in"
}
```

`Erro: Token not valid`

```json
{
  "success": false,
  "statusCode": 403,
  "code": "jwt_auth_invalid_token",
  "message": "Signature verification failed",
  "data": []
}
```

### **Atualização de Usuário**

_Requisição_

> PUT `/wp-json/api/v1/user`

```js
const headers = new Headers({
  Authorization: `Bearer ${token}`,
});
```

```json
{
  "first_name": "Henrique",
  "last_name": "Pires",
  "nickname": "Pires",
  "user_email": "pires@stagon.in",
  "display_name": "Henrique Pires",
  "description": "Ele é o cara do desenvolvimento pessoal!"
}
```

_Resposta_

`Sucesso`

```json
{
  "success": true,
  "message": "Pronto Vinícius! Os dados de sua conta foram atualizados!",
  "data": {
    "user_id": 25,
    "user_name": "obaraujo",
    "display_name": "Vinícius"
  }
}
```

`Erro: Token not valid`

```json
{
  "success": false,
  "statusCode": 403,
  "code": "jwt_auth_invalid_token",
  "message": "Signature verification failed",
  "data": []
}
```

### **Atualização de senha de Usuário**

_Requisição_

> POST `/wp-json/api/v1/user/pass`

```js
const headers = new Headers({
  Authorization: `Bearer ${token}`,
});
```

```json
{
  "user_pass": "obaraujo"
}
```

_Resposta_

`Sucesso`

```json
{
  "success": true,
  "message": "Pronto Vinícius! A sua senha atualizada!",
  "data": {
    "user_id": 25,
    "user_name": "obaraujo",
    "display_name": "Vinícius"
  }
}
```

`Erro: Token not valid`

```json
{
  "success": false,
  "statusCode": 403,
  "code": "jwt_auth_invalid_token",
  "message": "Signature verification failed",
  "data": []
}
```

`Erro: Senha enviada está vazia`

```json
{
  "code": "user_pass_empty",
  "message": "A senha enviada está vazia!",
  "data": { "status": 401 }
}
```

`Erro: Senha enviada longa demais`

```json
{
  "code": "user_pass_long",
  "message": "A senha enviada é longa demais!",
  "data": {
    "status": 401,
    "min": 6,
    "max": 14
  }
}
```

`Erro: Senha enviada curta demais`

```json
{
  "code": "user_pass_shirt",
  "message": "A senha enviada é curta demais!",
  "data": {
    "status": 401,
    "min": 6,
    "max": 14
  }
}
```

### **Exclusão de Usuário**

_Requisição_

> DELETE `/wp-json/api/v1/user`

```js
const headers = new Headers({
  Authorization: `Bearer ${token}`,
});
```

_Resposta_

`Sucesso`

```json
{
  "success": true,
  "message": "Pronto o usuário obaraujo foi deletado!",
  "data": {
    "user_id": 25,
    "user_name": "obaraujo"
  }
}
```

`Erro: Token not valid`

```json
{
  "success": false,
  "statusCode": 403,
  "code": "jwt_auth_invalid_token",
  "message": "Signature verification failed",
  "data": []
}
```
