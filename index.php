<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Procevo Community Hub</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', 'Hiragino Sans', 'Noto Sans JP', sans-serif;
      background: #f0f2f5; color: #1a1a2e; line-height: 1.6;
    }
    header {
      background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
      color: #fff; position: sticky; top: 0; z-index: 100;
      box-shadow: 0 2px 12px rgba(37,99,235,0.3);
    }
    .header-inner {
      max-width: 1200px; margin: 0 auto; padding: 16px 24px;
      display: flex; justify-content: space-between; align-items: center;
    }
    .logo { font-size: 22px; font-weight: 700; }
    .header-actions { display: flex; gap: 12px; align-items: center; }
    .btn {
      padding: 8px 20px; border-radius: 8px; border: none; cursor: pointer;
      font-size: 14px; font-weight: 600; transition: all 0.2s;
    }
    .btn-primary { background: #fff; color: #2563eb; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .btn-accent { background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; padding: 10px 24px; font-size: 15px; }
    .btn-accent:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(102,126,234,0.4); }
    .btn-danger { background: #ef4444; color: #fff; padding: 10px 24px; font-size: 15px; }
    .btn-danger:hover { background: #dc2626; }
    .btn-ghost { background: transparent; color: #64748b; border: 2px solid #e2e8f0; }
    .btn-ghost:hover { background: #f1f5f9; }

    /* Tabs */
    .nav-tabs { background: #fff; border-bottom: 1px solid #e2e8f0; }
    .nav-tabs-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: flex; overflow-x: auto; }
    .nav-tab {
      padding: 14px 20px; font-size: 14px; font-weight: 600; color: #64748b;
      cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.2s;
      white-space: nowrap;
    }
    .nav-tab:hover { color: #2563eb; }
    .nav-tab.active { color: #2563eb; border-bottom-color: #2563eb; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .container { max-width: 1200px; margin: 0 auto; padding: 24px; }

    /* Stats */
    .stats-bar { display: flex; gap: 16px; margin-bottom: 24px; }
    .stat-card {
      flex: 1; background: #fff; border-radius: 12px; padding: 16px 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06); text-align: center;
    }
    .stat-number {
      font-size: 28px; font-weight: 700;
      background: linear-gradient(135deg, #1e3a8a, #2563eb);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .stat-label { font-size: 12px; color: #94a3b8; font-weight: 600; margin-top: 2px; }

    /* Search + Filter area */
    .search-bar {
      background: #fff; border-radius: 16px; padding: 16px 20px; margin-bottom: 16px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06); display: flex; gap: 12px;
      align-items: center; flex-wrap: wrap;
    }
    .search-input {
      flex: 1; min-width: 200px; padding: 10px 16px 10px 40px;
      border: 2px solid #e2e8f0; border-radius: 10px; font-size: 14px;
      outline: none; transition: border-color 0.2s;
      background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242.156a5 5 0 1 1 0-10 5 5 0 0 1 0 10z'/%3E%3C/svg%3E") no-repeat 14px center;
    }
    .search-input:focus { border-color: #2563eb; }
    .btn-clear {
      padding: 8px 16px; border-radius: 10px; border: 2px solid #e2e8f0;
      background: #fff; color: #64748b; font-size: 13px; font-weight: 600;
      cursor: pointer; transition: all 0.2s; white-space: nowrap;
    }
    .btn-clear:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .btn-clear.has-filter { background: #fef2f2; border-color: #fca5a5; color: #ef4444; }

    /* Collapsible filter sections */
    .filter-panel {
      background: #fff; border-radius: 16px; margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06); overflow: hidden;
    }
    .filter-section { border-bottom: 1px solid #f1f5f9; }
    .filter-section:last-child { border-bottom: none; }
    .filter-header {
      padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;
      cursor: pointer; user-select: none; transition: background 0.2s;
    }
    .filter-header:hover { background: #f8fafc; }
    .filter-header-label { font-size: 13px; font-weight: 700; color: #374151; }
    .filter-header-count {
      font-size: 11px; color: #fff; background: #2563eb; border-radius: 10px;
      padding: 1px 8px; margin-left: 8px; display: none;
    }
    .filter-header-count.show { display: inline; }
    .filter-arrow {
      font-size: 12px; color: #94a3b8; transition: transform 0.2s;
    }
    .filter-section.open .filter-arrow { transform: rotate(180deg); }
    .filter-body {
      max-height: 0; overflow: hidden; transition: max-height 0.3s ease;
      padding: 0 20px;
    }
    .filter-section.open .filter-body { max-height: 500px; padding: 0 20px 16px; }
    .filter-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .filter-chip {
      padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
      border: 2px solid #e2e8f0; background: #fff; color: #475569;
      cursor: pointer; transition: all 0.15s;
    }
    .filter-chip:hover { border-color: #93c5fd; background: #eff6ff; }
    .filter-chip.selected { background: #2563eb; border-color: #2563eb; color: #fff; }

    /* Profile cards */
    .profile-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
    .empty-state { grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state-icon { font-size: 48px; margin-bottom: 16px; }
    .empty-state-text { font-size: 16px; font-weight: 600; }
    .profile-card {
      background: #fff; border-radius: 16px; overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06); transition: all 0.3s; cursor: pointer;
    }
    .profile-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.1); }
    .card-banner { height: 80px; }
    .avatar-wrapper { display: flex; justify-content: center; margin-top: -36px; position: relative; z-index: 1; }
    .avatar {
      width: 72px; height: 72px; border-radius: 50%; border: 4px solid #fff;
      display: flex; align-items: center; justify-content: center;
      font-size: 28px; font-weight: 700; color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12); overflow: hidden;
    }
    .avatar img { width: 100%; height: 100%; object-fit: cover; }
    .card-body { padding: 12px 20px 20px; text-align: center; }
    .card-name { font-size: 17px; font-weight: 700; margin-bottom: 2px; }
    .card-role { font-size: 12px; color: #2563eb; font-weight: 600; margin-bottom: 8px; }
    .card-bio {
      font-size: 13px; color: #64748b; margin-bottom: 14px;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .card-tags { display: flex; flex-wrap: wrap; gap: 6px; justify-content: center; margin-bottom: 14px; }
    .tag { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; }
    .tag-blue { background: #eef2ff; color: #2563eb; }
    .tag-pink { background: #fce7f3; color: #ec4899; }
    .tag-green { background: #ecfdf5; color: #10b981; }
    .tag-orange { background: #fff7ed; color: #f97316; }
    .tag-purple { background: #f5f3ff; color: #8b5cf6; }
    .card-footer { display: flex; gap: 8px; justify-content: center; }
    .btn-sm { padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; }
    .btn-detail { background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; }
    .btn-detail:hover { box-shadow: 0 4px 12px rgba(102,126,234,0.3); }
    .btn-edit { background: #f1f5f9; color: #475569; }
    .btn-edit:hover { background: #e2e8f0; }

    /* Grouped view */
    .group-section { margin-bottom: 32px; }
    .group-title {
      font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 16px;
      padding-bottom: 8px; border-bottom: 2px solid #e2e8f0;
      display: flex; align-items: center; gap: 10px;
    }
    .group-count {
      font-size: 12px; color: #fff; background: #2563eb; border-radius: 12px;
      padding: 2px 10px; font-weight: 600;
    }

    /* Modals */
    .modal-overlay {
      position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
      z-index: 200; display: flex; align-items: center; justify-content: center; padding: 24px;
    }
    .modal-overlay.hidden { display: none; }
    .modal {
      background: #fff; border-radius: 20px; width: 100%; max-width: 560px;
      max-height: 90vh; overflow-y: auto; box-shadow: 0 24px 48px rgba(0,0,0,0.2);
    }
    .modal-header { padding: 24px 28px 0; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h2 { font-size: 20px; font-weight: 700; }
    .modal-close {
      width: 36px; height: 36px; border-radius: 50%; border: none; background: #f1f5f9;
      font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #64748b;
    }
    .modal-close:hover { background: #e2e8f0; }
    .modal-body { padding: 24px 28px 28px; }
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .form-input, .form-textarea, .form-select {
      width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 10px;
      font-size: 14px; font-family: inherit; outline: none; transition: border-color 0.2s;
    }
    .form-input:focus, .form-textarea:focus, .form-select:focus { border-color: #2563eb; }
    .form-textarea { resize: vertical; min-height: 80px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .avatar-upload { display: flex; align-items: center; gap: 16px; margin-bottom: 18px; }
    .avatar-preview {
      width: 72px; height: 72px; border-radius: 50%;
      background: linear-gradient(135deg, #1e3a8a, #2563eb);
      display: flex; align-items: center; justify-content: center;
      font-size: 28px; color: #fff; flex-shrink: 0; overflow: hidden;
    }
    .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-upload-text { font-size: 13px; color: #64748b; }
    .avatar-upload-text strong { color: #2563eb; cursor: pointer; }
    .interest-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .interest-chip {
      padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500;
      border: 2px solid #e2e8f0; background: #fff; cursor: pointer; transition: all 0.2s;
    }
    .interest-chip:hover { border-color: #2563eb; }
    .interest-chip.selected { background: #eef2ff; border-color: #2563eb; color: #2563eb; }
    .custom-skill-row { display: flex; gap: 8px; margin-top: 10px; }
    .custom-skill-row .form-input { flex: 1; }
    .btn-add-skill {
      padding: 8px 16px; border-radius: 10px; border: none;
      background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff;
      font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap;
    }
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; }

    .detail-modal { max-width: 640px; }
    .detail-banner { height: 120px; border-radius: 20px 20px 0 0; position: relative; }
    .detail-avatar-wrapper { display: flex; justify-content: center; margin-top: -48px; position: relative; z-index: 1; }
    .detail-avatar {
      width: 96px; height: 96px; border-radius: 50%; border: 5px solid #fff;
      display: flex; align-items: center; justify-content: center;
      font-size: 36px; font-weight: 700; color: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15); overflow: hidden;
    }
    .detail-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .detail-body { padding: 16px 32px 32px; text-align: center; }
    .detail-name { font-size: 24px; font-weight: 700; margin-bottom: 4px; }
    .detail-role { color: #2563eb; font-weight: 600; font-size: 14px; margin-bottom: 16px; }
    .detail-bio { color: #475569; font-size: 14px; margin-bottom: 20px; line-height: 1.7; }
    .detail-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; text-align: left; margin-bottom: 20px; }
    .detail-info-item { background: #f8fafc; padding: 12px 16px; border-radius: 10px; }
    .detail-info-label { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-bottom: 2px; }
    .detail-info-value { font-size: 14px; font-weight: 600; color: #1e293b; }
    .detail-info-value a { color: #2563eb; text-decoration: none; }
    .detail-info-value a:hover { text-decoration: underline; }
    .detail-tags { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-bottom: 24px; }
    .detail-actions { display: flex; gap: 12px; justify-content: center; }

    #pwModal { z-index: 250; }
    .pw-modal { max-width: 380px; }
    .pw-modal .modal-body { text-align: center; }
    .pw-modal .form-input { text-align: center; font-size: 16px; letter-spacing: 2px; }
    .pw-hint { font-size: 12px; color: #94a3b8; margin-top: 8px; }

    .toast {
      position: fixed; bottom: 24px; right: 24px; background: #1e293b; color: #fff;
      padding: 14px 24px; border-radius: 12px; font-size: 14px; font-weight: 500;
      z-index: 300; transform: translateY(100px); opacity: 0; transition: all 0.3s;
    }
    .toast.show { transform: translateY(0); opacity: 1; }
    .toast.success { background: #10b981; }
    .toast.error { background: #ef4444; }

    @media (max-width: 640px) {
      .header-inner { padding: 12px 16px; }
      .logo { font-size: 18px; }
      .container { padding: 16px; }
      .stats-bar { flex-wrap: wrap; }
      .stat-card { min-width: calc(50% - 8px); }
      .profile-grid { grid-template-columns: 1fr; }
      .form-row { grid-template-columns: 1fr; }
      .detail-info-grid { grid-template-columns: 1fr; }
      .nav-tab { padding: 12px 14px; font-size: 13px; }
    }
  </style>
</head>
<body>

  <header>
    <div class="header-inner">
      <div class="logo">Procevo Community Hub</div>
      <div class="header-actions">
        <button class="btn btn-primary" onclick="openRegisterModal()">+ 新規登録</button>
      </div>
    </div>
  </header>

  <div class="nav-tabs">
    <div class="nav-tabs-inner">
      <div class="nav-tab active" data-tab="members">メンバー一覧</div>
      <div class="nav-tab" data-tab="by-industry">業界別</div>
      <div class="nav-tab" data-tab="by-skill">スキル別</div>
      <div class="nav-tab" data-tab="by-region">地域別</div>
    </div>
  </div>

  <div class="container">

    <!-- ===== Tab: メンバー一覧 ===== -->
    <div class="tab-content active" id="tabMembers">
      <div class="stats-bar">
        <div class="stat-card"><div class="stat-number" id="statTotal">0</div><div class="stat-label">登録メンバー</div></div>
        <div class="stat-card"><div class="stat-number" id="statNewWeek">0</div><div class="stat-label">今週の新規</div></div>
        <div class="stat-card"><div class="stat-number" id="statIndustries">0</div><div class="stat-label">業界カテゴリ</div></div>
      </div>

      <div class="search-bar">
        <input type="text" class="search-input" id="searchInput" placeholder="名前・キーワードで検索...">
        <button class="btn-clear" id="clearFilters" onclick="clearAllFilters()">フィルターをクリア</button>
      </div>

      <div class="filter-panel">
        <div class="filter-section" id="fsIndustry">
          <div class="filter-header" onclick="toggleFilter('fsIndustry')">
            <span><span class="filter-header-label">業界</span><span class="filter-header-count" id="cntIndustry">0</span></span>
            <span class="filter-arrow">&#9660;</span>
          </div>
          <div class="filter-body">
            <div class="filter-chips" id="chipsIndustry"></div>
          </div>
        </div>
        <div class="filter-section" id="fsSkills">
          <div class="filter-header" onclick="toggleFilter('fsSkills')">
            <span><span class="filter-header-label">スキル</span><span class="filter-header-count" id="cntSkills">0</span></span>
            <span class="filter-arrow">&#9660;</span>
          </div>
          <div class="filter-body">
            <div class="filter-chips" id="chipsSkills"></div>
          </div>
        </div>
        <div class="filter-section" id="fsLocation">
          <div class="filter-header" onclick="toggleFilter('fsLocation')">
            <span><span class="filter-header-label">地域</span><span class="filter-header-count" id="cntLocation">0</span></span>
            <span class="filter-arrow">&#9660;</span>
          </div>
          <div class="filter-body">
            <div class="filter-chips" id="chipsLocation"></div>
          </div>
        </div>
      </div>

      <div class="profile-grid" id="profileGrid"></div>
    </div>

    <!-- ===== Tab: 業界別 ===== -->
    <div class="tab-content" id="tabByIndustry"></div>
    <!-- ===== Tab: スキル別 ===== -->
    <div class="tab-content" id="tabBySkill"></div>
    <!-- ===== Tab: 地域別 ===== -->
    <div class="tab-content" id="tabByRegion"></div>

  </div>

  <!-- ===== Password Prompt Modal ===== -->
  <div class="modal-overlay hidden" id="pwModal">
    <div class="modal pw-modal">
      <div class="modal-header">
        <h2 id="pwModalTitle">パスワード確認</h2>
        <button class="modal-close" onclick="closeModal('pwModal')">&times;</button>
      </div>
      <div class="modal-body">
        <p style="color:#64748b; font-size:14px; margin-bottom:16px;" id="pwModalDesc">プロフィールを編集するにはパスワードを入力してください</p>
        <form id="pwForm">
          <div class="form-group" style="margin-bottom:8px;">
            <input type="password" class="form-input" id="pwInput" placeholder="パスワード" required>
          </div>
          <p class="pw-hint">登録時に設定したパスワード</p>
          <div class="form-actions" style="justify-content:stretch; margin-top:16px;">
            <button type="submit" class="btn btn-accent" style="width:100%;" id="pwSubmitBtn">確認</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ===== Registration / Edit Modal ===== -->
  <div class="modal-overlay hidden" id="registerModal">
    <div class="modal">
      <div class="modal-header">
        <h2 id="formModalTitle">新規登録</h2>
        <button class="modal-close" onclick="closeModal('registerModal')">&times;</button>
      </div>
      <div class="modal-body">
        <form id="profileForm" enctype="multipart/form-data">
          <input type="hidden" id="formEditId" value="">
          <input type="hidden" id="formEditPassword" value="">
          <div class="avatar-upload">
            <div class="avatar-preview" id="avatarPreview">&#128100;</div>
            <div class="avatar-upload-text">
              <strong onclick="document.getElementById('avatarInput').click()">画像をアップロード</strong><br>
              <span style="font-size:12px; color:#94a3b8;">JPG, PNG（最大 2MB）</span>
              <input type="file" id="avatarInput" accept="image/jpeg,image/png,image/gif" style="display:none">
            </div>
          </div>
          <div class="form-group" id="fieldName">
            <label class="form-label">ユーザー名 *</label>
            <input type="text" class="form-input" id="formName" placeholder="ユーザー名を入力" required>
          </div>
          <div class="form-group" id="fieldPassword">
            <label class="form-label" id="labelPassword">パスワード *（4文字以上）</label>
            <input type="password" class="form-input" id="formPassword" placeholder="パスワード">
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">業界 *</label>
              <select class="form-select" id="formIndustry" required>
                <option value="">選択してください</option>
                <option value="IT・テクノロジー">IT・テクノロジー</option>
                <option value="金融・保険">金融・保険</option>
                <option value="医療・ヘルスケア">医療・ヘルスケア</option>
                <option value="教育・研究">教育・研究</option>
                <option value="製造・メーカー">製造・メーカー</option>
                <option value="小売・EC">小売・EC</option>
                <option value="メディア・エンタメ">メディア・エンタメ</option>
                <option value="不動産・建設">不動産・建設</option>
                <option value="コンサルティング">コンサルティング</option>
                <option value="広告・マーケティング">広告・マーケティング</option>
                <option value="行政・公共">行政・公共</option>
                <option value="学生">学生</option>
                <option value="その他">その他</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">所在地</label>
              <select class="form-select" id="formLocation">
                <option value="">選択してください</option>
                <optgroup label="北海道地方"><option value="北海道地方">北海道地方</option><option value="北海道">　北海道</option></optgroup>
                <optgroup label="東北地方"><option value="東北地方">東北地方</option><option value="青森県">　青森県</option><option value="岩手県">　岩手県</option><option value="宮城県">　宮城県</option><option value="秋田県">　秋田県</option><option value="山形県">　山形県</option><option value="福島県">　福島県</option></optgroup>
                <optgroup label="関東地方"><option value="関東地方">関東地方</option><option value="茨城県">　茨城県</option><option value="栃木県">　栃木県</option><option value="群馬県">　群馬県</option><option value="埼玉県">　埼玉県</option><option value="千葉県">　千葉県</option><option value="東京都">　東京都</option><option value="神奈川県">　神奈川県</option></optgroup>
                <optgroup label="中部地方"><option value="中部地方">中部地方</option><option value="新潟県">　新潟県</option><option value="富山県">　富山県</option><option value="石川県">　石川県</option><option value="福井県">　福井県</option><option value="山梨県">　山梨県</option><option value="長野県">　長野県</option><option value="岐阜県">　岐阜県</option><option value="静岡県">　静岡県</option><option value="愛知県">　愛知県</option></optgroup>
                <optgroup label="近畿地方"><option value="近畿地方">近畿地方</option><option value="三重県">　三重県</option><option value="滋賀県">　滋賀県</option><option value="京都府">　京都府</option><option value="大阪府">　大阪府</option><option value="兵庫県">　兵庫県</option><option value="奈良県">　奈良県</option><option value="和歌山県">　和歌山県</option></optgroup>
                <optgroup label="中国地方"><option value="中国地方">中国地方</option><option value="鳥取県">　鳥取県</option><option value="島根県">　島根県</option><option value="岡山県">　岡山県</option><option value="広島県">　広島県</option><option value="山口県">　山口県</option></optgroup>
                <optgroup label="四国地方"><option value="四国地方">四国地方</option><option value="徳島県">　徳島県</option><option value="香川県">　香川県</option><option value="愛媛県">　愛媛県</option><option value="高知県">　高知県</option></optgroup>
                <optgroup label="九州・沖縄地方"><option value="九州・沖縄地方">九州・沖縄地方</option><option value="福岡県">　福岡県</option><option value="佐賀県">　佐賀県</option><option value="長崎県">　長崎県</option><option value="熊本県">　熊本県</option><option value="大分県">　大分県</option><option value="宮崎県">　宮崎県</option><option value="鹿児島県">　鹿児島県</option><option value="沖縄県">　沖縄県</option></optgroup>
                <optgroup label="その他"><option value="海外">海外</option></optgroup>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">自己紹介 *</label>
            <textarea class="form-textarea" id="formBio" placeholder="あなたのことを教えてください..." required></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">興味・スキル（複数選択可）</label>
            <div class="interest-chips" id="interestChips">
              <span class="interest-chip" data-skill="ChatGPT">ChatGPT</span>
              <span class="interest-chip" data-skill="Claude">Claude</span>
              <span class="interest-chip" data-skill="Gemini">Gemini</span>
              <span class="interest-chip" data-skill="LLM">LLM</span>
              <span class="interest-chip" data-skill="RAG">RAG</span>
              <span class="interest-chip" data-skill="プロンプトエンジニアリング">プロンプトエンジニアリング</span>
              <span class="interest-chip" data-skill="LangChain">LangChain</span>
              <span class="interest-chip" data-skill="AIエージェント">AIエージェント</span>
              <span class="interest-chip" data-skill="Stable Diffusion">Stable Diffusion</span>
              <span class="interest-chip" data-skill="Midjourney">Midjourney</span>
              <span class="interest-chip" data-skill="ComfyUI">ComfyUI</span>
              <span class="interest-chip" data-skill="画像生成">画像生成</span>
              <span class="interest-chip" data-skill="動画生成">動画生成</span>
              <span class="interest-chip" data-skill="音声AI">音声AI</span>
              <span class="interest-chip" data-skill="OpenAI API">OpenAI API</span>
              <span class="interest-chip" data-skill="Hugging Face">Hugging Face</span>
              <span class="interest-chip" data-skill="ファインチューニング">ファインチューニング</span>
              <span class="interest-chip" data-skill="Dify">Dify</span>
              <span class="interest-chip" data-skill="AI導入支援">AI導入支援</span>
              <span class="interest-chip" data-skill="業務自動化">業務自動化</span>
              <span class="interest-chip" data-skill="Python">Python</span>
              <span class="interest-chip" data-skill="機械学習">機械学習</span>
            </div>
            <div class="custom-skill-row">
              <input type="text" class="form-input" id="customSkillInput" placeholder="自分でスキルを追加...">
              <button type="button" class="btn-add-skill" id="addSkillBtn">追加</button>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group"><label class="form-label">GitHub</label><input type="text" class="form-input" id="formGithub" placeholder="username"></div>
            <div class="form-group"><label class="form-label">X (Twitter)</label><input type="text" class="form-input" id="formTwitter" placeholder="username"></div>
          </div>
          <div class="form-group">
            <label class="form-label">ポートフォリオURL</label>
            <input type="text" class="form-input" id="formPortfolio" placeholder="https://">
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-ghost" onclick="closeModal('registerModal')">キャンセル</button>
            <button type="submit" class="btn btn-accent" id="formSubmitBtn">登録する</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ===== Detail Modal ===== -->
  <div class="modal-overlay hidden" id="detailModal">
    <div class="modal detail-modal">
      <div class="detail-banner" id="detailBanner">
        <button class="modal-close" style="position:absolute; top:12px; right:12px; background:rgba(255,255,255,0.2); color:#fff;" onclick="closeModal('detailModal')">&times;</button>
      </div>
      <div class="detail-avatar-wrapper">
        <div class="detail-avatar" id="detailAvatar"></div>
      </div>
      <div class="detail-body">
        <div class="detail-name" id="detailName"></div>
        <div class="detail-role" id="detailIndustry"></div>
        <div class="detail-bio" id="detailBio"></div>
        <div class="detail-info-grid" id="detailInfoGrid"></div>
        <div class="detail-tags" id="detailTags"></div>
        <div class="detail-actions" id="detailActions"></div>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"></div>

<script>
// ===== Data & Config =====
let allProfiles = [];
let searchTimeout = null;
let pendingAction = null;

const selectedFilters = { industry: new Set(), skills: new Set(), location: new Set() };

const bannerGradients = [
  'linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%)',
  'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
  'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
  'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
  'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
  'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
];
const tagClasses = ['tag-blue','tag-pink','tag-green','tag-orange','tag-purple'];
const originalSkills = [
  'ChatGPT','Claude','Gemini','LLM','RAG','プロンプトエンジニアリング',
  'LangChain','AIエージェント','Stable Diffusion','Midjourney','ComfyUI',
  '画像生成','動画生成','音声AI','OpenAI API','Hugging Face',
  'ファインチューニング','Dify','AI導入支援','業務自動化','Python','機械学習'
];
const industries = [
  'IT・テクノロジー','金融・保険','医療・ヘルスケア','教育・研究','製造・メーカー',
  '小売・EC','メディア・エンタメ','不動産・建設','コンサルティング',
  '広告・マーケティング','行政・公共','学生','その他'
];
const regionMap = {
  '北海道':['北海道','北海道地方'],
  '東北':['青森県','岩手県','宮城県','秋田県','山形県','福島県','東北地方'],
  '関東':['茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','関東地方'],
  '中部':['新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','中部地方'],
  '近畿':['三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','近畿地方'],
  '中国':['鳥取県','島根県','岡山県','広島県','山口県','中国地方'],
  '四国':['徳島県','香川県','愛媛県','高知県','四国地方'],
  '九州・沖縄':['福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県','九州・沖縄地方'],
  '海外':['海外'],
};
const regionNames = Object.keys(regionMap);

function escapeHtml(s) { if (!s) return ''; const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
function showToast(msg, type='success') {
  const t = document.getElementById('toast');
  t.textContent = msg; t.className = 'toast ' + type + ' show';
  setTimeout(() => t.className = 'toast', 3000);
}
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function getRegion(loc) {
  if (!loc) return null;
  for (const [region, prefs] of Object.entries(regionMap)) {
    if (prefs.includes(loc)) return region;
  }
  return null;
}

// ===== Filter chips init =====
function buildFilterChips() {
  const indEl = document.getElementById('chipsIndustry');
  indEl.innerHTML = industries.map(v => `<span class="filter-chip" data-cat="industry" data-value="${escapeHtml(v)}">${escapeHtml(v)}</span>`).join('');
  const sklEl = document.getElementById('chipsSkills');
  sklEl.innerHTML = originalSkills.map(v => `<span class="filter-chip" data-cat="skills" data-value="${escapeHtml(v)}">${escapeHtml(v)}</span>`).join('');
  const locEl = document.getElementById('chipsLocation');
  locEl.innerHTML = regionNames.map(v => `<span class="filter-chip" data-cat="location" data-value="${escapeHtml(v)}">${escapeHtml(v)}</span>`).join('');

  document.querySelectorAll('.filter-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      const cat = chip.dataset.cat;
      const val = chip.dataset.value;
      if (selectedFilters[cat].has(val)) {
        selectedFilters[cat].delete(val);
        chip.classList.remove('selected');
      } else {
        selectedFilters[cat].add(val);
        chip.classList.add('selected');
      }
      updateFilterCounts();
      applyFilters();
    });
  });
}

function toggleFilter(id) {
  document.getElementById(id).classList.toggle('open');
}

function updateFilterCounts() {
  ['industry','skills','location'].forEach(cat => {
    const cnt = selectedFilters[cat].size;
    const el = document.getElementById('cnt' + cat.charAt(0).toUpperCase() + cat.slice(1));
    el.textContent = cnt;
    el.classList.toggle('show', cnt > 0);
  });
  const total = selectedFilters.industry.size + selectedFilters.skills.size + selectedFilters.location.size;
  document.getElementById('clearFilters').classList.toggle('has-filter', total > 0);
}

function clearAllFilters() {
  Object.values(selectedFilters).forEach(s => s.clear());
  document.querySelectorAll('.filter-chip.selected').forEach(c => c.classList.remove('selected'));
  document.getElementById('searchInput').value = '';
  updateFilterCounts();
  applyFilters();
}

// ===== Filtering =====
function matchesFilters(p) {
  const q = document.getElementById('searchInput').value.trim().toLowerCase();
  if (q && !(p.name||'').toLowerCase().includes(q) && !(p.bio||'').toLowerCase().includes(q) && !(p.skills||'').toLowerCase().includes(q)) return false;

  if (selectedFilters.industry.size > 0 && !selectedFilters.industry.has(p.industry)) return false;

  if (selectedFilters.skills.size > 0) {
    const pSkills = (p.skills||'').split(',').map(s => s.trim());
    let found = false;
    for (const s of selectedFilters.skills) { if (pSkills.includes(s)) { found = true; break; } }
    if (!found) return false;
  }

  if (selectedFilters.location.size > 0) {
    const pRegion = getRegion(p.location);
    if (!pRegion || !selectedFilters.location.has(pRegion)) return false;
  }

  return true;
}

function applyFilters() {
  renderProfiles(allProfiles.filter(matchesFilters));
}

// ===== API =====
async function fetchAllProfiles() {
  allProfiles = await (await fetch('api.php?action=profiles')).json();
}
async function fetchProfile(id) {
  return (await fetch('api.php?action=profile&id=' + id)).json();
}

// ===== Render card =====
function cardHtml(p) {
  const g = bannerGradients[p.id % bannerGradients.length];
  const skills = p.skills ? p.skills.split(',').slice(0, 3) : [];
  const av = p.avatar_path ? `<img src="${escapeHtml(p.avatar_path)}" alt="">` : escapeHtml((p.name||'?')[0]);
  return `<div class="profile-card" onclick="openDetailModal(${p.id})">
    <div class="card-banner" style="background:${g};"></div>
    <div class="avatar-wrapper"><div class="avatar" style="background:${g};">${av}</div></div>
    <div class="card-body">
      <div class="card-name">${escapeHtml(p.name)}</div>
      <div class="card-role">${escapeHtml(p.industry)}</div>
      <div class="card-bio">${escapeHtml(p.bio)}</div>
      <div class="card-tags">${skills.map((s,j) => `<span class="tag ${tagClasses[j%tagClasses.length]}">${escapeHtml(s.trim())}</span>`).join('')}</div>
      <div class="card-footer">
        <button class="btn-sm btn-detail" onclick="event.stopPropagation(); openDetailModal(${p.id})">詳細</button>
        <button class="btn-sm btn-edit" onclick="event.stopPropagation(); promptPassword('edit', ${p.id})">編集</button>
      </div>
    </div>
  </div>`;
}

function renderProfiles(profiles) {
  const grid = document.getElementById('profileGrid');
  if (!profiles.length) {
    grid.innerHTML = '<div class="empty-state"><div class="empty-state-icon">&#128101;</div><div class="empty-state-text">メンバーが見つかりませんでした</div></div>';
    return;
  }
  grid.innerHTML = profiles.map(cardHtml).join('');
}

async function loadStats() {
  const s = await (await fetch('api.php?action=stats')).json();
  document.getElementById('statTotal').textContent = s.total_members;
  document.getElementById('statNewWeek').textContent = s.new_this_week;
  document.getElementById('statIndustries').textContent = s.total_industries;
}

// ===== Grouped views =====
function renderGrouped(containerId, groupFn, labelFn) {
  const groups = {};
  allProfiles.forEach(p => {
    const keys = groupFn(p);
    keys.forEach(k => { if (!groups[k]) groups[k] = []; groups[k].push(p); });
  });
  const sorted = Object.entries(groups).sort((a,b) => b[1].length - a[1].length);
  document.getElementById(containerId).innerHTML = sorted.map(([key, members]) =>
    `<div class="group-section">
      <div class="group-title">${escapeHtml(labelFn ? labelFn(key) : key)} <span class="group-count">${members.length}</span></div>
      <div class="profile-grid">${members.map(cardHtml).join('')}</div>
    </div>`
  ).join('') || '<div class="empty-state"><div class="empty-state-text">データがありません</div></div>';
}

function renderAllGrouped() {
  renderGrouped('tabByIndustry', p => [p.industry].filter(Boolean), null);
  renderGrouped('tabBySkill', p => (p.skills||'').split(',').map(s=>s.trim()).filter(Boolean), null);
  renderGrouped('tabByRegion', p => { const r = getRegion(p.location); return r ? [r] : []; }, null);
}

// ===== Tabs =====
document.querySelectorAll('.nav-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    const map = { 'members':'tabMembers', 'by-industry':'tabByIndustry', 'by-skill':'tabBySkill', 'by-region':'tabByRegion' };
    document.getElementById(map[tab.dataset.tab]).classList.add('active');
    if (tab.dataset.tab !== 'members') renderAllGrouped();
  });
});

// ===== Search =====
document.getElementById('searchInput').addEventListener('input', () => {
  clearTimeout(searchTimeout); searchTimeout = setTimeout(applyFilters, 300);
});

// ===== Password prompt =====
function promptPassword(action, profileId) {
  pendingAction = { type: action, profileId };
  const isDelete = action === 'delete';
  document.getElementById('pwModalTitle').textContent = isDelete ? '削除の確認' : 'パスワード確認';
  document.getElementById('pwModalDesc').textContent = isDelete
    ? 'プロフィールを削除するにはパスワードを入力してください'
    : 'プロフィールを編集するにはパスワードを入力してください';
  document.getElementById('pwSubmitBtn').textContent = isDelete ? '削除する' : '確認';
  document.getElementById('pwSubmitBtn').className = isDelete ? 'btn btn-danger' : 'btn btn-accent';
  document.getElementById('pwSubmitBtn').style.width = '100%';
  document.getElementById('pwInput').value = '';
  document.getElementById('pwModal').classList.remove('hidden');
  setTimeout(() => document.getElementById('pwInput').focus(), 100);
}

document.getElementById('pwForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  if (!pendingAction) return;
  const pw = document.getElementById('pwInput').value;
  const { type, profileId } = pendingAction;
  const vRes = await fetch('api.php?action=verify&id=' + profileId, {
    method: 'POST', headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ password: pw }),
  });
  if (!vRes.ok) { const d = await vRes.json(); showToast(d.error || 'パスワードが正しくありません', 'error'); return; }
  closeModal('pwModal');
  if (type === 'edit') {
    openEditModal(profileId, pw);
  } else if (type === 'delete') {
    const dRes = await fetch('api.php?action=delete&id=' + profileId, {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ password: pw }),
    });
    if (dRes.ok) {
      showToast('プロフィールを削除しました', 'success');
      closeModal('detailModal');
      await reloadData();
    } else { const d = await dRes.json(); showToast(d.error || 'エラーが発生しました', 'error'); }
  }
  pendingAction = null;
});

// ===== Register / Edit =====
function resetSkillChips() {
  document.querySelectorAll('#interestChips .interest-chip').forEach(c => {
    if (!originalSkills.includes(c.dataset.skill)) c.remove();
    else c.classList.remove('selected');
  });
}
function openRegisterModal() {
  document.getElementById('formEditId').value = '';
  document.getElementById('formEditPassword').value = '';
  document.getElementById('formModalTitle').textContent = '新規登録';
  document.getElementById('formSubmitBtn').textContent = '登録する';
  document.getElementById('profileForm').reset();
  document.getElementById('avatarPreview').innerHTML = '&#128100;';
  document.getElementById('fieldPassword').style.display = 'block';
  document.getElementById('labelPassword').textContent = 'パスワード *（4文字以上）';
  document.getElementById('formPassword').required = true;
  document.getElementById('formPassword').placeholder = 'パスワード';
  document.getElementById('formName').readOnly = false;
  resetSkillChips();
  document.getElementById('registerModal').classList.remove('hidden');
}
async function openEditModal(id, verifiedPassword) {
  const p = await fetchProfile(id);
  document.getElementById('formEditId').value = p.id;
  document.getElementById('formEditPassword').value = verifiedPassword;
  document.getElementById('formModalTitle').textContent = 'プロフィール編集';
  document.getElementById('formSubmitBtn').textContent = '更新する';
  document.getElementById('fieldPassword').style.display = 'block';
  document.getElementById('labelPassword').textContent = '新しいパスワード（変更する場合のみ）';
  document.getElementById('formPassword').required = false;
  document.getElementById('formPassword').value = '';
  document.getElementById('formPassword').placeholder = '変更しない場合は空欄';
  document.getElementById('formName').value = p.name || '';
  document.getElementById('formName').readOnly = false;
  document.getElementById('formBio').value = p.bio || '';
  document.getElementById('formGithub').value = p.github || '';
  document.getElementById('formTwitter').value = p.twitter || '';
  document.getElementById('formPortfolio').value = p.portfolio_url || '';
  const iSel = document.getElementById('formIndustry');
  iSel.value = p.industry || '';
  if (!iSel.value && p.industry) { const o = document.createElement('option'); o.value = p.industry; o.textContent = p.industry; iSel.appendChild(o); iSel.value = p.industry; }
  document.getElementById('formLocation').value = p.location || '';
  resetSkillChips();
  (p.skills ? p.skills.split(',').map(s => s.trim()) : []).forEach(skill => {
    const ex = document.querySelector(`#interestChips .interest-chip[data-skill="${CSS.escape(skill)}"]`);
    if (ex) { ex.classList.add('selected'); } else {
      const c = document.createElement('span'); c.className = 'interest-chip selected'; c.dataset.skill = skill; c.textContent = skill;
      c.addEventListener('click', () => c.classList.toggle('selected'));
      document.getElementById('interestChips').appendChild(c);
    }
  });
  if (p.avatar_path) document.getElementById('avatarPreview').innerHTML = `<img src="${escapeHtml(p.avatar_path)}" alt="">`;
  else document.getElementById('avatarPreview').innerHTML = (p.name || '?')[0];
  document.getElementById('registerModal').classList.remove('hidden');
  document.getElementById('detailModal').classList.add('hidden');
}

document.getElementById('avatarInput').addEventListener('change', function() {
  if (this.files && this.files[0]) {
    const r = new FileReader();
    r.onload = (e) => { document.getElementById('avatarPreview').innerHTML = `<img src="${e.target.result}" alt="">`; };
    r.readAsDataURL(this.files[0]);
  }
});
document.querySelectorAll('.interest-chip').forEach(c => c.addEventListener('click', () => c.classList.toggle('selected')));
function addCustomSkill() {
  const input = document.getElementById('customSkillInput'); const v = input.value.trim();
  if (!v) return;
  const ex = document.querySelector(`#interestChips .interest-chip[data-skill="${CSS.escape(v)}"]`);
  if (ex) { ex.classList.add('selected'); input.value = ''; return; }
  const c = document.createElement('span'); c.className = 'interest-chip selected'; c.dataset.skill = v; c.textContent = v;
  c.addEventListener('click', () => c.classList.toggle('selected'));
  document.getElementById('interestChips').appendChild(c); input.value = '';
}
document.getElementById('addSkillBtn').addEventListener('click', addCustomSkill);
document.getElementById('customSkillInput').addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addCustomSkill(); } });

document.getElementById('profileForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const editId = document.getElementById('formEditId').value;
  const isEdit = !!editId;
  const selectedSkills = [];
  document.querySelectorAll('#interestChips .interest-chip.selected').forEach(c => selectedSkills.push(c.dataset.skill));
  const fd = new FormData();
  fd.append('name', document.getElementById('formName').value);
  fd.append('industry', document.getElementById('formIndustry').value);
  fd.append('location', document.getElementById('formLocation').value);
  fd.append('bio', document.getElementById('formBio').value);
  fd.append('skills', selectedSkills.join(','));
  fd.append('github', document.getElementById('formGithub').value);
  fd.append('twitter', document.getElementById('formTwitter').value);
  fd.append('portfolio_url', document.getElementById('formPortfolio').value);
  if (isEdit) {
    fd.append('password', document.getElementById('formEditPassword').value);
    const newPw = document.getElementById('formPassword').value;
    if (newPw) fd.append('new_password', newPw);
  } else { fd.append('password', document.getElementById('formPassword').value); }
  const av = document.getElementById('avatarInput').files[0];
  if (av) fd.append('avatar', av);
  const url = isEdit ? 'api.php?action=update&id=' + editId : 'api.php?action=create';
  try {
    const res = await fetch(url, { method: 'POST', body: fd });
    const data = await res.json();
    if (!res.ok) { showToast(data.error || 'エラーが発生しました', 'error'); return; }
    showToast(isEdit ? 'プロフィールを更新しました' : '登録しました！', 'success');
    closeModal('registerModal');
    await reloadData();
  } catch { showToast('通信エラーが発生しました', 'error'); }
});

// ===== Detail Modal =====
async function openDetailModal(id) {
  const p = await fetchProfile(id);
  const g = bannerGradients[p.id % bannerGradients.length];
  document.getElementById('detailBanner').style.background = g;
  const da = document.getElementById('detailAvatar');
  da.style.background = g;
  da.innerHTML = p.avatar_path ? `<img src="${escapeHtml(p.avatar_path)}" alt="">` : escapeHtml((p.name||'?')[0]);
  document.getElementById('detailName').textContent = p.name || '';
  document.getElementById('detailIndustry').textContent = p.industry || '';
  document.getElementById('detailBio').textContent = p.bio || '';
  const items = [];
  if (p.location) items.push({ label: '所在地', html: escapeHtml(p.location) });
  if (p.industry) items.push({ label: '業界', html: escapeHtml(p.industry) });
  if (p.updated_at) { const d = new Date(p.updated_at); items.push({ label: '更新日', html: d.getFullYear() + '/' + (d.getMonth()+1) + '/' + d.getDate() }); }
  if (p.github) { const gh = p.github.replace(/^@/, ''); items.push({ label: 'GitHub', html: `<a href="https://github.com/${encodeURIComponent(gh)}" target="_blank" rel="noopener">@${escapeHtml(gh)}</a>` }); }
  if (p.twitter) { const tw = p.twitter.replace(/^@/, ''); items.push({ label: 'X (Twitter)', html: `<a href="https://x.com/${encodeURIComponent(tw)}" target="_blank" rel="noopener">@${escapeHtml(tw)}</a>` }); }
  if (p.portfolio_url) { items.push({ label: 'ポートフォリオ', html: `<a href="${escapeHtml(p.portfolio_url)}" target="_blank" rel="noopener">${escapeHtml(p.portfolio_url)}</a>` }); }
  document.getElementById('detailInfoGrid').innerHTML = items.map(i => `<div class="detail-info-item"><div class="detail-info-label">${i.label}</div><div class="detail-info-value">${i.html}</div></div>`).join('');
  const skills = p.skills ? p.skills.split(',') : [];
  document.getElementById('detailTags').innerHTML = skills.map((s,i) => `<span class="tag ${tagClasses[i%tagClasses.length]}" style="padding:6px 14px;font-size:13px;">${escapeHtml(s.trim())}</span>`).join('');
  document.getElementById('detailActions').innerHTML = `
    <button class="btn btn-accent" onclick="promptPassword('edit', ${p.id})">編集する</button>
    <button class="btn btn-danger" onclick="promptPassword('delete', ${p.id})">削除する</button>`;
  document.getElementById('detailModal').classList.remove('hidden');
}

document.querySelectorAll('.modal-overlay').forEach(o => {
  o.addEventListener('click', e => { if (e.target === o) o.classList.add('hidden'); });
});

// ===== Init =====
async function reloadData() {
  await fetchAllProfiles();
  applyFilters();
  loadStats();
}

buildFilterChips();
reloadData();
</script>
</body>
</html>
