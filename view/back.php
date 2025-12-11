<?php
require_once dirname(__DIR__) . '/controller/config.php';
require_once dirname(__DIR__) . '/controller/participantC.php';
require_once dirname(__DIR__) . '/controller/sponsorsC.php';

$message = '';
$sponsorMessage = '';
$participantController = new ParticipantController();
$sponsorsController = new SponsorsController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id > 0) {
            try {
                $deleted = $participantController->deleteParticipant($id);
                $message = $deleted ? 'Participant supprimé avec succès.' : 'Aucun participant trouvé pour cet identifiant.';
            } catch (Exception $e) {
                $message = 'Erreur lors de la suppression : ' . $e->getMessage();
            }
        }
    } elseif ($action === 'sponsor_delete') {
        $sponsorId = isset($_POST['sponsor_id']) ? (int) $_POST['sponsor_id'] : 0;
        if ($sponsorId > 0) {
            try {
                $deleted = $sponsorsController->deleteSponsor($sponsorId);
                $sponsorMessage = $deleted ? 'Sponsor supprimé avec succès.' : 'Aucun sponsor trouvé pour cet identifiant.';
            } catch (Exception $e) {
                $sponsorMessage = 'Erreur lors de la suppression du sponsor : ' . $e->getMessage();
            }
        }
    } elseif ($action === 'sponsor_update') {
        $sponsorId = isset($_POST['sponsor_id']) ? (int) $_POST['sponsor_id'] : 0;
        $participantId = isset($_POST['participant_id']) ? (int) $_POST['participant_id'] : 0;
        $nomEntreprise = trim($_POST['nom_entreprise'] ?? '');
        $contactEmail = trim($_POST['contact_email'] ?? '');
        $pays = trim($_POST['pays'] ?? '');
        $montant = $_POST['montant'] ?? null;
        $dateSponsorisation = $_POST['date_sponsorisation'] ?? null;

        try {
            if ($sponsorId <= 0 || $participantId <= 0) {
                throw new InvalidArgumentException("Identifiants invalides.");
            }
            if (empty($nomEntreprise) || empty($contactEmail)) {
                throw new InvalidArgumentException("Merci de renseigner le nom de l'entreprise et l'email.");
            }
            if (!filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException("Format d'email invalide.");
            }
            if ($montant !== null && $montant !== '' && !is_numeric($montant)) {
                throw new InvalidArgumentException("Le montant doit être un nombre.");
            }

            $sponsor = new Sponsors(
                $nomEntreprise,
                $contactEmail,
                $participantId,
                $pays ?: null,
                $montant !== '' ? (float)$montant : null,
                $dateSponsorisation ?: null
            );
            $sponsor->setId($sponsorId);

            $sponsorsController->updateSponsor($sponsor);
            $sponsorMessage = 'Sponsor mis à jour avec succès.';
        } catch (Exception $e) {
            $sponsorMessage = 'Erreur lors de la mise à jour du sponsor : ' . $e->getMessage();
        }
    }
}

$participantsList = [];
$sponsorsList = [];
$participantSponsorCounts = [];
$countryCounts = [];

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->query("SELECT * FROM participant ORDER BY date_inscription DESC, id DESC");
    $participantsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($participantsList as $participantRow) {
        $participantSponsorCounts[$participantRow['id']] = [
            'label' => trim(($participantRow['nom'] ?? '') . ' ' . ($participantRow['prenom'] ?? '')),
            'count' => 0
        ];
        $countryLabel = $participantRow['pays'] ?? 'Inconnu';
        if (!isset($countryCounts[$countryLabel])) {
            $countryCounts[$countryLabel] = 0;
        }
        $countryCounts[$countryLabel]++;
    }
} catch (Exception $e) {
    $message = 'Impossible de charger les participants : ' . $e->getMessage();
}

try {
    $sponsorsList = $sponsorsController->getAllSponsors();
    foreach ($sponsorsList as $sponsorObj) {
        $pid = (int) $sponsorObj->getParticipantId();
        if (isset($participantSponsorCounts[$pid])) {
            $participantSponsorCounts[$pid]['count']++;
        }
    }
} catch (Exception $e) {
    $sponsorMessage = 'Impossible de charger les sponsors : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Together4Peace - Backoffice</title>
    <link rel="stylesheet" href="front/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #102b44;
            --sidebar-hover: #143555;
            --accent: #1ba3d8;
            --danger: #dc2626;
            --muted: #6b7280;
            --bg: #eef2f6;
            --surface: #ffffff;
            --color-primary: #002D62;
            --color-secondary: #ffc107;
            --color-accent: #26a69a;
            --color-dark: #333;
            --color-light: #f4f4f4;
        }
        * {
            box-sizing: border-box;
        }
        /* Override body for backoffice layout */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        /* Backoffice specific styles */
        .backoffice-wrapper {
            display: flex;
            flex: 1;
            min-height: 0;
        }
        .sidebar {
            width: 240px;
            background-color: var(--sidebar-bg);
            color: #f8fbff;
            display: flex;
            flex-direction: column;
            padding: 24px 18px;
            flex-shrink: 0;
        }
        .sidebar h2 {
            margin: 0 0 30px;
            font-size: 1.4rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar li {
            margin-bottom: 10px;
        }
        .sidebar a {
            text-decoration: none;
            color: inherit;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: var(--sidebar-hover);
        }
        .content {
            flex: 1;
            padding: 30px 35px;
            background-color: var(--bg);
            overflow-y: auto;
        }
        .tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
        }
        .tab-btn {
            border: 1px solid var(--sidebar-bg);
            background: transparent;
            color: var(--sidebar-bg);
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .tab-btn.active {
            background-color: var(--sidebar-bg);
            color: #fff;
            box-shadow: 0 6px 18px rgba(16, 43, 68, 0.25);
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .page-header h1 {
            margin: 0;
            color: #1f2937;
        }
        .message {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .message.success {
            background-color: #e6fffa;
            color: #047857;
        }
        .message.error {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .stats-btn {
            border: 1px solid var(--accent);
            background-color: transparent;
            color: var(--accent);
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .stats-btn:hover {
            background-color: var(--accent);
            color: #fff;
            box-shadow: 0 8px 20px rgba(27, 163, 216, 0.35);
        }
        .pdf-btn {
            border: 1px solid #dc2626;
            background-color: #dc2626;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .pdf-btn:hover {
            background-color: #b91c1c;
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.35);
        }
        .section-hidden {
            display: none;
        }
        .card {
            background-color: var(--surface);
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(16, 43, 68, 0.08);
        }
        .table-wrapper {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }
        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 0.95rem;
        }
        th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        tr:hover td {
            background-color: #f3f4f6;
        }
        .muted {
            color: var(--muted);
            font-style: italic;
        }
        .delete-btn {
            background-color: var(--danger);
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.2s ease;
        }
        .delete-btn:hover {
            background-color: #b91c1c;
        }
        form.inline {
            display: inline;
        }
        .action-btn {
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            margin-right: 6px;
        }
        .edit-btn {
            background-color: var(--accent);
            color: #fff;
        }
        dialog {
            border: none;
            border-radius: 12px;
            padding: 0;
            width: min(520px, 95vw);
        }
        dialog::backdrop {
            background: rgba(0, 0, 0, 0.45);
        }
        .stats-dialog {
            width: 100vw;
            height: 100vh;
            margin: 0;
            border-radius: 0;
            max-width: none;
        }
        .stats-dialog .dialog-content {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .stats-dialog .dialog-body {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 20px;
        }
        .dialog-content {
            padding: 24px 28px 30px;
        }
        .dialog-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .dialog-header h2 {
            margin: 0;
            color: var(--sidebar-bg);
        }
        .ghost-button {
            background: transparent;
            border: none;
            font-size: 1.6rem;
            line-height: 1;
            cursor: pointer;
            color: #475569;
        }
        .dialog-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .dialog-form label {
            font-weight: 600;
        }
        .dialog-form input,
        .dialog-form select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 0.95rem;
        }
        .dialog-form .error-text {
            color: #d9534f;
            font-size: 0.85rem;
            min-height: 18px;
        }
        .dialog-form .invalid {
            border-color: #d9534f !important;
            background-color: #fff5f5;
        }
        .dialog-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .footer-content {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .footer-links h4,
        .footer-social h4 {
            margin-bottom: 10px;
            color: var(--color-light);
        }
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        .footer-links ul li {
            margin-bottom: 8px;
        }
        .footer-links a,
        .footer-social a {
            color: var(--color-light);
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer-links a:hover,
        .footer-social a:hover {
            color: var(--color-secondary);
        }
        .footer-social {
            display: flex;
            flex-direction: column;
        }
        .footer-social a {
            display: inline-block;
            margin-right: 15px;
            font-size: 1.5em;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--color-light);
        }
        @media (max-width: 900px) {
            .backoffice-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                flex-direction: row;
                overflow-x: auto;
            }
            .sidebar ul {
                display: flex;
                gap: 10px;
            }
            .footer-content {
                flex-direction: column;
                gap: 30px;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="#" class="logo-link">
            <div class="logo">
                <img src="front/logo.png" alt="Logo Together4Peace" class="header-logo" onerror="this.style.display='none';">
                <span class="site-name">Together4Peace</span>
            </div>
        </a>
        <nav>
            <ul>
                <li><a href="front.php">Inscription</a></li>
                <li><a href="mainpage.php">Participants</a></li>
                <li><a href="back.php" class="nav-cta">Backoffice</a></li>
            </ul>
        </nav>
        <a href="mainpage.php" class="btn btn-secondary">Retour au site</a>
    </header>

    <div class="backoffice-wrapper">
        <aside class="sidebar">
            <h2>Tableau de bord</h2>
            <ul>
                <li><a href="#" class="active" data-tab="participants">Participants</a></li>
                <li><a href="#" data-tab="sponsors">Sponsors</a></li>
                <li><a href="#" data-tab="stats">Statistiques</a></li>
            </ul>
        </aside>

        <main class="content">
        <div class="page-header">
            <h1>Tableau de bord</h1>
            <span class="muted"><?php echo date('d/m/Y'); ?></span>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Erreur') === 0 ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="tabs" style="justify-content: space-between; align-items: center;">
            <div>
                <button class="tab-btn active" data-target="participantsSection">Participants</button>
                <button class="tab-btn" data-target="sponsorsSection">Sponsors</button>
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="button" class="pdf-btn" id="exportPdfBtn">
                    <i class="fas fa-file-pdf"></i> Exporter PDF
                </button>
                <button type="button" class="stats-btn" id="openStatsDialog">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </button>
            </div>
        </div>

        <div class="card" id="participantsSection">
            <h2>Liste des participants</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Pays</th>
                            <th>Langue</th>
                            <th>Date d'inscription</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($participantsList)): ?>
                            <tr>
                                <td colspan="8" class="muted">Aucun participant enregistré.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($participantsList as $participantRow): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($participantRow['id']); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['email']); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['pays'] ?? '—'); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['langue_preferee'] ?? '—'); ?></td>
                                    <td>
                                        <?php
                                            $dateValue = $participantRow['date_inscription'] ?? '';
                                            echo $dateValue ? htmlspecialchars(date('d/m/Y H:i', strtotime($dateValue))) : '—';
                                        ?>
                                    </td>
                                    <td>
                                        <form method="POST" class="inline" onsubmit="return confirm('Supprimer ce participant ?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($participantRow['id']); ?>">
                                            <button type="submit" class="delete-btn">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card section-hidden" id="sponsorsSection">
            <h2>Liste des sponsors</h2>
            <?php if (!empty($sponsorMessage)): ?>
                <div class="message <?php echo strpos($sponsorMessage, 'Erreur') === 0 ? 'error' : 'success'; ?>">
                    <?php echo htmlspecialchars($sponsorMessage); ?>
                </div>
            <?php endif; ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Entreprise</th>
                            <th>Email</th>
                            <th>Pays</th>
                            <th>Montant</th>
                            <th>Participant</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sponsorsList)): ?>
                            <tr>
                                <td colspan="8" class="muted">Aucun sponsor enregistré.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sponsorsList as $sponsorObj): ?>
                                <?php
                                    $participantInfo = $sponsorObj->participant_info ?? [];
                                    $participantName = isset($participantInfo['nom']) ? ($participantInfo['nom'] . ' ' . ($participantInfo['prenom'] ?? '')) : '—';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($sponsorObj->getId()); ?></td>
                                    <td><?php echo htmlspecialchars($sponsorObj->getNomEntreprise()); ?></td>
                                    <td><?php echo htmlspecialchars($sponsorObj->getContactEmail()); ?></td>
                                    <td><?php echo htmlspecialchars($sponsorObj->getPays() ?: '—'); ?></td>
                                    <td><?php echo $sponsorObj->getMontant() !== null ? htmlspecialchars(number_format($sponsorObj->getMontant(), 2)) . ' $' : '—'; ?></td>
                                    <td><?php echo htmlspecialchars($participantName); ?></td>
                                    <td>
                                        <?php
                                            $dateValue = $sponsorObj->getDateSponsorisation();
                                            echo $dateValue ? htmlspecialchars(date('d/m/Y H:i', strtotime($dateValue))) : '—';
                                        ?>
                                    </td>
                                    <td>
                                        <button
                                            type="button"
                                            class="action-btn edit-btn"
                                            data-id="<?php echo htmlspecialchars($sponsorObj->getId()); ?>"
                                            data-participant="<?php echo htmlspecialchars($sponsorObj->getParticipantId()); ?>"
                                            data-entreprise="<?php echo htmlspecialchars($sponsorObj->getNomEntreprise(), ENT_QUOTES); ?>"
                                            data-email="<?php echo htmlspecialchars($sponsorObj->getContactEmail(), ENT_QUOTES); ?>"
                                            data-pays="<?php echo htmlspecialchars($sponsorObj->getPays() ?? '', ENT_QUOTES); ?>"
                                            data-montant="<?php echo htmlspecialchars($sponsorObj->getMontant() ?? '', ENT_QUOTES); ?>"
                                            data-date="<?php echo htmlspecialchars($sponsorObj->getDateSponsorisation() ?? '', ENT_QUOTES); ?>"
                                        >Modifier</button>
                                        <form method="POST" class="inline" onsubmit="return confirm('Supprimer ce sponsor ?');">
                                            <input type="hidden" name="action" value="sponsor_delete">
                                            <input type="hidden" name="sponsor_id" value="<?php echo htmlspecialchars($sponsorObj->getId()); ?>">
                                            <button type="submit" class="action-btn delete-btn">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        </main>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="front/logo.png" alt="Logo Together4Peace" class="header-logo footer-logo-img" onerror="this.style.display='none';">
                <span class="site-name">Together4Peace</span>
            </div>
            <div class="footer-links">
                <h4>Liens Utiles</h4>
                <ul>
                    <li><a href="front.php">Inscription</a></li>
                    <li><a href="mainpage.php">Les Participants</a></li>
                    <li><a href="back.php">Backoffice</a></li>
                </ul>
            </div>
            <div class="footer-social">
                <h4>Suivez-nous</h4>
                <div>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date('Y'); ?> Together4Peace. Tous droits réservés. | Mentions Légales
        </div>
    </footer>

    <dialog id="editSponsorDialog">
        <div class="dialog-content">
            <div class="dialog-header">
                <h2>Modifier le sponsor</h2>
                <button type="button" class="ghost-button" id="closeSponsorDialog">&times;</button>
            </div>
            <form method="POST" class="dialog-form" id="editSponsorForm" novalidate>
                <input type="hidden" name="action" value="sponsor_update">
                <input type="hidden" name="sponsor_id" id="edit_sponsor_id">

                <label for="edit_nom_entreprise">Nom de l'entreprise *</label>
                <input type="text" name="nom_entreprise" id="edit_nom_entreprise">
                <small class="error-text" id="edit_nom_error"></small>

                <label for="edit_contact_email">Email de contact *</label>
                <input type="text" name="contact_email" id="edit_contact_email">
                <small class="error-text" id="edit_email_error"></small>

                <label for="edit_participant_id">Participant</label>
                <select name="participant_id" id="edit_participant_id">
                    <option value="">--Choisir un participant--</option>
                    <?php foreach ($participantsList as $participantRow): ?>
                        <option value="<?php echo htmlspecialchars($participantRow['id']); ?>">
                            <?php echo htmlspecialchars($participantRow['nom'] . ' ' . $participantRow['prenom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="edit_pays">Pays</label>
                <input type="text" name="pays" id="edit_pays">

                <label for="edit_montant">Montant (USD)</label>
                <input type="number" name="montant" id="edit_montant" min="0" step="0.01">

                <label for="edit_date_sponsorisation">Date de sponsorisation</label>
                <input type="datetime-local" name="date_sponsorisation" id="edit_date_sponsorisation">

                <div class="dialog-actions">
                    <button type="submit" class="action-btn edit-btn">Enregistrer</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="statsDialog" class="stats-dialog">
        <div class="dialog-content">
            <div class="dialog-header">
                <h2>Statistiques des participants</h2>
                <button type="button" class="ghost-button" id="closeStatsDialog">&times;</button>
            </div>
            <div class="dialog-body" style="display:flex;flex-direction:column;gap:30px;max-width:1200px;width:100%;align-self:center;">
                <div>
                    <h3>Nombre de sponsors par participant</h3>
                    <canvas id="sponsorsBarChart"></canvas>
                </div>
                <div>
                    <h3>Répartition des participants par pays</h3>
                    <canvas id="countriesPieChart"></canvas>
                </div>
            </div>
        </div>
    </dialog>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script>
        const participantSponsorLabels = <?php echo json_encode(array_values(array_column($participantSponsorCounts, 'label')), JSON_UNESCAPED_UNICODE); ?>;
        const participantSponsorValues = <?php echo json_encode(array_values(array_column($participantSponsorCounts, 'count')), JSON_UNESCAPED_UNICODE); ?>;
        const countryLabels = <?php echo json_encode(array_keys($countryCounts), JSON_UNESCAPED_UNICODE); ?>;
        const countryValues = <?php echo json_encode(array_values($countryCounts), JSON_UNESCAPED_UNICODE); ?>;
        const participantsDataForPdf = <?php echo json_encode(array_map(function($p) {
            return [
                'id' => $p['id'],
                'nom' => $p['nom'] ?? '',
                'prenom' => $p['prenom'] ?? '',
                'email' => $p['email'] ?? '',
                'pays' => $p['pays'] ?? '—',
                'langue' => $p['langue_preferee'] ?? '—',
                'date' => $p['date_inscription'] ? date('d/m/Y H:i', strtotime($p['date_inscription'])) : '—'
            ];
        }, $participantsList), JSON_UNESCAPED_UNICODE); ?>;
        
        document.addEventListener('DOMContentLoaded', () => {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const sections = {
                participantsSection: document.getElementById('participantsSection'),
                sponsorsSection: document.getElementById('sponsorsSection')
            };
            const statsDialog = document.getElementById('statsDialog');
            const openStatsBtn = document.getElementById('openStatsDialog');
            const closeStatsBtn = document.getElementById('closeStatsDialog');
            const exportPdfBtn = document.getElementById('exportPdfBtn');
            let sponsorsChart;
            let countriesChart;

            const sidebarLinks = document.querySelectorAll('.sidebar a[data-tab]');
            
            // Fonction pour exporter en PDF
            function exportParticipantsToPdf() {
                if (!participantsDataForPdf || participantsDataForPdf.length === 0) {
                    alert('Aucun participant à exporter.');
                    return;
                }

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4'); // Orientation paysage pour plus d'espace
                
                // En-tête
                doc.setFontSize(18);
                doc.setTextColor(0, 45, 98);
                doc.text('Liste des Participants - Together4Peace', 14, 15);
                
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text(`Date d'export : ${new Date().toLocaleDateString('fr-FR')}`, 14, 22);
                doc.text(`Total : ${participantsDataForPdf.length} participant(s)`, 14, 27);
                
                // Préparer les données pour le tableau
                const tableData = participantsDataForPdf.map(p => [
                    p.id,
                    p.nom,
                    p.prenom,
                    p.email,
                    p.pays,
                    p.langue,
                    p.date
                ]);
                
                // Créer le tableau avec autoTable
                doc.autoTable({
                    head: [['ID', 'Nom', 'Prénom', 'Email', 'Pays', 'Langue', 'Date d\'inscription']],
                    body: tableData,
                    startY: 32,
                    styles: {
                        fontSize: 8,
                        cellPadding: 2,
                        overflow: 'linebreak',
                        cellWidth: 'auto'
                    },
                    headStyles: {
                        fillColor: [0, 45, 98],
                        textColor: 255,
                        fontStyle: 'bold',
                        fontSize: 9
                    },
                    alternateRowStyles: {
                        fillColor: [245, 245, 245]
                    },
                    margin: { top: 32, left: 14, right: 14 },
                    columnStyles: {
                        0: { cellWidth: 15 },
                        1: { cellWidth: 30 },
                        2: { cellWidth: 30 },
                        3: { cellWidth: 50 },
                        4: { cellWidth: 35 },
                        5: { cellWidth: 30 },
                        6: { cellWidth: 40 }
                    }
                });
                
                // Pied de page
                const pageCount = doc.internal.getNumberOfPages();
                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    doc.text(
                        `Page ${i} / ${pageCount}`,
                        doc.internal.pageSize.getWidth() / 2,
                        doc.internal.pageSize.getHeight() - 10,
                        { align: 'center' }
                    );
                }
                
                // Sauvegarder le PDF
                const fileName = `participants_together4peace_${new Date().toISOString().split('T')[0]}.pdf`;
                doc.save(fileName);
            }
            
            // Événement pour le bouton PDF
            if (exportPdfBtn) {
                exportPdfBtn.addEventListener('click', exportParticipantsToPdf);
            }
            
            function switchToSection(sectionName) {
                if (sectionName === 'stats') {
                    if (typeof statsDialog.showModal === 'function') {
                        statsDialog.showModal();
                        renderCharts();
                    }
                    return;
                }
                
                const targetSection = sectionName === 'participants' ? 'participantsSection' : 'sponsorsSection';
                
                tabButtons.forEach((b) => b.classList.remove('active'));
                Object.values(sections).forEach((section) => section.classList.add('section-hidden'));
                
                if (targetSection === 'participantsSection') {
                    const participantsBtn = document.querySelector('.tab-btn[data-target="participantsSection"]');
                    if (participantsBtn) {
                        participantsBtn.classList.add('active');
                    }
                } else {
                    const sponsorsBtn = document.querySelector('.tab-btn[data-target="sponsorsSection"]');
                    if (sponsorsBtn) {
                        sponsorsBtn.classList.add('active');
                    }
                }
                
                if (sections[targetSection]) {
                    sections[targetSection].classList.remove('section-hidden');
                }
                
                sidebarLinks.forEach((link) => link.classList.remove('active'));
                const activeLink = document.querySelector(`.sidebar a[data-tab="${sectionName}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }

            tabButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    tabButtons.forEach((b) => b.classList.remove('active'));
                    btn.classList.add('active');

                    Object.values(sections).forEach((section) => section.classList.add('section-hidden'));
                    const target = btn.dataset.target;
                    if (sections[target]) {
                        sections[target].classList.remove('section-hidden');
                    }
                    
                    sidebarLinks.forEach((link) => link.classList.remove('active'));
                    if (target === 'participantsSection') {
                        const participantsLink = document.querySelector('.sidebar a[data-tab="participants"]');
                        if (participantsLink) participantsLink.classList.add('active');
                    } else if (target === 'sponsorsSection') {
                        const sponsorsLink = document.querySelector('.sidebar a[data-tab="sponsors"]');
                        if (sponsorsLink) sponsorsLink.classList.add('active');
                    }
                });
            });

            sidebarLinks.forEach((link) => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const tabName = link.dataset.tab;
                    switchToSection(tabName);
                });
            });

            function renderCharts() {
                const barCtx = document.getElementById('sponsorsBarChart');
                const pieCtx = document.getElementById('countriesPieChart');

                if (sponsorsChart) {
                    sponsorsChart.destroy();
                }
                if (countriesChart) {
                    countriesChart.destroy();
                }

                sponsorsChart = new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: participantSponsorLabels,
                        datasets: [{
                            label: 'Sponsors',
                            data: participantSponsorValues,
                            backgroundColor: 'rgba(27, 163, 216, 0.6)',
                            borderColor: '#1ba3d8',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });

                countriesChart = new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: countryLabels,
                        datasets: [{
                            data: countryValues,
                            backgroundColor: countryLabels.map((_, idx) => `hsl(${(idx * 57) % 360}, 70%, 60%)`)
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }

            if (openStatsBtn && statsDialog) {
                openStatsBtn.addEventListener('click', () => {
                    if (typeof statsDialog.showModal === 'function') {
                        statsDialog.showModal();
                        renderCharts();
                    }
                });
            }

            if (closeStatsBtn && statsDialog) {
                closeStatsBtn.addEventListener('click', () => statsDialog.close());
            }

            const editDialog = document.getElementById('editSponsorDialog');
            const closeDialogBtn = document.getElementById('closeSponsorDialog');
            const editForm = document.getElementById('editSponsorForm');
            const editFields = {
                id: document.getElementById('edit_sponsor_id'),
                entreprise: document.getElementById('edit_nom_entreprise'),
                email: document.getElementById('edit_contact_email'),
                participant: document.getElementById('edit_participant_id'),
                pays: document.getElementById('edit_pays'),
                montant: document.getElementById('edit_montant'),
                date: document.getElementById('edit_date_sponsorisation')
            };
            const editErrors = {
                nom: document.getElementById('edit_nom_error'),
                email: document.getElementById('edit_email_error')
            };
            const namePattern = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
            const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

            const sanitizeName = (value) => value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s'-]/g, '');
            const sanitizeEmail = (value) => value.replace(/\s+/g, '');

            function setFieldError(input, errorEl, message) {
                if (!input || !errorEl) return;
                if (message) {
                    errorEl.textContent = message;
                    input.classList.add('invalid');
                } else {
                    errorEl.textContent = '';
                    input.classList.remove('invalid');
                }
            }

            function validateNameField() {
                if (!editFields.entreprise) return true;
                const sanitized = sanitizeName(editFields.entreprise.value);
                if (sanitized !== editFields.entreprise.value) {
                    editFields.entreprise.value = sanitized;
                }
                const trimmed = sanitized.trim();
                if (!trimmed) {
                    setFieldError(editFields.entreprise, editErrors.nom, "Le nom de l'entreprise est requis.");
                    return false;
                }
                if (!namePattern.test(trimmed)) {
                    setFieldError(editFields.entreprise, editErrors.nom, 'Uniquement des lettres et espaces.');
                    return false;
                }
                setFieldError(editFields.entreprise, editErrors.nom, '');
                return true;
            }

            function validateEmailField() {
                if (!editFields.email) return true;
                const sanitized = sanitizeEmail(editFields.email.value);
                if (sanitized !== editFields.email.value) {
                    editFields.email.value = sanitized;
                }
                const trimmed = sanitized.trim();
                if (!trimmed) {
                    setFieldError(editFields.email, editErrors.email, "L'email est requis.");
                    return false;
                }
                if (!emailPattern.test(trimmed)) {
                    setFieldError(editFields.email, editErrors.email, "Format d'email invalide.");
                    return false;
                }
                setFieldError(editFields.email, editErrors.email, '');
                return true;
            }

            if (editFields.entreprise) {
                editFields.entreprise.addEventListener('input', validateNameField);
            }
            if (editFields.email) {
                editFields.email.addEventListener('input', validateEmailField);
            }

            if (editForm) {
                editForm.addEventListener('submit', (event) => {
                    const nameValid = validateNameField();
                    const emailValid = validateEmailField();
                    if (!nameValid || !emailValid) {
                        event.preventDefault();
                    }
                });
            }

            document.querySelectorAll('.edit-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    if (!btn.dataset.id) {
                        return;
                    }
                    editForm.reset();
                    editFields.id.value = btn.dataset.id || '';
                    editFields.entreprise.value = btn.dataset.entreprise || '';
                    editFields.email.value = btn.dataset.email || '';
                    editFields.participant.value = btn.dataset.participant || '';
                    editFields.pays.value = btn.dataset.pays || '';
                    editFields.montant.value = btn.dataset.montant || '';
                    editFields.date.value = btn.dataset.date ? btn.dataset.date.replace(' ', 'T') : '';
                    setFieldError(editFields.entreprise, editErrors.nom, '');
                    setFieldError(editFields.email, editErrors.email, '');

                    if (typeof editDialog.showModal === 'function') {
                        editDialog.showModal();
                    }
                });
            });

            if (closeDialogBtn && editDialog) {
                closeDialogBtn.addEventListener('click', () => editDialog.close());
            }

            if (editDialog) {
                editDialog.addEventListener('close', () => {
                    setFieldError(editFields.entreprise, editErrors.nom, '');
                    setFieldError(editFields.email, editErrors.email, '');
                });
            }
        });
    </script>
</body>
</html>

