<?php
// view/front/leaderboard.php

require_once __DIR__ . '/../../controller/ScoreC.php';
$scoreC = new ScoreC();

$quiz_id = isset($_GET['quiz_id']) && is_numeric($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : null;

$rows = $quiz_id ? $scoreC->top10Quiz($quiz_id) : $scoreC->top10Global();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Top Scores - Leaderboard</title>

<style>
    body{font-family: Arial, Helvetica, sans-serif; background:#f7f9fb; padding:20px;}
    .container{max-width:1100px;margin:20px auto;}
    
    h1{
        font-size:32px;
        margin-bottom:12px;
        display:flex;
        align-items:center;
        gap:10px;
        color:#1e293b;
    }
    
    .bar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    }

    .search-box{
        padding:10px 14px;
        border-radius:8px;
        border:1px solid #cbd5e1;
        width:250px;
        font-size:15px;
    }

    .btn{
        padding:10px 18px;
        background:#0ea5e9;
        color:white;
        border-radius:8px;
        border:none;
        cursor:pointer;
        font-weight:bold;
        transition:0.2s;
    }

    .btn:hover{ background:#0284c7; }

    .btn-back{
        background:#e2e8f0;
        color:#334155;
        margin-left:8px;
    }
    .btn-back:hover{
        background:#cbd5e1;
    }

    table{
        width:100%;
        border-collapse:collapse;
        border:1px solid #d1d5db;
        background:white;
        border-radius:10px;
        overflow:hidden;
    }

    th, td{
        padding:14px;
        border-bottom:1px solid #e5e7eb;
        text-align:left;
        font-size:15px;
    }

    th{
        background:#f8fafc;
        font-weight:bold;
        color:#1e293b;
    }

    tr:hover{ background:#f1f5f9; }

    .center{text-align:center;}

    .medal {font-size:18px; margin-right:6px;}
    .gold{color:#d4af37}
    .silver{color:#c0c0c0}
    .bronze{color:#cd7f32}

    .tip{
        margin-top:12px;
        font-size:14px;
        color:#64748b;
        font-style:italic;
    }
</style>

</head>
<body>

<div class="container">
    <h1>🏆 Top Scores (Global)</h1>

    <div class="bar">
        <input type="text" id="searchBox" class="search-box" placeholder="Rechercher par nom...">
        
        <div>
            <!-- ❌ CSV supprimé -->
            <!-- PDF export (OK) -->
            <button class="btn" onclick="window.print()">Exporter PDF</button>

            <button class="btn btn-back" onclick="history.back()">← Retour</button>
        </div>
    </div>

    <?php if (empty($rows)): ?>
        <p>Aucun score enregistré pour le moment.</p>
    <?php else: ?>
        <table id="scoreTable">
            <thead>
                <tr>
                    <th>Classement</th>
                    <th>Nom</th>
                    <th class="center">Score (%)</th>
                    <th class="center">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 0;
                foreach ($rows as $r) {
                    $rank++;

                    $medal = '';
                    if ($rank === 1) $medal = '<span class="medal gold">🥇</span>';
                    elseif ($rank === 2) $medal = '<span class="medal silver">🥈</span>';
                    elseif ($rank === 3) $medal = '<span class="medal bronze">🥉</span>';

                    $percent = is_numeric($r['pourcentage']) ? (int)$r['pourcentage'] . '%' : htmlspecialchars($r['pourcentage']);
                ?>
                <tr>
                    <td><?php echo $medal . $rank . "."; ?></td>
                    <td><?php echo htmlspecialchars($r['user_name'] ?: '—'); ?></td>
                    <td class="center"><?php echo $percent; ?></td>
                    <td class="center"><?php echo htmlspecialchars($r['submitted_at']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php endif; ?>

    

</div>

<script>
// ⭐ Recherche dynamique
document.getElementById("searchBox").addEventListener("input", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#scoreTable tbody tr");

    rows.forEach(row => {
        let name = row.cells[1].innerText.toLowerCase();
        row.style.display = name.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>
