<?php

require_once 'include/pmieducar/geral.inc.php';

class clsPmieducarModulo
{
    public $cod_modulo;

    public $ref_usuario_exc;

    public $ref_usuario_cad;

    public $nm_tipo;

    public $descricao;

    public $num_meses;

    public $num_semanas;

    public $data_cadastro;

    public $data_exclusao;

    public $ativo;

    public $ref_cod_instituicao;

    public $num_etapas;

    // propriedades padrao

    /**
     * Armazena o total de resultados obtidos na ultima chamada ao metodo lista
     *
     * @var int
     */
    public $_total;

    /**
     * Nome do schema
     *
     * @var string
     */
    public $_schema;

    /**
     * Nome da tabela
     *
     * @var string
     */
    public $_tabela;

    /**
     * Lista separada por virgula, com os campos que devem ser selecionados na proxima chamado ao metodo lista
     *
     * @var string
     */
    public $_campos_lista;

    /**
     * Lista com todos os campos da tabela separados por virgula, padrao para selecao no metodo lista
     *
     * @var string
     */
    public $_todos_campos;

    /**
     * Valor que define a quantidade de registros a ser retornada pelo metodo lista
     *
     * @var int
     */
    public $_limite_quantidade;

    /**
     * Define o valor de offset no retorno dos registros no metodo lista
     *
     * @var int
     */
    public $_limite_offset;

    /**
     * Define o campo padrao para ser usado como padrao de ordenacao no metodo lista
     *
     * @var string
     */
    public $_campo_order_by;

    /**
     * Construtor
     *
     * @return object
     */
    public function __construct(
        $cod_modulo = null,
        $ref_usuario_exc = null,
        $ref_usuario_cad = null,
        $nm_tipo = null,
        $descricao = null,
        $num_meses = null,
        $num_semanas = null,
        $data_cadastro = null,
        $data_exclusao = null,
        $ativo = null,
        $ref_cod_instituicao = null,
        $num_etapas = null
    ) {
        $db = new clsBanco();
        $this->_schema = 'pmieducar.';
        $this->_tabela = "{$this->_schema}modulo";

        $this->_campos_lista = $this->_todos_campos = 'cod_modulo, ref_usuario_exc, ref_usuario_cad, nm_tipo, descricao, num_meses, num_semanas, data_cadastro, data_exclusao, ativo, ref_cod_instituicao, num_etapas';

        if (is_numeric($ref_cod_instituicao)) {
            if (class_exists('clsPmieducarInstituicao')) {
                $tmp_obj = new clsPmieducarInstituicao($ref_cod_instituicao);

                if (method_exists($tmp_obj, 'existe')) {
                    if ($tmp_obj->existe()) {
                        $this->ref_cod_instituicao = $ref_cod_instituicao;
                    }
                } elseif (method_exists($tmp_obj, 'detalhe')) {
                    if ($tmp_obj->detalhe()) {
                        $this->ref_cod_instituicao = $ref_cod_instituicao;
                    }
                }
            } else {
                if ($db->CampoUnico("SELECT 1 FROM pmieducar.instituicao WHERE cod_instituicao = '{$ref_cod_instituicao}'")) {
                    $this->ref_cod_instituicao = $ref_cod_instituicao;
                }
            }
        }

        if (is_numeric($ref_usuario_exc)) {
            if (class_exists('clsPmieducarUsuario')) {
                $tmp_obj = new clsPmieducarUsuario($ref_usuario_exc);
                if (method_exists($tmp_obj, 'existe')) {
                    if ($tmp_obj->existe()) {
                        $this->ref_usuario_exc = $ref_usuario_exc;
                    }
                } elseif (method_exists($tmp_obj, 'detalhe')) {
                    if ($tmp_obj->detalhe()) {
                        $this->ref_usuario_exc = $ref_usuario_exc;
                    }
                }
            } else {
                if ($db->CampoUnico("SELECT 1 FROM pmieducar.usuario WHERE cod_usuario = '{$ref_usuario_exc}'")) {
                    $this->ref_usuario_exc = $ref_usuario_exc;
                }
            }
        }

        if (is_numeric($ref_usuario_cad)) {
            if (class_exists('clsPmieducarUsuario')) {
                $tmp_obj = new clsPmieducarUsuario($ref_usuario_cad);
                if (method_exists($tmp_obj, 'existe')) {
                    if ($tmp_obj->existe()) {
                        $this->ref_usuario_cad = $ref_usuario_cad;
                    }
                } elseif (method_exists($tmp_obj, 'detalhe')) {
                    if ($tmp_obj->detalhe()) {
                        $this->ref_usuario_cad = $ref_usuario_cad;
                    }
                }
            } else {
                if ($db->CampoUnico("SELECT 1 FROM pmieducar.usuario WHERE cod_usuario = '{$ref_usuario_cad}'")) {
                    $this->ref_usuario_cad = $ref_usuario_cad;
                }
            }
        }

        if (is_numeric($cod_modulo)) {
            $this->cod_modulo = $cod_modulo;
        }

        if (is_string($nm_tipo)) {
            $this->nm_tipo = $nm_tipo;
        }

        if (is_string($descricao)) {
            $this->descricao = $descricao;
        }

        if (is_numeric($num_meses)) {
            $this->num_meses = $num_meses;
        }

        if (is_numeric($num_semanas)) {
            $this->num_semanas = $num_semanas;
        }

        if (is_string($data_cadastro)) {
            $this->data_cadastro = $data_cadastro;
        }

        if (is_string($data_exclusao)) {
            $this->data_exclusao = $data_exclusao;
        }

        if (is_numeric($ativo)) {
            $this->ativo = $ativo;
        }

        if (is_numeric($num_etapas)) {
            $this->num_etapas = $num_etapas;
        }
    }

    /**
     * Cria um novo registro
     *
     * @return bool
     */
    public function cadastra()
    {
        if (
            is_numeric($this->ref_usuario_cad)
            && is_string($this->nm_tipo)
            && is_numeric($this->ref_cod_instituicao)
            && is_numeric($this->num_etapas)
        ) {
            $db = new clsBanco();

            $campos = [];
            $valores = [];

            if (is_numeric($this->ref_usuario_cad)) {
                $campos[] = "ref_usuario_cad";
                $valores[] = "'{$this->ref_usuario_cad}'";
            }

            if (is_string($this->nm_tipo)) {
                $campos[] = "nm_tipo";
                $valores[] = "'{$this->nm_tipo}'";
            }

            if (is_string($this->descricao)) {
                $campos[] = "descricao";
                $valores[] = "'{$this->descricao}'";
            }
            if (is_numeric($this->num_meses)) {
                $campos[] = "num_meses";
                $valores[] = "'{$this->num_meses}'";
            }

            if (is_numeric($this->num_semanas)) {
                $campos[] = "num_semanas";
                $valores[] = "'{$this->num_semanas}'";
            }

            $campos[] = "data_cadastro";
            $valores[] = "NOW()";

            $campos[] = "ativo";
            $valores[] = "'1'";

            if (is_numeric($this->ref_cod_instituicao)) {
                $campos[] = "ref_cod_instituicao";
                $valores[] = "'{$this->ref_cod_instituicao}'";
            }

            $campos = join(', ', $campos);
            $valores = join(', ', $valores);

            $db->Consulta("INSERT INTO {$this->_tabela} ( $campos ) VALUES( $valores )");

            return $db->InsertId("{$this->_tabela}_cod_modulo_seq");
        }

        return false;
    }

    /**
     * Edita os dados de um registro
     *
     * @return bool
     */
    public function edita()
    {
        if (is_numeric($this->cod_modulo) && is_numeric($this->ref_usuario_exc)) {
            $db = new clsBanco();
            $set = [];

            if (is_numeric($this->ref_usuario_exc)) {
                $set[] = "ref_usuario_exc = '{$this->ref_usuario_exc}'";
            }

            if (is_numeric($this->ref_usuario_cad)) {
                $set[] = "ref_usuario_cad = '{$this->ref_usuario_cad}'";
            }

            if (is_string($this->nm_tipo)) {
                $set[] = "nm_tipo = '{$this->nm_tipo}'";
            }

            if (is_string($this->descricao)) {
                $set[] = "descricao = '{$this->descricao}'";
            }

            if (is_numeric($this->num_meses)) {
                $set[] = "num_meses = '{$this->num_meses}'";
            }

            if (is_numeric($this->num_semanas)) {
                $set[] = "num_semanas = '{$this->num_semanas}'";
            }

            if (is_string($this->data_cadastro)) {
                $set[] = "data_cadastro = '{$this->data_cadastro}'";
            }

            if (is_numeric($this->ativo)) {
                $set[] = "ativo = '{$this->ativo}'";

                if ((bool)$this->ativo === false) {
                    $set[] = "data_exclusao = NOW()";
                }
            }

            if (is_numeric($this->ref_cod_instituicao)) {
                $set[] = "ref_cod_instituicao = '{$this->ref_cod_instituicao}'";
            }

            if (!empty($set)) {
                $set = join(', ', $set);

                $db->Consulta("UPDATE {$this->_tabela} SET $set WHERE cod_modulo = '{$this->cod_modulo}'");

                return true;
            }
        }

        return false;
    }

    /**
     * Retorna uma lista filtrados de acordo com os parametros
     *
     * @return array
     */
    public function lista(
        $int_cod_modulo = null,
        $int_ref_usuario_exc = null,
        $int_ref_usuario_cad = null,
        $str_nm_tipo = null,
        $str_descricao = null,
        $int_num_meses = null,
        $int_num_semanas = null,
        $date_data_cadastro_ini = null,
        $date_data_cadastro_fim = null,
        $date_data_exclusao_ini = null,
        $date_data_exclusao_fim = null,
        $int_ativo = null,
        $int_ref_cod_instituicao = null,
        $num_etapas = null
    ) {
        $sql = "SELECT {$this->_campos_lista} FROM {$this->_tabela} WHERE ";
        $filtros = [];

        if (is_numeric($int_cod_modulo)) {
            $filtros[] = "cod_modulo = '{$int_cod_modulo}'";
        }

        if (is_numeric($int_ref_usuario_exc)) {
            $filtros[] = "ref_usuario_exc = '{$int_ref_usuario_exc}'";
        }

        if (is_numeric($int_ref_usuario_cad)) {
            $filtros[] = "ref_usuario_cad = '{$int_ref_usuario_cad}'";
        }

        if (is_string($str_nm_tipo)) {
            $filtros[] = "nm_tipo LIKE '%{$str_nm_tipo}%'";
        }

        if (is_string($str_descricao)) {
            $filtros[] = "descricao LIKE '%{$str_descricao}%'";
        }

        if (is_numeric($int_num_meses)) {
            $filtros[] = "num_meses = '{$int_num_meses}'";
        }

        if (is_numeric($int_num_semanas)) {
            $filtros[] = "num_semanas = '{$int_num_semanas}'";
        }

        if (is_string($date_data_cadastro_ini)) {
            $filtros[] = "data_cadastro >= '{$date_data_cadastro_ini}'";
        }

        if (is_string($date_data_cadastro_fim)) {
            $filtros[] = "data_cadastro <= '{$date_data_cadastro_fim}'";
        }

        if (is_string($date_data_exclusao_ini)) {
            $filtros[] = "data_exclusao >= '{$date_data_exclusao_ini}'";
        }

        if (is_string($date_data_exclusao_fim)) {
            $filtros[] = "data_exclusao <= '{$date_data_exclusao_fim}'";
        }

        if (is_null($int_ativo) || $int_ativo) {
            $filtros[] = "ativo = '1'";
        } else {
            $filtros[] = "ativo = '0'";
        }

        if (is_numeric($int_ref_cod_instituicao)) {
            $filtros[] = "ref_cod_instituicao = '{$int_ref_cod_instituicao}'";
        }

        if (empty($filtros)) {
            return false;
        }

        $db = new clsBanco();
        $countCampos = count(explode(',', $this->_campos_lista));
        $resultado = [];
        $filtros = join(' AND ', $filtros);

        $sql .= $filtros . $this->getOrderby() . $this->getLimite();

        $this->_total = $db->CampoUnico("SELECT COUNT(0) FROM {$this->_tabela} WHERE {$filtros}");

        $db->Consulta($sql);

        if ($countCampos > 1) {
            while ($db->ProximoRegistro()) {
                $tupla = $db->Tupla();

                $tupla['_total'] = $this->_total;
                $resultado[] = $tupla;
            }
        } else {
            while ($db->ProximoRegistro()) {
                $tupla = $db->Tupla();
                $resultado[] = $tupla[$this->_campos_lista];
            }
        }

        return !empty($resultado) ? $resultado : false;
    }

    /**
     * Retorna um array com os dados de um registro
     *
     * @return array
     */
    public function detalhe()
    {
        if (is_numeric($this->cod_modulo)) {
            $db = new clsBanco();
            $db->Consulta("SELECT {$this->_todos_campos} FROM {$this->_tabela} WHERE cod_modulo = '{$this->cod_modulo}'");
            $db->ProximoRegistro();

            return $db->Tupla();
        }

        return false;
    }

    /**
     * Retorna um array com os dados de um registro
     *
     * @return array
     */
    public function existe()
    {
        if (is_numeric($this->cod_modulo)) {
            $db = new clsBanco();
            $db->Consulta("SELECT 1 FROM {$this->_tabela} WHERE cod_modulo = '{$this->cod_modulo}'");
            $db->ProximoRegistro();

            return $db->Tupla();
        }

        return false;
    }

    /**
     * Exclui um registro
     *
     * @return bool
     */
    public function excluir()
    {
        if (is_numeric($this->cod_modulo) && is_numeric($this->ref_usuario_exc)) {
            $this->ativo = 0;

            return $this->edita();
        }

        return false;
    }

    /**
     * Define quais campos da tabela serao selecionados na invocacao do metodo lista
     *
     * @return null
     */
    public function setCamposLista($str_campos)
    {
        $this->_campos_lista = $str_campos;
    }

    /**
     * Define que o metodo Lista devera retornoar todos os campos da tabela
     *
     * @return null
     */
    public function resetCamposLista()
    {
        $this->_campos_lista = $this->_todos_campos;
    }

    /**
     * Define limites de retorno para o metodo lista
     *
     * @return null
     */
    public function setLimite($intLimiteQtd, $intLimiteOffset = null)
    {
        $this->_limite_quantidade = $intLimiteQtd;
        $this->_limite_offset = $intLimiteOffset;
    }

    /**
     * Retorna a string com o trecho da query resposavel pelo Limite de registros
     *
     * @return string
     */
    public function getLimite()
    {
        if (is_numeric($this->_limite_quantidade)) {
            $retorno = " LIMIT {$this->_limite_quantidade}";

            if (is_numeric($this->_limite_offset)) {
                $retorno .= " OFFSET {$this->_limite_offset} ";
            }

            return $retorno;
        }

        return '';
    }

    /**
     * Define campo para ser utilizado como ordenacao no metolo lista
     *
     * @return null
     */
    public function setOrderby($strNomeCampo)
    {
        if (is_string($strNomeCampo) && $strNomeCampo) {
            $this->_campo_order_by = $strNomeCampo;
        }
    }

    /**
     * Retorna a string com o trecho da query resposavel pela Ordenacao dos registros
     *
     * @return string
     */
    public function getOrderby()
    {
        if (is_string($this->_campo_order_by)) {
            return " ORDER BY {$this->_campo_order_by} ";
        }

        return '';
    }
}
