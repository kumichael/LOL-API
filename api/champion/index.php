<?php
	require_once('connection.php');
	
	//http://na.leagueoflegends.com/sites/default/files/game_data/1.0.0.140/content/champion/icons/<id>.jpg
	//http://na.leagueoflegends.com/sites/default/files/game_data/1.0.0.140/content/champion/portraits/<id>.jpg
	//http://riot-web-static.s3.amazonaws.com/images/news/Champ_Splashes/<name>_Splash.jpg
	
	//TEST FOR:
	//http://localhost/lolapi/api/champion/?type=champion&name=teemo&skills=true&format=xml
	
	if(isset($_GET['name'])){
		$name = mysql_real_escape_string($_GET['name']);
		$query = '
			SELECT * 
			FROM champions
			WHERE name
			LIKE "%'.$name.'%" 
		';

		$result = mysql_query($query) or die ('Error getting Champion Info.');
		$champ = mysql_fetch_array($result);
		$id = $champ[0];
				
		if(isset($_GET['skills'])){
			$get_skills = mysql_real_escape_string($_GET['skills']);

			if($get_skills == "true"){
				$get_skills = true;
			}

			else{
				$get_skills = false;
			}
		}

		else{
			$get_skills = false;
		}
				
		if($get_skills == true){
			$query = '
				SELECT * 
				FROM skills
				WHERE id = '.$id.'
				ORDER BY skills.order ASC
			';
			$result = mysql_query($query) or die ('Error getting Skill Info');
			
			$skill = array();				
			while($skills = mysql_fetch_array($result)){
				$order = $skills['order'];
				$name = $skills['name'];
				$description = $skills['description'];
				$cost = $skills['cost'];
				$cooldown = $skills['cooldown'];
				$range = $skills['range'];
				array_push($skill, $order, $name, $description, $cost, $cooldown, $range);
			}
				
			$return = Array(
				'champion' => Array(
					'id' => $champ[0],
					'name' => $champ[1],
					'title' => $champ[2],
					'attributes' => $champ[3],
					'attack' => $champ[4],
					'health' => $champ[5],
					'magic' => $champ[6],
					'difficulty' => $champ[7],
					'ip' => $champ[8],
					'rp' => $champ[9],
					'release' => $champ[10],
					'skill' => Array(
						'passive' => Array(
							'name' => $skill[1],
							'description' => $skill[2],
							'cooldown' => $skill[4]
						),
						'q' => Array(
							'name' => $skill[7],
							'description' => $skill[8],
							'cost' => $skill[9],
							'cooldown' => $skill[10],
							'range' => $skill[11]
						),
						'w' => Array(
							'name' => $skill[13],
							'description' => $skill[14],
							'cost' => $skill[15],
							'cooldown' => $skill[16],
							'range' => $skill[17]
						),
						'e' => Array(
							'name' => $skill[19],
							'description' => $skill[20],
							'cost' => $skill[21],
							'cooldown' => $skill[22],
							'range' => $skill[23]
						),
						'r' => Array(
							'name' => $skill[25],
							'description' => $skill[26],
							'cost' => $skill[27],
							'cooldown' => $skill[28],
							'range' => $skill[29]
						)
					)
				)
			);
		}
					
		else{
			$return = Array(
				'champion' => Array(
					'id' => $champ[0],
					'name' => $champ[1],
					'title' => $champ[2],
					'attributes' => $champ[3],
					'attack' => $champ[4],
					'health' => $champ[5],
					'magic' => $champ[6],
					'difficulty' => $champ[7],
					'ip' => $champ[8],
					'rp' => $champ[9],
					'release' => $champ[10]
				)
			);
		}

		if(!isset($_GET['format'])){
			$_GET['format'] = "json";
		}
		
		if(isset($_GET['format'])){
			$format = mysql_real_escape_string($_GET['format']);
			switch ($format) {			
				case 'xml' :
					@header ("content-type: text/xml charset=utf-8"); 

					function write(XMLWriter $xml, $data){
                        foreach($data as $key => $value){
							if(is_array($value)){
								$xml->startElement($key);
								write($xml, $value);
								$xml->endElement();
								continue;
							}
							
							$xml->writeElement($key, $value);
                        }
					}
					
					$xml = new XmlWriter();
					$xml->openMemory();
					$xml->startDocument('1.0', 'UTF-8');
					write($xml, $return);
					$xml->endElement();
					echo $xml->outputMemory(true);
					break;
					
				case 'json' :
					@header ("content-type: text/json charset=utf-8");
					echo json_encode($return);
					break;
			}
		}
	}
?>