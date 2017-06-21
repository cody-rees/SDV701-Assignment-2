package code.slipswhitley.gcgeneral.ui;

import java.awt.Dialog;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.IOException;
import java.net.URLEncoder;

import javax.swing.JButton;
import javax.swing.JDialog;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;

import code.slipswhitley.gcgeneral.GCGeneralMain;
import code.slipswhitley.gcgeneral.model.Game;
import net.minidev.json.JSONArray;
import net.minidev.json.JSONObject;
import net.minidev.json.parser.ParseException;

public class CreateOrderUI extends JDialog implements ActionListener {

	private static final long serialVersionUID = 6309114303744782951L;

	private static final String CREATE_URL = GCGeneralMain.BASE_URL + "/json-encode/create-order?order-info=";
	
	private Game game;
	
	private JTextField fieldFirstName;
	private JTextField fieldLastName;
	private JTextField fieldEmail;
	private JTextField fieldQuantity;
	private JTextArea fieldAddress;
	
	public CreateOrderUI(Dialog dialog, Game game) {
		super(dialog);
		this.game = game;
		
		this.setTitle("Creating Order for Game: " + game.title);
		this.setSize(523, 475);
		this.setResizable(false);
		this.setDefaultCloseOperation(DISPOSE_ON_CLOSE);
		getContentPane().setLayout(null);
		
		fieldFirstName = new JTextField();
		fieldFirstName.setBounds(117, 33, 358, 28);
		getContentPane().add(fieldFirstName);
		fieldFirstName.setColumns(10);
		
		fieldLastName = new JTextField();
		fieldLastName.setColumns(10);
		fieldLastName.setBounds(117, 72, 358, 28);
		getContentPane().add(fieldLastName);
		
		JLabel lblFirstName = new JLabel("First Name");
		lblFirstName.setBounds(33, 40, 75, 14);
		getContentPane().add(lblFirstName);
		
		JLabel lblLastName = new JLabel("Last Name");
		lblLastName.setBounds(32, 79, 75, 14);
		getContentPane().add(lblLastName);
		
		fieldEmail = new JTextField();
		fieldEmail.setColumns(10);
		fieldEmail.setBounds(117, 130, 358, 28);
		getContentPane().add(fieldEmail);
		
		JLabel lblEmail = new JLabel("Email");
		lblEmail.setBounds(33, 137, 75, 14);
		getContentPane().add(lblEmail);
		
		JLabel lblQuantity = new JLabel("Quantity");
		lblQuantity.setBounds(36, 354, 75, 14);
		getContentPane().add(lblQuantity);
		
		fieldQuantity = new JTextField();
		fieldQuantity.setColumns(10);
		fieldQuantity.setBounds(115, 347, 126, 28);
		getContentPane().add(fieldQuantity);
		
		JLabel lblTotal = new JLabel("Price Per/Item: ");
		lblTotal.setBounds(34, 329, 90, 14);
		getContentPane().add(lblTotal);
		
		JLabel itemPrice = new JLabel("$" + game.price);
		itemPrice.setBounds(118, 329, 46, 14);
		getContentPane().add(itemPrice);
		
		JLabel lblAddress = new JLabel("Address");
		lblAddress.setBounds(34, 180, 75, 14);
		getContentPane().add(lblAddress);
		
		JScrollPane scrollPane = new JScrollPane();
		scrollPane.setBounds(117, 169, 358, 124);
		getContentPane().add(scrollPane);
		
		fieldAddress = new JTextArea();
		scrollPane.setViewportView(fieldAddress);
		
		JButton btnCreateOrder = new JButton("Create Order");
		btnCreateOrder.addActionListener(this);
		btnCreateOrder.setBounds(117, 394, 358, 23);
		getContentPane().add(btnCreateOrder);
		
		
		this.setVisible(true);
	}

	@Override
	public void actionPerformed(ActionEvent ev) {
		JSONObject object = new JSONObject();
		object.put("game_id", game.gameID);
		object.put("first_name", fieldFirstName.getText());
		object.put("last_name", fieldLastName.getText());
		object.put("email", fieldEmail.getText());
		object.put("quantity", fieldQuantity.getText());
		object.put("address", fieldAddress.getText());
		
		//Send JSON via GET over HTTP
		try {
			//Encode URL for Safety
			JSONArray response = GCGeneralMain.readURL(CREATE_URL 
					+ URLEncoder.encode(object.toJSONString(), "UTF-8"));
		
			JSONObject responseObj = (JSONObject) response.get(0);
			String message = (String) responseObj.get("message");
			
			//Display Message
			JOptionPane.showMessageDialog(this, message);
			
		} catch (IOException | ParseException e) {
			JOptionPane.showInternalMessageDialog(this, "Failed to create order: " + e.getMessage());
			e.printStackTrace();
			return;
		}
	}
}
