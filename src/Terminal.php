<?php

declare(strict_types=1);

namespace Freep\Console;

use Freep\Console\Comandos\Ajuda;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class Terminal
{
    private string $caminhoAplicacao;

    /** @var array<string> */
    private array $listaDiretorios = [];

    private string $comandoExecutado = "nao";

    public function __construct(string $caminhoAplicacao)
    {
        $this->caminhoAplicacao = realpath($caminhoAplicacao);

        if ($this->caminhoAplicacao === false || is_dir($this->caminhoAplicacao) === false) {
            throw new InvalidArgumentException("O diretório de aplicação especificado não existe");
        }

        $this->carregarComandosDe(__DIR__ . '/Comandos');
    }

    public function caminhoAplicacao(): string
    {
        return $this->caminhoAplicacao;
    }

    public function carregarComandosDe(string $caminhoComandos): self
    {
        $caminhoReal = realpath($caminhoComandos);

        if ($caminhoReal === false || is_dir($caminhoReal) === false) {
            throw new InvalidArgumentException("O diretórios especificado para comandos não existe");
        }

        $this->listaDiretorios[] = $caminhoComandos;
        return $this;
    }

    /**
     * @param array<int,string> $argumentos
     */
    public function executar(array $argumentos): void
    {
        if (isset($argumentos[0]) === false) {
            return;
        }

        if (in_array($argumentos[0], ['--ajuda', '-a']) === true) {
            $argumentos[0] = "ajuda";
        }

        $nomeComando          = array_shift($argumentos);
        $quantidadeArgumentos = count($argumentos);

        ini_set("display_errors", "1");

        try {
            $this->executarComando($nomeComando, $argumentos);
        } catch (Throwable $e) {
            echo "\033[0;31m✗  " . $e->getFile() . " on line " . $e->getLine() . "\033[0m\n";
            echo "\033[0;31m   " . $e->getMessage() . "\033[0m\n";
        }
    }

    public function obterListaComandos(): array
    {
        $todosComandos = [];

        foreach ($this->listaDiretorios as $path) {
            $todosComandos = array_merge($todosComandos, $this->obterListaDiretorios($path));
        }

        return $todosComandos;
    }

    /**
     * @param array<int,string> $argumentosDoComando
     */
    private function executarComando(string $nome, array $argumentos): void
    {
        $nomeComando = $this->normalizarNomeComando($nome);

        $todosComandos = $this->obterListaComandos();

        if ($nomeComando === "Ajuda") {
            (new Ajuda($this))->executar($argumentos);
            $this->comandoExecutado = Ajuda::class;
            return;
        }

        foreach ($todosComandos as $arquivoComando) {
            $nomeCompletoClasse = $this->interpretarNomeClasse($arquivoComando);

            if (class_exists($nomeCompletoClasse) === false) {
                continue;
            }

            $objetoComando = (new $nomeCompletoClasse($this));
            if ($nomeComando !== $this->normalizarNomeComando($objetoComando->nome())) {
                continue;
            }
    
            $objetoComando->executar($argumentos);
            $this->comandoExecutado = $nomeCompletoClasse;
            return;
        }

        // dispara saída padrão para o bash capturar
        echo "comando nao encontrado";
    }

    /** @return array<int,string> */
    private function obterListaDiretorios(string $caminho): array
    {
        $caminhosContexto = array_diff(
            (array)scandir($caminho),
            ['.', '..', '.gitkeep']
        );

        return array_map(fn($comando) => "$caminho/$comando", $caminhosContexto);
    }

    private function extrairNamespace(string $umArquivo): string
    {
        if (is_file($umArquivo) === false) {
            return "";
        }

        $todasLinhas = (array)file($umArquivo);
        foreach ($todasLinhas as $linha) {
            $linha = (string)$linha;
            if (str_starts_with(trim($linha), "namespace") === true) {
                return trim(str_replace(["namespace ", ";"], "", $linha));
            }
        }

        throw new RuntimeException("Não é possível extrair o namespace do arquivo '{$umArquivo}'");
    }

    private function extrairNomeClasse(string $arquivoComando): string
    {
        return str_replace('.php', '', array_slice(explode("/", $arquivoComando), -1)[0]);
    }

    public function interpretarNomeClasse(string $arquivoComando): string
    {
        return $this->extrairNamespace($arquivoComando)
            . "\\" . $this->extrairNomeClasse($arquivoComando);
    }

    private function normalizarNomeComando(string $nomeKebabCase): string
    {
        // make:user-controller -> [Make, User-controller]
        $kebabCase = array_map(
            fn($noh) => ucfirst($noh),
            explode(":", $nomeKebabCase)
        );

        $nomeSemDoisPontos = implode("", $kebabCase); // MakeUser-controller

        // MakeUser-controller -> [MakeUser, Controller]
        $pascalCase = array_map(
            fn($noh) => ucfirst($noh),
            explode("-", $nomeSemDoisPontos)
        );

        return implode("", $pascalCase); // MakeUserController
    }

    public function comandoExecutado(): string
    {
        return $this->comandoExecutado;
    }
}
