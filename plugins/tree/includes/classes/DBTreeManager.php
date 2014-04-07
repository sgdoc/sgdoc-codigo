<?php

class DBTreeManager extends Base {

    function checkVariable($string) {
        return str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&apos;', '&lt;', '&gt;'), $string);
    }

    public function getVinculacaoDocumento($digital, $pageName, $vinculacao/* Anexacao=1 Apensacao=2 */) {
        try {
            $sttm = $this->conn->prepare("SELECT DCP.DIGITAL AS PAI, DCF.DIGITAL AS FILHO FROM TB_DOCUMENTOS_VINCULACAO DV
INNER JOIN TB_DOCUMENTOS_CADASTRO DCP ON DCP.ID = DV.ID_DOCUMENTO_PAI
INNER JOIN TB_DOCUMENTOS_CADASTRO DCF ON DCF.ID = DV.ID_DOCUMENTO_FILHO
WHERE DCP.DIGITAL =  ? AND DV.FG_ATIVO = 1 AND ST_ATIVO = 1 AND ID_VINCULACAO = ?");
            $sttm->bindParam(1, $digital, PDO::PARAM_STR);
            $sttm->bindParam(2, $vinculacao, PDO::PARAM_INT);
            $sttm->execute();
            $out = $sttm->fetchAll(PDO::FETCH_ASSOC);
            $str = NULL;

            if (!empty($out)) {
                foreach ($out as $key => $value) {
                    $supp = NULL;
                    if (true) {
                        $supp = "<ul class='ajax'>"
                                . "<li id='" . $value['FILHO'] . "'>{url:" . $pageName . "?action=getElementList&ownerEl=" . $value['FILHO'] . "}</li>"
                                . "</ul>";
                    }
                    $str .= "<li class='text' id='" . $value['FILHO'] . "'>"
                            . "<span>" . $value['FILHO'] . "</span>"
                            . $supp
                            . "</li>";
                }
            }
        } catch (PDOException $e) {
            $str = FAILED;
        }

        return $str;
    }

    public function setRootId($root) {
        $this->root = $root;
    }

    public function getRootId() {
        if ($this->root) {
            return $this->root;
        }
        return 0;
    }

}

?>