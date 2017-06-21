package code.slipswhitley.gcgeneral.ui;

import java.awt.Color;
import java.awt.GridBagConstraints;
import java.awt.GridBagLayout;
import java.awt.Insets;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.IOException;

import javax.swing.DefaultListModel;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JList;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.border.LineBorder;

import code.slipswhitley.gcgeneral.GCGeneralMain;
import code.slipswhitley.gcgeneral.model.Genre;
import net.minidev.json.JSONArray;
import net.minidev.json.JSONObject;
import net.minidev.json.parser.ParseException;

public class SelectGenreMainUI extends JFrame implements ActionListener {

	/**
	 * 
	 */
	private static final long serialVersionUID = 8488097402939898688L;
	
	private static final String UPDATE_URL = GCGeneralMain.BASE_URL + "/json-encode/genres-list";
	private JList<Genre> list;

	public SelectGenreMainUI() {
		this.setTitle("Game Central General");
		this.setSize(850, 500);
		this.setResizable(false);
		this.setDefaultCloseOperation(EXIT_ON_CLOSE);
		
		GridBagLayout gridBagLayout = new GridBagLayout();
		gridBagLayout.columnWidths = new int[]{250, 0, 0};
		gridBagLayout.rowHeights = new int[]{0, 0};
		gridBagLayout.columnWeights = new double[]{0.0, 1.0, Double.MIN_VALUE};
		gridBagLayout.rowWeights = new double[]{1.0, Double.MIN_VALUE};
		getContentPane().setLayout(gridBagLayout);
		
		JPanel panelTools = new JPanel();
		panelTools.setLayout(null);
		GridBagConstraints gbc_panelTools = new GridBagConstraints();
		gbc_panelTools.insets = new Insets(0, 0, 0, 5);
		gbc_panelTools.fill = GridBagConstraints.BOTH;
		gbc_panelTools.gridx = 0;
		gbc_panelTools.gridy = 0;
		getContentPane().add(panelTools, gbc_panelTools);
		
		JButton btnNewButton = new JButton("View Products for Genre");
		btnNewButton.addActionListener(this);
		btnNewButton.setBounds(10, 11, 225, 28);
		panelTools.add(btnNewButton);
		
		JButton btnRefreshList = new JButton("Refresh List");
		
		//Update List on Click
		btnRefreshList.addActionListener(new ActionListener() {
			
			public void actionPerformed(ActionEvent e) {
				updateList();
			}
			
		});
		
		btnRefreshList.setBounds(10, 50, 225, 28);
		panelTools.add(btnRefreshList);
		
		list = new JList<Genre>();
		list.setModel(new DefaultListModel<Genre>());
		list.setBorder(new LineBorder(Color.LIGHT_GRAY));
		
		GridBagConstraints gbc_list = new GridBagConstraints();
		gbc_list.fill = GridBagConstraints.BOTH;
		gbc_list.gridx = 1;
		gbc_list.gridy = 0;
		getContentPane().add(list, gbc_list);

		this.updateList();
		this.setVisible(true);
	}

	//Update Genres List
	public void updateList() {
		JSONArray genreList;
		try {
			genreList = GCGeneralMain.readURL(UPDATE_URL);
		
		} catch (IOException | ParseException e) {
			JOptionPane.showInternalMessageDialog(this, "Failed to retrieve list update: " + e.getMessage());
			e.printStackTrace();
			return;
		}
		
		DefaultListModel<Genre> model = (DefaultListModel<Genre>) list.getModel();
		model.removeAllElements();
		
		for(int i = 0; i < genreList.size(); i++) {
			model.addElement(new Genre((JSONObject) genreList.get(i)));
		}
	}
	
	//Genre Selected
	@Override
	public void actionPerformed(ActionEvent e) {
		Genre genre = list.getSelectedValue();
		if(genre == null) {
			return;
		}
		
		new SelectGameUI(this, genre);
	}
	
}
