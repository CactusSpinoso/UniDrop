<?php
require_once __DIR__ . "/classes/user.php";
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['Email'] ) || !isset($_SESSION['ID_User'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/classes/FileManager.php";

$fm = new FileManager();
$files = $fm->getUserFiles((int) $_SESSION['ID_User']);

$userEmail = $_SESSION['Email'] instanceof User
    ? $_SESSION['Email']->getEmail()
    : (string) $_SESSION['Email'];

function getFileIcon(string $name): array {
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $icons = [
        'pdf'  => ['📄', '#e53935'],
        'doc'  => ['📝', '#1565c0'],
        'docx' => ['📝', '#1565c0'],
        'xls'  => ['📊', '#2e7d32'],
        'xlsx' => ['📊', '#2e7d32'],
        'ppt'  => ['📑', '#e65100'],
        'pptx' => ['📑', '#e65100'],
        'jpg'  => ['🖼️', '#6a1b9a'],
        'jpeg' => ['🖼️', '#6a1b9a'],
        'png'  => ['🖼️', '#6a1b9a'],
        'gif'  => ['🖼️', '#6a1b9a'],
        'mp4'  => ['🎬', '#00838f'],
        'mp3'  => ['🎵', '#00838f'],
        'zip'  => ['🗜️', '#5d4037'],
        'rar'  => ['🗜️', '#5d4037'],
        'txt'  => ['📃', '#546e7a'],
    ];
    return $icons[$ext] ?? ['📁', '#1a73e8'];
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I miei file – UniDrop</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Google Sans', Roboto, Arial, sans-serif;
            background: #f8f9fa;
            color: #202124;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 100;
            height: 64px;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .header-logo-icon {
            width: 40px;
            height: 40px;
            background: #1a73e8;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .header-logo-text {
            font-size: 22px;
            color: #5f6368;
            font-weight: 400;
        }

        .header-logo-text span {
            color: #1a73e8;
            font-weight: 600;
        }

        .header-search {
            flex: 1;
            max-width: 720px;
            margin: 0 24px;
            display: flex;
            align-items: center;
            background: #f1f3f4;
            border-radius: 24px;
            padding: 10px 16px;
            gap: 12px;
            transition: background 0.2s;
        }

        .header-search:focus-within {
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .header-search input {
            border: none;
            background: transparent;
            font-size: 16px;
            outline: none;
            width: 100%;
            color: #202124;
        }

        .search-icon {
            color: #5f6368;
            font-size: 18px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .avatar {
            width: 36px;
            height: 36px;
            background: #1a73e8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        /* ── LAYOUT ── */
        .layout {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 256px;
            flex-shrink: 0;
            padding: 12px 8px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow-y: auto;
        }

        .sidebar-new-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 24px;
            background: #fff;
            border: 1px solid #dadce0;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 500;
            color: #202124;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: box-shadow 0.2s;
        }

        .sidebar-new-btn:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .sidebar-new-btn .plus {
            font-size: 22px;
            color: #1a73e8;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 10px 16px;
            border-radius: 0 24px 24px 0;
            font-size: 14px;
            font-weight: 500;
            color: #202124;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
        }

        .sidebar-item:hover {
            background: #e8eaed;
        }

        .sidebar-item.active {
            background: #d2e3fc;
            color: #1a73e8;
        }

        .sidebar-item .icon {
            font-size: 18px;
        }

        .sidebar-storage {
            margin-top: auto;
            padding: 16px;
        }

        /* ── MAIN CONTENT ── */
        .main {
            flex: 1;
            padding: 20px 24px;
            overflow-y: auto;
        }

        .section-title {
            font-size: 14px;
            font-weight: 500;
            color: #5f6368;
            margin-bottom: 16px;
            letter-spacing: 0.3px;
        }

        /* ── FILE GRID ── */
        .files-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .file-card {
            display: flex;
            flex-direction: column;
            width: 180px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            transition: box-shadow 0.2s, border-color 0.2s;
            overflow: hidden;
        }

        .file-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border-color: #c6c6c6;
        }

        .file-card-preview {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            font-size: 52px;
            border-bottom: 1px solid #e0e0e0;
        }

        .file-card-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
        }

        .file-card-icon-small {
            font-size: 18px;
            flex-shrink: 0;
        }

        .file-card-meta {
            flex: 1;
            min-width: 0;
        }

        .file-card-name {
            font-size: 13px;
            font-weight: 500;
            color: #202124;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-card-date {
            font-size: 11px;
            color: #5f6368;
            margin-top: 2px;
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 80px 24px;
            color: #5f6368;
            gap: 16px;
        }

        .empty-state-icon {
            font-size: 80px;
            opacity: 0.4;
        }

        .empty-state h2 {
            font-size: 22px;
            font-weight: 400;
            color: #3c4043;
        }

        .empty-state p {
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header class="header">
    <a href="index.php" class="header-logo">
        <div class="header-logo-icon">☁️</div>
        <span class="header-logo-text">Uni<span>Drop</span></span>
    </a>

    <div class="header-search">
        <span class="search-icon">🔍</span>
        <input type="text" placeholder="Cerca in UniDrop">
    </div>

    <div class="header-actions">
        <div class="avatar" title="<?php echo htmlspecialchars($userEmail); ?>">
            <?php echo strtoupper(substr($userEmail, 0, 1)); ?>
        </div>
    </div>
</header>

<!-- LAYOUT -->
<div class="layout">

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <a href="file.php" class="sidebar-new-btn">
            <span class="plus">+</span> Nuovo caricamento
        </a>

        <a href="visualizzaFile.php" class="sidebar-item active">
            <span class="icon">🖥️</span> I miei file
        </a>
        <a href="index.php" class="sidebar-item">
            <span class="icon">🏠</span> Home
        </a>
        <a href="logout.php" class="sidebar-item">
            <span class="icon">🚪</span> Esci
        </a>
    </nav>

    <!-- MAIN -->
    <main class="main">
        <div class="section-title">I miei file</div>

        <?php if (empty($files)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📂</div>
                <h2>Nessun file caricato</h2>
                <p>Carica il tuo primo file cliccando su "Nuovo caricamento".</p>
            </div>
        <?php else: ?>
            <div class="files-grid">
                <?php foreach ($files as $file):
                    [$icon, $color] = getFileIcon($file['NomeOriginale']);
                    $date = date('d M Y', strtotime($file['DataCaricamento']));
                ?>
                    <div class="file-card" title="SHA1: <?php echo htmlspecialchars($file['SHA1']); ?>">
                        <div class="file-card-preview" style="color:<?php echo $color; ?>">
                            <?php echo $icon; ?>
                        </div>
                        <div class="file-card-info">
                            <span class="file-card-icon-small" style="color:<?php echo $color; ?>"><?php echo $icon; ?></span>
                            <div class="file-card-meta">
                                <div class="file-card-name"><?php echo htmlspecialchars($file['NomeOriginale']); ?></div>
                                <div class="file-card-date"><?php echo $date; ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</div>

</body>
</html>
