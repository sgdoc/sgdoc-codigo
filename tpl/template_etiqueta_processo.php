<table width="449" class="etiqueta_processo" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="68" rowspan="3"><img src="<?php print("imagens/" . __LOGO_JPG__); ?>" width="50" height="50" /></td>
        <td width="381" align="left" valign="bottom"><strong><?php print Config::factory()->getParam('config.processo.etiqueta.titulo'); ?></strong></td>
    </tr>
    <tr>
        <td align="left" valign="middle"><strong><?php print Config::factory()->getParam('config.processo.etiqueta.subtitulo'); ?></strong></td>
    </tr>
    <tr>
        <td height="19" align="left" valign="top"></td>
    </tr>
    <tr>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Numero do Processo: </strong><strong><?php echo $TMPL['NUMERO_PROCESSO']; ?></strong></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Autua&ccedil;ao: </strong><strong><?php echo $TMPL['AUTUACAO']; ?></strong></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Interessado: </strong><strong><?php echo $TMPL['INTERESSADO']; ?></strong></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Assunto: </strong><strong> <?php echo $TMPL['ASSUNTO']; ?></strong></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Assunto Complementar: </strong><strong> <?php echo $TMPL['ASSUNTO_COMPLEMENTAR']; ?></strong></td>
    </tr>
    <?php if ($TMPL['DIGITAL'] != NULL): ?>
        <tr>
            <td colspan="2">
                <strong>Digital: </strong><strong><?php echo $TMPL['DIGITAL']; ?></strong>
                <strong>Tipo: <?php echo $TMPL['TIPO']; ?> - Numero: <?php echo $TMPL['NUMERO']; ?></strong></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="2" align="center" valign="middle"><strong><img src="<?php echo "gerador_etiquetas_processos.php?numero_processo={$TMPL['NUMERO_PROCESSO']}&ext=.png" ?>" width="300" height="45" /></strong></td>
    </tr>
</table>