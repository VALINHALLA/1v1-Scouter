<?php
include 'pokemontypes.php';

function twoindexes() {
    global $pkmn;
    $rand1 = rand(0, count($pkmn)-1);
    $rand2 = rand(0, count($pkmn)-1);
    return [$pkmn[$rand1], $pkmn[$rand2]];
}
$sprites = twoindexes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Big Monk Mentality's 1v1 Scouter</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
<style>
:root{
--bg:#09090b;--panel:rgba(24,24,27,.72);--border:#2b2b33;--text:#fafafa;
--muted:#9ca3af;--grad:linear-gradient(135deg,#60a5fa,#8b5cf6,#ec4899);
}
*{box-sizing:border-box}
body{
margin:0;
font-family:'Space Grotesk',system-ui,sans-serif;
background:
radial-gradient(circle at 20% 20%,rgba(96,165,250,.12),transparent 35%),
radial-gradient(circle at 80% 10%,rgba(139,92,246,.14),transparent 35%),
radial-gradient(circle at 50% 100%,rgba(236,72,153,.10),transparent 40%),
var(--bg);
color:var(--text);
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
padding:40px 20px;
}
.card{
width:min(900px,100%);
background:var(--panel);
backdrop-filter:blur(18px);
border:1px solid rgba(255,255,255,.08);
border-radius:12px;
padding:42px;
box-shadow:0 20px 70px rgba(0,0,0,.55);
}
.hero{text-align:center;margin-bottom:32px}
.hero h1{
font-size:3rem;margin:.4rem 0;
background:#2563eb;
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}
.hero p{color:var(--muted);margin:0}
.sprite{width:48px;height:48px;image-rendering:pixelated;vertical-align:middle;animation:float 4s ease-in-out infinite}
.sprite:last-child{animation-delay:2s}
@keyframes float{50%{transform:translateY(-8px)}}
label{display:block;margin:20px 0 8px;font-weight:600}
textarea,input[type=text]{
width:100%;
background:#111318;
border:1px solid var(--border);
border-radius:8px;
padding:16px;
color:white;
font:inherit;
transition:.25s;
}
textarea{min-height:320px;resize:vertical}
textarea:focus,input:focus{
outline:none;
border-color:#60a5fa;
box-shadow:0 0 0 4px rgba(96,165,250,.18);
}
.row{display:flex;align-items:center;gap:10px;margin:18px 0;color:#d4d4d8}
a{color:#7dd3fc;text-decoration:none}
button{
margin-top:28px;
width:100%;
padding:18px;
border:none;
border-radius:8px;
background:#2563eb;
color:white;
font-size:1rem;
font-weight:700;
cursor:pointer;
transition:.25s;
}
button:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(96,165,250,.25)}
.template{
display:inline-block;
margin-top:26px;
padding:12px 22px;
border-radius:8px;
border:1px solid rgba(255,255,255,.08);
background:#12141a;
}
.footer{text-align:center}
</style>
</head>
<body>
<div class="card">
<div class="hero" style="display:flex;align-items:center;justify-content:center;gap:18px;margin-bottom:32px;">
<img class="sprite" src="https://www.smogon.com/forums/media/minisprites/<?php echo $sprites[0]; ?>.png" alt="left sprite">
<h1 style="margin:0;font-size:2.6rem;">Big Monk Mentality's 1v1 Scouter</h1>
<img class="sprite" src="https://www.smogon.com/forums/media/minisprites/<?php echo $sprites[1]; ?>.png" alt="right sprite">
</div>

<form action="scouter.php" method="post">
<label>Replay URLs</label>
<textarea name="statsbox" placeholder="Paste one replay URL per line..."></textarea>

<div class="row">
<input type="checkbox" id="altusagebox" name="altusagebox" value="altusageboxvalue" style="width:auto">
<label for="altusagebox" style="margin:0">Enable 2-tab Pokémon usage</label>
<a href="2_tab_pkmn_usage.png" target="_blank">What is this?</a>
</div>

<label>Usernames (comma separated)</label>
<input type="text" name="fname" placeholder="Player1, Player2">

<button type="submit" name="poop">Analyze Replays</button>
</form>

<div class="footer">
<a class="template" target="_blank" href="https://docs.google.com/spreadsheets/d/1Cu6fz5rAankoKFMYSC2b7dQ5oarxKpYFXHHUqOqnYkA/edit#gid=0">Open Sheet Template</a>
</div>

</div>
</body>
</html>
