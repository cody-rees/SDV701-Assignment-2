package code.slipswhitley.gcgeneral.ui;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.Frame;
import java.awt.GridBagConstraints;
import java.awt.GridBagLayout;
import java.awt.Insets;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.IOException;

import javax.swing.DefaultListModel;
import javax.swing.JButton;
import javax.swing.JDialog;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.border.LineBorder;

import code.slipswhitley.gcgeneral.model.Game;
import code.slipswhitley.gcgeneral.model.Genre;
import net.minidev.json.parser.ParseException;

public class SelectGameUI extends JDialog implements ActionListener {

	/**
	 * 
	 */
	private static final long serialVersionUID = 8488097402939898688L;
	
	private JList<Game> list;
	private Genre genre;
	
	public SelectGameUI(Frame frame, Genre genre) {
		super(frame);
		
		this.genre = genre;
		
		this.setTitle("Viewing Games for Genre: " + genre.name);
		this.setSize(850, 500);
		this.setResizable(false);
		this.setDefaultCloseOperation(DISPOSE_ON_CLOSE);
		
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
		
		JButton btnNewButton = new JButton("Create Order");
		btnNewButton.addActionListener(this);
		btnNewButton.setBounds(10, 49, 225, 28);
		panelTools.add(btnNewButton);
		
		JButton btnRefreshList = new JButton("Refresh List");
		
		//Update List on Click
		btnRefreshList.addActionListener(new ActionListener() {
			
			public void actionPerformed(ActionEvent e) {
				updateList();
			}
			
		});
		
		btnRefreshList.setBounds(10, 127, 225, 28);
		panelTools.add(btnRefreshList);
		
		JLabel lblViewingGamesFor = new JLabel("Viewing Games for Genre: " + genre.name);
		lblViewingGamesFor.setBounds(10, 11, 225, 27);
		panelTools.add(lblViewingGamesFor);
		
		JButton btnViewGameDescription = new JButton("View Game Description");
		btnViewGameDescription.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				viewGameDescription();
			}
			
		});
		btnViewGameDescription.setBounds(10, 88, 225, 28);
		panelTools.add(btnViewGameDescription);
		
		list = new JList<Game>();
		list.setModel(new DefaultListModel<Game>());
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
		
		DefaultListModel<Game> model = (DefaultListModel<Game>) list.getModel();
		model.removeAllElements();
		
		try {
			for(Game game : genre.getGamesList()) {
				model.addElement(game);
			}
			
		} catch (IOException | ParseException e) {
			JOptionPane.showInternalMessageDialog(this, "Failed to retrieve list update: " + e.getMessage());
			e.printStackTrace();
			return;
		}
	}
	
	public void viewGameDescription() {
		//Return if no game selected
		Game game = list.getSelectedValue();
		if(game == null) {
			return;
		}
		
		//Create TextArea for game description to be displayed
		JTextArea msg = new JTextArea("Game Description: " + game.description);
		msg.setLineWrap(true);
		msg.setWrapStyleWord(true);

		JScrollPane scrollPane = new JScrollPane(msg);
		scrollPane.setPreferredSize(new Dimension(400, 200));
		
		//Display Game Description
		JOptionPane.showMessageDialog(this, scrollPane, "Game Description: " + game.title, JOptionPane.OK_OPTION);
	}
	
	//Game Selected, Create Order Form
	@Override
	public void actionPerformed(ActionEvent e) {
		//Return if no game selected
		Game game = list.getSelectedValue();
		if(game == null) {
			return;
		}
		
		new CreateOrderUI(this, game);
	}
}
