# Quebec

O Quebec é o projeto que dá vida ao Sistema de Requerimento Estudantil (SRE), uma plataforma web criada para organizar, digitalizar e dar mais transparência aos pedidos acadêmicos do IFPE Campus Igarassu.

Na prática, o sistema foi pensado para reduzir a dependência de formulários dispersos, planilhas e trocas manuais de e-mail, reunindo em um só lugar o envio de solicitações, o acompanhamento de status, os encaminhamentos internos e o histórico de movimentações.

## Sobre o projeto

O SRE nasce como uma resposta a um problema cotidiano da comunidade acadêmica: o caminho para abrir, acompanhar e concluir um requerimento ainda era fragmentado e pouco intuitivo. A proposta do sistema é deixar esse processo mais simples para o estudante e mais organizado para os setores responsáveis.

O projeto é desenvolvido com base em levantamento com discentes, reuniões com a CRADT e validação em ambiente controlado com estudantes do campus. O resultado é uma plataforma que busca aproximar a experiência digital da rotina real da instituição, com foco em clareza, agilidade e autonomia.

## Prévia do sistema

[(Clique Aqui) - Sistema de Requerimento Estudantil](https://freeimage.host/a/sistema-de-requerimento-estudantil.H3CdwQ)


## O que o sistema oferece

- Para o Estudante: criação de requerimentos, acompanhamento do andamento, acesso ao histórico e recebimento de notificações.
- Para a CRADT: análise das solicitações, complementações, encaminhamentos e gestão do fluxo dos pedidos.
- Para a Coordenadores e Responsáveis: processamento de encaminhamentos, consultas e avaliação de aprovação.
- Para a Instituição: centralização das informações, rastreabilidade dos processos e redução de retrabalho.

## Estrutura do projeto

A estrutura segue o padrão do Laravel e organiza o sistema da seguinte forma:

- `app/`: regras de negócio, modelos, controladores, serviços, eventos, notificações e mailables.
- `bootstrap/`: inicialização da aplicação.
- `config/`: configurações gerais do projeto.
- `database/`: migrações, factories e seeds.
- `public/`: ponto de entrada da aplicação e arquivos públicos.
- `resources/`: views, estilos e scripts da interface.
- `routes/`: definição das rotas do sistema.
- `storage/`: arquivos gerados pela aplicação, logs e uploads.
- `tests/`: testes automatizados.

## Tecnologias e recursos

- Laravel 11
- PHP 8.2+
- Vite e Tailwind CSS para a interface
- Laravel Breeze para autenticação
- Geração de relatórios e documentos em PDF
- Notificações e atualização de status
- Recursos de gráficos e painéis administrativos

## Requisitos

Antes de rodar o projeto, verifique se você tem instalado:

- PHP 8.2 ou superior
- Composer
- Node.js com npm

## Como executar localmente

1. Clone o repositório:

    ```bash
    git clone https://github.com/Timeless-inc/Quebec.git
    cd Quebec
    ```

2. Instale as dependências do PHP:

    ```bash
    composer install
    ```

3. Instale as dependências do frontend:

    ```bash
    npm install
    ```

4. Configure o ambiente:

    Copie o arquivo `.env.example` para `.env` e ajuste as configurações do seu ambiente, principalmente banco de dados, e-mail e demais serviços usados pela aplicação.

5. Gere a chave da aplicação:

    ```bash
    php artisan key:generate
    ```

6. Execute as migrações e os seeds:

    ```bash
    php artisan migrate --seed
    ```

7. Inicie o projeto em modo desenvolvimento:

    ```bash
    composer run dev
    ```

    Esse comando sobe a aplicação, o Vite, os logs e o listener de filas em um único passo.

Se preferir iniciar manualmente, você também pode usar `php artisan serve` e `npm run dev` em terminais separados.

8. Instale e inicie o Reverb:

    ```bash
    php artisan reverb:install 
    ```
    e depois rode o comando para iniciar o Reverb:

    ```bash
    php artisan reverb:start
    ```

9. Execute o a fila de jobs do Laravel:

    ```bash
    php artisan queue:work
    ```

Aqui é apenas caso queira testar o envio de emails e notificações, caso contrário, não é necessário. (Faltando setar apenas um Sandbox de email para testes, como Mailtrap ou Mailhog no .env).

## Contribuição

Contribuições são bem-vindas. Se você quiser colaborar, siga este fluxo:

1. Faça um fork do repositório.
2. Crie uma branch para sua alteração.
3. Implemente as mudanças e registre o commit.
4. Envie a branch para o repositório remoto.
5. Abra um pull request.

## Licença

Este projeto está licenciado sob a AGPL-3.0. Consulte o arquivo [LICENSE](LICENSE) para os detalhes completos.

## Equipe

Projeto desenvolvido por:

- Anderson Gabriel
- Aristoteles Lins
- Brenno Victor
- João Pedro
- Joyce Kelle
- Kauê Luí
- Luís Eduardo

Se desejar, você também pode acompanhar o repositório da equipe em [Timeless-inc](https://github.com/Timeless-inc).
