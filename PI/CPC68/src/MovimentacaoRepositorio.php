<?php
namespace src;
date_default_timezone_set('America/Sao_Paulo');
include_once 'Movimentacao.php';
include_once 'Ativo.php';
include_once 'Subgrupo.php';
include_once 'Funcionario.php';
include_once 'Ambiente.php';
include_once 'Conexao.php';

class MovimentacaoRepositorio
{

    private $Conexao;
    
    public function __construct() {
        $this->Conexao = new Conexao();
    }
    
    public function existeMovimentacao($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        $numeroLinhas = mysqli_num_rows($this->Conexao->getConexao()->query(
            "SELECT M.*, U.ID" .
            " FROM MOVIMENTACAO AS M, UNIDADE AS U" .
            " WHERE M.IDATIVO = " . $Objeto->getAtivo()->getId() .
            " AND U.ID = " . $Objeto->getAmbienteOrigem()->getUnidade()->getId() .
            " AND M.ESTATUS <> 0" .
            " AND M.SITUACAO = 0" .
            ";"));
        if ($numeroLinhas > 0) {
            $retorno = TRUE;
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    
    // TRANSFERENCIA
    public function movCadastrarTransferencia($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        if ($this->Conexao->getConexao()->query(
            "INSERT INTO MOVIMENTACAO(IDATIVO,IDFUNCIONARIO,IDAMBIENTEORIGEM,IDAMBIENTEDESTINO,TIPO,DESCRICAO,ESTATUS,DTSAIDA,DTREGISTRO,SITUACAO)" .
            "VALUES(" .
            "    " . $Objeto->getAtivo()->getId() .
            " ,  " . $Objeto->getFuncionario()->getId() .
            " ,  " . $Objeto->getAmbienteOrigem()->getId() .
            " ,  " . $Objeto->getAmbienteDestino()->getId() .
            " ,  " . $Objeto->getTipo() .
            " , '" . $Objeto->getDescricao() .
            "', 0" .
            " , '" . $Objeto->getDtSaida() .
            "', NOW()" .
            " , 0)")) {
            $retorno = TRUE;
        } else {
            echo "FALHA NO REPOSITORIO.";
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    
    public function movTransferencia($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        if ($this->Conexao->getConexao()->query(
            "UPDATE ATIVO SET" .
            " IDAMBIENTE  = " . $Objeto->getAmbienteDestino()->getId() .
            " WHERE ID = " . $Objeto->getAtivo()->getId())) {
            $retorno = TRUE;
        } else {
            echo "Falha na transferência. (REPOSITÓRIO)<br />";
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    
    // EMPRESTIMO
    public function movCadastrarEmprestimo($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        if ($this->Conexao->getConexao()->query(
            "INSERT INTO MOVIMENTACAO(IDATIVO,IDFUNCIONARIO,IDAMBIENTEORIGEM,IDAMBIENTEDESTINO,TIPO,DESCRICAO,ESTATUS,DTSAIDA,DTRETORNO,DTREGISTRO,SITUACAO)" .
            "VALUES(" .
            "    " . $Objeto->getAtivo()->getId() .
            " ,  " . $Objeto->getFuncionario()->getId() .
            " ,  " . $Objeto->getAmbienteOrigem()->getId() .
            " ,  " . $Objeto->getAmbienteDestino()->getId() .
            " ,  " . $Objeto->getTipo() .
            " , '" . $Objeto->getDescricao() .
            "', 1" .
            " , '" . $Objeto->getDtSaida() .
            "', '" . $Objeto->getDtRetorno() .
            "', NOW()" .
            " , 0)")) {
            $retorno = TRUE;
        } else {
            echo "FALHA NO REPOSITORIO.";
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    public function movEmprestimo($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        if ($this->Conexao->getConexao()->query(
            "UPDATE ATIVO SET" .
            " ESTATUS  = 1" .
            " WHERE ID = " . $Objeto->getAtivo()->getId())) {
            $retorno = TRUE;
        } else {
            echo "Falha na transferência. (REPOSITÓRIO)<br />";
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    
    // ASSISTENCIA TÉCNICA
    public function movCadastrarConcerto($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        if ($this->Conexao->getConexao()->query(
            "INSERT INTO MOVIMENTACAO(IDATIVO,IDFUNCIONARIO,IDAMBIENTEORIGEM,TIPO,DESCRICAO,ESTATUS,DTSAIDA,DTRETORNO,DTREGISTRO,SITUACAO)" .
            "VALUES(" .
            "    " . $Objeto->getAtivo()->getId() .
            " ,  " . $Objeto->getFuncionario()->getId() .
            " ,  " . $Objeto->getAmbienteOrigem()->getId() .
            " ,  " . $Objeto->getTipo() .
            " , '" . $Objeto->getDescricao() .
            "', 1" .
            " , '" . $Objeto->getDtSaida() .
            "', '" . $Objeto->getDtRetorno() .
            "', NOW()" .
            " , 0)")) {
            $retorno = TRUE;
        } else {
            echo "FALHA NO REPOSITORIO.";
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    public function movAssTecnica($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        if ($this->Conexao->getConexao()->query(
            "UPDATE ATIVO SET" .
            " ESTATUS  = 2" .
            " WHERE ID = " . $Objeto->getAtivo()->getId())) {
            $retorno = TRUE;
        } else {
            echo "Falha na transferência. (REPOSITÓRIO)<br />";
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    
    // BAIXA
    public function movCadastrarBaixa($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        if ($this->Conexao->getConexao()->query(
            "INSERT INTO MOVIMENTACAO(IDATIVO,IDFUNCIONARIO,IDAMBIENTEORIGEM,TIPO,DESCRICAO,ESTATUS,DTSAIDA,DTREGISTRO,SITUACAO)" .
            "VALUES(" .
            "    " . $Objeto->getAtivo()->getId() .
            " ,  " . $Objeto->getFuncionario()->getId() .
            " ,  " . $Objeto->getAmbienteOrigem()->getId() .
            " ,  " . $Objeto->getTipo() .
            " , '" . $Objeto->getDescricao() .
            "', 0" .
            " , '" . $Objeto->getDtSaida() .
            "', NOW()" .
            " , 0)")) {
            $retorno = TRUE;
        } else {
            echo "FALHA NO REPOSITORIO.";
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    public function movBaixa($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        if ($this->Conexao->getConexao()->query(
            "UPDATE ATIVO SET" .
            " ESTATUS  = 3" .
            ", SITUACAO = 1" .
            " WHERE ID = " . $Objeto->getAtivo()->getId())) {
            $retorno = TRUE;
        } else {
            echo "Falha na transferência. (REPOSITÓRIO)<br />";
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    
    public function atualizarStatus($Objeto) {
        $retorno = FALSE;
        $this->Conexao->abrirConexao();
        if ($this->Conexao->getConexao()->query(
            "UPDATE MOVIMENTACAO SET" .
            " ESTATUS = " . $Objeto->getStatus() .
            " WHERE ID = " . $Objeto->getId() . ";")) {
            $retorno = TRUE;
        } else {
            echo "Falha na atualização do ativo. (REPOSITÓRIO)<br />";
        }
        $this->Conexao->fecharConexao();
        return $retorno;
    }
    
    public function consultarMovimentacaoEmAndamento($Objeto) {
        $this->Conexao->abrirConexao();
        $codigoSQL = "SELECT M.*, " .
            
            "A.ID AS ATIVOID, " .
            "A.IDSUBGRUPO AS ATIVOIDSUBGRUPO, " .
            "A.IDAMBIENTE AS ATIVOIDAMBIENTE, " .
            "A.IDFUNCIONARIO AS ATIVOIDFUNCIONARIO, " .
            "A.NOME AS ATIVONOME, " .
            "A.DESCRICAO AS ATIVODESCRICAO, " .
            "A.CODIGOBARRA AS ATIVOCODIGOBARRA, " .
            "A.ESTATUS AS ATIVOESTATUS, " .
            "A.DTREGISTRO AS ATIVODTREGISTRO, " .
            "A.SITUACAO AS ATIVOSITUACAO, " .
            
            "F.ID AS FUNCIONARIOID, " .
            "F.NOME AS FUNCIONARIONOME, " .
            
            "AMBO.ID AS AMBIENTEORIGEMID, " .
            "AMBO.NOME AS AMBIENTEORIGEMNOME " .
            
            " FROM MOVIMENTACAO AS M, ATIVO AS A, FUNCIONARIO AS F, AMBIENTE AS AMBO" .
            
            " WHERE  A.CODIGOBARRA = '" . $Objeto->getAtivo()->getCodigoBarra() .
            "' AND M.IDATIVO = A.ID" .
            " AND M.SITUACAO = 0" .
            " AND M.ESTATUS <> 0" .
            " AND M.ESTATUS <> 3" .
            " AND M.IDFUNCIONARIO = F.ID" .
            " AND M.IDAMBIENTEORIGEM = AMBO.ID;";
        $numeroLinhas = mysqli_num_rows($this->Conexao->getConexao()->query($codigoSQL));
        if ($numeroLinhas > 0) {
            $linha = mysqli_fetch_assoc($this->Conexao->getConexao()->query($codigoSQL));
            $Objeto->setId($linha['ID']);
            
            $Ativo = new Ativo();
            $Ativo->setId($linha['ATIVOID']);
            $SubgrupoAt = new Subgrupo();
            $SubgrupoAt->setId($linha['ATIVOIDSUBGRUPO']);
            $Ativo->setSubgrupo($SubgrupoAt);
            $AmbienteAt = new Ambiente();
            $AmbienteAt->setId($linha['ATIVOIDAMBIENTE']);
            $Ativo->setAmbiente($AmbienteAt);
            $FuncionarioAt = new Funcionario();
            $FuncionarioAt->setId($linha['ATIVOIDFUNCIONARIO']);
            $Ativo->setFuncionario($FuncionarioAt);
            $Ativo->setNome($linha['ATIVONOME']);
            $Ativo->setDescricao($linha['ATIVODESCRICAO']);
            $Ativo->setCodigoBarra($linha['ATIVOCODIGOBARRA']);
            $Ativo->setStatus($linha['ATIVOESTATUS']);
            $Ativo->setDtRegistro($linha['ATIVODTREGISTRO']);
            $Ativo->setSituacao($linha['ATIVOSITUACAO']);
            $Objeto->setAtivo($Ativo);
            
            $Funcionario = new Funcionario();
            $Funcionario->setId($linha['IDFUNCIONARIO']);
            $Funcionario->setNome($linha['FUNCIONARIONOME']);
            $Objeto->setFuncionario($Funcionario);
            $AmbienteOrigem = new Ambiente();
            $AmbienteOrigem->setId($linha['AMBIENTEORIGEMID']);
            $AmbienteOrigem->setNome($linha['AMBIENTEORIGEMNOME']);
            $Objeto->setAmbienteOrigem($AmbienteOrigem);
            $AmbienteDestino = new Ambiente();
            $AmbienteDestino->setId($linha['IDAMBIENTEDESTINO']);
            $Objeto->setAmbienteDestino($AmbienteDestino);
            $Objeto->setTipo($linha['TIPO']);
            $Objeto->setDescricao($linha['DESCRICAO']);
            
            $dtRetorno = date('d-m-Y',strtotime($linha['DTRETORNO'])); // Transformar data retorno em dia-mes-ANO 31-12-2018
            $dtHoje = date('d-m-Y'); // Pegar data de hoje como dia-mes-ANO 31-12-2018
            if ($linha['ESTATUS'] == 1 && $dtRetorno < $dtHoje) { // Se o status da movimentacao estiver 1 (Em empréstimo) e data retorno for menor que a data de hoje:
                $this->Conexao->getConexao()->query("UPDATE MOVIMENTACAO SET ESTATUS = 2 WHERE ID = " . $linha['ID'] . ";"); // Mudar status da movimentacao para 2 (Atrasado)
                $linha['ESTATUS'] = 2; // Para nao precisar atualizar a pagina
            }
            
            $Objeto->setStatus($linha['ESTATUS']);
            $Objeto->setDtEntrada($linha['DTENTRADA']);
            $Objeto->setDtSaida($linha['DTSAIDA']);
            $Objeto->setDtRetorno($linha['DTRETORNO']);
            $Objeto->setDtRegistro($linha['DTREGISTRO']);
            $Objeto->setSituacao($linha['SITUACAO']);
        }
        $this->Conexao->fecharConexao();
        return $Objeto;
    }
    
    public function consultarMovimentacao($Objeto) {
        $this->Conexao->abrirConexao();
        $codigoSQL = "SELECT M.*, " .
            
            "A.ID AS ATIVOID, " .
            "A.IDSUBGRUPO AS ATIVOIDSUBGRUPO, " .
            "A.IDAMBIENTE AS ATIVOIDAMBIENTE, " .
            "A.IDFUNCIONARIO AS ATIVOIDFUNCIONARIO, " .
            "A.NOME AS ATIVONOME, " .
            "A.DESCRICAO AS ATIVODESCRICAO, " .
            "A.CODIGOBARRA AS ATIVOCODIGOBARRA, " .
            "A.ESTATUS AS ATIVOESTATUS, " .
            "A.DTREGISTRO AS ATIVODTREGISTRO, " .
            "A.SITUACAO AS ATIVOSITUACAO, " .
            
            "F.ID AS FUNCIONARIOID, " .
            "F.NOME AS FUNCIONARIONOME, " .
            
            "AMBO.ID AS AMBIENTEORIGEMID, " .
            "AMBO.NOME AS AMBIENTEORIGEMNOME " .
            
            " FROM MOVIMENTACAO AS M, ATIVO AS A, FUNCIONARIO AS F, AMBIENTE AS AMBO" .
            
            " WHERE  M.ID = " . $Objeto->getId() .
            " AND M.IDATIVO = A.ID" .
            " AND M.SITUACAO = 0" .
            " AND M.IDFUNCIONARIO = F.ID" .
            " AND M.IDAMBIENTEORIGEM = AMBO.ID;";
        $numeroLinhas = mysqli_num_rows($this->Conexao->getConexao()->query($codigoSQL));
        if ($numeroLinhas > 0) {
            $linha = mysqli_fetch_assoc($this->Conexao->getConexao()->query($codigoSQL));
            $Objeto->setId($linha['ID']);
            
            $Ativo = new Ativo();
            $Ativo->setId($linha['ATIVOID']);
            $SubgrupoAt = new Subgrupo();
            $SubgrupoAt->setId($linha['ATIVOIDSUBGRUPO']);
            $Ativo->setSubgrupo($SubgrupoAt);
            $AmbienteAt = new Ambiente();
            $AmbienteAt->setId($linha['ATIVOIDAMBIENTE']);
            $Ativo->setAmbiente($AmbienteAt);
            $FuncionarioAt = new Funcionario();
            $FuncionarioAt->setId($linha['ATIVOIDFUNCIONARIO']);
            $Ativo->setFuncionario($FuncionarioAt);
            $Ativo->setNome($linha['ATIVONOME']);
            $Ativo->setDescricao($linha['ATIVODESCRICAO']);
            $Ativo->setCodigoBarra($linha['ATIVOCODIGOBARRA']);
            $Ativo->setStatus($linha['ATIVOESTATUS']);
            $Ativo->setDtRegistro($linha['ATIVODTREGISTRO']);
            $Ativo->setSituacao($linha['ATIVOSITUACAO']);
            $Objeto->setAtivo($Ativo);
            
            $Funcionario = new Funcionario();
            $Funcionario->setId($linha['IDFUNCIONARIO']);
            $Funcionario->setNome($linha['FUNCIONARIONOME']);
            $Objeto->setFuncionario($Funcionario);
            $AmbienteOrigem = new Ambiente();
            $AmbienteOrigem->setId($linha['AMBIENTEORIGEMID']);
            $AmbienteOrigem->setNome($linha['AMBIENTEORIGEMNOME']);
            $Objeto->setAmbienteOrigem($AmbienteOrigem);
            $AmbienteDestino = new Ambiente();
            $AmbienteDestino->setId($linha['IDAMBIENTEDESTINO']);
            $Objeto->setAmbienteDestino($AmbienteDestino);
            $Objeto->setTipo($linha['TIPO']);
            $Objeto->setDescricao($linha['DESCRICAO']);
            
            $dtRetorno = date('d-m-Y',strtotime($linha['DTRETORNO'])); // Transformar data retorno em dia-mes-ANO 31-12-2018
            $dtHoje = date('d-m-Y'); // Pegar data de hoje como dia-mes-ANO 31-12-2018
            if ($linha['ESTATUS'] == 1 && $dtRetorno < $dtHoje) { // Se o status da movimentacao estiver 1 (Em empréstimo) e data retorno for menor que a data de hoje:
                $this->Conexao->getConexao()->query("UPDATE MOVIMENTACAO SET ESTATUS = 2 WHERE ID = " . $linha['ID'] . ";"); // Mudar status da movimentacao para 2 (Atrasado)
                $linha['ESTATUS'] = 2; // Para nao precisar atualizar a pagina
            }
            
            $Objeto->setStatus($linha['ESTATUS']);
            $Objeto->setDtEntrada($linha['DTENTRADA']);
            $Objeto->setDtSaida($linha['DTSAIDA']);
            $Objeto->setDtRetorno($linha['DTRETORNO']);
            $Objeto->setDtRegistro($linha['DTREGISTRO']);
            $Objeto->setSituacao($linha['SITUACAO']);
        }
        $this->Conexao->fecharConexao();
        return $Objeto;
    }
    
    public function contarUltimaMovimentacao() {
        $quantidade = 0;
        $this->Conexao->abrirConexao();
        $resultado = $this->Conexao->getConexao()->query(
            "SELECT COUNT(ID) AS QUANTIDADE FROM MOVIMENTACAO;");
        $numeroLinhas = mysqli_num_rows($resultado);
        if ($numeroLinhas > 0) {
            $linha = mysqli_fetch_assoc($resultado);
            $quantidade = $linha['QUANTIDADE'];
        }
        $this->Conexao->fecharConexao();
        return $quantidade;
    }
    
    public function contarMovimentacaoPorAmbienteDestinoDesbloqueado($idAmbiente) {
        $quantidade = 0;
        $this->Conexao->abrirConexao();
        $resultado = $this->Conexao->getConexao()->query(
            "SELECT COUNT(ID) AS QUANTIDADE FROM MOVIMENTACAO AS M" .
            " WHERE M.IDAMBIENTEDESTINO = " . $idAmbiente .";");
        $numeroLinhas = mysqli_num_rows($resultado);
        if ($numeroLinhas > 0) {
            $linha = mysqli_fetch_assoc($resultado);
            $quantidade = $linha['QUANTIDADE'];
        }
        $this->Conexao->fecharConexao();
        return $quantidade;
    }
    
    public function contarMovimentacaoPorAmbienteOrigemDesbloqueado($idAmbiente) {
        $quantidade = 0;
        $this->Conexao->abrirConexao();
        $resultado = $this->Conexao->getConexao()->query(
            "SELECT COUNT(ID) AS QUANTIDADE FROM MOVIMENTACAO AS M" .
            " WHERE M.IDAMBIENTEORIGEM = " . $idAmbiente .";");
        $numeroLinhas = mysqli_num_rows($resultado);
        if ($numeroLinhas > 0) {
            $linha = mysqli_fetch_assoc($resultado);
            $quantidade = $linha['QUANTIDADE'];
        }
        $this->Conexao->fecharConexao();
        return $quantidade;
    }
    
    public function listarUltimaMovimentacao() {
        $lista = null;
        $this->Conexao->abrirConexao();
        $resultado = $this->Conexao->getConexao()->query(
            "SELECT M.*, " .
            
            "A.ID AS ATIVOID, " .
            "A.IDSUBGRUPO AS ATIVOIDSUBGRUPO, " .
            "A.IDAMBIENTE AS ATIVOIDAMBIENTE, " .
            "A.IDFUNCIONARIO AS ATIVOIDFUNCIONARIO, " .
            "A.NOME AS ATIVONOME, " .
            "A.DESCRICAO AS ATIVODESCRICAO, " .
            "A.CODIGOBARRA AS ATIVOCODIGOBARRA, " .
            "A.ESTATUS AS ATIVOESTATUS, " .
            "A.DTREGISTRO AS ATIVODTREGISTRO, " .
            "A.SITUACAO AS ATIVOSITUACAO, " .
            
            "F.ID AS FUNCIONARIOID, " .
            "F.NOME AS FUNCIONARIONOME, " .
            
            "AMBO.ID AS AMBIENTEORIGEMID, " .
            "AMBO.NOME AS AMBIENTEORIGEMNOME " .
            
            " FROM MOVIMENTACAO AS M, ATIVO AS A, FUNCIONARIO AS F, AMBIENTE AS AMBO" .
            
            " WHERE " .
            " M.IDATIVO = A.ID" .
            " AND M.SITUACAO = 0" .
            " AND M.IDFUNCIONARIO = F.ID" .
            " AND M.IDAMBIENTEORIGEM = AMBO.ID" .
            
            
            " ORDER BY M.DTREGISTRO DESC;"
            );
        $numeroLinhas = mysqli_num_rows($resultado);
        if ($numeroLinhas > 0) {
            $c = 0;
            while ($linha = mysqli_fetch_assoc($resultado)) {
                $Movimentacao = new Movimentacao();
                $Movimentacao->setId($linha['ID']);
                
                $Ativo = new Ativo();
                $Ativo->setId($linha['ATIVOID']);
                $SubgrupoAt = new Subgrupo();
                $SubgrupoAt->setId($linha['ATIVOIDSUBGRUPO']);
                $Ativo->setSubgrupo($SubgrupoAt);
                $AmbienteAt = new Ambiente();
                $AmbienteAt->setId($linha['ATIVOIDAMBIENTE']);
                $Ativo->setAmbiente($AmbienteAt);
                $FuncionarioAt = new Funcionario();
                $FuncionarioAt->setId($linha['ATIVOIDFUNCIONARIO']);
                $Ativo->setFuncionario($FuncionarioAt);
                $Ativo->setNome($linha['ATIVONOME']);
                $Ativo->setDescricao($linha['ATIVODESCRICAO']);
                $Ativo->setCodigoBarra($linha['ATIVOCODIGOBARRA']);
                $Ativo->setStatus($linha['ATIVOESTATUS']);
                $Ativo->setDtRegistro($linha['ATIVODTREGISTRO']);
                $Ativo->setSituacao($linha['ATIVOSITUACAO']);
                $Movimentacao->setAtivo($Ativo);
                
                $Funcionario = new Funcionario();
                $Funcionario->setId($linha['IDFUNCIONARIO']);
                $Funcionario->setNome($linha['FUNCIONARIONOME']);
                $Movimentacao->setFuncionario($Funcionario);
                $AmbienteOrigem = new Ambiente();
                $AmbienteOrigem->setId($linha['AMBIENTEORIGEMID']);
                $AmbienteOrigem->setNome($linha['AMBIENTEORIGEMNOME']);
                $Movimentacao->setAmbienteOrigem($AmbienteOrigem);
                $AmbienteDestino = new Ambiente();
                $AmbienteDestino->setId($linha['IDAMBIENTEDESTINO']);
                $Movimentacao->setAmbienteDestino($AmbienteDestino);
                $Movimentacao->setTipo($linha['TIPO']);
                $Movimentacao->setDescricao($linha['DESCRICAO']);
                $Movimentacao->setDtEntrada($linha['DTENTRADA']);
                $Movimentacao->setDtSaida($linha['DTSAIDA']);
                $Movimentacao->setDtRetorno($linha['DTRETORNO']);
                
                $dtRetorno = date('d-m-Y',strtotime($linha['DTRETORNO'])); // Transformar data retorno em dia-mes-ANO 31-12-2018
                $dtHoje = date('d-m-Y'); // Pegar data de hoje como dia-mes-ANO 31-12-2018
                if ($linha['ESTATUS'] == 1 && $dtRetorno < $dtHoje) { // Se o status da movimentacao estiver 1 (Em empréstimo) e data retorno for menor que a data de hoje:
                    $this->Conexao->getConexao()->query("UPDATE MOVIMENTACAO SET ESTATUS = 2 WHERE ID = " . $linha['ID'] . ";"); // Mudar status da movimentacao para 2 (Atrasado)
                    $linha['ESTATUS'] = 2; // Para nao precisar atualizar a pagina
                }
                
                $Movimentacao->setStatus($linha['ESTATUS']);
                $Movimentacao->setDtRegistro($linha['DTREGISTRO']);
                $Movimentacao->setSituacao($linha['SITUACAO']);
                $lista[$c] = $Movimentacao;
                $c++;
            }
        }
        $this->Conexao->fecharConexao();
        return $lista;
    }
    
    public function listarMovimentacaoPorAmbienteDestinoDesbloqueado($idAmbiente) {
        $lista = null;
        $this->Conexao->abrirConexao();
        $resultado = $this->Conexao->getConexao()->query(
            "SELECT M.*, " .
            
            "A.ID AS ATIVOID, " .
            "A.IDSUBGRUPO AS ATIVOIDSUBGRUPO, " .
            "A.IDAMBIENTE AS ATIVOIDAMBIENTE, " .
            "A.IDFUNCIONARIO AS ATIVOIDFUNCIONARIO, " .
            "A.NOME AS ATIVONOME, " .
            "A.DESCRICAO AS ATIVODESCRICAO, " .
            "A.CODIGOBARRA AS ATIVOCODIGOBARRA, " .
            "A.ESTATUS AS ATIVOESTATUS, " .
            "A.DTREGISTRO AS ATIVODTREGISTRO, " .
            "A.SITUACAO AS ATIVOSITUACAO, " .
            
            "F.ID AS FUNCIONARIOID, " .
            "F.NOME AS FUNCIONARIONOME, " .
            
            "AMBO.ID AS AMBIENTEORIGEMID, " .
            "AMBO.NOME AS AMBIENTEORIGEMNOME, " .
            
            "AMBD.ID AS AMBIENTEDESTINOID, " .
            "AMBD.NOME AS AMBIENTEDESTINONOME " .

            " FROM MOVIMENTACAO AS M, ATIVO AS A, FUNCIONARIO AS F, AMBIENTE AS AMBO, AMBIENTE AS AMBD" .
            
            " WHERE M.IDAMBIENTEDESTINO = " . $idAmbiente .
            " AND M.IDATIVO = A.ID" .
            " AND M.SITUACAO = 0" .
            " AND M.IDFUNCIONARIO = F.ID" .
            " AND M.IDAMBIENTEORIGEM = AMBO.ID" .
            " AND M.IDAMBIENTEDESTINO = AMBD.ID" .
            
            
            " ORDER BY M.DTREGISTRO DESC;"
            );
        $numeroLinhas = mysqli_num_rows($resultado);
        if ($numeroLinhas > 0) {
            $c = 0;
            while ($linha = mysqli_fetch_assoc($resultado)) {
                $Movimentacao = new Movimentacao();
                $Movimentacao->setId($linha['ID']);
                
                $Ativo = new Ativo();
                $Ativo->setId($linha['ATIVOID']);
                $SubgrupoAt = new Subgrupo();
                $SubgrupoAt->setId($linha['ATIVOIDSUBGRUPO']);
                $Ativo->setSubgrupo($SubgrupoAt);
                $AmbienteAt = new Ambiente();
                $AmbienteAt->setId($linha['ATIVOIDAMBIENTE']);
                $Ativo->setAmbiente($AmbienteAt);
                $FuncionarioAt = new Funcionario();
                $FuncionarioAt->setId($linha['ATIVOIDFUNCIONARIO']);
                $Ativo->setFuncionario($FuncionarioAt);
                $Ativo->setNome($linha['ATIVONOME']);
                $Ativo->setDescricao($linha['ATIVODESCRICAO']);
                $Ativo->setCodigoBarra($linha['ATIVOCODIGOBARRA']);
                $Ativo->setStatus($linha['ATIVOESTATUS']);
                $Ativo->setDtRegistro($linha['ATIVODTREGISTRO']);
                $Ativo->setSituacao($linha['ATIVOSITUACAO']);
                $Movimentacao->setAtivo($Ativo);
                
                $Funcionario = new Funcionario();
                $Funcionario->setId($linha['IDFUNCIONARIO']);
                $Funcionario->setNome($linha['FUNCIONARIONOME']);
                $Movimentacao->setFuncionario($Funcionario);
                $AmbienteOrigem = new Ambiente();
                $AmbienteOrigem->setId($linha['AMBIENTEORIGEMID']);
                $AmbienteOrigem->setNome($linha['AMBIENTEORIGEMNOME']);
                $Movimentacao->setAmbienteOrigem($AmbienteOrigem);
                $AmbienteDestino = new Ambiente();
                $AmbienteDestino->setId($linha['AMBIENTEDESTINOID']);
                $AmbienteDestino->setNome($linha['AMBIENTEDESTINONOME']);
                $Movimentacao->setAmbienteDestino($AmbienteDestino);
                $Movimentacao->setTipo($linha['TIPO']);
                $Movimentacao->setDescricao($linha['DESCRICAO']);
                $Movimentacao->setDtEntrada($linha['DTENTRADA']);
                $Movimentacao->setDtSaida($linha['DTSAIDA']);
                $Movimentacao->setDtRetorno($linha['DTRETORNO']);
                
                
                $dtRetorno = date('d-m-Y',strtotime($linha['DTRETORNO'])); // Transformar data retorno em dia-mes-ANO 31-12-2018
                $dtHoje = date('d-m-Y'); // Pegar data de hoje como dia-mes-ANO 31-12-2018
                if ($linha['ESTATUS'] == 1 && $dtRetorno < $dtHoje) { // Se o status da movimentacao estiver 1 (Em empréstimo) e data retorno for menor que a data de hoje:
                    $this->Conexao->getConexao()->query("UPDATE MOVIMENTACAO SET ESTATUS = 2 WHERE ID = " . $linha['ID'] . ";"); // Mudar status da movimentacao para 2 (Atrasado)
                    $linha['ESTATUS'] = 2; // Mudar "ESTATUS" para 2 pra nao precisar atualizar a pagina
                }
                
                $Movimentacao->setStatus($linha['ESTATUS']);
                $Movimentacao->setDtRegistro($linha['DTREGISTRO']);
                $Movimentacao->setSituacao($linha['SITUACAO']);
                $lista[$c] = $Movimentacao;
                $c++;
            }
        }
        $this->Conexao->fecharConexao();
        return $lista;
    }
    
    public function listarMovimentacaoPorAmbienteOrigemDesbloqueado($idAmbiente) {
        $lista = null;
        $this->Conexao->abrirConexao();
        $resultado = $this->Conexao->getConexao()->query(
            "SELECT M.*, " .
            
            "A.ID AS ATIVOID, " .
            "A.IDSUBGRUPO AS ATIVOIDSUBGRUPO, " .
            "A.IDAMBIENTE AS ATIVOIDAMBIENTE, " .
            "A.IDFUNCIONARIO AS ATIVOIDFUNCIONARIO, " .
            "A.NOME AS ATIVONOME, " .
            "A.DESCRICAO AS ATIVODESCRICAO, " .
            "A.CODIGOBARRA AS ATIVOCODIGOBARRA, " .
            "A.ESTATUS AS ATIVOESTATUS, " .
            "A.DTREGISTRO AS ATIVODTREGISTRO, " .
            "A.SITUACAO AS ATIVOSITUACAO, " .
            
            "F.ID AS FUNCIONARIOID, " .
            "F.NOME AS FUNCIONARIONOME, " .
            
            "AMBO.ID AS AMBIENTEORIGEMID, " .
            "AMBO.NOME AS AMBIENTEORIGEMNOME " .
            
            " FROM MOVIMENTACAO AS M, ATIVO AS A, FUNCIONARIO AS F, AMBIENTE AS AMBO" .
            
            " WHERE M.IDAMBIENTEORIGEM = " . $idAmbiente .
            " AND M.IDATIVO = A.ID" .
            " AND M.SITUACAO = 0" .
            " AND M.IDFUNCIONARIO = F.ID" .
            " AND M.IDAMBIENTEORIGEM = AMBO.ID" .
            
            
            " ORDER BY M.DTREGISTRO DESC;"
            );
        $numeroLinhas = mysqli_num_rows($resultado);
        if ($numeroLinhas > 0) {
            $c = 0;
            while ($linha = mysqli_fetch_assoc($resultado)) {
                $Movimentacao = new Movimentacao();
                $Movimentacao->setId($linha['ID']);
                
                $Ativo = new Ativo();
                $Ativo->setId($linha['ATIVOID']);
                $SubgrupoAt = new Subgrupo();
                $SubgrupoAt->setId($linha['ATIVOIDSUBGRUPO']);
                $Ativo->setSubgrupo($SubgrupoAt);
                $AmbienteAt = new Ambiente();
                $AmbienteAt->setId($linha['ATIVOIDAMBIENTE']);
                $Ativo->setAmbiente($AmbienteAt);
                $FuncionarioAt = new Funcionario();
                $FuncionarioAt->setId($linha['ATIVOIDFUNCIONARIO']);
                $Ativo->setFuncionario($FuncionarioAt);
                $Ativo->setNome($linha['ATIVONOME']);
                $Ativo->setDescricao($linha['ATIVODESCRICAO']);
                $Ativo->setCodigoBarra($linha['ATIVOCODIGOBARRA']);
                $Ativo->setStatus($linha['ATIVOESTATUS']);
                $Ativo->setDtRegistro($linha['ATIVODTREGISTRO']);
                $Ativo->setSituacao($linha['ATIVOSITUACAO']);
                $Movimentacao->setAtivo($Ativo);
                
                $Funcionario = new Funcionario();
                $Funcionario->setId($linha['IDFUNCIONARIO']);
                $Funcionario->setNome($linha['FUNCIONARIONOME']);
                $Movimentacao->setFuncionario($Funcionario);
                $AmbienteOrigem = new Ambiente();
                $AmbienteOrigem->setId($linha['AMBIENTEORIGEMID']);
                $AmbienteOrigem->setNome($linha['AMBIENTEORIGEMNOME']);
                $Movimentacao->setAmbienteOrigem($AmbienteOrigem);
                $AmbienteDestino = new Ambiente();
                $AmbienteDestino->setId($linha['IDAMBIENTEDESTINO']);
                $Movimentacao->setAmbienteDestino($AmbienteDestino);
                $Movimentacao->setTipo($linha['TIPO']);
                $Movimentacao->setDescricao($linha['DESCRICAO']);
                
                $dtRetorno = date('d-m-Y',strtotime($linha['DTRETORNO'])); // Transformar data retorno em dia-mes-ANO 31-12-2018
                $dtHoje = date('d-m-Y'); // Pegar data de hoje como dia-mes-ANO 31-12-2018
                if ($linha['ESTATUS'] == 1 && $dtRetorno < $dtHoje) { // Se o status da movimentacao estiver 1 (Em empréstimo) e data retorno for menor que a data de hoje:
                    $this->Conexao->getConexao()->query("UPDATE MOVIMENTACAO SET ESTATUS = 2 WHERE ID = " . $linha['ID'] . ";"); // Mudar status da movimentacao para 2 (Atrasado)
                    $linha['ESTATUS'] = 2; // Para nao precisar atualizar a pagina
                }
                
                $Movimentacao->setStatus($linha['ESTATUS']);
                $Movimentacao->setDtEntrada($linha['DTENTRADA']);
                $Movimentacao->setDtSaida($linha['DTSAIDA']);
                $Movimentacao->setDtRetorno($linha['DTRETORNO']);
                $Movimentacao->setDtRegistro($linha['DTREGISTRO']);
                $Movimentacao->setSituacao($linha['SITUACAO']);
                $lista[$c] = $Movimentacao;
                $c++;
            }
        }
        $this->Conexao->fecharConexao();
        return $lista;
    }
    
    
}