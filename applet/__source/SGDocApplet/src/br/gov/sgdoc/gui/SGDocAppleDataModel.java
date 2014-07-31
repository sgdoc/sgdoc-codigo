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
 * @name SGDocAppletDataModel
 * @author Fábio Lima <fabioolima@gmail.com>
 */

package br.gov.sgdoc.gui;

import javax.swing.table.AbstractTableModel;

import java.util.Vector;


public class SGDocAppleDataModel extends AbstractTableModel
{	
	private static final long serialVersionUID = 4355974384878391046L;
	
	private Vector<Vector<?>> data = new Vector<Vector<?>>();

	/* Array de Strings com o nome das colunas. */
    private String[] column = new String[]{"Nome", "Tamanho", "Público", "caminho"};
    
    public final Object[] longValues = {"", new Long(20), Boolean.TRUE, ""};
    
	@Override
	public int getRowCount() {
		return data.size();
	}

	@Override
	public int getColumnCount() {
		return column.length;
	}
	
	@Override
	@SuppressWarnings({ "unchecked", "rawtypes" })
    public Class getColumnClass(int column) {
        switch (column) {
            case 0:
                return String.class;
            case 1:
                return String.class;
            case 2:
                return Boolean.class;
            default:
                return String.class;
        }
    }
	
	public boolean isCellEditable(int row, int col)
	{
		 return (2 == col) ? true : false;
	}

	@Override  
    public Object getValueAt(int rowIndex, int columnIndex) 
	{
		return ((Vector<?>) data.get(rowIndex)).get(columnIndex);
    }

	@Override
    public String getColumnName(int columnIndex) 
	{    
        return column[columnIndex];
    }
	
	@SuppressWarnings("unchecked")
	public void addElement(Object[] values) 
	{
		data.add(new Vector<Object>());
		for(int idx = 0; idx < values.length; idx++){
			((Vector<Object>) data.get(data.size() - 1)).add(values[idx]);
		}
		fireTableDataChanged(); 
	}

	@Override
	@SuppressWarnings("unchecked")
	public void setValueAt(Object value, int rowIndex, int columnIndex)
	{
		 ((Vector<Object>) data.get(rowIndex)).setElementAt(value, columnIndex);
		 fireTableCellUpdated(rowIndex, columnIndex);
	}
	
	public boolean checkAlreadyExist (String name)
	{
		int qtdRow = this.getRowCount();
		if (qtdRow > 0) {
			for (int idx = 0; idx < qtdRow; idx++) {
				if (name.equalsIgnoreCase(this.getValueAt(idx, 0).toString())) {
					return true;
				}
			}			
		}
		return false;	
	}
	
	public void deleteData() {
        int rows = getRowCount();
        if (rows == 0) {
            return;
        }
        data.clear();
        fireTableRowsDeleted(0, rows - 1);
    }
}
