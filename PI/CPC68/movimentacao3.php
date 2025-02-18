<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

include_once 'src/Movimentacao.php';
include_once 'src/MovimentacaoRepositorio.php';
include_once 'src/Ativo.php';
include_once 'src/Funcionario.php';
include_once 'src/Ambiente.php';
include_once 'src/Unidade.php';

use src\Movimentacao;
use src\MovimentacaoRepositorio;
use src\Ativo;
use src\Funcionario;
use src\Ambiente;
use src\Unidade;

$idAtivo = $_POST['idAtivo'];
$idFuncionario = $_POST['idAtorCadastro'];
$idAmbienteOrigem = $_POST['idAmbienteOrigem'];
$idUnidade = $_POST['idUnidade'];
$tipo = $_POST['tipo'];
$descricao = $_POST['descricao'];

$Movimentacao = new Movimentacao();
$MovimentacaoRepositorio = new MovimentacaoRepositorio();

//Ativo
$Ativo = new Ativo();
$Ativo->setId($idAtivo);
$Movimentacao->setAtivo($Ativo);

//Quem cadastrou
$Funcionario = new Funcionario();
$Funcionario->setId($idFuncionario);
$Movimentacao->setFuncionario($Funcionario);

//Ambiente de origem e sua Unidade
$AmbienteOrigem = new Ambiente();
$Unidade = new Unidade();
$Unidade->setId($idUnidade);
$AmbienteOrigem->setUnidade($Unidade);
$AmbienteOrigem->setId($idAmbienteOrigem);
$Movimentacao->setAmbienteOrigem($AmbienteOrigem);

$Movimentacao->setTipo($tipo);
$Movimentacao->setDescricao($descricao);


if ($tipo == 1) { // EMPRESTIMO ENTRE AMBIENTES DE MESMA UNIDADE
    $idAmbienteDestino = $_POST['idAmbienteDestino'];
    $AmbienteDestino = new Ambiente();
    $AmbienteDestino->setId($idAmbienteDestino);
    $Movimentacao->setAmbienteDestino($AmbienteDestino);
    
    $dtRetornoDate = $_POST['dtRetornoDate'];
    $dtRetornoTime = $_POST['dtRetornoTime'];
    $dtRetorno = $dtRetornoDate . " " . $dtRetornoTime;
    $Movimentacao->setDtRetorno($dtRetorno);
    
    $dtSaidaDate = $_POST['dtSaidaDate'];
    $dtSaidaTime = $_POST['dtSaidaTime'];
    $dtSaida = $dtSaidaDate . " " . $dtSaidaTime;
    $Movimentacao->setDtSaida($dtSaida);
    
    $existe = $MovimentacaoRepositorio->existeMovimentacao($Movimentacao);
    if ($existe != true) {
        $cadastro = $MovimentacaoRepositorio->movCadastrarEmprestimo($Movimentacao);
        $retorno = $MovimentacaoRepositorio->movEmprestimo($Movimentacao);
        if ($retorno == true && $cadastro == true) {
            $_SESSION['resultado'] = 0;
            header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
        } else {
            $_SESSION['resultado'] = 1;
            header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
        }
    } else {
        $_SESSION['resultado'] = 3;
        header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
    }
    
} else if ($tipo == 2) { // TRANSFERENCIA ENTRE AMBIENTES DE MESMA UNIDADE
    $idAmbienteDestino = $_POST['idAmbienteDestino'];
    $AmbienteDestino = new Ambiente();
    $AmbienteDestino->setId($idAmbienteDestino);
    $Movimentacao->setAmbienteDestino($AmbienteDestino);
    
    $dtSaidaDate = $_POST['dtSaidaDate'];
    $dtSaidaTime = $_POST['dtSaidaTime'];
    $dtSaida = $dtSaidaDate . " " . $dtSaidaTime;
    $Movimentacao->setDtSaida($dtSaida);
    
    $existe = $MovimentacaoRepositorio->existeMovimentacao($Movimentacao);
    if ($existe != true) {
        $cadastro = $MovimentacaoRepositorio->movCadastrarTransferencia($Movimentacao);
        $retorno = $MovimentacaoRepositorio->movTransferencia($Movimentacao);
        if ($retorno == true && $cadastro == true) {
            $_SESSION['resultado'] = 0;
            header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
        } else {
            $_SESSION['resultado'] = 1;
            header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
        }
    } else {
        $_SESSION['resultado'] = 3;
        header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
    }
} else if ($tipo == 3) { // ENVIAR APRA ASS. TÉCNICA
    $dtRetornoDate = $_POST['dtRetornoDate'];
    $dtRetornoTime = $_POST['dtRetornoTime'];
    $dtRetorno = $dtRetornoDate . " " . $dtRetornoTime;
    $Movimentacao->setDtRetorno($dtRetorno);
    
    $dtSaidaDate = $_POST['dtSaidaDate'];
    $dtSaidaTime = $_POST['dtSaidaTime'];
    $dtSaida = $dtSaidaDate . " " . $dtSaidaTime;
    $Movimentacao->setDtSaida($dtSaida);

    $existe = $MovimentacaoRepositorio->existeMovimentacao($Movimentacao);
    if ($existe != true) {
        $cadastro = $MovimentacaoRepositorio->movCadastrarConcerto($Movimentacao);
        $retorno = $MovimentacaoRepositorio->movAssTecnica($Movimentacao);
        if ($retorno == true && $cadastro == true) {
            $_SESSION['resultado'] = 0;
            header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
        } else {
            $_SESSION['resultado'] = 1;
            header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
        }
    } else {
        $_SESSION['resultado'] = 3;
        header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
    }
} else if ($tipo == 4) { // BAIXA
    $dtSaidaDate = $_POST['dtSaidaDate'];
    $dtSaidaTime = $_POST['dtSaidaTime'];
    $dtSaida = $dtSaidaDate . " " . $dtSaidaTime;
    $Movimentacao->setDtSaida($dtSaida);
    
    $existe = $MovimentacaoRepositorio->existeMovimentacao($Movimentacao);
    if ($existe != true) {
        $cadastro = $MovimentacaoRepositorio->movCadastrarBaixa($Movimentacao);
        $retorno = $MovimentacaoRepositorio->movBaixa($Movimentacao);
        if ($retorno == true && $cadastro == true) {
            $_SESSION['resultado'] = 0;
            header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
        } else {
            $_SESSION['resultado'] = 1;
            header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
        }
    } else {
        $_SESSION['resultado'] = 3;
        header('Location: movimentacao1-2.php?idUnidade=' . $idUnidade . '&idAmbiente=' . $idAmbienteOrigem . '&nomeUnidade=' . $_POST['nomeUnidadeOrigem'] . '&nomeAmbiente=' . $_POST['nomeAmbienteOrigem'] . '');
    }
}


