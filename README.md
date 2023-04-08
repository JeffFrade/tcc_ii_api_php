# API para Coleta de dados do J3M
---

Esse projeto foi desenvolvido para coletar os dados enviados pelo `J3M` e armazenar para posteriormente serem utilizados para análise.
O projeto foi desenvolvido para funcionar em `Docker`, possuindo containers de `PHP`, `nginx`, `MySQL` e `MongoDB` e também para funcionar em ambientes na `Nuvem`.
O projeto foi desenvolvido utilizando `Laravel` (Um `framework` do `PHP`), sobre um servidor `nginx`, bancos de dados `MySQL` para a parte de usuários e `MongoDB` para armazenar as métricas vindas do `J3M`.
A `API` utiliza token `JWT` para acessar as requisições (Exceção ao `login`).

### Como configurar o projeto
---

Na raiz do projeto contém um arquivo chamado `config.sh`, basta o executar (`sh config.sh`) e toda a configuração será efetuada.
Opcional: É possível trocar as variáveis do `.env` para customizar o `Docker` ou a aplicação.

### Documentação da API
---

A documentação da `API` foi feita via `Swagger` e para acessar, basta digitar o mesmo `IP` de onde o `Docker` está executando + a `porta` informada dentro do `.env`.

### Testes da API
---

Na raiz do projeto, contém o arquivo `TCC II - Api.postman_collection.json` que são collections criadas no `Postman` para teste da `API`.
Atualmente a `URL` está direcionando para o ambiente de `produção` da `API`.
