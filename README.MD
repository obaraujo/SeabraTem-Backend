# Cadastro de Usuário

/wp-json/api/v1/user/create
{
"first_name": "Nome",
"last_name": "Sobrenome",
"username": "obaraujo",
"user_pass": "obaraujo",
"user_email": "baraujo@stagon.in"
}

# Atualização de Usuário

{
"first_name": "Nome",
"last_name": "Sobrenome",
"nickname": "Apelido",
"user_email": "seu@email.aqui",
"user_pass": "senha",
"description": "A descrição vai aqui"
}

# Gerar toke

/wp-json/jwt-auth/v1/token
{
"username": "obaraujo",
"password": "obaraujo"
}

# Cadastro de Produto

/wp-json/api/v1/product
{
"name": "Nome",
"description": "Sobrenome",
"price": "15.99"
}
