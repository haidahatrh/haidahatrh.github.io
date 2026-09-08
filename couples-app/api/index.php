<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

$DATA_DIR = __DIR__ . '/../data/';
$UPLOAD_DIR = __DIR__ . '/../uploads/';
$UPLOAD_URL = 'uploads/';

if (!is_dir($DATA_DIR)) mkdir($DATA_DIR, 0777, true);
if (!is_dir($UPLOAD_DIR)) mkdir($UPLOAD_DIR, 0777, true);

// ---------- Helpers ----------
function readJson($file, $default) {
  if (!file_exists($file)) return $default;
  $s = file_get_contents($file);
  $d = json_decode($s, true);
  return $d === null ? $default : $d;
}
function writeJson($file, $data) {
  $fp = fopen($file, 'c+');
  if (!$fp) return false;
  flock($fp, LOCK_EX);
  ftruncate($fp, 0);
  rewind($fp);
  fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  fflush($fp);
  flock($fp, LOCK_UN);
  fclose($fp);
  return true;
}
function ok($data = null) { echo json_encode(['ok' => true, 'data' => $data]); exit; }
function err($msg) { echo json_encode(['ok' => false, 'error' => $msg]); exit; }
function newId() { return bin2hex(random_bytes(6)); }

// ---------- Parse input ----------
$action = '';
$input = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ct = $_SERVER['CONTENT_TYPE'] ?? '';
  if (strpos($ct, 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $action = $input['action'] ?? '';
  } else {
    $input = $_POST;
    $action = $_POST['action'] ?? '';
  }
} else {
  $action = $_GET['action'] ?? '';
  $input = $_GET;
}

// ---------- Question of the day pool ----------
$QUESTIONS = [
  "What's the best part of your day so far?",
  "What are you most looking forward to this week?",
  "What made you smile today?",
  "If we could have any meal together tonight, what?",
  "What's one thing you appreciate about me today?",
  "What song is stuck in your head?",
  "What's a small win you had recently?",
  "What are you grateful for today?",
  "If we could teleport anywhere for an hour, where?",
  "What's on your mind?",
  "What's a random memory of us you thought about lately?",
  "What's your energy level 1-10 today?",
  "What's a tiny thing that made you happy this week?",
  "What do you need from me today?",
  "What's the weirdest thought you had today?",
];

// ---------- Actions ----------
switch ($action) {

  // ========== TODAY ==========
  case 'today_get': {
    $file = $DATA_DIR . 'today.json';
    $today = date('Y-m-d');
    $data = readJson($file, [
      'since' => null,
      'myMood' => null,
      'bfMood' => null,
      'moodDate' => null,
      'question' => null,
      'questionDate' => null,
      'answers' => new stdClass()
    ]);
    // Reset moods daily
    if (($data['moodDate'] ?? '') !== $today) {
      $data['myMood'] = null;
      $data['bfMood'] = null;
      $data['moodDate'] = $today;
    }
    // Rotate question daily
    if (($data['questionDate'] ?? '') !== $today) {
      $data['question'] = $QUESTIONS[array_rand($QUESTIONS)];
      $data['questionDate'] = $today;
      $data['answers'] = [];
      $data['replies'] = [];
    }
    writeJson($file, $data);
    ok($data);
  }

  case 'today_set_mood': {
    $file = $DATA_DIR . 'today.json';
    $data = readJson($file, []);
    $user = $input['user'] ?? '';
    $mood = $input['mood'] ?? '';
    if ($user === 'me') $data['myMood'] = $mood;
    elseif ($user === 'bf') $data['bfMood'] = $mood;
    else err('bad user');
    $data['moodDate'] = date('Y-m-d');
    writeJson($file, $data);
    ok();
  }

  case 'today_answer': {
    $file = $DATA_DIR . 'today.json';
    $data = readJson($file, []);
    $user = $input['user'] ?? '';
    $ans = trim($input['answer'] ?? '');
    if (!$ans || !in_array($user, ['me','bf'])) err('bad input');
    if (!isset($data['answers']) || !is_array($data['answers'])) $data['answers'] = [];
    $data['answers'][$user] = $ans;
    writeJson($file, $data);
    ok();
  }

  case 'today_reply': {
    $file = $DATA_DIR . 'today.json';
    $data = readJson($file, []);
    $user = $input['user'] ?? '';
    $text = trim($input['text'] ?? '');
    if (!$text || !in_array($user, ['me','bf'])) err('bad input');
    if (!isset($data['replies']) || !is_array($data['replies'])) $data['replies'] = [];
    $data['replies'][] = ['user' => $user, 'text' => $text, 'at' => date('c')];
    writeJson($file, $data);
    ok();
  }

  case 'today_set_anniversary': {
    $file = $DATA_DIR . 'today.json';
    $data = readJson($file, []);
    $date = $input['date'] ?? '';
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) err('bad date');
    $data['since'] = $date;
    writeJson($file, $data);
    ok();
  }

  // ========== PLANS ==========
  case 'plans_list': {
    ok(readJson($DATA_DIR . 'plans.json', []));
  }

  case 'plans_add': {
    $file = $DATA_DIR . 'plans.json';
    $plans = readJson($file, []);
    $plans[] = [
      'id' => newId(),
      'title' => trim($input['title'] ?? ''),
      'date' => $input['date'] ?? date('Y-m-d'),
      'location' => trim($input['location'] ?? ''),
      'notes' => trim($input['notes'] ?? ''),
      'done' => false,
      'createdBy' => $input['createdBy'] ?? 'me',
      'createdAt' => date('c')
    ];
    writeJson($file, $plans);
    ok();
  }

  case 'plans_toggle': {
    $file = $DATA_DIR . 'plans.json';
    $plans = readJson($file, []);
    $id = $input['id'] ?? '';
    foreach ($plans as &$p) if ($p['id'] === $id) $p['done'] = !$p['done'];
    writeJson($file, $plans);
    ok();
  }

  case 'plans_delete': {
    $file = $DATA_DIR . 'plans.json';
    $plans = readJson($file, []);
    $id = $input['id'] ?? '';
    $plans = array_values(array_filter($plans, fn($p) => $p['id'] !== $id));
    writeJson($file, $plans);
    ok();
  }

  // ========== FEED ==========
  case 'feed_list': {
    $posts = readJson($DATA_DIR . 'feed.json', []);
    usort($posts, fn($a, $b) => strcmp($b['createdAt'], $a['createdAt']));
    ok($posts);
  }

  case 'feed_get': {
    $posts = readJson($DATA_DIR . 'feed.json', []);
    $id = $input['id'] ?? '';
    foreach ($posts as $p) if ($p['id'] === $id) ok($p);
    err('not found');
  }

  case 'feed_add': {
    $file = $DATA_DIR . 'feed.json';
    $posts = readJson($file, []);
    $user = $_POST['user'] ?? 'me';
    $caption = trim($_POST['caption'] ?? '');
    $images = [];
    if (!empty($_FILES['images'])) {
      $files = $_FILES['images'];
      $count = is_array($files['name']) ? count($files['name']) : 1;
      for ($i = 0; $i < min($count, 4); $i++) {
        $tmp = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
        $name = is_array($files['name']) ? $files['name'][$i] : $files['name'];
        $err = is_array($files['error']) ? $files['error'][$i] : $files['error'];
        if ($err !== UPLOAD_ERR_OK || !is_uploaded_file($tmp)) continue;
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','gif','webp','heic'])) $ext = 'jpg';
        $fname = date('Ymd_His') . '_' . newId() . '.' . $ext;
        if (move_uploaded_file($tmp, $UPLOAD_DIR . $fname)) {
          $images[] = $UPLOAD_URL . $fname;
        }
      }
    }
    if (!$images && !$caption) err('empty post');
    $posts[] = [
      'id' => newId(),
      'user' => $user,
      'caption' => $caption,
      'images' => $images,
      'likes' => [],
      'comments' => [],
      'createdAt' => date('c')
    ];
    writeJson($file, $posts);
    ok();
  }

  case 'feed_like': {
    $file = $DATA_DIR . 'feed.json';
    $posts = readJson($file, []);
    $id = $input['id'] ?? '';
    $user = $input['user'] ?? '';
    foreach ($posts as &$p) {
      if ($p['id'] === $id) {
        $p['likes'] = $p['likes'] ?? [];
        if (in_array($user, $p['likes'])) {
          $p['likes'] = array_values(array_filter($p['likes'], fn($u) => $u !== $user));
        } else {
          $p['likes'][] = $user;
        }
      }
    }
    writeJson($file, $posts);
    ok();
  }

  case 'feed_comment': {
    $file = $DATA_DIR . 'feed.json';
    $posts = readJson($file, []);
    $id = $input['id'] ?? '';
    $text = trim($input['text'] ?? '');
    $user = $input['user'] ?? '';
    if (!$text) err('empty');
    foreach ($posts as &$p) {
      if ($p['id'] === $id) {
        $p['comments'] = $p['comments'] ?? [];
        $p['comments'][] = ['user' => $user, 'text' => $text, 'at' => date('c')];
      }
    }
    writeJson($file, $posts);
    ok();
  }

  case 'feed_delete': {
    $file = $DATA_DIR . 'feed.json';
    $posts = readJson($file, []);
    $id = $input['id'] ?? '';
    $keep = [];
    foreach ($posts as $p) {
      if ($p['id'] === $id) {
        foreach (($p['images'] ?? []) as $img) {
          $path = __DIR__ . '/../' . $img;
          if (file_exists($path)) @unlink($path);
        }
      } else {
        $keep[] = $p;
      }
    }
    writeJson($file, $keep);
    ok();
  }

  // ========== RSVP ==========
  case 'rsvp_get': {
    ok(readJson($DATA_DIR . 'rsvp.json', null));
  }

  case 'rsvp_set': {
    $file = $DATA_DIR . 'rsvp.json';
    $data = [
      'confirmed' => true,
      'at' => date('c'),
      'by' => $input['by'] ?? 'bf'
    ];
    writeJson($file, $data);
    ok($data);
  }

  // ========== ACTIVITY FEED ==========
  case 'activity_feed': {
    $limit = (int)($input['limit'] ?? 30);
    $events = [];

    // Plans
    foreach (readJson($DATA_DIR . 'plans.json', []) as $p) {
      if (!empty($p['createdAt'])) {
        $events[] = [
          'type' => 'plan',
          'icon' => '📅',
          'user' => $p['createdBy'] ?? 'me',
          'at' => $p['createdAt'],
          'text' => 'added plan',
          'detail' => $p['title'] ?? '',
          'sub' => $p['date'] ?? ''
        ];
      }
    }

    // Finance — hangouts + purchases
    foreach (readJson($DATA_DIR . 'finance.json', []) as $h) {
      if (!empty($h['createdAt'])) {
        $events[] = [
          'type' => 'hangout',
          'icon' => '🍽️',
          'user' => null,
          'at' => $h['createdAt'],
          'text' => 'new hangout',
          'detail' => $h['title'] ?? '',
          'sub' => $h['date'] ?? ''
        ];
      }
      foreach (($h['purchases'] ?? []) as $p) {
        if (!empty($p['at'])) {
          $events[] = [
            'type' => 'purchase',
            'icon' => '💰',
            'user' => $p['paidBy'] ?? 'me',
            'at' => $p['at'],
            'text' => 'spent RM ' . number_format((float)$p['amount'], 2),
            'detail' => $p['item'] ?? '',
            'sub' => 'in ' . ($h['title'] ?? '')
          ];
        }
      }
    }

    // Feed posts
    foreach (readJson($DATA_DIR . 'feed.json', []) as $post) {
      if (!empty($post['createdAt'])) {
        $imgCount = count($post['images'] ?? []);
        $events[] = [
          'type' => 'post',
          'icon' => '📸',
          'user' => $post['user'] ?? 'me',
          'at' => $post['createdAt'],
          'text' => $imgCount ? "posted $imgCount photo" . ($imgCount > 1 ? 's' : '') : 'posted',
          'detail' => mb_substr($post['caption'] ?? '', 0, 60),
          'sub' => ''
        ];
      }
    }

    // Custom questions
    $games = readJson($DATA_DIR . 'games.json', []);
    $gameTitles = ['wyr' => 'Would You Rather', 'truth' => 'Deep Questions', 'dare' => 'Playful Dares', 'quiz' => 'How Well Do You Know Me'];
    foreach ($games as $gameKey => $qs) {
      if (!is_array($qs)) continue;
      foreach ($qs as $q) {
        if (!empty($q['at'])) {
          $events[] = [
            'type' => 'question',
            'icon' => '✨',
            'user' => $q['by'] ?? 'me',
            'at' => $q['at'],
            'text' => 'added question to ' . ($gameTitles[$gameKey] ?? $gameKey),
            'detail' => mb_substr($q['text'] ?? '', 0, 80),
            'sub' => ''
          ];
        }
      }
    }

    // RSVP
    $rsvp = readJson($DATA_DIR . 'rsvp.json', null);
    if ($rsvp && !empty($rsvp['confirmed']) && !empty($rsvp['at'])) {
      $events[] = [
        'type' => 'rsvp',
        'icon' => '💌',
        'user' => $rsvp['by'] ?? 'bf',
        'at' => $rsvp['at'],
        'text' => 'confirmed the invitation',
        'detail' => "I'll be there 💗",
        'sub' => ''
      ];
    }

    // Sort desc by at
    usort($events, fn($a, $b) => strcmp($b['at'], $a['at']));
    $events = array_slice($events, 0, $limit);

    ok($events);
  }

  // ========== GAMES ==========
  case 'games_list': {
    $data = readJson($DATA_DIR . 'games.json', []);
    // Ensure all keys exist
    foreach (['wyr','truth','dare','quiz'] as $k) {
      if (!isset($data[$k]) || !is_array($data[$k])) $data[$k] = [];
    }
    ok($data);
  }

  case 'games_add': {
    $file = $DATA_DIR . 'games.json';
    $data = readJson($file, []);
    $game = $input['game'] ?? '';
    $text = trim($input['text'] ?? '');
    $by = in_array($input['by'] ?? '', ['me','bf']) ? $input['by'] : 'me';
    if (!in_array($game, ['wyr','truth','dare','quiz'])) err('bad game');
    if (!$text) err('empty');
    if (!isset($data[$game]) || !is_array($data[$game])) $data[$game] = [];
    $data[$game][] = ['id' => newId(), 'text' => $text, 'by' => $by, 'at' => date('c')];
    writeJson($file, $data);
    ok();
  }

  case 'games_delete': {
    $file = $DATA_DIR . 'games.json';
    $data = readJson($file, []);
    $game = $input['game'] ?? '';
    $id = $input['id'] ?? '';
    if (!isset($data[$game]) || !is_array($data[$game])) err('bad game');
    $data[$game] = array_values(array_filter($data[$game], fn($q) => $q['id'] !== $id));
    writeJson($file, $data);
    ok();
  }

  // ========== FINANCE ==========
  case 'finance_list': {
    ok(readJson($DATA_DIR . 'finance.json', []));
  }

  case 'finance_add_hangout': {
    $file = $DATA_DIR . 'finance.json';
    $hs = readJson($file, []);
    $hs[] = [
      'id' => newId(),
      'title' => trim($input['title'] ?? ''),
      'date' => $input['date'] ?? date('Y-m-d'),
      'location' => trim($input['location'] ?? ''),
      'purchases' => [],
      'createdAt' => date('c')
    ];
    writeJson($file, $hs);
    ok();
  }

  case 'finance_add_purchase': {
    $file = $DATA_DIR . 'finance.json';
    $hs = readJson($file, []);
    $hid = $input['hangoutId'] ?? ($_POST['hangoutId'] ?? '');
    $item = trim($input['item'] ?? ($_POST['item'] ?? ''));
    $amount = (float)($input['amount'] ?? ($_POST['amount'] ?? 0));
    $paidByRaw = $input['paidBy'] ?? ($_POST['paidBy'] ?? '');
    $paidBy = in_array($paidByRaw, ['me','bf']) ? $paidByRaw : 'me';

    // Optional receipt upload
    $receiptUrl = null;
    if (!empty($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['receipt']['tmp_name'])) {
      $ext = strtolower(pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, ['jpg','jpeg','png','gif','webp','heic'])) $ext = 'jpg';
      $rcDir = $UPLOAD_DIR . 'receipts/';
      if (!is_dir($rcDir)) mkdir($rcDir, 0777, true);
      $fname = date('Ymd_His') . '_' . newId() . '.' . $ext;
      if (move_uploaded_file($_FILES['receipt']['tmp_name'], $rcDir . $fname)) {
        $receiptUrl = $UPLOAD_URL . 'receipts/' . $fname;
      }
    }

    foreach ($hs as &$h) {
      if ($h['id'] === $hid) {
        $h['purchases'] = $h['purchases'] ?? [];
        $entry = [
          'id' => newId(),
          'item' => $item,
          'amount' => $amount,
          'paidBy' => $paidBy,
          'at' => date('c')
        ];
        if ($receiptUrl) $entry['receipt'] = $receiptUrl;
        $h['purchases'][] = $entry;
      }
    }
    writeJson($file, $hs);
    ok();
  }

  case 'finance_del_purchase': {
    $file = $DATA_DIR . 'finance.json';
    $hs = readJson($file, []);
    $hid = $input['hangoutId'] ?? '';
    $pid = $input['pid'] ?? '';
    foreach ($hs as &$h) {
      if ($h['id'] === $hid) {
        foreach (($h['purchases'] ?? []) as $p) {
          if ($p['id'] === $pid && !empty($p['receipt'])) {
            $path = __DIR__ . '/../' . $p['receipt'];
            if (file_exists($path)) @unlink($path);
          }
        }
        $h['purchases'] = array_values(array_filter($h['purchases'] ?? [], fn($p) => $p['id'] !== $pid));
      }
    }
    writeJson($file, $hs);
    ok();
  }

  case 'finance_del_hangout': {
    $file = $DATA_DIR . 'finance.json';
    $hs = readJson($file, []);
    $id = $input['id'] ?? '';
    // Delete receipt files
    foreach ($hs as $h) {
      if ($h['id'] === $id) {
        foreach (($h['purchases'] ?? []) as $p) {
          if (!empty($p['receipt'])) {
            $path = __DIR__ . '/../' . $p['receipt'];
            if (file_exists($path)) @unlink($path);
          }
        }
      }
    }
    $hs = array_values(array_filter($hs, fn($h) => $h['id'] !== $id));
    writeJson($file, $hs);
    ok();
  }

  default:
    err('unknown action: ' . $action);
}
