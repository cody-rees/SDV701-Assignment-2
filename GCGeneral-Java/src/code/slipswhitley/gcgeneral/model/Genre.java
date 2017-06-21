package code.slipswhitley.gcgeneral.model;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import code.slipswhitley.gcgeneral.GCGeneralMain;
import net.minidev.json.JSONArray;
import net.minidev.json.JSONObject;
import net.minidev.json.parser.ParseException;

public class Genre {
	
	public static final String GAME_LIST_UPDATE_URL = GCGeneralMain.BASE_URL + "/json-encode/games-list?genre=";

	public final int genreID;
	public final String name;
	public final String desc;
	
	public Genre(JSONObject json) {
		this.genreID = Integer.valueOf(json.get("genre_id").toString());
		this.name = (String) json.get("genre");
		this.desc = (String) json.get("description");
	}
	
	public List<Game> getGamesList() throws IOException, ParseException {
		List<Game> list = new ArrayList<Game>();
		
		JSONArray gamesListJSON = GCGeneralMain.readURL(GAME_LIST_UPDATE_URL + genreID);

		for(int i = 0; i < gamesListJSON.size(); i++) {
			list.add(new Game((JSONObject) gamesListJSON.get(i)));
		}
		
		return list;
	}

	public String toString() {
		return genreID + ": " + name + " - " + desc;
	}
	
}