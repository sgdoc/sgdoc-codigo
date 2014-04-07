/*
 * Copyright 2013 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

/**
 * Applet de tratamento de documentos
 * @package br.gov.sgdoc
 * @name SGDocGui
 * @author Fábio Lima <fabioolima@gmail.com>
 */

package br.gov.sgdoc;

import javax.swing.JLabel;
import java.awt.BorderLayout;
import javax.swing.JDialog;
import javax.swing.JFrame;
import javax.swing.JButton;
import javax.swing.JPanel;
import javax.swing.JApplet;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;


public class SGDocGui extends JApplet implements ActionListener 
{
	private static final long serialVersionUID = -8041570592536192215L;
	
	private JDialog dialog;

    public void showMessage (String message)
    {
        JFrame frm = new JFrame();
        frm.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frm.getContentPane().add(getContentPane());
        JPanel messagePane = new JPanel();
        messagePane.add(new JLabel(message));
        JPanel buttonPane = new JPanel();
        JButton button = new JButton("OK"); 
        buttonPane.add(button);
        button.addActionListener(this);
        dialog = new JDialog(frm, "SGDocApplet", true);
        dialog.setModal(true);
        dialog.add(messagePane);
        dialog.add(buttonPane, BorderLayout.SOUTH);
        dialog.setSize(260,70);
        dialog.setLocationRelativeTo(getContentPane());
        dialog.setVisible( true );
    }

    public void actionPerformed (ActionEvent e) {
        if ("OK".equals(e.getActionCommand())) {
            dialog.setVisible(false);
        }
    }
}