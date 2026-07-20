<?php
session_start();

// ── Questions ────────────────────────────────────────────────
$questions = [
    ["id"=>1,"emoji"=>"💋","cat"=>"ROMANCE","q"=>"What's your partner's go-to love language?",
     "opts"=>["Words of Affirmation","Physical Touch","Quality Time","Acts of Service","Gift Giving"]],
    ["id"=>2,"emoji"=>"🧠","cat"=>"MEMORY","q"=>"What was the first movie you watched together?",
     "opts"=>["An action film","A romantic comedy","A horror movie","A drama","We didn't watch movies"]],
    ["id"=>3,"emoji"=>"🌹","cat"=>"ROMANCE","q"=>"Which date night would your partner pick?",
     "opts"=>["Candlelit dinner at home","Star-gazing on a hilltop","Dance class together","Road trip surprise","Netflix & takeout"]],
    ["id"=>4,"emoji"=>"😂","cat"=>"FUN","q"=>"What does your partner do when they're embarrassed?",
     "opts"=>["Laughs it off","Goes quiet","Changes the subject","Gets red-faced","Blames someone else 😂"]],
    ["id"=>5,"emoji"=>"🔥","cat"=>"SPICY","q"=>"Which word best describes your partner in the morning?",
     "opts"=>["A grumpy bear 🐻","Sunshine personified ☀️","Robot mode 🤖","Chaotic energy ⚡","Still half asleep 😴"]],
    ["id"=>6,"emoji"=>"💌","cat"=>"DEEP","q"=>"What's something your partner is secretly proud of?",
     "opts"=>["Their cooking","Their sense of humor","Their loyalty","Their intelligence","Their style"]],
    ["id"=>7,"emoji"=>"🎯","cat"=>"MEMORY","q"=>"Where was your first kiss?",
     "opts"=>["At their place","At my place","On a date out","At a party","In a car"]],
    ["id"=>8,"emoji"=>"💬","cat"=>"DEEP","q"=>"What topic can your partner talk about for hours?",
     "opts"=>["Their passion/hobby","Travel dreams","Childhood memories","Future plans","Food & restaurants"]],
    ["id"=>9,"emoji"=>"😍","cat"=>"ROMANCE","q"=>"What do you notice first about your partner every morning?",
     "opts"=>["Their eyes","Their smile","Their messy hair","Their voice","Their hands"]],
    ["id"=>10,"emoji"=>"🏆","cat"=>"ULTIMATE","q"=>"If your relationship were a movie genre, what would it be?",
     "opts"=>["Epic Romance 💕","Comedy Adventure 😂","Slow Burn Drama 🎭","Action-Packed Thriller ⚡","Heartwarming Indie Film 🎬"]],
];

$cat_colors = [
    "ROMANCE"=>"#ff6b9d","MEMORY"=>"#a78bfa","FUN"=>"#fbbf24",
    "SPICY"=>"#f97316","DEEP"=>"#34d399","ULTIMATE"=>"#f43f5e",
];

// ── Session init ──────────────────────────────────────────────
if (!isset($_SESSION['phase'])) {
    $_SESSION['phase']   = 'intro';
    $_SESSION['qIndex']  = 0;
    $_SESSION['score']   = 0;
    $_SESSION['streak']  = 0;
    $_SESSION['p1name']  = '';
    $_SESSION['p2name']  = '';
    $_SESSION['p1ans']   = null;
    $_SESSION['p2ans']   = null;
    $_SESSION['turn']    = 1;
    $_SESSION['matched'] = null;
    $_SESSION['history'] = [];
}

// ── POST handling ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'start') {
        $_SESSION['p1name'] = htmlspecialchars(trim($_POST['p1name']));
        $_SESSION['p2name'] = htmlspecialchars(trim($_POST['p2name']));
        if ($_SESSION['p1name'] && $_SESSION['p2name']) {
            $_SESSION['phase'] = 'exam';
            $_SESSION['turn']  = 1;
        }
    }

    if ($action === 'answer') {
        $opt = htmlspecialchars($_POST['option'] ?? '');
        if ($_SESSION['turn'] === 1) {
            $_SESSION['p1ans'] = $opt;
            $_SESSION['turn']  = 2;
        } else {
            $_SESSION['p2ans'] = $opt;
            // reveal
            $matched = ($_SESSION['p1ans'] === $_SESSION['p2ans']);
            $_SESSION['matched'] = $matched;
            if ($matched) {
                $_SESSION['score']++;
                $_SESSION['streak']++;
            } else {
                $_SESSION['streak'] = 0;
            }
            $_SESSION['phase'] = 'reveal';
        }
    }

    if ($action === 'next') {
        $_SESSION['qIndex']++;
        if ($_SESSION['qIndex'] >= count($questions)) {
            $_SESSION['phase'] = 'result';
        } else {
            $_SESSION['phase'] = 'exam';
            $_SESSION['turn']  = 1;
            $_SESSION['p1ans'] = null;
            $_SESSION['p2ans'] = null;
            $_SESSION['matched'] = null;
        }
    }

    if ($action === 'restart') {
        session_destroy();
        session_start();
        $_SESSION = [
            'phase'=>'intro','qIndex'=>0,'score'=>0,'streak'=>0,
            'p1name'=>'','p2name'=>'','p1ans'=>null,'p2ans'=>null,
            'turn'=>1,'matched'=>null,'history'=>[]
        ];
    }

    // PRG redirect to prevent resubmit
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// ── Read session vars ─────────────────────────────────────────
$phase   = $_SESSION['phase'];
$qIndex  = $_SESSION['qIndex'];
$score   = $_SESSION['score'];
$streak  = $_SESSION['streak'];
$p1name  = $_SESSION['p1name'];
$p2name  = $_SESSION['p2name'];
$p1ans   = $_SESSION['p1ans'];
$p2ans   = $_SESSION['p2ans'];
$turn    = $_SESSION['turn'];
$matched = $_SESSION['matched'];
$q       = $questions[$qIndex] ?? $questions[0];
$catColor = $cat_colors[$q['cat']] ?? '#f43f5e';
$total   = count($questions);
$pct     = round(($score / $total) * 100);

// Result tier
$result = ['title'=>'Just Getting Started 🌱','msg'=>"You two are still writing your story! Every great love takes time.",'color'=>'#a78bfa','icon'=>'🌱'];
if ($score >= 4) $result = ['title'=>'Love in Bloom 🌸','msg'=>"You know each other well — but beautiful mystery still remains!",'color'=>'#f9a8d4','icon'=>'🌸'];
if ($score >= 7) $result = ['title'=>'Deeply Connected 💞','msg'=>"Wow — you really see each other. Your bond runs deep!",'color'=>'#f97316','icon'=>'💞'];
if ($score >= 9) $result = ['title'=>'Soulmates! 💍✨','msg'=>"PERFECT SCORE! You two are literally written in the stars. Absolute goals.",'color'=>'#f43f5e','icon'=>'💍'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>💘 The Love Exam</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
    font-family: 'Segoe UI', system-ui, sans-serif;
    overflow-x: hidden;
    color: #fff;
  }

  /* Floating hearts */
  .hearts-bg { position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; }
  .heart {
    position: absolute; bottom: -60px; font-size: 20px; opacity: 0.3;
    animation: floatUp linear infinite;
  }
  @keyframes floatUp {
    0%   { transform: translateY(0) rotate(0deg); opacity: 0.3; }
    50%  { opacity: 0.6; }
    100% { transform: translateY(-110vh) rotate(360deg); opacity: 0; }
  }

  /* Layout */
  .wrap { position: relative; z-index: 1; max-width: 580px; margin: 0 auto; padding: 24px 16px 60px; }

  /* Cards */
  .card {
    background: rgba(255,255,255,0.07);
    border-radius: 28px;
    border: 1px solid rgba(255,255,255,0.12);
    padding: 28px 24px;
    backdrop-filter: blur(12px);
  }

  /* Buttons */
  .btn-primary {
    display: block; width: 100%; padding: 18px;
    background: linear-gradient(135deg, #f43f5e, #ec4899);
    border: none; border-radius: 18px;
    color: #fff; font-size: 17px; font-weight: 800;
    cursor: pointer; text-align: center;
    box-shadow: 0 0 30px rgba(244,63,94,.5);
    transition: transform .2s, box-shadow .2s;
    text-decoration: none;
  }
  .btn-primary:hover { transform: scale(1.03); box-shadow: 0 0 45px rgba(244,63,94,.75); }

  .btn-opt {
    display: block; width: 100%; padding: 15px 20px;
    background: rgba(255,255,255,0.08);
    border: 2px solid rgba(255,255,255,0.12);
    border-radius: 16px; color: #fff;
    font-size: 15px; font-weight: 600;
    cursor: pointer; text-align: left;
    transition: background .2s, border-color .2s, transform .15s;
    margin-bottom: 10px;
  }
  .btn-opt:hover { background: rgba(255,255,255,0.17); border-color: rgba(255,255,255,.35); transform: translateX(6px); }

  /* Input */
  .inp {
    width: 100%; padding: 14px 18px;
    background: rgba(255,255,255,0.1);
    border: 2px solid rgba(255,255,255,0.2);
    border-radius: 14px; color: #fff; font-size: 16px;
    outline: none; transition: border-color .2s;
  }
  .inp:focus { border-color: rgba(244,63,94,.7); }
  .inp::placeholder { color: rgba(255,255,255,.35); }

  label { display: block; font-size: 12px; font-weight: 800; letter-spacing: 2px; margin-bottom: 8px; }

  /* Progress bar */
  .prog-track { height: 7px; background: rgba(255,255,255,.1); border-radius: 99px; margin-bottom: 22px; }
  .prog-fill  { height: 100%; border-radius: 99px; background: linear-gradient(90deg,#f43f5e,#ec4899); transition: width .5s ease; }

  /* Timer bar */
  .timer-track { height: 8px; background: rgba(255,255,255,.1); border-radius: 99px; }

  /* Cat badge */
  .badge {
    display: inline-block; font-size: 11px; font-weight: 800;
    letter-spacing: 2px; padding: 4px 14px; border-radius: 20px;
  }

  /* Reveal panel */
  .reveal-match  { background: rgba(52,211,153,.12); border: 2px solid rgba(52,211,153,.4); border-radius: 24px; padding: 24px; text-align:center; margin-bottom:16px; }
  .reveal-miss   { background: rgba(244,63,94,.12);  border: 2px solid rgba(244,63,94,.4);  border-radius: 24px; padding: 24px; text-align:center; margin-bottom:16px; }
  .ans-card { flex:1; background: rgba(255,255,255,.07); border-radius:18px; padding:16px 14px; text-align:center; }

  /* Streak banner */
  .streak-banner {
    background: rgba(251,191,36,.15); border: 2px solid rgba(251,191,36,.4);
    border-radius: 18px; padding: 12px 20px; text-align:center;
    color: #fbbf24; font-weight:800; font-size:15px; margin-bottom:16px;
    animation: popIn .4s ease;
  }

  /* Result */
  .result-card { border-radius: 32px; padding: 36px 28px; text-align:center; margin-bottom:24px; }
  .stat-box { background: rgba(255,255,255,.07); border-radius:20px; padding:20px 24px; border:1px solid rgba(255,255,255,.1); text-align:center; }

  /* Animations */
  @keyframes popIn {
    0%   { transform: scale(0.5); opacity:0; }
    70%  { transform: scale(1.12); }
    100% { transform: scale(1); opacity:1; }
  }
  @keyframes slideUp {
    from { transform: translateY(28px); opacity:0; }
    to   { transform: translateY(0);    opacity:1; }
  }
  @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.07)} }
  @keyframes glow  { 0%,100%{opacity:.6} 50%{opacity:1} }
  @keyframes spin  { from{transform:rotate(0)} to{transform:rotate(360deg)} }

  .anim-slide { animation: slideUp .5s ease; }
  .anim-pop   { animation: popIn .4s ease; }
  .anim-pulse { animation: pulse 2s ease infinite; }

  /* Timer countdown display */
  #timer-num { transition: color .5s; }

  /* Flash overlay */
  .flash-match { animation: flashGreen 1s ease forwards; }
  .flash-miss  { animation: flashRed  1s ease forwards; }
  @keyframes flashGreen { 0%{background:transparent} 30%{background:rgba(52,211,153,.18)} 100%{background:transparent} }
  @keyframes flashRed   { 0%{background:transparent} 30%{background:rgba(244,63,94,.18)}  100%{background:transparent} }

  .flex-row { display:flex; gap:12px; }
  .mb16 { margin-bottom:16px; }
  .mb24 { margin-bottom:24px; }
  .mb32 { margin-bottom:32px; }
  .text-sm  { font-size:13px; color:rgba(255,255,255,.5); }
  .text-center { text-align:center; }
</style>
</head>
<body>

<!-- Floating hearts -->
<div class="hearts-bg" id="hearts"></div>
<!-- Flash overlay -->
<div id="flash" style="position:fixed;inset:0;pointer-events:none;z-index:99;"></div>

<div class="wrap">

<?php if ($phase === 'intro'): ?>
<!-- ════════════════════ INTRO ════════════════════ -->
<div class="anim-slide text-center">
  <div style="font-size:80px;margin-bottom:12px;" class="anim-pulse">💘</div>
  <h1 style="font-size:34px;font-weight:900;letter-spacing:-1px;">The Love Exam</h1>
  <p style="color:rgba(255,255,255,.6);margin:8px 0 36px;font-size:16px;line-height:1.6;">
    10 questions. Two answers. One truth.<br>
    How well do you <em>really</em> know each other?
  </p>

  <div class="card mb24">
    <p style="color:rgba(255,255,255,.8);margin-bottom:24px;font-size:15px;">Enter your names to begin 💕</p>
    <form method="POST">
      <input type="hidden" name="action" value="start">
      <div class="mb16">
        <label style="color:#f9a8d4;">PLAYER 1</label>
        <input class="inp" name="p1name" placeholder="Your name…" maxlength="20" required autocomplete="off">
      </div>
      <div class="mb24">
        <label style="color:#a78bfa;">PLAYER 2</label>
        <input class="inp" name="p2name" placeholder="Their name…" maxlength="20" required autocomplete="off">
      </div>
      <button type="submit" class="btn-primary">Start the Exam 🔥</button>
    </form>
  </div>

  <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
    <?php foreach(["💡 Pass & play","⏱️ 15s each","💎 Match = 1 pt"] as $tip): ?>
    <span style="background:rgba(255,255,255,.07);padding:6px 14px;border-radius:20px;font-size:13px;color:rgba(255,255,255,.55);"><?= $tip ?></span>
    <?php endforeach; ?>
  </div>
</div>

<?php elseif ($phase === 'exam'): ?>
<!-- ════════════════════ EXAM ════════════════════ -->
<div class="anim-slide">

  <!-- Header row -->
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
    <div>
      <div class="text-sm">Question</div>
      <div style="font-size:22px;font-weight:900;"><?= $qIndex+1 ?> <span style="color:rgba(255,255,255,.4);font-size:16px;">/ <?= $total ?></span></div>
    </div>
    <div class="text-center">
      <div style="font-size:28px;">❤️</div>
      <div style="color:#f43f5e;font-size:20px;font-weight:900;"><?= $score ?> pts</div>
    </div>
    <div style="text-align:right;">
      <div class="text-sm">Whose turn</div>
      <div style="font-weight:800;font-size:15px;color:<?= $turn===1?'#f9a8d4':'#a78bfa' ?>;">
        <?= $turn===1 ? htmlspecialchars($p1name) : htmlspecialchars($p2name) ?>
      </div>
    </div>
  </div>

  <!-- Progress bar -->
  <div class="prog-track">
    <div class="prog-fill" style="width:<?= round(($qIndex/$total)*100) ?>%;"></div>
  </div>

  <!-- Timer -->
  <div class="mb16">
    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
      <span class="text-sm">Time remaining</span>
      <span id="timer-num" style="font-weight:900;font-size:16px;color:#34d399;">15s</span>
    </div>
    <div class="timer-track">
      <div id="timer-fill" style="height:100%;border-radius:99px;background:linear-gradient(90deg,#34d399,#34d39988);width:100%;transition:width 1s linear,background .5s;"></div>
    </div>
  </div>

  <!-- Question card -->
  <div class="card mb16" style="border-color:<?= $catColor ?>44;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
      <span style="font-size:28px;"><?= $q['emoji'] ?></span>
      <span class="badge" style="background:<?= $catColor ?>22;color:<?= $catColor ?>;border:1px solid <?= $catColor ?>44;"><?= $q['cat'] ?></span>
    </div>
    <p style="font-size:19px;font-weight:700;line-height:1.45;"><?= htmlspecialchars($q['q']) ?></p>
  </div>

  <!-- Whose-turn banner -->
  <div style="text-align:center;margin-bottom:16px;padding:10px 20px;border-radius:14px;
       background:<?= $turn===1?'rgba(249,168,212,.1)':'rgba(167,139,250,.1)' ?>;
       border:1px solid <?= $turn===1?'rgba(249,168,212,.3)':'rgba(167,139,250,.3)' ?>;">
    <span style="font-weight:700;font-size:15px;color:<?= $turn===1?'#f9a8d4':'#a78bfa' ?>;">
      <?= $turn===1
          ? '🌸 ' . htmlspecialchars($p1name) . ', pick your answer!'
          : '💜 ' . htmlspecialchars($p2name) . ', your turn — no peeking!' ?>
    </span>
  </div>

  <!-- Options form -->
  <form method="POST">
    <input type="hidden" name="action" value="answer">
    <?php foreach($q['opts'] as $i => $opt): ?>
    <button type="submit" name="option" value="<?= htmlspecialchars($opt) ?>" class="btn-opt">
      <span style="opacity:.5;margin-right:10px;"><?= chr(65+$i) ?>.</span><?= htmlspecialchars($opt) ?>
    </button>
    <?php endforeach; ?>
  </form>
</div>

<!-- Timer JS -->
<script>
(function(){
  var t = 15;
  var fill = document.getElementById('timer-fill');
  var num  = document.getElementById('timer-num');
  var iv   = setInterval(function(){
    t--;
    var pct = (t / 15) * 100;
    fill.style.width = pct + '%';
    num.textContent  = t + 's';
    if (t <= 5) {
      fill.style.background = 'linear-gradient(90deg,#f43f5e,#f43f5e88)';
      num.style.color = '#f43f5e';
      num.style.animation = 'pulse .5s ease infinite';
    } else if (t <= 9) {
      fill.style.background = 'linear-gradient(90deg,#fbbf24,#fbbf2488)';
      num.style.color = '#fbbf24';
    }
    if (t <= 0) {
      clearInterval(iv);
      // Auto-submit with a "timed out" value
      var f = document.createElement('form');
      f.method = 'POST';
      f.innerHTML = '<input name="action" value="answer"><input name="option" value="⏰ Timed out!">';
      document.body.appendChild(f);
      f.submit();
    }
  }, 1000);
})();
</script>

<?php elseif ($phase === 'reveal'): ?>
<!-- ════════════════════ REVEAL ════════════════════ -->
<?php
  $flashClass = $matched ? 'flash-match' : 'flash-miss';
  $revealClass = $matched ? 'reveal-match' : 'reveal-miss';
?>
<script>
  document.getElementById('flash').className = '<?= $flashClass ?>';
</script>
<div class="anim-pop">

  <!-- Match / miss panel -->
  <div class="<?= $revealClass ?>">
    <div style="font-size:52px;margin-bottom:8px;"><?= $matched ? '🎉' : '💔' ?></div>
    <div style="font-size:22px;font-weight:900;color:<?= $matched?'#34d399':'#f43f5e' ?>;">
      <?= $matched ? 'Perfect Match!' : 'Not Quite!' ?>
    </div>
    <div style="color:rgba(255,255,255,.6);font-size:14px;margin-top:6px;">
      <?= $matched ? 'You think alike! +1 point 💕' : 'You see things differently — that\'s okay!' ?>
    </div>
  </div>

  <!-- Both answers -->
  <div class="flex-row mb16">
    <div class="ans-card" style="border:2px solid rgba(249,168,212,.35);">
      <div style="color:#f9a8d4;font-size:11px;font-weight:800;letter-spacing:1px;margin-bottom:6px;"><?= htmlspecialchars($p1name) ?></div>
      <div style="font-size:14px;font-weight:600;"><?= htmlspecialchars($p1ans) ?></div>
    </div>
    <div class="ans-card" style="border:2px solid rgba(167,139,250,.35);">
      <div style="color:#a78bfa;font-size:11px;font-weight:800;letter-spacing:1px;margin-bottom:6px;"><?= htmlspecialchars($p2name) ?></div>
      <div style="font-size:14px;font-weight:600;"><?= htmlspecialchars($p2ans) ?></div>
    </div>
  </div>

  <!-- Streak -->
  <?php if($streak >= 2): ?>
  <div class="streak-banner">🔥 <?= $streak ?> in a row! You're on fire!</div>
  <?php endif; ?>

  <!-- Next button -->
  <form method="POST">
    <input type="hidden" name="action" value="next">
    <button type="submit" class="btn-primary">
      <?= ($qIndex+1 >= $total) ? 'See Your Results! 🏆' : 'Next Question →' ?>
    </button>
  </form>
</div>

<?php elseif ($phase === 'result'): ?>
<!-- ════════════════════ RESULT ════════════════════ -->
<div class="anim-slide text-center">
  <div style="font-size:80px;margin-bottom:12px;" class="anim-pulse">🏆</div>
  <h2 style="font-size:28px;font-weight:900;">Exam Complete!</h2>
  <p style="color:rgba(255,255,255,.5);margin:4px 0 28px;"><?= htmlspecialchars($p1name) ?> &amp; <?= htmlspecialchars($p2name) ?></p>

  <!-- Result tier -->
  <div class="result-card mb24"
       style="background:<?= $result['color'] ?>22;border:2px solid <?= $result['color'] ?>55;box-shadow:0 0 60px <?= $result['color'] ?>33;">
    <div style="font-size:52px;margin-bottom:12px;"><?= $result['icon'] ?></div>
    <div class="badge mb16" style="background:<?= $result['color'] ?>33;color:<?= $result['color'] ?>;border:1px solid <?= $result['color'] ?>44;">RESULT</div>
    <div style="font-size:24px;font-weight:900;margin-bottom:10px;"><?= $result['title'] ?></div>
    <p style="color:rgba(255,255,255,.7);font-size:15px;line-height:1.65;"><?= $result['msg'] ?></p>
  </div>

  <!-- Stats -->
  <div class="flex-row mb24">
    <div class="stat-box" style="flex:1;">
      <div style="font-size:40px;font-weight:900;color:#f43f5e;"><?= $score ?></div>
      <div class="text-sm">Matches</div>
    </div>
    <div class="stat-box" style="flex:1;">
      <div style="font-size:40px;font-weight:900;color:#a78bfa;"><?= $total-$score ?></div>
      <div class="text-sm">Misses</div>
    </div>
    <div class="stat-box" style="flex:1;">
      <div style="font-size:40px;font-weight:900;color:#fbbf24;"><?= $pct ?>%</div>
      <div class="text-sm">Match Rate</div>
    </div>
  </div>

  <!-- Compatibility bar -->
  <div class="card mb24" style="text-align:left;">
    <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
      <span class="text-sm">Compatibility</span>
      <span style="font-weight:800;"><?= $pct ?>%</span>
    </div>
    <div style="height:12px;background:rgba(255,255,255,.1);border-radius:99px;">
      <div id="compat-fill" style="height:100%;border-radius:99px;background:linear-gradient(90deg,#f43f5e,<?= $result['color'] ?>);width:0%;transition:width 1.4s ease;box-shadow:0 0 14px <?= $result['color'] ?>88;"></div>
    </div>
  </div>
  <script>
    setTimeout(function(){ document.getElementById('compat-fill').style.width = '<?= $pct ?>%'; }, 100);
  </script>

  <!-- Restart -->
  <form method="POST">
    <input type="hidden" name="action" value="restart">
    <button type="submit" class="btn-primary">Play Again 💕</button>
  </form>
</div>
<?php endif; ?>

</div><!-- /wrap -->

<!-- Generate floating hearts -->
<script>
(function(){
  var emojis = ['💕','💖','💗','💓','💞','❤️','🌹','✨','💘','🌸'];
  var c = document.getElementById('hearts');
  for(var i=0;i<14;i++){
    var d = document.createElement('div');
    d.className = 'heart';
    d.textContent = emojis[i % emojis.length];
    d.style.left = (Math.random()*100) + '%';
    d.style.fontSize = (Math.random()*18+10) + 'px';
    d.style.animationDuration = (Math.random()*8+7) + 's';
    d.style.animationDelay    = (Math.random()*10)  + 's';
    c.appendChild(d);
  }
})();
</script>
</body>
</html>