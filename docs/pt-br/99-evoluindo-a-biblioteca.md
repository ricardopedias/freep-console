# Evoluindo a biblioteca

[◂ Testando comandos](08-testando-comandos.md) | [Voltar ao índice](indice.md)
-- | --

## 1. Infraestrutura

Se o [Docker](https://www.docker.com/) estiver instalado no computador, não será necessário ter o Composer ou PHP instalados.

Para usar o Composer e as bibliotecas de qualidade de código, use o script `./composer`, localizado na raiz deste repositório. Este script é, na verdade, uma ponte para todos os comandos do Composer, executando-os através do Docker.

## 2. Controle de qualidade

### 2.1. Ferramentas

Para o desenvolvimento, foram utilizadas ferramentas para testes de unidade e análise estática. Todas configuradas no nível máximo de exigência.

São as seguintes ferramentas:

- [PHP Unit](https://phpunit.de)
- [PHP Stan](https://phpstan.org)
- [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHP MD](https://phpmd.org)

### 2.2. Análise estática

Para fazer a análise do código implementado e colher feedback das ferramentas, use:

```bash
./composer analyse
```

### 2.3. Análise estática

Para executar os testes de unidade, use:

```bash
./composer test
```

[◂ Testando comandos](08-testando-comandos.md) | [Voltar ao índice](indice.md)
-- | --
