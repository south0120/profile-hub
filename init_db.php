<?php
/**
 * DB初期化スクリプト
 * ブラウザからアクセスするとDBを再作成しサンプルデータを投入します。
 * 実行後はこのファイルを削除してください。
 */

$db_path = __DIR__ . '/data/community.db';
$data_dir = __DIR__ . '/data';
$upload_dir = __DIR__ . '/static/uploads';

// ディレクトリ作成
if (!is_dir($data_dir)) mkdir($data_dir, 0755, true);
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

// 既存DB削除
if (file_exists($db_path)) unlink($db_path);

$db = new PDO('sqlite:' . $db_path);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

$samples = [
    [
        'name' => 'タナカー例',
        'password' => 'pass',
        'industry' => 'IT・テクノロジー',
        'location' => '東京都',
        'bio' => 'LLMを活用したアプリケーション開発に取り組んでいます。RAGやエージェント構築が得意です。最新のAI技術をプロダクトに落とし込むのが好きです。',
        'skills' => 'ChatGPT,Claude,RAG,LangChain,プロンプトエンジニアリング',
        'github' => 'tanaka-ai',
        'twitter' => 'tanaka_ai',
        'portfolio_url' => 'https://tanaka-ai.example.com',
    ],
    [
        'name' => 'ハナコー例',
        'password' => 'pass',
        'industry' => '広告・マーケティング',
        'location' => '大阪府',
        'bio' => '生成AIを活用したクリエイティブ制作を行っています。画像生成AIでの広告ビジュアル制作やAIコピーライティングが専門です。',
        'skills' => 'Stable Diffusion,Midjourney,ComfyUI,ChatGPT,画像生成',
        'github' => 'hanako-creative',
        'twitter' => 'hanako_aigc',
        'portfolio_url' => 'https://hanako-creative.example.com',
    ],
    [
        'name' => 'ケンター例',
        'password' => 'pass',
        'industry' => '教育・研究',
        'location' => '福岡県',
        'bio' => '大学でLLMの研究をしています。ファインチューニングやRLHFの手法を探求中。オープンソースモデルの活用にも力を入れています。',
        'skills' => 'LLM,ファインチューニング,Hugging Face,Python,機械学習',
        'github' => 'kenta-research',
        'twitter' => 'kenta_llm',
        'portfolio_url' => null,
    ],
    [
        'name' => 'ミサキー例',
        'password' => 'pass',
        'industry' => 'コンサルティング',
        'location' => '東京都',
        'bio' => '企業へのAI導入支援を行うコンサルタントです。業務プロセスの自動化やAI活用戦略の策定が専門。生成AIのビジネス活用に情熱を持っています。',
        'skills' => 'AI導入支援,プロンプトエンジニアリング,ChatGPT,業務自動化,Dify',
        'github' => null,
        'twitter' => 'misaki_aiconsul',
        'portfolio_url' => null,
    ],
    [
        'name' => 'ショウター例',
        'password' => 'pass',
        'industry' => '金融・保険',
        'location' => '愛知県',
        'bio' => '金融業界でAIを活用したリスク分析や自動レポート生成に取り組んでいます。LLMによるドキュメント処理の効率化が得意です。',
        'skills' => 'OpenAI API,RAG,Python,データ分析,Claude',
        'github' => null,
        'twitter' => 'shota_finai',
        'portfolio_url' => null,
    ],
    [
        'name' => 'サクラー例',
        'password' => 'pass',
        'industry' => 'IT・テクノロジー',
        'location' => '京都府',
        'bio' => 'AIエージェント開発に夢中な学生です。LangChainやCrewAIを使ったマルチエージェントシステムの構築に挑戦中。一緒に学びましょう！',
        'skills' => 'LangChain,AIエージェント,Python,CrewAI,Claude',
        'github' => 'sakura-agent',
        'twitter' => 'sakura_aiagent',
        'portfolio_url' => null,
    ],
];

$stmt = $db->prepare('INSERT INTO profiles (name, password_hash, industry, location, bio, skills, github, twitter, portfolio_url)
    VALUES (:name, :pw, :industry, :location, :bio, :skills, :github, :twitter, :portfolio)');

foreach ($samples as $s) {
    $stmt->execute([
        ':name'     => $s['name'],
        ':pw'       => password_hash($s['password'], PASSWORD_DEFAULT),
        ':industry' => $s['industry'],
        ':location' => $s['location'],
        ':bio'      => $s['bio'],
        ':skills'   => $s['skills'],
        ':github'   => $s['github'],
        ':twitter'  => $s['twitter'],
        ':portfolio'=> $s['portfolio_url'],
    ]);
}

$count = $db->query('SELECT COUNT(*) FROM profiles')->fetchColumn();

header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>DB初期化完了</title></head><body style='font-family:sans-serif;max-width:600px;margin:40px auto;padding:20px;'>";
echo "<h1>DB初期化完了</h1>";
echo "<p>{$count}件のサンプルデータを投入しました。</p>";
echo "<p>サンプルアカウント: ユーザー名=タナカ, パスワード=pass（他も同様）</p>";
echo "<p>管理者パスワード: admin1234</p>";
echo "<p style='color:red;font-weight:bold;'>セキュリティのため、このファイル (init_db.php) を削除してください。</p>";
echo "<p><a href='./'>メインページへ</a></p>";
echo "</body></html>";
