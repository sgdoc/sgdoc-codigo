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
 * @name SGDocApplet
 * @author Fábio Lima <fabioolima@gmail.com>
 */

package br.gov.sgdoc;

import br.gov.sgdoc.gui.MainApplet;
import br.gov.sgdoc.gui.SGDocAppleDataModel;
import br.gov.sgdoc.Uploader;
import br.gov.sgdoc.SGDocGui;

import javax.swing.JApplet;
import javax.swing.JFrame;
import javax.swing.JButton;

public class SGDocApplet extends JApplet 
{

    private static final long serialVersionUID = -3207851532114846776L;
    protected JButton btnUpload;
    private static String myUrl;
    private static SGDocGui gui = new SGDocGui();

    public void init() 
    {
    	MainApplet main = new MainApplet();
    	myUrl = this.getParameter("uploadUrl");
    	getContentPane().add(main);
    }

    public static void runx(JApplet applet, int width, int height) 
    {
	    JFrame frame = new JFrame();
	    frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
	    frame.getContentPane().add(applet);
	    frame.setSize(width, height);
	    applet.init();
	    frame.setVisible(true);
  	}

    public static void main(String[] args) throws Exception
    {
        javax.swing.SwingUtilities.invokeLater(new Runnable() {
            public void run() {
                runx(new SGDocApplet(), 90, 30);
            }
        });
    }
    
    public static void startUploader (SGDocAppleDataModel model) throws Exception
    {
    	try {
    		System.out.println(myUrl);
    		Uploader upload = new Uploader(myUrl, model);
    		upload.run();
    		gui.showMessage(upload.getStatus());
    		System.out.println(upload.getStatus());
    	} catch (Exception ex) {
    		ex.printStackTrace();
    	}
    }
}