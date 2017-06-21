package code.slipswhitley.gcgeneral.model;

import net.minidev.json.JSONObject;

public class Game {

	public final int gameID;
	public final String title;
	public final String description;
	
	public final double price;
	public final String type;
	
	public final int quantity;
	public final String downloadURL;
	
	public Game(JSONObject json) {
		this.gameID = Integer.valueOf(json.get("game_id").toString());
		this.title = (String) json.get("title");
		this.description = (String) json.get("description");
		
		this.price = Double.valueOf(json.get("price").toString());
		this.type = (String) json.get("type");
		
		this.quantity = Integer.valueOf(json.get("quantity").toString());
		this.downloadURL = (String) json.get("download_url");
	}
	
	@Override
	public String toString() {
		return gameID + ": " + title + " - Price: $" + price + "      DownloadType: " + type + "      Quantity Available: " + quantity; 
	}
	

}
