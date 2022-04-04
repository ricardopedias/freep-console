# Modo de Usar

[Voltar ao índice](indice.md)

A primeira coisa a fazer é criar os comandos necessários e alocá-los em algum diretório. Um comando deve ser implementado com base na classe abstrata `Freep\Console\Comando`:

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

    /**
     * O método "self->manipular()" deve ser implementado em todos os comandos.
     * É neste método que a rotina do comando deverá ser implementada.
     * 
     * O argumento "$argumentos" contém dois métodos úteis:
     * 
     * - opcao(nome da opçao): obtém o valor de uma opção a partir de sua chave. 
     * Se o usuário especificar a opção no terminal, o valor correspondente será 
     * devolvido aqui. Caso contrário, um valor padrão será devolvido de acordo 
     * com o tipo de opção;
     * 
     * - argumento(indice): obtém um argumento fornecido pelo usuário no terminal. 
     * Um argumento é toda palavra sem chaves (ex: -e ou --exemplo) que for especificada 
     * no terminal. 
     */ 
    protected function manipular(Argumentos $argumentos): void
    {
        $mensagem = "Olá";

        // a executar a opção, pode-se exibir uma mensagem padrão no terminal para 
        // notificar o usuário do que está acontecendo
        if ($argumentos->opcao('-l') !== '1') {
            $this->linha("Lendo o arquivo texto contendo a mensagem de olá");

            // rotina que lê o arquivo e atribui a mensagem na variável
            $mensagem = "";
        }

        // se algo der errado, pode-se exibir uma mensagem de erro no terminal
        if ($mensagem === "") {
            $this->erro("Não foi possível ler o arquivo texto");
        }

        // se a exclusão for solicitada, pode-se exibir uma mensagem de alerta no terminal
        if ($argumentos->opcao('-d') === '1') {
            $this->alerta("Apagando o arquivo texto usado");
            // ... rotina para apagar o arquivo
        }

        // Enfim, exibe a mensagem apropriada de forma destacada
        $this->info($mensagem);
    }
}
```

Com os comandos implementados no diretório desejado, é preciso criar uma instância de `Freep\Console\Terminal` e dizer para ela quais são os diretórios contendo os comandos implementados.

Por fim, basta mandar o Terminal executar os comandos através do método `Terminal->executar()`:

```php
// Cria uma instãncia do Terminal. O caminho para a raiz da aplicação deve ser 
// especificado para que os comandos possam utilizá-lo
$terminal = new Terminal("raiz/da/super/aplicacao");

// Uma dica sobre como o terminal pode ser utilizado
// Esta mensagem será exibida nas informações de ajuda
$terminal->setarModoDeUsar("./superapp comando [opcoes] [argumentos]");

// Adiciona dois diretórios contendo comandos
$terminal->carregarComandosDe(__DIR__ . "/comandos");
$terminal->carregarComandosDe(__DIR__ . "/mais-comandos");

// Executa o comando a partir de uma lista
// Esta lista de palavras pode ser obtida da variável "$argv" do PHP
// https://www.php.net/manual/pt_BR/reserved.variables.argv.php
$terminal->executar([ "dizer-ola", "-l", "mensagem.txt", "-d" ]);

```

[Voltar ao índice](indice.md)