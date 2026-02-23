<?php
/**
 * Procevo Community Hub - API
 * 全エンドポイントを api.php?action=XXX で振り分け
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

// --- 設定 ---
define('DB_PATH', __DIR__ . '/data/community.db');
define('UPLOAD_DIR', __DIR__ . '/static/uploads');
define('ADMIN_PASSWORD', 'admin1234');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB

// --- DB接続 ---
function get_db(): PDO {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // テーブル自動作成
    $db->exec('CREATE TABLE IF NOT EXISTS profiles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        industry TEXT NOT NULL,
        location TEXT,
        bio TEXT NOT NULL,
        skills TEXT,
        github TEXT,
        twitter TEXT,
        portfolio_url TEXT,
        avatar_path TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )');
    return $db;
}

// --- ヘルパー ---
function json_response($data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_error(string $message, int $status = 400): void {
    json_response(['error' => $message], $status);
}

function get_post_json(): array {
    $ct = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($ct, 'application/json') !== false) {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
    return $_POST;
}

function strip_password_hash(array $row): array {
    unset($row['password_hash']);
    return $row;
}

function handle_avatar_upload(): ?string {
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $file = $_FILES['avatar'];
    if ($file['size'] > MAX_FILE_SIZE) {
        json_error('ファイルサイズは2MB以下にしてください');
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        json_error('画像はJPG/PNG/GIF形式のみ対応しています');
    }
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $dest = UPLOAD_DIR . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        json_error('画像のアップロードに失敗しました', 500);
    }
    return 'static/uploads/' . $filename;
}

// --- ルーティング ---
$action = $_GET['action'] ?? '';

switch ($action) {

    // ========== プロフィール一覧 ==========
    case 'profiles':
        $db = get_db();
        $q = trim($_GET['q'] ?? '');
        $industry = trim($_GET['industry'] ?? '');
        $skills = trim($_GET['skills'] ?? '');
        $location = trim($_GET['location'] ?? '');

        $sql = 'SELECT * FROM profiles';
        $conditions = [];
        $params = [];

        if ($q !== '') {
            $conditions[] = '(name LIKE :q1 OR bio LIKE :q2 OR skills LIKE :q3)';
            $like = '%' . $q . '%';
            $params[':q1'] = $like;
            $params[':q2'] = $like;
            $params[':q3'] = $like;
        }
        if ($industry !== '') {
            $conditions[] = 'industry LIKE :industry';
            $params[':industry'] = '%' . $industry . '%';
        }
        if ($skills !== '') {
            $conditions[] = 'skills LIKE :skills';
            $params[':skills'] = '%' . $skills . '%';
        }
        if ($location !== '') {
            // 地域名の場合は所属する都道府県に展開
            $region_map = [
                '北海道'   => ['北海道'],
                '東北'     => ['青森県','岩手県','宮城県','秋田県','山形県','福島県','東北地方'],
                '関東'     => ['茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','関東地方'],
                '中部'     => ['新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','中部地方'],
                '近畿'     => ['三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','近畿地方'],
                '中国'     => ['鳥取県','島根県','岡山県','広島県','山口県','中国地方'],
                '四国'     => ['徳島県','香川県','愛媛県','高知県','四国地方'],
                '九州'     => ['福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県','九州・沖縄地方'],
            ];
            if (isset($region_map[$location])) {
                $prefs = $region_map[$location];
                $placeholders = [];
                foreach ($prefs as $i => $pref) {
                    $key = ':loc' . $i;
                    $placeholders[] = $key;
                    $params[$key] = $pref;
                }
                $conditions[] = 'location IN (' . implode(',', $placeholders) . ')';
            } else {
                $conditions[] = 'location LIKE :location';
                $params[':location'] = '%' . $location . '%';
            }
        }
        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY created_at DESC';

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        json_response(array_map('strip_password_hash', $rows));

    // ========== プロフィール詳細 ==========
    case 'profile':
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) json_error('IDが不正です');
        $db = get_db();
        $stmt = $db->prepare('SELECT * FROM profiles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) json_error('Not found', 404);
        json_response(strip_password_hash($row));

    // ========== 新規登録 ==========
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_error('POST required', 405);
        $data = array_merge($_POST, get_post_json());

        $name = trim($data['name'] ?? '');
        $password = $data['password'] ?? '';
        $industry = trim($data['industry'] ?? '');
        $bio = trim($data['bio'] ?? '');

        if ($name === '' || $password === '' || $industry === '' || $bio === '') {
            json_error('ユーザー名、パスワード、業界、自己紹介は必須です');
        }
        if (strlen($password) < 4) {
            json_error('パスワードは4文字以上で入力してください');
        }

        $db = get_db();
        $stmt = $db->prepare('SELECT id FROM profiles WHERE name = :name');
        $stmt->execute([':name' => $name]);
        if ($stmt->fetch()) {
            json_error('このユーザー名は既に使われています', 409);
        }

        $avatar_path = handle_avatar_upload();

        $stmt = $db->prepare('INSERT INTO profiles (name, password_hash, industry, location, bio, skills, github, twitter, portfolio_url, avatar_path)
            VALUES (:name, :pw, :industry, :location, :bio, :skills, :github, :twitter, :portfolio, :avatar)');
        $stmt->execute([
            ':name'     => $name,
            ':pw'       => password_hash($password, PASSWORD_DEFAULT),
            ':industry' => $industry,
            ':location' => trim($data['location'] ?? '') ?: null,
            ':bio'      => $bio,
            ':skills'   => trim($data['skills'] ?? '') ?: null,
            ':github'   => trim($data['github'] ?? '') ?: null,
            ':twitter'  => trim($data['twitter'] ?? '') ?: null,
            ':portfolio'=> trim($data['portfolio_url'] ?? '') ?: null,
            ':avatar'   => $avatar_path,
        ]);
        $new_id = $db->lastInsertId();
        $stmt = $db->prepare('SELECT * FROM profiles WHERE id = :id');
        $stmt->execute([':id' => $new_id]);
        json_response(strip_password_hash($stmt->fetch()), 201);

    // ========== パスワード確認 ==========
    case 'verify':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_error('POST required', 405);
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) json_error('IDが不正です');

        $data = get_post_json();
        $password = $data['password'] ?? '';
        if ($password === '') json_error('パスワードを入力してください');

        $db = get_db();
        $stmt = $db->prepare('SELECT * FROM profiles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) json_error('Not found', 404);

        if (!password_verify($password, $row['password_hash'])) {
            json_error('パスワードが正しくありません', 401);
        }
        json_response(['ok' => true]);

    // ========== プロフィール更新 ==========
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_error('POST required', 405);
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) json_error('IDが不正です');

        $data = array_merge($_POST, get_post_json());
        $password = $data['password'] ?? '';
        if ($password === '') json_error('パスワードが必要です', 403);

        $db = get_db();
        $stmt = $db->prepare('SELECT * FROM profiles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $existing = $stmt->fetch();
        if (!$existing) json_error('Not found', 404);

        if (!password_verify($password, $existing['password_hash'])) {
            json_error('パスワードが正しくありません', 401);
        }

        $name = trim($data['name'] ?? $existing['name']);
        $industry = trim($data['industry'] ?? $existing['industry']);
        $bio = trim($data['bio'] ?? $existing['bio']);

        if ($name === '' || $industry === '' || $bio === '') {
            json_error('ユーザー名、業界、自己紹介は必須です');
        }

        if ($name !== $existing['name']) {
            $stmt = $db->prepare('SELECT id FROM profiles WHERE name = :name AND id != :id');
            $stmt->execute([':name' => $name, ':id' => $id]);
            if ($stmt->fetch()) {
                json_error('このユーザー名は既に使われています', 409);
            }
        }

        $avatar_path = handle_avatar_upload() ?? $existing['avatar_path'];

        $new_password = trim($data['new_password'] ?? '');
        if ($new_password !== '') {
            if (strlen($new_password) < 4) {
                json_error('新しいパスワードは4文字以上で入力してください');
            }
            $pw_hash = password_hash($new_password, PASSWORD_DEFAULT);
        } else {
            $pw_hash = $existing['password_hash'];
        }

        $stmt = $db->prepare('UPDATE profiles SET
            name=:name, password_hash=:pw, industry=:industry, location=:location,
            bio=:bio, skills=:skills, github=:github, twitter=:twitter,
            portfolio_url=:portfolio, avatar_path=:avatar, updated_at=CURRENT_TIMESTAMP
            WHERE id=:id');
        $stmt->execute([
            ':name'     => $name,
            ':pw'       => $pw_hash,
            ':industry' => $industry,
            ':location' => trim($data['location'] ?? $existing['location'] ?? '') ?: null,
            ':bio'      => $bio,
            ':skills'   => trim($data['skills'] ?? $existing['skills'] ?? '') ?: null,
            ':github'   => trim($data['github'] ?? $existing['github'] ?? '') ?: null,
            ':twitter'  => trim($data['twitter'] ?? $existing['twitter'] ?? '') ?: null,
            ':portfolio'=> trim($data['portfolio_url'] ?? $existing['portfolio_url'] ?? '') ?: null,
            ':avatar'   => $avatar_path,
            ':id'       => $id,
        ]);

        $stmt = $db->prepare('SELECT * FROM profiles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        json_response(strip_password_hash($stmt->fetch()));

    // ========== プロフィール削除 ==========
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_error('POST required', 405);
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) json_error('IDが不正です');

        $data = get_post_json();
        $password = $data['password'] ?? '';
        if ($password === '') json_error('パスワードが必要です', 403);

        $db = get_db();
        $stmt = $db->prepare('SELECT * FROM profiles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $existing = $stmt->fetch();
        if (!$existing) json_error('Not found', 404);

        if (!password_verify($password, $existing['password_hash'])) {
            json_error('パスワードが正しくありません', 401);
        }

        if ($existing['avatar_path']) {
            $filepath = __DIR__ . '/' . $existing['avatar_path'];
            if (file_exists($filepath)) unlink($filepath);
        }

        $stmt = $db->prepare('DELETE FROM profiles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        json_response(['message' => '削除しました']);

    // ========== 統計 ==========
    case 'stats':
        $db = get_db();
        $total = $db->query('SELECT COUNT(*) FROM profiles')->fetchColumn();
        $new_week = $db->query("SELECT COUNT(*) FROM profiles WHERE created_at >= datetime('now', '-7 days')")->fetchColumn();
        $industries = $db->query('SELECT COUNT(DISTINCT industry) FROM profiles')->fetchColumn();
        json_response([
            'total_members'    => (int)$total,
            'new_this_week'    => (int)$new_week,
            'total_industries' => (int)$industries,
        ]);

    // ========== 管理者ログイン ==========
    case 'admin_login':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_error('POST required', 405);
        $data = get_post_json();
        if (($data['password'] ?? '') === ADMIN_PASSWORD) {
            $_SESSION['is_admin'] = true;
            json_response(['ok' => true]);
        }
        json_error('管理者パスワードが正しくありません', 401);

    // ========== 管理者ログアウト ==========
    case 'admin_logout':
        unset($_SESSION['is_admin']);
        json_response(['message' => 'ログアウトしました']);

    // ========== 管理者用一覧 ==========
    case 'admin_profiles':
        if (empty($_SESSION['is_admin'])) json_error('管理者権限が必要です', 403);
        $db = get_db();
        $rows = $db->query('SELECT * FROM profiles ORDER BY created_at DESC')->fetchAll();
        json_response(array_map('strip_password_hash', $rows));

    // ========== 管理者用削除 ==========
    case 'admin_delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_error('POST required', 405);
        if (empty($_SESSION['is_admin'])) json_error('管理者権限が必要です', 403);
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) json_error('IDが不正です');

        $db = get_db();
        $stmt = $db->prepare('SELECT * FROM profiles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $existing = $stmt->fetch();
        if (!$existing) json_error('Not found', 404);

        if ($existing['avatar_path']) {
            $filepath = __DIR__ . '/' . $existing['avatar_path'];
            if (file_exists($filepath)) unlink($filepath);
        }

        $stmt = $db->prepare('DELETE FROM profiles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        json_response(['message' => '削除しました']);

    default:
        json_error('Unknown action', 400);
}
