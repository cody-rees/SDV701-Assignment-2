package code.slipswhitley.gcgeneral;

import java.io.IOException;
import java.io.InputStream;
import java.net.URL;
import java.net.URLConnection;

import javax.swing.UIManager;

import code.slipswhitley.gcgeneral.ui.SelectGenreMainUI;
import net.minidev.json.JSONArray;
import net.minidev.json.parser.JSONParser;
import net.minidev.json.parser.ParseException;

public class GCGeneralMain {

	//Base URL for Server
	public static final String BASE_URL = "http://localhost/GCGeneral/public";
	
	//Main UI
	private static SelectGenreMainUI MAIN_UI;
	
	public static void main(String[] args) {
		try {
			UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
		
		} catch (Exception e) {
			e.printStackTrace();
			
		}
		
		MAIN_UI = new SelectGenreMainUI();	
	}
	
	public static JSONArray readURL(String url) throws IOException, ParseException {
		
		//Open Connection / InputStream
		URLConnection connection = new URL(url).openConnection();
		InputStream in = connection.getInputStream();
		
		//Map Object Result and return
		JSONArray jsonArray = (JSONArray) new JSONParser(JSONParser.MODE_JSON_SIMPLE).parse(in);
		in.close();
		
		return jsonArray;
	}
	
	
	public static SelectGenreMainUI getMainUI() {
		return MAIN_UI;
	}
}
