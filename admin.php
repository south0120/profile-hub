<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理者ページ - Procevo Community Hub</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', 'Hiragino Sans', 'Noto Sans JP', sans-serif;
      background: #f0f2f5; color: #1a1a2e; line-height: 1.6;
    }
    header {
      background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
      color: #fff; padding: 16px 24px;
    }
    .header-inner {
      max-width: 1200px; margin: 0 auto;
      display: flex; justify-content: space-between; align-items: center;
    }
    .logo { font-size: 20px; font-weight: 700; }
    .logo span { opacity: 0.6; font-weight: 400; font-size: 13px; margin-left: 8px; }
    .btn {
      padding: 8px 20px; border-radius: 8px; border: none; cursor: pointer;
      font-size: 14px; font-weight: 600; transition: all 0.2s;
    }
    .btn-outline { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,0.3); }
    .btn-outline:hover { border-color: #fff; background: rgba(255,255,255,0.1); }
    .btn-danger-sm { background: #ef4444; color: #fff; padding: 6px 14px; font-size: 12px; border-radius: 6px; border: none; cursor: pointer; }
    .btn-danger-sm:hover { background: #dc2626; }

    .container { max-width: 1200px; margin: 0 auto; padding: 24px; }

    .login-card {
      max-width: 400px; margin: 80px auto; background: #fff; border-radius: 16px;
      padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); text-align: center;
    }
    .login-card h2 { margin-bottom: 8px; }
    .login-card p { color: #64748b; font-size: 14px; margin-bottom: 24px; }
    .form-input {
      width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px;
      font-size: 15px; outline: none; text-align: center; letter-spacing: 2px;
      margin-bottom: 16px;
    }
    .form-input:focus { border-color: #2563eb; }
    .btn-login {
      width: 100%; padding: 12px; border-radius: 10px; border: none;
      background: #1e293b; color: #fff; font-size: 15px; font-weight: 600; cursor: pointer;
    }
    .btn-login:hover { background: #334155; }

    .admin-panel { display: none; }
    .admin-panel.active { display: block; }
    .summary { display: flex; gap: 16px; margin-bottom: 24px; }
    .summary-card {
      flex: 1; background: #fff; border-radius: 12px; padding: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06); text-align: center;
    }
    .summary-num { font-size: 32px; font-weight: 700; color: #1e293b; }
    .summary-label { font-size: 12px; color: #94a3b8; font-weight: 600; }

    .table-wrap {
      background: #fff; border-radius: 16px; overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    table { width: 100%; border-collapse: collapse; }
    th {
      background: #f8fafc; padding: 12px 16px; text-align: left;
      font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase;
      border-bottom: 2px solid #e2e8f0;
    }
    td {
      padding: 14px 16px; border-bottom: 1px solid #f1f5f9;
      font-size: 14px; color: #334155;
    }
    tr:hover td { background: #f8fafc; }
    .user-name { font-weight: 700; color: #1e293b; }
    .badge {
      display: inline-block; padding: 2px 10px; border-radius: 12px;
      font-size: 11px; font-weight: 600; background: #eef2ff; color: #2563eb;
    }
    .skills-cell { max-width: 200px; }
    .skills-cell .tag {
      display: inline-block; padding: 2px 8px; border-radius: 4px;
      font-size: 10px; font-weight: 600; background: #f1f5f9; color: #475569;
      margin: 1px 2px;
    }
    .link-cell a { color: #2563eb; text-decoration: none; font-size: 13px; }
    .link-cell a:hover { text-decoration: underline; }
    .date-cell { font-size: 12px; color: #94a3b8; }
    .back-link { display: inline-block; margin-top: 24px; color: #2563eb; text-decoration: none; font-size: 14px; }
    .back-link:hover { text-decoration: underline; }
    .toast {
      position: fixed; bottom: 24px; right: 24px; background: #10b981; color: #fff;
      padding: 14px 24px; border-radius: 12px; font-size: 14px; font-weight: 500;
      z-index: 300; transform: translateY(100px); opacity: 0; transition: all 0.3s;
    }
    .toast.show { transform: translateY(0); opacity: 1; }
    .toast.error { background: #ef4444; }
  </style>
</head>
<body>

  <header>
    <div class="header-inner">
      <div class="logo">Procevo Community Hub <span>管理者ページ</span></div>
      <div>
        <button class="btn btn-outline" id="logoutBtn" style="display:none;" onclick="adminLogout()">ログアウト</button>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="login-card" id="loginCard">
      <h2>管理者ログイン</h2>
      <p>管理者パスワードを入力してください</p>
      <form id="loginForm">
        <input type="password" class="form-input" id="adminPw" placeholder="管理者パスワード" required>
        <button type="submit" class="btn-login">ログイン</button>
      </form>
    </div>

    <div class="admin-panel" id="adminPanel">
      <div class="summary">
        <div class="summary-card">
          <div class="summary-num" id="totalCount">0</div>
          <div class="summary-label">総登録数</div>
        </div>
        <div class="summary-card">
          <div class="summary-num" id="weekCount">0</div>
          <div class="summary-label">今週の新規</div>
        </div>
        <div class="summary-card">
          <div class="summary-num" id="industryCount">0</div>
          <div class="summary-label">業界数</div>
        </div>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>ユーザー名</th>
              <th>業界</th>
              <th>所在地</th>
              <th>スキル</th>
              <th>リンク</th>
              <th>登録日</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody id="profilesTable"></tbody>
        </table>
      </div>

      <a href="./" class="back-link">&#8592; メンバー一覧に戻る</a>
    </div>
  </div>

  <div class="toast" id="toast"></div>

  <script>
    function showToast(msg, type='success') {
      const t = document.getElementById('toast');
      t.textContent = msg; t.className = 'toast ' + (type === 'error' ? 'error ' : '') + 'show';
      setTimeout(() => t.className = 'toast', 3000);
    }

    // Login
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const pw = document.getElementById('adminPw').value;
      const res = await fetch('api.php?action=admin_login', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ password: pw }),
      });
      if (res.ok) {
        document.getElementById('loginCard').style.display = 'none';
        document.getElementById('adminPanel').classList.add('active');
        document.getElementById('logoutBtn').style.display = 'inline-block';
        loadAdminData();
      } else {
        const d = await res.json();
        showToast(d.error || 'ログインに失敗しました', 'error');
      }
    });

    async function adminLogout() {
      await fetch('api.php?action=admin_logout', { method: 'POST' });
      document.getElementById('loginCard').style.display = 'block';
      document.getElementById('adminPanel').classList.remove('active');
      document.getElementById('logoutBtn').style.display = 'none';
      document.getElementById('adminPw').value = '';
    }

    async function loadAdminData() {
      const stats = await (await fetch('api.php?action=stats')).json();
      document.getElementById('totalCount').textContent = stats.total_members;
      document.getElementById('weekCount').textContent = stats.new_this_week;
      document.getElementById('industryCount').textContent = stats.total_industries;

      const res = await fetch('api.php?action=admin_profiles');
      if (!res.ok) { showToast('権限エラー', 'error'); return; }
      const profiles = await res.json();

      function esc(s) { if (!s) return ''; const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

      document.getElementById('profilesTable').innerHTML = profiles.map(p => {
        const skills = p.skills ? p.skills.split(',').map(s => `<span class="tag">${esc(s.trim())}</span>`).join('') : '-';
        const links = [];
        if (p.github) links.push(`<a href="https://github.com/${encodeURIComponent(p.github)}" target="_blank">GitHub</a>`);
        if (p.twitter) links.push(`<a href="https://x.com/${encodeURIComponent(p.twitter.replace(/^@/,''))}" target="_blank">X</a>`);
        if (p.portfolio_url) links.push(`<a href="${esc(p.portfolio_url)}" target="_blank">Portfolio</a>`);
        const d = p.created_at ? new Date(p.created_at) : null;
        const dateStr = d ? `${d.getFullYear()}/${(d.getMonth()+1).toString().padStart(2,'0')}/${d.getDate().toString().padStart(2,'0')}` : '-';

        return `<tr>
          <td>${p.id}</td>
          <td class="user-name">${esc(p.name)}</td>
          <td><span class="badge">${esc(p.industry)}</span></td>
          <td>${esc(p.location) || '-'}</td>
          <td class="skills-cell">${skills}</td>
          <td class="link-cell">${links.join(' / ') || '-'}</td>
          <td class="date-cell">${dateStr}</td>
          <td><button class="btn-danger-sm" onclick="adminDelete(${p.id})">削除</button></td>
        </tr>`;
      }).join('');
    }

    async function adminDelete(id) {
      if (!confirm('このプロフィールを削除しますか？')) return;
      const res = await fetch('api.php?action=admin_delete&id=' + id, { method: 'POST' });
      if (res.ok) {
        showToast('削除しました');
        loadAdminData();
      } else {
        const d = await res.json();
        showToast(d.error || 'エラー', 'error');
      }
    }
  </script>
</body>
</html>
