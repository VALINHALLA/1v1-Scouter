<?php 
include 'pokemontypes.php';

function twoindexes() {
    global $pkmn;
    // Fix: Arrays are 0-indexed, so subtract 1 from the total count
    $rand1 = rand(0, count($pkmn) - 1);
    $rand2 = rand(0, count($pkmn) - 1);
    return [$pkmn[$rand1], $pkmn[$rand2]];
}
$sprites = twoindexes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goliad Scouter</title>
    <style>
        body { text-align: center; font-family: sans-serif; padding: 15px; }
        .title-header { text-decoration: none; display: inline; }
        .sprite { display: inline; position: relative; top: 6px; }
        .links h1 { text-decoration: none; display: inline; margin: 0 5px; font-size: 24px; }
        .changelog { display: inline-block; text-align: left; border: 1px solid #ccc; padding: 15px; margin-top: 20px; max-width: 600px; }
        .help-text { text-decoration: none; font-size: 10px; }
        textarea { max-width: 100%; }
    </style>
</head>
<body>
    <h1 class="title-header">
        <img src="https://www.smogon.com/forums/media/minisprites/<?php echo $sprites[0]; ?>.png" class="sprite" alt="sprite">
        Goliad Scouter: Sheet Edition
        <img src="https://www.smogon.com/forums/media/minisprites/<?php echo $sprites[1]; ?>.png" class="sprite" alt="sprite">
    </h1>
    <br /><br />
    
    <u>Replays</u> (put a newline after each one):<br />
    <form action="scouter.php" method="post">
        <textarea rows="20" cols="60" id="statsbox" name="statsbox" wrap="soft"></textarea><br />
        <input type="checkbox" name="altusagebox" value="altusageboxvalue" id="altusagebox" />
        <label for="altusagebox">2 tab pokemon usage</label> 
        <a href="2_tab_pkmn_usage.png" target="_blank" class="help-text">(what is this?)</a><br />
        
        <u>Usernames (comma separated)</u>:<br />
        <input type="text" id="fname" name="fname"><br /><br />
        <input type="submit" value="Send Data" id="poop" name="poop">
    </form>
    <br />

    <div class="links">
        <h1><a href="https://docs.google.com/spreadsheets/d/1Cu6fz5rAankoKFMYSC2b7dQ5oarxKpYFXHHUqOqnYkA/edit#gid=0" target="_blank" style="color: mediumspringgreen;">[sheet template]</a></h1>
        <h1><a href="https://youtu.be/Qe-2LLyIJWA" target="_blank" style="color: red;">[tutorial]</a></h1> 
        <h1><a href="https://pastebin.com/raw/vv42UtMY" target="_blank" style="color: #FDDA0D;">[example input]</a></h1>
        <h1><a href="https://media.discordapp.net/attachments/323936068414078976/989324615249707049/unknown.png?width=1432&height=864" target="_blank" style="color: lightskyblue;">[example scout]</a></h1>
        <h1><a href="https://github.com/partys-over/replay-scouter" target="_blank" style="color: mediumpurple;">[source code]</a></h1>
    </div>

    <div class="changelog">
        <h3 style="display: inline; margin-bottom: 5px;">Updates</h3>
        <ul style="list-style-position: inside; padding-left: 0;">
            <li>Added 2 tab pokemon usage for simple top-to-bottom across 2 sheet tabs</li>
            <li>Scouter now suggests the best sheet template based on replay count</li>
            <li>Added SSRF protection, strict typing, and timeout protections</li>
            <li>Added error feedback for private/unreachable replays</li>
        </ul>
    </div>
</body>
</html>
