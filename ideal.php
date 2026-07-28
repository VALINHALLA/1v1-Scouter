<?php
echo "Poop2";
include('pokemontypes.php');

//include('simple_html_dom.php');
//$html = file_get_html('https://replay.pokemonshowdown.com/gen41v1-1231369678.log');
// PHP 5.6.30
$samplereplay = 'https://replay.pokemonshowdown.com/gen71v1-1261031806' . '.log'; 
//$samplereplays = ['https://replay.pokemonshowdown.com/gen41v1-1163901063.log', 'https://replay.pokemonshowdown.com/gen41v1-1163899480.log', 'https://replay.pokemonshowdown.com/gen41v1-1163898005.log', 'https://replay.pokemonshowdown.com/gen41v1-1163896676.log', 'https://replay.pokemonshowdown.com/gen41v1-1166066495.log', 'https://replay.pokemonshowdown.com/gen41v1-1166065222.log', 'https://replay.pokemonshowdown.com/gen41v1-1166064380.log', 'https://replay.pokemonshowdown.com/gen41v1-1166062879.log', 'https://replay.pokemonshowdown.com/gen41v1-1165078709.log', 'https://replay.pokemonshowdown.com/gen41v1-1165077765.log', 'https://replay.pokemonshowdown.com/gen41v1-1165076326.log'];
$samplereplays = ['https://replay.pokemonshowdown.com/gen61v1-949443390.log', 'https://replay.pokemonshowdown.com/gen61v1-949441915.log', 'https://replay.pokemonshowdown.com/gen61v1-949439807.log', 'https://replay.pokemonshowdown.com/gen61v1-949438120.log', 'https://replay.pokemonshowdown.com/gen61v1-956378962.log', 'https://replay.pokemonshowdown.com/gen61v1-956381342.log', 'https://replay.pokemonshowdown.com/gen61v1-956382087.log', 'https://replay.pokemonshowdown.com/gen61v1-953917120.log', 'https://replay.pokemonshowdown.com/gen61v1-953919346.log', 'https://replay.pokemonshowdown.com/gen61v1-953920910.log', 'https://replay.pokemonshowdown.com/smogtours-gen61v1-453581.log', 'https://replay.pokemonshowdown.com/smogtours-gen61v1-453582.log', 'https://replay.pokemonshowdown.com/smogtours-gen61v1-453585.log'];

// ACTUAL SCOUTING PART STARTS
$nums = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
$box = htmlspecialchars($_POST['statsbox']);
$verifiedreplays = [];

if (strlen($box) > 2) {
	$submitreplays = explode('https://', $box);

	foreach ($submitreplays as $key => $value) {
		if (in_array(substr($value, -1), $nums)) { // weird bug where it kept echoing replay .log instead of replay.log
			$x = "https://" . $value . ".log";
			array_push($verifiedreplays, $x);
			echo $x . "<br />";
		}
		else {
			$x = "https://" . substr($value, 0, -2) . ".log";
			array_push($verifiedreplays, $x);
			echo $x . "<br />";
		}
	}
}
else {
	echo "u dont have any replays fool";
}

echo "poop";







$scoutedalts0 = ['SECTOR 7 dom', 'fadeonitdom', 'loopedupdom', 'G5 SCRAF dom'];
$scoutedalts = [];
foreach ($scoutedalts0 as $key => $value) {
	$name = preg_replace("/[^a-zA-Z0-9]+/", "", $value);
	$name = strtolower($name);
	array_push($scoutedalts, $name);
}
$allpokemon = [];
$allteams = [];
$nodupespokemon = [];
$firesused = []; $watersused = []; $grassesused = []; $bugsused = []; $darksused = []; $dragonsused = []; $electricsused = []; $fairiesused = []; $fightingsused = []; $flyingsused = []; $ghostsused = []; $groundsused = []; $icesused = []; $normalsused = []; $poisonsused = []; $psychicsused = []; $rocksused = []; $steelsused = [];

function cleanmon($string) {
	if (strpos(substr($string, 1, 20), ',') !== false) { // GENDERED
		$num = (strpos($string, ', ') - 3);
		return substr($string, 3, $num);
	}
	else { // GENDERLESS
		$string2 = substr($string, 3, -1);
		$num = (strpos($string2, '|'));
		//substr($string2, 0, $num);
		return substr($string2, 0, $num);
	}
}

function scoutreplay($replay) {
	global $scoutedalts, $allpokemon, $allteams, $firetypes, $firesused, $watertypes, $watersused, $grasstypes, $grassesused, $groundtypes, $groundsused, $steeltypes, $steelsused, $fairytypes, $fairiesused, $flyingtypes, $flyingsused, $psychictypes, $psychicsused, $darktypes, $darksused, $dragontypes, $dragonsused, $electrictypes, $electricsused, $fightingtypes, $fightingsused, $ghosttypes, $ghostsused, $icetypes, $icesused, $normaltypes, $normalsused, $poisontypes, $poisonsused, $rocktypes, $rocksused, $bugtypes, $bugsused;

	$htmlbeta = file_get_contents($replay);

	$html = htmlspecialchars($htmlbeta);
	$dar = explode("|poke|", $html);
	//print_r($dar);
	$frfr = explode("|player|", $dar[0]); // for player

	// player string manipulation, made it all lower case and no spaces or characters for simplicity. i tried making this a function and it just didn't work out, ik it would be cleaner, bitch!
	$playerone = substr($frfr[1], 3); // getting rid of some stupid text idc about
	$playerone = substr($playerone, 0, strpos($playerone, '|'));
	$playerone = $result = preg_replace("/[^a-zA-Z0-9]+/", "", $playerone); $playerone = strtolower($playerone);
	//echo ("<br />" . $playerone);//
	$playertwo = substr($frfr[2], 3); // getting rid of some stupid text idc about
	$playertwo = substr($playertwo, 0, strpos($playertwo, '|'));
	$playertwo = $result = preg_replace("/[^a-zA-Z0-9]+/", "", $playertwo); $playertwo = strtolower($playertwo);
	//echo ("<br />" . $playertwo);//
	//echo "<br />";//
	//comments that end in // are important, made the program what it is
	if (strpos($replay, '1v1')) { // 1v1, 3 pkmn each team
		$teamlist = [cleanmon($dar[1]), cleanmon($dar[2]), cleanmon($dar[3])];
		//print_r($teamlist);//
		$teamlist2 = [cleanmon($dar[4]), cleanmon($dar[5]), cleanmon($dar[6])];
		//print_r($teamlist2);//
	}
	else {
		$teamlist = [cleanmon($dar[1]), cleanmon($dar[2]), cleanmon($dar[3]), cleanmon($dar[4]), cleanmon($dar[5]), cleanmon($dar[6])];
		print_r($teamlist);
		$teamlist2 = [cleanmon($dar[7]), cleanmon($dar[8]), cleanmon($dar[9]), cleanmon($dar[10]), cleanmon($dar[11]), cleanmon($dar[12])];
		print_r($teamlist2);
	}
	if (in_array($playerone, $scoutedalts)) {
		//echo 'player1 in list';///
		array_push($allteams, $teamlist);
		foreach ($teamlist as $key => $value) {
			//echo $value;//
			array_push($allpokemon, $value);
			if (in_array($value, $firetypes)) {
			    array_push($firesused, $value);
			}
			if (in_array($value, $watertypes)) {
			    array_push($watersused, $value);
			}
			if (in_array($value, $grasstypes)) {
			    array_push($grassesused, $value);
			}
			if (in_array($value, $groundtypes)) {
			    array_push($groundsused, $value);
			}
			if (in_array($value, $steeltypes)) {
			    array_push($steelsused, $value);
			}
			if (in_array($value, $fairytypes)) {
			    array_push($fairiesused, $value);
			}
			if (in_array($value, $flyingtypes)) {
			    array_push($flyingsused, $value);
			}
			if (in_array($value, $psychictypes)) {
			    array_push($psychicsused, $value);
			}
			if (in_array($value, $darktypes)) {
			    array_push($darksused, $value);
			}
			if (in_array($value, $dragontypes)) {
			    array_push($dragonsused, $value);
			}
			if (in_array($value, $electrictypes)) {
			    array_push($electricsused, $value);
			}
			if (in_array($value, $fightingtypes)) {
			    array_push($fightingsused, $value);
			}
			if (in_array($value, $ghosttypes)) {
			    array_push($ghostsused, $value);
			}
			if (in_array($value, $icetypes)) {
			    array_push($icesused, $value);
			}
			if (in_array($value, $normaltypes)) {
			    array_push($normalsused, $value);
			}
			if (in_array($value, $poisontypes)) {
			    array_push($poisonsused, $value);
			}
			if (in_array($value, $rocktypes)) {
			    array_push($rocksused, $value);
			}
			if (in_array($value, $bugtypes)) {
			    array_push($bugsused, $value);
			}
		}
	}
	elseif (in_array($playertwo, $scoutedalts)) {
		//echo 'player2 in list';//
		array_push($allteams, $teamlist2);
		foreach ($teamlist2 as $key => $value) {
			//echo $value;//
			array_push($allpokemon, $value);
			if (in_array($value, $firetypes)) {
			    array_push($firesused, $value);
			}
			if (in_array($value, $watertypes)) {
			    array_push($watersused, $value);
			}
			if (in_array($value, $grasstypes)) {
			    array_push($grassesused, $value);
			}
			if (in_array($value, $groundtypes)) {
			    array_push($groundsused, $value);
			}
			if (in_array($value, $steeltypes)) {
			    array_push($steelsused, $value);
			}
			if (in_array($value, $fairytypes)) {
			    array_push($fairiesused, $value);
			}
			if (in_array($value, $flyingtypes)) {
			    array_push($flyingsused, $value);
			}
			if (in_array($value, $psychictypes)) {
			    array_push($psychicsused, $value);
			}
			if (in_array($value, $darktypes)) {
			    array_push($darksused, $value);
			}
			if (in_array($value, $dragontypes)) {
			    array_push($dragonsused, $value);
			}
			if (in_array($value, $electrictypes)) {
			    array_push($electricsused, $value);
			}
			if (in_array($value, $fightingtypes)) {
			    array_push($fightingsused, $value);
			}
			if (in_array($value, $ghosttypes)) {
			    array_push($ghostsused, $value);
			}
			if (in_array($value, $icetypes)) {
			    array_push($icesused, $value);
			}
			if (in_array($value, $normaltypes)) {
			    array_push($normalsused, $value);
			}
			if (in_array($value, $poisontypes)) {
			    array_push($poisonsused, $value);
			}
			if (in_array($value, $rocktypes)) {
			    array_push($rocksused, $value);
			}
			if (in_array($value, $bugtypes)) {
			    array_push($bugsused, $value);
			}
		}
	}
	else {
		echo '<br /> u forgot an alt name, budster';
		//echo "'" . $playerone . "' "; echo $playertwo;
	}
}
foreach ($verifiedreplays as $key => $value) {
	scoutreplay($value);
}


echo "Teams: <br />";
foreach ($allteams as $key => $value) {
	?><a style="text-decoration: none; color: black;" target="_blank" href="<?php echo substr($verifiedreplays[$key], 0, -4) ?>">
	<?php
	for ($i=0; $i < 3; $i++) { 
		if ($i == 2) {
			echo $value[$i] . "<br />"?></a><?php ;
		}
		else {
			echo $value[$i] . " / ";
		}
	}
}

$countingfreq = array_count_values($allpokemon);
arsort($countingfreq);
//print_r($countingfreq);
//echo $count;

foreach ($allpokemon as $key => $value) {
	//echo $value;
	if (!in_array($value, $nodupespokemon)) {
		//echo $value;
		array_push($nodupespokemon, $value);
	}
}
echo "<br />";
echo "Number of Teams: " . count($allteams) . "<br />";
echo "Number of Pokemon: " . count($allpokemon) . "<br />";
echo "Number of Unique Pokemon: " . count($nodupespokemon) . "<br />";

foreach ($countingfreq as $key => $value) { // the main thing that shows mon: number
	?><img src="https://www.smogon.com/forums//media/minisprites/<?php echo strtolower($key) ?>.png" /><?php echo $key . ': ' . $value . '<br />';
}

//var_dump($dar);
function typedump($typeused, $tstring) {
	global $allpokemon;
	echo "<br />";
	$typecounting = array_count_values($typeused);
	arsort($typecounting);
	$typesum = 0;
	$typecount = 1;
	$strings = [];
	foreach ($typecounting as $key => $value) { 
		if ($typecount !== count($typecounting)) {
			array_push($strings, ($key . ' (' . $value . "), "));
		}
		else {
			array_push($strings, ($key . ' (' . $value . ")"));
		}
		$typesum = $typesum + $value;
		$typecount = $typecount + 1;
	}
	$percentie = substr(strval(($typesum/count($allpokemon) * 100)), 0, 5);
	echo $tstring . " Types Used: " . $typesum . "/" . count($allpokemon) . " (" . $percentie . "%) - ";
	foreach ($strings as $key => $value) {
		?><img src="https://www.smogon.com/forums//media/minisprites/<?php echo substr(strtolower($value), 0, strpos($value, ' ')) ?>.png" /><?php echo $value;
	}



}
?>
<br />
<a href="index.php"><button>Back</button></a><br />
<?php

typedump($watersused, "Water");
typedump($firesused, "Fire");
typedump($grassesused, "Grass");

//print_r($watersused);

//$split = explode($html, "|");
//var_dump($split);

?>