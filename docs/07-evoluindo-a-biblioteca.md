# Evoluindo a biblioteca

- [Voltar ao índice](indice.md)
- [Usando os argumentos](06-usando-os-argumentos.md)
## X

## Infraestrutura

Se o [Docker](https://www.docker.com/) estiver instalado no computador, não será necessário ter o Composer, e nem mesmo o PHP, instalados na máquina do desenvolvedor. Para usar o Composer e as bibliotecas de qualidade de código, 
use o script `./composer`, localizado na raiz deste repositório. Este script é, na verdade, uma ponte para todos os comandos do Composer, executando-os através do Docker.

## Controle de qualidade

Para o desenvolvimento, foram utilizadas ferramentas para testes de unidade e análise estática. Todas configuradas no nível máximo de exigência.

São as seguintes ferramentas:

- [PHP Unit](https://phpunit.de)
- [PHP Stan](https://phpstan.org)
- [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHP MD](https://phpmd.org)



[Voltar ao índice](indice.md)
