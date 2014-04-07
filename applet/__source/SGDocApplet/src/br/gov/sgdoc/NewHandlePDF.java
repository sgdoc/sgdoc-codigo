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
 * @name NewHandlePDF
 * @author Fábio Lima <fabioolima@gmail.com>
 */

package br.gov.sgdoc;

import br.gov.sgdoc.gui.MainApplet;
import br.gov.sgdoc.gui.SGDocAppleDataModel;

import java.awt.Graphics2D;
import java.awt.geom.AffineTransform;
import java.awt.image.BufferedImage;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;

import javax.imageio.ImageIO;

import com.itextpdf.text.pdf.PRStream;
import com.itextpdf.text.pdf.PdfName;
import com.itextpdf.text.pdf.PdfNumber;
import com.itextpdf.text.pdf.PdfObject;
import com.itextpdf.text.pdf.PdfReader;
import com.itextpdf.text.pdf.PdfStamper;
import com.itextpdf.text.pdf.parser.PdfImageObject;


public class NewHandlePDF implements Runnable
{

    public static float FACTOR = 0.35f;

    private String filePath;
    private SGDocAppleDataModel model;
    private int rowLine;

    public NewHandlePDF (String file, SGDocAppleDataModel model, int line) 
    {
        this.filePath = file;
        this.model = model;
        this.rowLine = line;
    }

    public int getSize () 
    {
        int size = 0;
        try {
            PdfReader reader = new PdfReader(filePath);
            size = reader.getXrefSize();
            reader.close();
        } catch (Exception excp) {
            excp.printStackTrace();
        }
        return size;
    }

    public void run ()
    {
        try {
            // Le o Arquivo
            PdfReader reader = new PdfReader(filePath);
            int numXsize = reader.getXrefSize();
            PdfObject object;
            PRStream stream;
            // Procura por imagens
            for (int idx = 0; idx < numXsize; idx++) {
                object = reader.getPdfObject(idx);
                if (object == null || !object.isStream())
                    continue;
                stream = (PRStream)object;
                PdfObject pdfsubtype = stream.get(PdfName.SUBTYPE);
                if (pdfsubtype != null && pdfsubtype.toString().equals(PdfName.IMAGE.toString())) {
                    PdfImageObject image = new PdfImageObject(stream);

                    if ("png".equals(image.getFileType())) {
                        continue;
                    }

                    BufferedImage bufImg = image.getBufferedImage();
                    if (bufImg == null) { continue; }
                    int width = (int)(bufImg.getWidth() * FACTOR);
                    int height = (int)(bufImg.getHeight() * FACTOR);
                    BufferedImage img = new BufferedImage(width, height, BufferedImage.TYPE_BYTE_GRAY);
                    Graphics2D graph = img.createGraphics();
                    graph.drawRenderedImage(bufImg, AffineTransform.getScaleInstance(FACTOR, FACTOR));
                    ByteArrayOutputStream imgBytes = new ByteArrayOutputStream();
                    ImageIO.write(img, "JPG", imgBytes);
                    stream.clear();
                    stream.setData(imgBytes.toByteArray(), false, PRStream.BEST_COMPRESSION);
                    stream.put(PdfName.TYPE, PdfName.XOBJECT);
                    stream.put(PdfName.SUBTYPE, PdfName.IMAGE);
                    stream.put(PdfName.FILTER, PdfName.DCTDECODE);
                    stream.put(PdfName.WIDTH, new PdfNumber(width));
                    stream.put(PdfName.HEIGHT, new PdfNumber(height));
                    stream.put(PdfName.BITSPERCOMPONENT, new PdfNumber(8));
                    stream.put(PdfName.COLORSPACE, PdfName.DEVICEGRAY);
                }
            }
            // System.getProperty("java.io.tmpdir");
            String destFile = filePath.substring(filePath.lastIndexOf("/") + 1, filePath.lastIndexOf("."));
            destFile = System.getProperty("java.io.tmpdir").toString() + java.io.File.separator + destFile + ".pdf";
            // Salva PDF Modificado
            PdfStamper stamper = new PdfStamper(reader, new FileOutputStream(destFile), 'A');
            stamper.setFullCompression();
            stamper.createXmpMetadata();
            stamper.close();
            reader.close();
            
            this.model.setValueAt(MainApplet.readableFileSize(new File(destFile).length()), this.rowLine, 1);
            this.model.setValueAt(destFile, this.rowLine, 3);
        } catch (Exception de) {
            System.err.println(de.getMessage());
        }
    }
}