# Implementando opções

- [Voltar ao índice](indice.md)
- [Criando Comandos](04-criando-comandos.md)

## Implementando opções


```php
class DizerOla extends Comando
{
    /**
     * O método "self->inicializar()" deve ser implementado em todos os comandos.
     * Pelo menos o método "setarNome" deverá ser invocado para determinar a palavra 
     * a ser usada no terminal para invocar o comando.
     * Os outros métodos podem ser invocados para melhorar a experiência do usuário.
     */
    protected function inicializar(): void
    {
        // O nome do comando, que será usado para invocá-lo no terminal
        $this->setarNome("dizer-ola");

        // Uma descrição sobre o objetivo do comando
        // Esta mensagem será exibida nas informações de ajuda
        $this->setarDescricao("Exibe a mensagem 'olá' no terminal");

        // Uma dica sobre como este comando pode ser utilizado
        // Esta mensagem será exibida nas informações de ajuda
        $this->setarModoDeUsar("./superapp dizer-ola [opcoes]");

        // Uma opção obrigatória e valorada.
        // Quando especificada no terminal, deverá vir acompanhada de um valor
        $this->adicionarOpcao(
            new Opcao(
                '-l',
                '--ler-arquivo',
                'Lê a mensagem a partir de um arquivo texto',
                Opcao::OBRIGATORIA | Opcao::COM_VALOR
            )
        );

        // Uma opção não-obrigatória
        $this->adicionarOpcao(
            new Opcao(
                '-d',
                '--destruir',
                'Apaga o arquivo texto após usá-lo',
                Opcao::OPCIONAL
            )
        );
    }
```

- [Usando os argumentos](06-usando-os-argumentos.md)
- [Voltar ao índice](indice.md)
