# Quebec

Bem-vindo ao Quebec, uma aplicação web desenvolvida com o framework [Laravel](https://laravel.com).

## Estrutura do Projeto

A estrutura do projeto segue o padrão do Laravel e inclui os seguintes diretórios e arquivos principais:

- `app/`: Contém o código-fonte da aplicação, incluindo os controladores, modelos e serviços.
- `bootstrap/`: Contém o arquivo de inicialização da aplicação.
- `config/`: Contém os arquivos de configuração da aplicação.
- `database/`: Contém as migrações e seeds do banco de dados.
- `public/`: Contém o arquivo `index.php`, que é o ponto de entrada da aplicação, além de ativos públicos como imagens, scripts e estilos.
- `resources/`: Contém as views e os recursos de linguagem.
- `routes/`: Contém os arquivos de definição de rotas.
- `storage/`: Contém os logs e outros arquivos gerados pela aplicação.
- `tests/`: Contém os testes automatizados da aplicação.

## Requisitos

Antes de começar, certifique-se de ter as seguintes dependências instaladas:

- [PHP](https://www.php.net/) (versão 8.0 ou superior)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) com npm

## Instalação

Siga os passos abaixo para configurar o ambiente de desenvolvimento:

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/Timeless-inc/Quebec.git
   cd Quebec
   ```

2. **Instale as dependências do PHP:**

    ```bash
    composer install
    ```

3. **Instale as dependências do Node.js:**

    ```bash
    npm install
    ```

4. **Configure o arquivo .env:**

    Copie o arquivo `.env.example` para `.env` e ajuste as configurações conforme necessário, especialmente as configurações de banco de dados.

5. **Gere a chave da aplicação:**

    ```bash
    php artisan key:generate
    ```

6. **Execute as migrações do banco de dados e seeds:**

    ```bash
    php artisan migrate --seed
    ```

7. **Inicie o servidor de desenvolvimento:**
    ```bash
    php artisan serve
    ```

    #### A aplicação estará disponível em http://localhost:8000.

## Contribuição
Contribuições são bem-vindas! Se você deseja contribuir para o projeto, siga as etapas abaixo:
1. Faça um fork do repositório.
2. Crie uma branch para sua contribuição: 
```bash
git checkout -b minha-contribuicao.
```
3. Faça as alterações desejadas e faça commit das suas mudanças: 
```bash 
 git commit -m "Minha contribuição".
```
4. Envie suas alterações para o repositório remoto: 
```bash
git push origin minha-contribuicao.
```
5. Abra um pull request no repositório original.
## Licença
Este projeto está licenciado sob a Licença MIT. Consulte o arquivo LICENSE para obter mais informações.
## Contato
Para mais informações, entre em contato com o desenvolvedor do projeto:

- GitHub: [GitHub](https://github.com/Timeless-inc)

- E-mail Time:
    #### [Anderson Gabriel](mailto:agm4@discente.ifpe.edu.br)
    #### [Aristoteles Lins](mailto:aristoteles.lins.silva@gmail.com)
    #### [Brenno Victor](mailto:bvps1@discente.ifpe.edu.br
    #### [João Pedro](mailto:joaopedro.s.dev@gmail.com)
    #### [Joyce Kelle](mailto:joycekelle.cordeiro@gmail.com)
    #### [Kauê Luí](mailto:klls2@discente.ifpe.edu.br)
    #### [Luís Eduardo](mailto:luisemoliveira1000@gmail.com)
