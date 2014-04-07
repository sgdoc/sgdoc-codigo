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

/* Notificacao de prazos */
include('schedule.php');

$count = Util::headerVerificadorCaixas();
$auth = Zend_Auth::getInstance()->getStorage()->read();
?>

<style type="text/css">
    body {
        margin-left: 0px;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
    }
    .link-caixas{
        cursor: pointer;
    }
    #menu_verificador_caixas{

    }
</style>

<script type="text/javascript">

    function caixa_saida() {
        window.location.href = 'caixa_saida.php';
    }

    function area_trabalho() {
        window.location.href = 'area_trabalho.php';
    }

    function caixa_entrada() {
        window.location.href = 'caixa_entrada.php';
    }

    function caixa_externos() {
        window.location.href = 'caixa_externos.php';
    }

    function caixa_prazos() {
        window.location.href = 'lista_prazos.php';
    }

    function caixa_chamados() {
        window.location.href = 'qualificar_demandas.php';
    }

    function alterar_unidade() {
        window.location.href = '<?php print Controlador::getInstance()->acl->getLinkChangeUnidade(); ?>';
    }

</script>
<table id="menu_verificador_caixas" width="100%" border="0" align="center" class="style13">
    <tr>
        <th align="left" valign="baseline">
            <span class="style25"><?php print("Olá, " . $auth->NOME . " - " . $auth->DIRETORIA); ?>.</span>
        </th>
        <th align="right" valign="baseline">
            <span class="style25">
                <a class="link-caixas" OnClick=caixa_entrada(); title="Caixa de Entrada - Onde são listados os documentos e processos do seu setor!"><?php echo "CAIXA DE ENTRADA ({$count['caixa_entrada']})"; ?>
                </a> - <a class="link-caixas" OnClick=area_trabalho(); title="Área de Trabalho - Onde são listados os documentos e processos do seu setor!"><?php echo "ÁREA DE TRABALHO ({$count['area_trabalho']})"; ?>
                </a> - <a class="link-caixas" OnClick=caixa_saida(); title="Caixa de Saída - Onde são listados os documentos e processos enviados para outro setor e que ainda não foram recebidos pelo destinatário!"><?php echo "CAIXA DE SAÍDA ({$count['caixa_saida']})"; ?>
                </a> - <a class="link-caixas" OnClick=caixa_externos(); title="Externos - Onde são listados os documentos e processos encaminhados para destinos que não compõe a estrutura da instituição!!" ><?php echo "EXTERNOS ({$count['caixa_externos']})"; ?>
                </a> - <a class="link-caixas" OnClick=caixa_prazos(); title="Prazos - Onde são listados os prazos setorias dos documentos e processos!">
                    <?php
                    ($count['prazos'] == 0) ? print("PRAZOS ({$count['prazos']})")  : print("<blink>PRAZOS ({$count['prazos']})</blick>");
                    ?>
                </a> - <a class="link-caixas" OnClick=caixa_chamados(); title="Qualifique chamados finalizados ou simplesmente veja o histórico de chamados abertos por você!">CHAMADOS</a>
            </a> - <a class="link-caixas" OnClick=alterar_unidade(); title="Alterar Unidade em que está logado!">[ALTERAR UNIDADE]</a>
    </span>
</th>
</tr>
</table>