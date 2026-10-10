# Correção de vulnerabilidades — 09/10/2026

## Resultado

| Auditoria | Antes | Depois |
| --- | ---: | ---: |
| Composer: alertas de segurança | 48, em 14 pacotes | 0 |
| npm: alertas distintos | 63, em 18 pacotes | 0 |
| npm: pacotes vulneráveis críticos | 1 | 0 |

A contagem do npm separa os alertas dos pacotes afetados: um pacote pode ter vários alertas. Foram removidos 111 alertas conhecidos nas duas árvores de dependências. Isso não equivale a uma auditoria completa de segurança do código da aplicação.

## Alterações

- Laravel atualizado de 11.51.0 para 12.69.3, com atualização das dependências transitivas do Composer. A migração segue o [guia oficial do Laravel 12](https://laravel.com/docs/12.x/upgrade).
- Atualizados Dompdf, Guzzle, PSR-7, CommonMark, Flysystem e componentes Symfony, entre outros pacotes com correções de segurança.
- Tailwind atualizado para 4.3.3 pelo migrador oficial, com revisão das classes que ele não converteu, incluindo opacidade de fundos, anéis de foco e seletores CSS de gradientes. Configuração migrada para CSS e para `@tailwindcss/postcss`, conforme o [guia oficial do Tailwind](https://tailwindcss.com/docs/upgrade-guide).
- Removido o carregamento do Tailwind antigo por CDN da página inicial. Ela usa o mesmo CSS compilado do restante do projeto, preservando as cores personalizadas. Removido dessa página o CSS legado que sobrescrevia os espaçamentos do Tailwind 4.
- Bootstrap importado na camada CSS `base` por um componente compartilhado, preservando a precedência das classes utilitárias do Tailwind nas telas que utilizam os dois frameworks.
- Axios atualizado para 1.20.0, Vite para 6.4.4, PostCSS para 8.5.29 e dependências transitivas corrigidas. Os arquivos de lock foram atualizados.
- Definidos limites de versão para mPDF e FPDI, antes declarados com `*`.
- Requisito de PHP documentado como 8.4+, alinhado ao OpenSpout 5 já utilizado pelo projeto. Node.js 22+ recomendado para desenvolvimento e CI.
- Tailwind 4 requer navegadores modernos: Safari 16.4+, Chrome 111+ ou Firefox 128+.
- Adicionado workflow de auditoria para alterações nas dependências, execução manual e verificação semanal. Nenhum alerta foi suprimido ou colocado em uma lista de exceções.

## Validação

- `composer audit --locked`: nenhum alerta, nenhum pacote abandonado.
- `npm audit`: zero vulnerabilidades.
- `npm ci`: instalação do zero validada em diretório isolado. Corrigidas duas entradas ausentes dos pacotes embarcados no compilador do Tailwind, que impediam a instalação reproduzível.
- `composer validate --strict`: válido.
- `composer check-platform-reqs`: requisitos atendidos.
- `npm run build`: compilação concluída. Permanece o aviso de tamanho do bundle de gráficos, sem relação com vulnerabilidades.
- Laravel inicializa e registra 115 rotas.
- Testes executados antes e depois em banco MySQL exclusivo e temporário: **24 passaram e as mesmas 6 falharam**, com 53 asserções em ambos os casos. Nenhuma falha adicional foi introduzida. O banco temporário foi removido ao concluir a verificação.
- Verificações adicionais: oito modelos de e-mail com as duas logos embutidas; transporte Gmail com API simulada; PDF do requerimento gerado pelo Dompdf; geração com mPDF; leitura com FPDI; exportação XLSX pelo OpenSpout; respostas HTTP 200 para login, cadastro, página inicial e recuperação de senha. Nenhum e-mail real foi enviado durante esta auditoria.
- Respostas HTTP 200 também para os painéis do aluno, CRADT e diretor geral, novo requerimento e perfil, usando banco temporário e usuário fictício.
- Revisão visual do HTML renderizado das nove telas com o CSS compilado, em 1280 e 390 pixels: sem rolagem horizontal, com logos carregadas e campos visíveis.

## Correção do servidor de desenvolvimento

A validação inicial do visual utilizou o build compilado e deixou de verificar o Vite que já estava em execução. Esse processo ainda referenciava módulos da versão anterior do Vite que foram removidos pela atualização. Como `public/hot` direcionava a aplicação a esse servidor, o CSS retornava HTTP 500 e as páginas ficavam sem estilos.

O processo Vite do projeto foi reiniciado com as dependências atualizadas e o cache de views foi limpo. O CSS passou a responder HTTP 200. Login, página inicial, cadastro e recuperação de senha foram conferidos no navegador pelo servidor ativo; o menu da página inicial também foi testado no tamanho de celular. Nenhuma dependência foi revertida.

## Falhas de teste preexistentes

- Dois testes de recuperação de senha esperam a notificação padrão do Laravel, mas a aplicação usa `App\Notifications\ResetPasswordNotification`.
- Dois testes de cadastro esperam autenticação imediata; a aplicação possui um fluxo de confirmação por código e validações adicionais.
- Dois testes de edição de perfil não fornecem CPF, RG e matrícula exigidos pela validação atual.

As falhas foram registradas antes da atualização. O comportamento dessas funcionalidades não foi alterado para fazer testes antigos passarem.

Os relatórios detalhados, logs de execução e artefatos de verificação locais estão em `storage/app/security-audit/`, ignorado pelo Git.

## Aplicação em outro ambiente

Use PHP 8.4+ e instale as versões dos arquivos de lock com `composer install` e `npm ci`. Gere os assets com `npm run build`, limpe os caches da aplicação e reinicie os processos persistentes de filas e Reverb para que carreguem as dependências corrigidas.
