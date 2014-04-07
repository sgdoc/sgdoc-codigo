<?php
/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */
?>

<style type="text/css">
    body {
        margin-left: 0px;
        margin-top: 0px;
    }
</style>

<link href='css/CSS_001.css' rel='stylesheet' type='text/css'>

<?php
try {
    Processo::gerarEtiquetaProcessoByNumero($_GET['numero_processo']);
} catch (Exception $e) {
    print $e->getMessage();
}
?>

<script type="text/javascript">
    if (confirm("Você deseja imprimir esta etiqueta?\n"
            + "Obs: Para a imprimir corretamente esta etiqueta\n"
            + "remova as informações adicionais do cabeçalho da página!")) {
        window.print();
    }
</script>