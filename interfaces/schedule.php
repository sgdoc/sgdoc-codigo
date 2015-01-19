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
<script type='text/javascript'>

    var INTERVAL_NTF_PRAZO = 1;

    $(document).ready(function(){
        //        #TODO: Corrigir e refatorar forma de chamado, de preferencia incluindo um timer na sessao do usuário
        $(document).everyTime((INTERVAL_NTF_PRAZO*60*1000),function() {
            $.getJSON('modelos/prazos/verificar_prazos_pendentes.php',{
                request: 'individual'
            }, function(data){
               
                if(data){
                
                    if(data.timeout == 'S'){
                        window.location.href = "<?php echo __URLSERVERFILES__; ?>/logoff.php";
                    }
                    
                    if(data.prazos && data.prazos.notificado == 'S'){
                     
                        $.each(data.prazos.totais, function(i,item){
                            $('#t_meus_prazos_vencidos').html('Meus Prazos Vencidos ('+item.meus_vencidos+')');
                            $('#t_meus_prazos_novos').html('Meus Novos Prazos ('+item.meus_novos+')');
                            $('#t_meus_prazos_pendentes').html('Meus Prazos Pendentes ('+item.meus_pendentes+')');
                            $('#t_setor_prazos_vencidos').html('Prazos Vencidos do Setor ('+item.setor_vencidos+')');
                            $('#t_setor_prazos_novos').html('Novos Prazos do Setor ('+item.setor_novos+')');
                            $('#t_setor_prazos_pendentes').html('Prazos Pendentes do Setor ('+item.setor_pendentes+')');
                        });
                                         
                        if(data.prazos.vencidos && data.prazos.vencidos.usuario){
                            $('#ul_meus_prazos_vencidos').html('');
                            $.each(data.prazos.vencidos.usuario, function(i,item){
                                $('#ul_meus_prazos_vencidos').append('<li>O Prazo <strong>N. '+item.id+'</strong> venceu em <strong>' + item.prazo+' ( '+item.dias*-1+' dia(s) )</strong></li>');
                            });
                        }
                        
                        if(data.prazos.vencidos && data.prazos.vencidos.setor){
                            $('#ul_prazos_vencidos_setor').html('');
                            $.each(data.prazos.vencidos.setor, function(i,item){
                                $('#ul_prazos_vencidos_setor').append('<li>O Prazo <strong>N. '+item.id+'</strong> venceu em <strong>' + item.prazo+' ( '+item.dias*-1+' dia(s) )</strong></li>');
                            });
                        }
                        
                        if(data.prazos.pendentes && data.prazos.pendentes.usuario){
                            $('#ul_prazos_pendentes_usuario').html('');
                            $.each(data.prazos.pendentes.usuario, function(i,item){
                                $('#ul_prazos_pendentes_usuario').append('<li>O Prazo <strong>N. '+item.id+'</strong> esgotara em <strong>' + item.prazo+' ( '+item.dias+' dia(s) )</strong></li>');
                            });
                        }
                        
                        if(data.prazos.pendentes && data.prazos.pendentes.setor){
                            $('#ul_prazos_pendentes_setor').html('');
                            $.each(data.prazos.pendentes.setor, function(i,item){
                                $('#ul_prazos_pendentes_setor').append('<li>O Prazo <strong>N. '+item.id+'</strong> esgotara em <strong>' + item.prazo+' ( '+item.dias+' dia(s) )</strong></li>');
                            });
                        }
                        
                        $('#alert_prazos').dialog({
                            width: 650,
                            modal: true,
                            buttons: {
                                'Ser notificado novamente em: ': function() {},
                                '5mins': function() {
                                    var dialog = $(this);
                                    $.post('modelos/prazos/alterar_sessao_notificacao.php', {time:300}, function(){
                                        dialog.dialog('close');
                                    }, 'json');
                                },
                                '30mins': function() {
                                    var dialog = $(this);
                                    $.post('modelos/prazos/alterar_sessao_notificacao.php', {time:1800}, function(){
                                        dialog.dialog('close');
                                    }, 'json');
                                },
                                '60mins': function() {
                                    var dialog = $(this);
                                    $.post('modelos/prazos/alterar_sessao_notificacao.php', {time:3600}, function(){
                                        dialog.dialog('close');
                                    }, 'json');
                                },
                                '120mins': function() {
                                    var dialog = $(this);
                                    $.post('modelos/prazos/alterar_sessao_notificacao.php', {time:7200}, function(){
                                        dialog.dialog('close');
                                    }, 'json');
                                },
                                '180mins': function() {
                                    var dialog = $(this);
                                    $.post('modelos/prazos/alterar_sessao_notificacao.php', {time:10800}, function(){
                                        dialog.dialog('close');
                                    }, 'json');
                                }
                            }
                        });
                        
                        $( '#accordion_prazos' ).accordion({
                            autoHeight: false,
                            navigation: true
                        });
                    }
                }
            });
            
            INTERVAL_NTF_PRAZO = <?php print(__INTERVAL_NTF_PRAZO__); ?>;
            
        });
    });
</script>

<div id='alert_prazos' title='Alerta de Prazo' style='display: none'>
    <div id='accordion_prazos' >
        <h3><a href='#' id='t_meus_prazos_vencidos'></a></h3>
        <div>
            <ul id='ul_meus_prazos_vencidos'>
            </ul>
        </div>
        <h3><a href='#' id='t_meus_prazos_pendentes'>Novos Prazos</a></h3>
        <div>
            <ul id='ul_prazos_pendentes_usuario'>
            </ul>
        </div>
        <h3><a href='#' id='t_setor_prazos_vencidos'>Meus Prazos Pendentes</a></h3>
        <div>
            <ul id='ul_prazos_vencidos_setor'>
            </ul>
        </div>
        <h3><a href='#' id='t_setor_prazos_pendentes'>Prazos Pendentes do Setor</a></h3>
        <div>
            <ul id='ul_prazos_pendentes_setor'>
            </ul>
        </div>
    </div>
</div>