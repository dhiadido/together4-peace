<?php
require_once dirname(__DIR__) . '/controller/config.php';
require_once dirname(__DIR__) . '/controller/participantC.php';
require_once dirname(__DIR__) . '/controller/sponsorsC.php';

$participantsList = [];
$error = '';
$sponsorFeedback = '';
$participantSponsors = [];

$sponsorsController = new SponsorsController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['ajax'] ?? '') === 'participants') {
    header('Content-Type: application/json; charset=utf-8');
    $searchTerm = trim($_GET['search'] ?? '');

    try {
        $pdo = config::getConnexion();
        if ($searchTerm !== '') {
            $stmt = $pdo->prepare("SELECT * FROM participant WHERE nom LIKE :term OR prenom LIKE :term ORDER BY date_inscription DESC, id DESC");
            $stmt->execute([':term' => '%' . $searchTerm . '%']);
        } else {
            $stmt = $pdo->query("SELECT * FROM participant ORDER BY date_inscription DESC, id DESC");
        }

        $participantsResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sponsorsMap = [];

        if (!empty($participantsResult)) {
            $participantIds = array_column($participantsResult, 'id');
            $participantIdSet = array_flip($participantIds);

            $allSponsors = $sponsorsController->getAllSponsors();
            foreach ($allSponsors as $sponsorObj) {
                $pid = (int) $sponsorObj->getParticipantId();
                if (!isset($participantIdSet[$pid])) {
                    continue;
                }
                if (!isset($sponsorsMap[$pid])) {
                    $sponsorsMap[$pid] = [];
                }
                $sponsorsMap[$pid][] = [
                    'company' => $sponsorObj->getNomEntreprise(),
                    'email' => $sponsorObj->getContactEmail(),
                    'country' => $sponsorObj->getPays(),
                    'amount' => $sponsorObj->getMontant(),
                    'date' => $sponsorObj->getDateSponsorisation()
                ];
            }
        }

        echo json_encode([
            'participants' => $participantsResult,
            'sponsors' => $sponsorsMap
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Impossible de charger les participants : ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

$countries = [
    ['code' => 'AF', 'name' => 'Afghanistan', 'flag' => '🇦🇫'],
    ['code' => 'AL', 'name' => 'Albanie', 'flag' => '🇦🇱'],
    ['code' => 'DZ', 'name' => 'Algérie', 'flag' => '🇩🇿'],
    ['code' => 'AS', 'name' => 'Samoa américaines', 'flag' => '🇦🇸'],
    ['code' => 'AD', 'name' => 'Andorre', 'flag' => '🇦🇩'],
    ['code' => 'AO', 'name' => 'Angola', 'flag' => '🇦🇴'],
    ['code' => 'AI', 'name' => 'Anguilla', 'flag' => '🇦🇮'],
    ['code' => 'AQ', 'name' => 'Antarctique', 'flag' => '🇦🇶'],
    ['code' => 'AG', 'name' => 'Antigua-et-Barbuda', 'flag' => '🇦🇬'],
    ['code' => 'AR', 'name' => 'Argentine', 'flag' => '🇦🇷'],
    ['code' => 'AM', 'name' => 'Arménie', 'flag' => '🇦🇲'],
    ['code' => 'AW', 'name' => 'Aruba', 'flag' => '🇦🇼'],
    ['code' => 'AU', 'name' => 'Australie', 'flag' => '🇦🇺'],
    ['code' => 'AT', 'name' => 'Autriche', 'flag' => '🇦🇹'],
    ['code' => 'AZ', 'name' => 'Azerbaïdjan', 'flag' => '🇦🇿'],
    ['code' => 'BS', 'name' => 'Bahamas', 'flag' => '🇧🇸'],
    ['code' => 'BH', 'name' => 'Bahreïn', 'flag' => '🇧🇭'],
    ['code' => 'BD', 'name' => 'Bangladesh', 'flag' => '🇧🇩'],
    ['code' => 'BB', 'name' => 'Barbade', 'flag' => '🇧🇧'],
    ['code' => 'BY', 'name' => 'Biélorussie', 'flag' => '🇧🇾'],
    ['code' => 'BE', 'name' => 'Belgique', 'flag' => '🇧🇪'],
    ['code' => 'BZ', 'name' => 'Belize', 'flag' => '🇧🇿'],
    ['code' => 'BJ', 'name' => 'Bénin', 'flag' => '🇧🇯'],
    ['code' => 'BM', 'name' => 'Bermudes', 'flag' => '🇧🇲'],
    ['code' => 'BT', 'name' => 'Bhoutan', 'flag' => '🇧🇹'],
    ['code' => 'BO', 'name' => 'Bolivie', 'flag' => '🇧🇴'],
    ['code' => 'BQ', 'name' => 'Pays-Bas caribéens', 'flag' => '🇧🇶'],
    ['code' => 'BA', 'name' => 'Bosnie-Herzégovine', 'flag' => '🇧🇦'],
    ['code' => 'BW', 'name' => 'Botswana', 'flag' => '🇧🇼'],
    ['code' => 'BV', 'name' => 'Île Bouvet', 'flag' => '🇧🇻'],
    ['code' => 'BR', 'name' => 'Brésil', 'flag' => '🇧🇷'],
    ['code' => 'IO', 'name' => "Territoire britannique de l’océan Indien", 'flag' => '🇮🇴'],
    ['code' => 'BN', 'name' => 'Brunei', 'flag' => '🇧🇳'],
    ['code' => 'BG', 'name' => 'Bulgarie', 'flag' => '🇧🇬'],
    ['code' => 'BF', 'name' => 'Burkina Faso', 'flag' => '🇧🇫'],
    ['code' => 'BI', 'name' => 'Burundi', 'flag' => '🇧🇮'],
    ['code' => 'KH', 'name' => 'Cambodge', 'flag' => '🇰🇭'],
    ['code' => 'CM', 'name' => 'Cameroun', 'flag' => '🇨🇲'],
    ['code' => 'CA', 'name' => 'Canada', 'flag' => '🇨🇦'],
    ['code' => 'CV', 'name' => 'Cap-Vert', 'flag' => '🇨🇻'],
    ['code' => 'KY', 'name' => 'Îles Caïmans', 'flag' => '🇰🇾'],
    ['code' => 'CF', 'name' => 'République centrafricaine', 'flag' => '🇨🇫'],
    ['code' => 'TD', 'name' => 'Tchad', 'flag' => '🇹🇩'],
    ['code' => 'CL', 'name' => 'Chili', 'flag' => '🇨🇱'],
    ['code' => 'CN', 'name' => 'Chine', 'flag' => '🇨🇳'],
    ['code' => 'CX', 'name' => 'Île Christmas', 'flag' => '🇨🇽'],
    ['code' => 'CC', 'name' => 'Îles Cocos', 'flag' => '🇨🇨'],
    ['code' => 'CO', 'name' => 'Colombie', 'flag' => '🇨🇴'],
    ['code' => 'KM', 'name' => 'Comores', 'flag' => '🇰🇲'],
    ['code' => 'CG', 'name' => 'Congo', 'flag' => '🇨🇬'],
    ['code' => 'CD', 'name' => 'République démocratique du Congo', 'flag' => '🇨🇩'],
    ['code' => 'CK', 'name' => 'Îles Cook', 'flag' => '🇨🇰'],
    ['code' => 'CR', 'name' => 'Costa Rica', 'flag' => '🇨🇷'],
    ['code' => 'HR', 'name' => 'Croatie', 'flag' => '🇭🇷'],
    ['code' => 'CU', 'name' => 'Cuba', 'flag' => '🇨🇺'],
    ['code' => 'CW', 'name' => 'Curaçao', 'flag' => '🇨🇼'],
    ['code' => 'CY', 'name' => 'Chypre', 'flag' => '🇨🇾'],
    ['code' => 'CZ', 'name' => 'Tchéquie', 'flag' => '🇨🇿'],
    ['code' => 'DK', 'name' => 'Danemark', 'flag' => '🇩🇰'],
    ['code' => 'DJ', 'name' => 'Djibouti', 'flag' => '🇩🇯'],
    ['code' => 'DM', 'name' => 'Dominique', 'flag' => '🇩🇲'],
    ['code' => 'DO', 'name' => 'République dominicaine', 'flag' => '🇩🇴'],
    ['code' => 'EC', 'name' => 'Équateur', 'flag' => '🇪🇨'],
    ['code' => 'EG', 'name' => 'Égypte', 'flag' => '🇪🇬'],
    ['code' => 'SV', 'name' => 'Salvador', 'flag' => '🇸🇻'],
    ['code' => 'GQ', 'name' => 'Guinée équatoriale', 'flag' => '🇬🇶'],
    ['code' => 'ER', 'name' => 'Érythrée', 'flag' => '🇪🇷'],
    ['code' => 'EE', 'name' => 'Estonie', 'flag' => '🇪🇪'],
    ['code' => 'SZ', 'name' => 'Eswatini', 'flag' => '🇸🇿'],
    ['code' => 'ET', 'name' => 'Éthiopie', 'flag' => '🇪🇹'],
    ['code' => 'FK', 'name' => 'Îles Falkland', 'flag' => '🇫🇰'],
    ['code' => 'FO', 'name' => 'Îles Féroé', 'flag' => '🇫🇴'],
    ['code' => 'FJ', 'name' => 'Fidji', 'flag' => '🇫🇯'],
    ['code' => 'FI', 'name' => 'Finlande', 'flag' => '🇫🇮'],
    ['code' => 'FR', 'name' => 'France', 'flag' => '🇫🇷'],
    ['code' => 'GF', 'name' => 'Guyane française', 'flag' => '🇬🇫'],
    ['code' => 'PF', 'name' => 'Polynésie française', 'flag' => '🇵🇫'],
    ['code' => 'TF', 'name' => 'Terres australes françaises', 'flag' => '🇹🇫'],
    ['code' => 'GA', 'name' => 'Gabon', 'flag' => '🇬🇦'],
    ['code' => 'GM', 'name' => 'Gambie', 'flag' => '🇬🇲'],
    ['code' => 'GE', 'name' => 'Géorgie', 'flag' => '🇬🇪'],
    ['code' => 'DE', 'name' => 'Allemagne', 'flag' => '🇩🇪'],
    ['code' => 'GH', 'name' => 'Ghana', 'flag' => '🇬🇭'],
    ['code' => 'GI', 'name' => 'Gibraltar', 'flag' => '🇬🇮'],
    ['code' => 'GR', 'name' => 'Grèce', 'flag' => '🇬🇷'],
    ['code' => 'GL', 'name' => 'Groenland', 'flag' => '🇬🇱'],
    ['code' => 'GD', 'name' => 'Grenade', 'flag' => '🇬🇩'],
    ['code' => 'GP', 'name' => 'Guadeloupe', 'flag' => '🇬🇵'],
    ['code' => 'GU', 'name' => 'Guam', 'flag' => '🇬🇺'],
    ['code' => 'GT', 'name' => 'Guatemala', 'flag' => '🇬🇹'],
    ['code' => 'GG', 'name' => 'Guernesey', 'flag' => '🇬🇬'],
    ['code' => 'GN', 'name' => 'Guinée', 'flag' => '🇬🇳'],
    ['code' => 'GW', 'name' => 'Guinée-Bissau', 'flag' => '🇬🇼'],
    ['code' => 'GY', 'name' => 'Guyana', 'flag' => '🇬🇾'],
    ['code' => 'HT', 'name' => 'Haïti', 'flag' => '🇭🇹'],
    ['code' => 'HM', 'name' => 'Îles Heard-et-MacDonald', 'flag' => '🇭🇲'],
    ['code' => 'VA', 'name' => 'Vatican', 'flag' => '🇻🇦'],
    ['code' => 'HN', 'name' => 'Honduras', 'flag' => '🇭🇳'],
    ['code' => 'HK', 'name' => 'Hong Kong', 'flag' => '🇭🇰'],
    ['code' => 'HU', 'name' => 'Hongrie', 'flag' => '🇭🇺'],
    ['code' => 'IS', 'name' => 'Islande', 'flag' => '🇮🇸'],
    ['code' => 'IN', 'name' => 'Inde', 'flag' => '🇮🇳'],
    ['code' => 'ID', 'name' => 'Indonésie', 'flag' => '🇮🇩'],
    ['code' => 'IR', 'name' => 'Iran', 'flag' => '🇮🇷'],
    ['code' => 'IQ', 'name' => 'Irak', 'flag' => '🇮🇶'],
    ['code' => 'IE', 'name' => 'Irlande', 'flag' => '🇮🇪'],
    ['code' => 'IM', 'name' => 'Île de Man', 'flag' => '🇮🇲'],
    ['code' => 'IL', 'name' => 'Israël', 'flag' => '🇮🇱'],
    ['code' => 'IT', 'name' => 'Italie', 'flag' => '🇮🇹'],
    ['code' => 'JM', 'name' => 'Jamaïque', 'flag' => '🇯🇲'],
    ['code' => 'JP', 'name' => 'Japon', 'flag' => '🇯🇵'],
    ['code' => 'JE', 'name' => 'Jersey', 'flag' => '🇯🇪'],
    ['code' => 'JO', 'name' => 'Jordanie', 'flag' => '🇯🇴'],
    ['code' => 'KZ', 'name' => 'Kazakhstan', 'flag' => '🇰🇿'],
    ['code' => 'KE', 'name' => 'Kenya', 'flag' => '🇰🇪'],
    ['code' => 'KI', 'name' => 'Kiribati', 'flag' => '🇰🇮'],
    ['code' => 'KP', 'name' => 'Corée du Nord', 'flag' => '🇰🇵'],
    ['code' => 'KR', 'name' => 'Corée du Sud', 'flag' => '🇰🇷'],
    ['code' => 'KW', 'name' => 'Koweït', 'flag' => '🇰🇼'],
    ['code' => 'KG', 'name' => 'Kirghizistan', 'flag' => '🇰🇬'],
    ['code' => 'LA', 'name' => 'Laos', 'flag' => '🇱🇦'],
    ['code' => 'LV', 'name' => 'Lettonie', 'flag' => '🇱🇻'],
    ['code' => 'LB', 'name' => 'Liban', 'flag' => '🇱🇧'],
    ['code' => 'LS', 'name' => 'Lesotho', 'flag' => '🇱🇸'],
    ['code' => 'LR', 'name' => 'Libéria', 'flag' => '🇱🇷'],
    ['code' => 'LY', 'name' => 'Libye', 'flag' => '🇱🇾'],
    ['code' => 'LI', 'name' => 'Liechtenstein', 'flag' => '🇱🇮'],
    ['code' => 'LT', 'name' => 'Lituanie', 'flag' => '🇱🇹'],
    ['code' => 'LU', 'name' => 'Luxembourg', 'flag' => '🇱🇺'],
    ['code' => 'MO', 'name' => 'Macao', 'flag' => '🇲🇴'],
    ['code' => 'MG', 'name' => 'Madagascar', 'flag' => '🇲🇬'],
    ['code' => 'MW', 'name' => 'Malawi', 'flag' => '🇲🇼'],
    ['code' => 'MY', 'name' => 'Malaisie', 'flag' => '🇲🇾'],
    ['code' => 'MV', 'name' => 'Maldives', 'flag' => '🇲🇻'],
    ['code' => 'ML', 'name' => 'Mali', 'flag' => '🇲🇱'],
    ['code' => 'MT', 'name' => 'Malte', 'flag' => '🇲🇹'],
    ['code' => 'MH', 'name' => 'Îles Marshall', 'flag' => '🇲🇭'],
    ['code' => 'MQ', 'name' => 'Martinique', 'flag' => '🇲🇶'],
    ['code' => 'MR', 'name' => 'Mauritanie', 'flag' => '🇲🇷'],
    ['code' => 'MU', 'name' => 'Maurice', 'flag' => '🇲🇺'],
    ['code' => 'YT', 'name' => 'Mayotte', 'flag' => '🇾🇹'],
    ['code' => 'MX', 'name' => 'Mexique', 'flag' => '🇲🇽'],
    ['code' => 'FM', 'name' => 'Micronésie', 'flag' => '🇫🇲'],
    ['code' => 'MD', 'name' => 'Moldavie', 'flag' => '🇲🇩'],
    ['code' => 'MC', 'name' => 'Monaco', 'flag' => '🇲🇨'],
    ['code' => 'MN', 'name' => 'Mongolie', 'flag' => '🇲🇳'],
    ['code' => 'ME', 'name' => 'Monténégro', 'flag' => '🇲🇪'],
    ['code' => 'MS', 'name' => 'Montserrat', 'flag' => '🇲🇸'],
    ['code' => 'MA', 'name' => 'Maroc', 'flag' => '🇲🇦'],
    ['code' => 'MZ', 'name' => 'Mozambique', 'flag' => '🇲🇿'],
    ['code' => 'MM', 'name' => 'Myanmar', 'flag' => '🇲🇲'],
    ['code' => 'NA', 'name' => 'Namibie', 'flag' => '🇳🇦'],
    ['code' => 'NR', 'name' => 'Nauru', 'flag' => '🇳🇷'],
    ['code' => 'NP', 'name' => 'Népal', 'flag' => '🇳🇵'],
    ['code' => 'NL', 'name' => 'Pays-Bas', 'flag' => '🇳🇱'],
    ['code' => 'NC', 'name' => 'Nouvelle-Calédonie', 'flag' => '🇳🇨'],
    ['code' => 'NZ', 'name' => 'Nouvelle-Zélande', 'flag' => '🇳🇿'],
    ['code' => 'NI', 'name' => 'Nicaragua', 'flag' => '🇳🇮'],
    ['code' => 'NE', 'name' => 'Niger', 'flag' => '🇳🇪'],
    ['code' => 'NG', 'name' => 'Nigéria', 'flag' => '🇳🇬'],
    ['code' => 'NU', 'name' => 'Niue', 'flag' => '🇳🇺'],
    ['code' => 'NF', 'name' => 'Île Norfolk', 'flag' => '🇳🇫'],
    ['code' => 'MK', 'name' => 'Macédoine du Nord', 'flag' => '🇲🇰'],
    ['code' => 'MP', 'name' => 'Îles Mariannes du Nord', 'flag' => '🇲🇵'],
    ['code' => 'NO', 'name' => 'Norvège', 'flag' => '🇳🇴'],
    ['code' => 'OM', 'name' => 'Oman', 'flag' => '🇴🇲'],
    ['code' => 'PK', 'name' => 'Pakistan', 'flag' => '🇵🇰'],
    ['code' => 'PW', 'name' => 'Palaos', 'flag' => '🇵🇼'],
    ['code' => 'PS', 'name' => 'Palestine', 'flag' => '🇵🇸'],
    ['code' => 'PA', 'name' => 'Panama', 'flag' => '🇵🇦'],
    ['code' => 'PG', 'name' => 'Papouasie-Nouvelle-Guinée', 'flag' => '🇵🇬'],
    ['code' => 'PY', 'name' => 'Paraguay', 'flag' => '🇵🇾'],
    ['code' => 'PE', 'name' => 'Pérou', 'flag' => '🇵🇪'],
    ['code' => 'PH', 'name' => 'Philippines', 'flag' => '🇵🇭'],
    ['code' => 'PN', 'name' => 'Pitcairn', 'flag' => '🇵🇳'],
    ['code' => 'PL', 'name' => 'Pologne', 'flag' => '🇵🇱'],
    ['code' => 'PT', 'name' => 'Portugal', 'flag' => '🇵🇹'],
    ['code' => 'PR', 'name' => 'Porto Rico', 'flag' => '🇵🇷'],
    ['code' => 'QA', 'name' => 'Qatar', 'flag' => '🇶🇦'],
    ['code' => 'RE', 'name' => 'La Réunion', 'flag' => '🇷🇪'],
    ['code' => 'RO', 'name' => 'Roumanie', 'flag' => '🇷🇴'],
    ['code' => 'RU', 'name' => 'Russie', 'flag' => '🇷🇺'],
    ['code' => 'RW', 'name' => 'Rwanda', 'flag' => '🇷🇼'],
    ['code' => 'BL', 'name' => 'Saint-Barthélemy', 'flag' => '🇧🇱'],
    ['code' => 'SH', 'name' => 'Sainte-Hélène', 'flag' => '🇸🇭'],
    ['code' => 'KN', 'name' => 'Saint-Christophe-et-Niévès', 'flag' => '🇰🇳'],
    ['code' => 'LC', 'name' => 'Sainte-Lucie', 'flag' => '🇱🇨'],
    ['code' => 'MF', 'name' => 'Saint-Martin (France)', 'flag' => '🇲🇫'],
    ['code' => 'PM', 'name' => 'Saint-Pierre-et-Miquelon', 'flag' => '🇵🇲'],
    ['code' => 'VC', 'name' => 'Saint-Vincent-et-les-Grenadines', 'flag' => '🇻🇨'],
    ['code' => 'WS', 'name' => 'Samoa', 'flag' => '🇼🇸'],
    ['code' => 'SM', 'name' => 'Saint-Marin', 'flag' => '🇸🇲'],
    ['code' => 'ST', 'name' => 'Sao Tomé-et-Principe', 'flag' => '🇸🇹'],
    ['code' => 'SA', 'name' => 'Arabie saoudite', 'flag' => '🇸🇦'],
    ['code' => 'SN', 'name' => 'Sénégal', 'flag' => '🇸🇳'],
    ['code' => 'RS', 'name' => 'Serbie', 'flag' => '🇷🇸'],
    ['code' => 'SC', 'name' => 'Seychelles', 'flag' => '🇸🇨'],
    ['code' => 'SL', 'name' => 'Sierra Leone', 'flag' => '🇸🇱'],
    ['code' => 'SG', 'name' => 'Singapour', 'flag' => '🇸🇬'],
    ['code' => 'SX', 'name' => 'Saint-Martin (Pays-Bas)', 'flag' => '🇸🇽'],
    ['code' => 'SK', 'name' => 'Slovaquie', 'flag' => '🇸🇰'],
    ['code' => 'SI', 'name' => 'Slovénie', 'flag' => '🇸🇮'],
    ['code' => 'SB', 'name' => 'Îles Salomon', 'flag' => '🇸🇧'],
    ['code' => 'SO', 'name' => 'Somalie', 'flag' => '🇸🇴'],
    ['code' => 'ZA', 'name' => 'Afrique du Sud', 'flag' => '🇿🇦'],
    ['code' => 'GS', 'name' => 'Géorgie du Sud-et-les îles Sandwich du Sud', 'flag' => '🇬🇸'],
    ['code' => 'SS', 'name' => 'Soudan du Sud', 'flag' => '🇸🇸'],
    ['code' => 'ES', 'name' => 'Espagne', 'flag' => '🇪🇸'],
    ['code' => 'LK', 'name' => 'Sri Lanka', 'flag' => '🇱🇰'],
    ['code' => 'SD', 'name' => 'Soudan', 'flag' => '🇸🇩'],
    ['code' => 'SR', 'name' => 'Suriname', 'flag' => '🇸🇷'],
    ['code' => 'SJ', 'name' => 'Svalbard et Jan Mayen', 'flag' => '🇸🇯'],
    ['code' => 'SE', 'name' => 'Suède', 'flag' => '🇸🇪'],
    ['code' => 'CH', 'name' => 'Suisse', 'flag' => '🇨🇭'],
    ['code' => 'SY', 'name' => 'Syrie', 'flag' => '🇸🇾'],
    ['code' => 'TW', 'name' => 'Taïwan', 'flag' => '🇹🇼'],
    ['code' => 'TJ', 'name' => 'Tadjikistan', 'flag' => '🇹🇯'],
    ['code' => 'TZ', 'name' => 'Tanzanie', 'flag' => '🇹🇿'],
    ['code' => 'TH', 'name' => 'Thaïlande', 'flag' => '🇹🇭'],
    ['code' => 'TL', 'name' => 'Timor oriental', 'flag' => '🇹🇱'],
    ['code' => 'TG', 'name' => 'Togo', 'flag' => '🇹🇬'],
    ['code' => 'TK', 'name' => 'Tokelau', 'flag' => '🇹🇰'],
    ['code' => 'TO', 'name' => 'Tonga', 'flag' => '🇹🇴'],
    ['code' => 'TT', 'name' => 'Trinité-et-Tobago', 'flag' => '🇹🇹'],
    ['code' => 'TN', 'name' => 'Tunisie', 'flag' => '🇹🇳'],
    ['code' => 'TR', 'name' => 'Turquie', 'flag' => '🇹🇷'],
    ['code' => 'TM', 'name' => 'Turkménistan', 'flag' => '🇹🇲'],
    ['code' => 'TC', 'name' => 'Îles Turques-et-Caïques', 'flag' => '🇹🇨'],
    ['code' => 'TV', 'name' => 'Tuvalu', 'flag' => '🇹🇻'],
    ['code' => 'UG', 'name' => 'Ouganda', 'flag' => '🇺🇬'],
    ['code' => 'UA', 'name' => 'Ukraine', 'flag' => '🇺🇦'],
    ['code' => 'AE', 'name' => 'Émirats arabes unis', 'flag' => '🇦🇪'],
    ['code' => 'GB', 'name' => 'Royaume-Uni', 'flag' => '🇬🇧'],
    ['code' => 'US', 'name' => 'États-Unis', 'flag' => '🇺🇸'],
    ['code' => 'UM', 'name' => 'Îles mineures éloignées des États-Unis', 'flag' => '🇺🇲'],
    ['code' => 'UY', 'name' => 'Uruguay', 'flag' => '🇺🇾'],
    ['code' => 'UZ', 'name' => 'Ouzbékistan', 'flag' => '🇺🇿'],
    ['code' => 'VU', 'name' => 'Vanuatu', 'flag' => '🇻🇺'],
    ['code' => 'VE', 'name' => 'Venezuela', 'flag' => '🇻🇪'],
    ['code' => 'VN', 'name' => 'Vietnam', 'flag' => '🇻🇳'],
    ['code' => 'VG', 'name' => 'Îles Vierges britanniques', 'flag' => '🇻🇬'],
    ['code' => 'VI', 'name' => 'Îles Vierges américaines', 'flag' => '🇻🇮'],
    ['code' => 'WF', 'name' => 'Wallis-et-Futuna', 'flag' => '🇼🇫'],
    ['code' => 'EH', 'name' => 'Sahara occidental', 'flag' => '🇪🇭'],
    ['code' => 'YE', 'name' => 'Yémen', 'flag' => '🇾🇪'],
    ['code' => 'ZM', 'name' => 'Zambie', 'flag' => '🇿🇲'],
    ['code' => 'ZW', 'name' => 'Zimbabwe', 'flag' => '🇿🇼'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'sponsor') {
    $participantId = isset($_POST['participant_id']) ? (int) $_POST['participant_id'] : 0;
    $nomEntreprise = trim($_POST['nom_entreprise'] ?? '');
    $contactEmail = trim($_POST['contact_email'] ?? '');
    $pays = trim($_POST['pays'] ?? '');
    $montant = $_POST['montant'] ?? null;
    $dateSponsorisation = $_POST['date_sponsorisation'] ?? null;

    try {
        if ($participantId <= 0) {
            throw new InvalidArgumentException("Participant invalide.");
        }
        if (empty($nomEntreprise) || empty($contactEmail)) {
            throw new InvalidArgumentException("Merci de renseigner le nom de l'entreprise et le contact email.");
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
            $montant !== '' ? (float) $montant : null,
            $dateSponsorisation ?: null
        );

        $sponsorsController->createSponsor($sponsor);
        $sponsorFeedback = 'success:Merci pour votre sponsorisation !';
    } catch (Exception $e) {
        $sponsorFeedback = 'error:' . $e->getMessage();
    }
}

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->query("SELECT * FROM participant ORDER BY date_inscription DESC, id DESC");
    $participantsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Impossible de charger les participants : ' . $e->getMessage();
}

try {
    $allSponsors = $sponsorsController->getAllSponsors();
    foreach ($allSponsors as $sponsorObj) {
        $pid = (int) $sponsorObj->getParticipantId();
        if (!isset($participantSponsors[$pid])) {
            $participantSponsors[$pid] = [];
        }
        $participantSponsors[$pid][] = [
            'company' => $sponsorObj->getNomEntreprise(),
            'email' => $sponsorObj->getContactEmail(),
            'country' => $sponsorObj->getPays(),
            'amount' => $sponsorObj->getMontant(),
            'date' => $sponsorObj->getDateSponsorisation()
        ];
    }
} catch (Exception $e) {
    if (empty($sponsorFeedback)) {
        $sponsorFeedback = 'error:Impossible de charger les sponsors : ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Together4Peace - Bâtir des Ponts. Non des Murs.</title>
    <link rel="stylesheet" href="front/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --peace-navy: #1f4b6e;
            --peace-green: #34a853;
            --peace-blue: #1ba3d8;
            --peace-orange: #f28c28;
            --bg-cream: #fdf9f4;
            --text-dark: #1f2933;
            --border-soft: #e5e7eb;
        }
        * {
            box-sizing: border-box;
        }
        /* Override body background to match index.html */
        body {
            background-color: #fff;
        }
        /* Additional styles for participants section and cards */
        .participants-section {
            padding: 40px 5%;
            max-width: 1400px;
            margin: 0 auto;
        }
        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .section-header h2 {
            font-size: 2rem;
            color: var(--peace-navy);
            margin: 0 0 10px;
        }
        .section-header p {
            color: #54616d;
            font-size: 1.05rem;
        }
        .search-bar {
            max-width: 420px;
            margin: 0 auto 30px;
            position: relative;
        }
        .search-bar input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 999px;
            border: 1px solid var(--border-soft);
            font-size: 1rem;
            box-shadow: 0 6px 18px rgba(31, 75, 110, 0.08);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .search-bar input:focus {
            outline: none;
            border-color: var(--peace-blue);
            box-shadow: 0 10px 24px rgba(27, 163, 216, 0.2);
        }
        .filters {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .filter-btn {
            border: 1px solid rgba(31, 75, 110, 0.3);
            background-color: transparent;
            color: var(--peace-navy);
            padding: 8px 18px;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }
        .filter-btn.active {
            background-color: var(--peace-navy);
            color: #fff;
            border-color: var(--peace-navy);
            box-shadow: 0 6px 18px rgba(31, 75, 110, 0.25);
        }
        .sort-controls {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .sort-btn {
            border: 1px solid rgba(27, 163, 216, 0.4);
            background-color: transparent;
            color: var(--peace-blue);
            padding: 8px 18px;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }
        .sort-btn.active {
            background-color: var(--peace-blue);
            color: #fff;
            border-color: var(--peace-blue);
            box-shadow: 0 6px 18px rgba(27, 163, 216, 0.25);
        }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .pagination button {
            border: 1px solid rgba(31, 75, 110, 0.3);
            background-color: transparent;
            color: var(--peace-navy);
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .pagination button.active {
            background-color: var(--peace-navy);
            color: #fff;
        }
        .pagination button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        .pagination span {
            font-weight: 600;
            color: #475569;
        }
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }
        .participant-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(31, 75, 110, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .participant-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(31, 75, 110, 0.15);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border-soft);
        }
        .card-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--peace-blue), var(--peace-green));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .card-name {
            flex: 1;
        }
        .card-name h3 {
            margin: 0;
            font-size: 1.2rem;
            color: var(--peace-navy);
        }
        .card-name p {
            margin: 4px 0 0;
            color: #54616d;
            font-size: 0.9rem;
        }
        .card-body {
            flex: 1;
            margin-bottom: 16px;
        }
        .card-info {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: #54616d;
            font-size: 0.95rem;
        }
        .card-info-icon {
            color: var(--peace-blue);
            font-size: 1.1rem;
        }
        .card-temoignage {
            background-color: #f8f9fa;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid var(--peace-blue);
            margin-top: 16px;
            font-style: italic;
            color: #374151;
            line-height: 1.6;
        }
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid var(--border-soft);
            font-size: 0.85rem;
            color: #6b7280;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(31, 75, 110, 0.1);
        }
        .empty-state h3 {
            color: var(--peace-navy);
            margin-bottom: 10px;
        }
        .empty-state p {
            color: #54616d;
        }
        .muted {
            color: #6b7280;
            font-style: italic;
        }
        .feedback-message {
            max-width: 600px;
            margin: 25px auto;
            padding: 16px 20px;
            border-radius: 10px;
            font-weight: 600;
            text-align: center;
        }
        .feedback-success {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #6ee7b7;
        }
        .feedback-error {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .sponsor-btn {
            background-color: var(--peace-orange);
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .sponsor-btn:hover {
            background-color: #d9791f;
        }
        .view-sponsors-btn {
            background-color: transparent;
            color: var(--peace-blue);
            border: 1px solid rgba(27, 163, 216, 0.4);
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .view-sponsors-btn:hover {
            background-color: rgba(27, 163, 216, 0.08);
            color: var(--peace-blue);
        }
        .qr-code-btn {
            background-color: var(--peace-green);
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .qr-code-btn:hover {
            background-color: #2d8f47;
        }
        dialog {
            border: none;
            border-radius: 14px;
            padding: 0;
            width: min(520px, 95vw);
            box-shadow: 0 20px 60px rgba(16, 43, 68, 0.25);
        }
        dialog::backdrop {
            background: rgba(0, 0, 0, 0.45);
        }
        .dialog-content {
            padding: 24px 28px 30px;
        }
        .dialog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .dialog-header h2 {
            margin: 0;
            color: var(--peace-navy);
        }
        .ghost-button {
            background: transparent;
            border: none;
            font-size: 1.8rem;
            line-height: 1;
            cursor: pointer;
            color: #475569;
        }
        .dialog-participant-name {
            margin: 6px 0 18px;
            color: #54616d;
        }
        .sponsors-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-height: 320px;
            overflow-y: auto;
        }
        .sponsor-entry {
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            padding: 12px 14px;
            background-color: #f9fafb;
        }
        .sponsor-entry strong {
            color: var(--peace-navy);
        }
        .dialog-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .dialog-actions button {
            min-width: 140px;
        }
        .dialog-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .dialog-form label {
            font-weight: 600;
            color: var(--peace-navy);
            font-size: 0.95rem;
        }
        .dialog-form input,
        .dialog-form select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid var(--border-soft);
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background-color: #fdfefe;
        }
        .dialog-form input:focus,
        .dialog-form select:focus {
            outline: none;
            border-color: var(--peace-blue);
            box-shadow: 0 0 0 2px rgba(27, 163, 216, 0.15);
        }
        .error-text {
            color: #d9534f;
            font-size: 0.85rem;
            min-height: 18px;
            margin-top: -8px;
        }
        .invalid {
            border-color: #d9534f !important;
            background-color: #fff5f5;
        }
        .error-message {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 5%;
        }
        /* Footer styles are now in styles.css */
        @media (max-width: 768px) {
            .cards-container {
                grid-template-columns: 1fr;
            }
            .hero h1 {
                font-size: 1.8rem;
            }
            .navbar {
                flex-direction: column;
                gap: 12px;
            }
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
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
            .footer-content {
                flex-direction: column;
                gap: 30px;
            }
        }
        /* Chatbot styles */
        .chatbot-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--peace-blue), var(--peace-green));
            color: white;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(27, 163, 216, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .chatbot-button:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 32px rgba(27, 163, 216, 0.5);
        }
        .chatbot-button .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc2626;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        #chatbotDialog {
            width: min(420px, 95vw);
            max-height: 85vh;
            border-radius: 16px;
        }
        .chatbot-header {
            background: linear-gradient(135deg, var(--peace-blue), var(--peace-green));
            color: white;
            padding: 20px;
            border-radius: 16px 16px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chatbot-header h3 {
            margin: 0;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .chatbot-header .status {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-left: 10px;
        }
        .chatbot-messages {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background-color: #f9fafb;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .chatbot-message {
            display: flex;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .chatbot-message.user {
            flex-direction: row-reverse;
        }
        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .chatbot-message.bot .message-avatar {
            background: linear-gradient(135deg, var(--peace-blue), var(--peace-green));
            color: white;
        }
        .chatbot-message.user .message-avatar {
            background-color: var(--peace-navy);
            color: white;
        }
        .message-content {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 12px;
            line-height: 1.5;
            font-size: 0.95rem;
        }
        .chatbot-message.bot .message-content {
            background-color: white;
            color: var(--text-dark);
            border: 1px solid var(--border-soft);
        }
        .chatbot-message.user .message-content {
            background: linear-gradient(135deg, var(--peace-blue), var(--peace-green));
            color: white;
        }
        .chatbot-input-area {
            padding: 16px;
            background-color: white;
            border-top: 1px solid var(--border-soft);
            display: flex;
            gap: 10px;
        }
        .chatbot-input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid var(--border-soft);
            border-radius: 24px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s ease;
        }
        .chatbot-input:focus {
            border-color: var(--peace-blue);
        }
        .chatbot-send-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--peace-blue), var(--peace-green));
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }
        .chatbot-send-btn:hover {
            transform: scale(1.1);
        }
        .quick-questions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
        }
        .quick-question-btn {
            background-color: white;
            border: 1px solid var(--border-soft);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            text-align: left;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .quick-question-btn:hover {
            background-color: #f3f4f6;
            border-color: var(--peace-blue);
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
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <a href="back.php" class="btn btn-donate">Espace Admin</a>
    </header>

    <section class="hero-section">
        <div class="hero-content">
            <h1>Nos Participants</h1>
            <p>Découvrez les voix inspirantes qui rejoignent notre mouvement pour la paix</p>
            <div class="hero-actions">
                <a href="front.php" class="btn btn-primary">Ajouter un participant</a>
            </div>
        </div>
    </section>

    <section class="participants-section">
        <?php if (!empty($sponsorFeedback)):
            [$feedbackType, $feedbackText] = explode(':', $sponsorFeedback, 2);
            $feedbackClass = $feedbackType === 'success' ? 'feedback-success' : 'feedback-error';
        ?>
            <div class="feedback-message <?php echo $feedbackClass; ?>">
                <?php echo htmlspecialchars($feedbackText); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php else: ?>
            <div class="section-header">
                <h2 id="participantsCount"><?php echo count($participantsList); ?> Participant<?php echo count($participantsList) > 1 ? 's' : ''; ?></h2>
                <p>Rejoignez ces personnes passionnées qui partagent leur engagement pour la paix</p>
            </div>

            <div class="search-bar">
                <input type="text" id="participantSearch" placeholder="Rechercher un participant par nom...">
            </div>

            <div class="filters" id="participantsFilters">
                <button class="filter-btn active" data-filter="all">Tous</button>
                <button class="filter-btn" data-filter="sponsored">Sponsorisés</button>
                <button class="filter-btn" data-filter="unsponsored">Non sponsorisés</button>
            </div>

            <div class="sort-controls" id="participantsSort">
                <button class="sort-btn" data-sort="asc">Nom A → Z</button>
                <button class="sort-btn" data-sort="desc">Nom Z → A</button>
            </div>

            <div id="participantsContainer">
                <?php if (empty($participantsList)): ?>
                    <div class="empty-state">
                        <h3>Aucun participant pour le moment</h3>
                        <p>Soyez le premier à rejoindre notre communauté !</p>
                    </div>
                <?php else: ?>
                    <div class="cards-container">
                        <?php foreach ($participantsList as $participant): ?>
                            <div class="participant-card">
                                <div class="card-header">
                                    <div class="card-avatar">
                                        <?php 
                                            $initials = strtoupper(substr($participant['nom'], 0, 1) . substr($participant['prenom'], 0, 1));
                                            echo htmlspecialchars($initials);
                                        ?>
                                    </div>
                                    <div class="card-name">
                                        <h3><?php echo htmlspecialchars($participant['nom'] . ' ' . $participant['prenom']); ?></h3>
                                        <p><?php echo htmlspecialchars($participant['email']); ?></p>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <?php if (!empty($participant['pays'])): ?>
                                        <div class="card-info">
                                            <span class="card-info-icon">🌍</span>
                                            <span><?php echo htmlspecialchars($participant['pays']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($participant['langue_preferee'])): ?>
                                        <div class="card-info">
                                            <span class="card-info-icon">🗣️</span>
                                            <span><?php echo htmlspecialchars($participant['langue_preferee']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($participant['temoignage'])): ?>
                                        <div class="card-temoignage">
                                            "<?php echo nl2br(htmlspecialchars($participant['temoignage'])); ?>"
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-footer">
                                    <span>
                                        Rejoint le 
                                        <?php
                                            $dateValue = $participant['date_inscription'] ?? '';
                                            echo $dateValue ? htmlspecialchars(date('d/m/Y', strtotime($dateValue))) : '—';
                                        ?>
                                    </span>
                                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                                        <button
                                            type="button"
                                            class="qr-code-btn"
                                            data-id="<?php echo htmlspecialchars($participant['id']); ?>"
                                            data-nom="<?php echo htmlspecialchars($participant['nom'], ENT_QUOTES); ?>"
                                            data-prenom="<?php echo htmlspecialchars($participant['prenom'], ENT_QUOTES); ?>"
                                            data-email="<?php echo htmlspecialchars($participant['email'], ENT_QUOTES); ?>"
                                            data-pays="<?php echo htmlspecialchars($participant['pays'] ?? '', ENT_QUOTES); ?>"
                                            data-langue="<?php echo htmlspecialchars($participant['langue_preferee'] ?? '', ENT_QUOTES); ?>"
                                            data-temoignage="<?php echo htmlspecialchars($participant['temoignage'] ?? '', ENT_QUOTES); ?>"
                                            data-date="<?php echo htmlspecialchars($participant['date_inscription'] ?? '', ENT_QUOTES); ?>"
                                        >
                                            <i class="fas fa-qrcode"></i> QR Code
                                        </button>
                                        <button
                                            type="button"
                                            class="view-sponsors-btn"
                                            data-id="<?php echo htmlspecialchars($participant['id']); ?>"
                                            data-name="<?php echo htmlspecialchars($participant['nom'] . ' ' . $participant['prenom'], ENT_QUOTES); ?>"
                                        >
                                            Voir sponsors
                                        </button>
                                        <button
                                            type="button"
                                            class="sponsor-btn"
                                            data-id="<?php echo htmlspecialchars($participant['id']); ?>"
                                            data-name="<?php echo htmlspecialchars($participant['nom'] . ' ' . $participant['prenom'], ENT_QUOTES); ?>"
                                        >
                                            Sponsoriser
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div id="paginationControls" class="pagination"></div>
        <?php endif; ?>
    </section>

    <dialog id="sponsorDialog">
        <div class="dialog-content">
            <div class="dialog-header">
                <h2>Sponsoriser un participant</h2>
                <button type="button" class="ghost-button" id="closeSponsorDialog" aria-label="Fermer">&times;</button>
            </div>
            <p class="dialog-participant-name" id="sponsorParticipantLabel"></p>
            <form method="POST" id="sponsorForm" class="dialog-form" novalidate>
                <input type="hidden" name="form_type" value="sponsor">
                <input type="hidden" name="participant_id" id="sponsor_participant_id">

                <label for="nom_entreprise">Nom de l'entreprise *</label>
                <input type="text" id="nom_entreprise" name="nom_entreprise">
                <small class="error-text" id="sponsor_nom_error"></small>

                <label for="contact_email">Email de contact *</label>
                <input type="text" id="contact_email" name="contact_email">
                <small class="error-text" id="sponsor_email_error"></small>

                <label for="pays_sponsor">Pays</label>
                <select id="pays_sponsor" name="pays">
                    <option value="">--Choisir un pays--</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo htmlspecialchars($country['name']); ?>">
                            <?php echo htmlspecialchars($country['flag'] . ' ' . $country['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="montant">Montant (USD)</label>
                <input type="number" id="montant" name="montant" min="0" step="0.01" placeholder="Ex : 2500">

                <label for="date_sponsorisation">Date de sponsorisation</label>
                <input type="datetime-local" id="date_sponsorisation" name="date_sponsorisation">

                <div class="dialog-actions">
                    <button type="submit">Confirmer</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="sponsorListDialog">
        <div class="dialog-content">
            <div class="dialog-header">
                <h2 id="sponsorListTitle">Sponsors du participant</h2>
                <button type="button" class="ghost-button" id="closeSponsorListDialog" aria-label="Fermer">&times;</button>
            </div>
            <p class="dialog-participant-name" id="sponsorListSubtitle"></p>
            <div id="sponsorListContent" class="sponsors-list"></div>
        </div>
    </dialog>

    <dialog id="qrCodeDialog">
        <div class="dialog-content">
            <div class="dialog-header">
                <h2>QR Code du Participant</h2>
                <button type="button" id="closeQrCodeDialog" class="ghost-button" aria-label="Fermer">&times;</button>
            </div>
            <div style="text-align: center; padding: 20px;">
                <p id="qrCodeParticipantName" style="font-size: 1.1rem; font-weight: bold; color: var(--peace-navy); margin-bottom: 20px;"></p>
                <div style="display: inline-block; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <div id="qrcode"></div>
                </div>
                <p style="margin-top: 20px; color: #666; font-size: 0.9rem;">
                    Scannez ce QR code pour obtenir les détails du participant
                </p>
            </div>
        </div>
    </dialog>

    <!-- Chatbot Button -->
    <button type="button" class="chatbot-button" id="openChatbot" aria-label="Ouvrir le chatbot">
        <i class="fas fa-comments"></i>
        <span class="badge">1</span>
    </button>

    <!-- Chatbot Dialog -->
    <dialog id="chatbotDialog">
        <div class="chatbot-header">
            <h3>
                <i class="fas fa-robot"></i>
                Assistant Together4Peace
                <span class="status">• En ligne</span>
            </h3>
            <button type="button" class="ghost-button" id="closeChatbot" aria-label="Fermer" style="color: white;">&times;</button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="chatbot-message bot">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <p>Bonjour ! 👋 Je suis l'assistant de Together4Peace. Comment puis-je vous aider aujourd'hui ?</p>
                    <div class="quick-questions">
                        <button type="button" class="quick-question-btn" data-question="Qu'est-ce que Together4Peace ?">Qu'est-ce que Together4Peace ?</button>
                        <button type="button" class="quick-question-btn" data-question="Comment s'inscrire ?">Comment s'inscrire ?</button>
                        <button type="button" class="quick-question-btn" data-question="Comment sponsoriser un participant ?">Comment sponsoriser un participant ?</button>
                        <button type="button" class="quick-question-btn" data-question="Comment voir les participants ?">Comment voir les participants ?</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="chatbot-input-area">
            <input type="text" id="chatbotInput" class="chatbot-input" placeholder="Tapez votre question..." autocomplete="off">
            <button type="button" class="chatbot-send-btn" id="chatbotSend">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </dialog>

    <footer id="contact">
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
                    <li><a href="#contact">Contact</a></li>
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

    <!-- Bibliothèque QRCode -->
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs@gh-pages/qrcode.min.js"></script>
    <script>
        // Vérifier que QRCode est chargé avant d'exécuter le code
        window.addEventListener('load', function() {
            if (typeof QRCode === 'undefined') {
                console.error('QRCode library not loaded');
            } else {
                console.log('QRCode library loaded successfully');
            }
        });
    </script>
    <script>
        const participantSponsorsInitial = <?php echo json_encode($participantSponsors, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        const participantsInitial = <?php echo json_encode($participantsList, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        document.addEventListener('DOMContentLoaded', () => {
            const sponsorDialog = document.getElementById('sponsorDialog');
            const sponsorParticipantId = document.getElementById('sponsor_participant_id');
            const sponsorParticipantLabel = document.getElementById('sponsorParticipantLabel');
            const closeSponsorDialog = document.getElementById('closeSponsorDialog');
            const sponsorForm = document.getElementById('sponsorForm');
            const sponsorDateInput = document.getElementById('date_sponsorisation');
            const sponsorNameInput = document.getElementById('nom_entreprise');
            const sponsorEmailInput = document.getElementById('contact_email');
            const sponsorErrors = {
                nom: document.getElementById('sponsor_nom_error'),
                email: document.getElementById('sponsor_email_error')
            };
            const sponsorListDialog = document.getElementById('sponsorListDialog');
            const closeSponsorListDialog = document.getElementById('closeSponsorListDialog');
            const sponsorListSubtitle = document.getElementById('sponsorListSubtitle');
            const sponsorListContent = document.getElementById('sponsorListContent');
            const qrCodeDialog = document.getElementById('qrCodeDialog');
            const closeQrCodeDialog = document.getElementById('closeQrCodeDialog');
            const qrCodeContainer = document.getElementById('qrcode');
            const qrCodeParticipantName = document.getElementById('qrCodeParticipantName');
            const participantsCountLabel = document.getElementById('participantsCount');
            const participantsContainer = document.getElementById('participantsContainer');
            const searchInput = document.getElementById('participantSearch');
            const filtersContainer = document.getElementById('participantsFilters');
            const sortContainer = document.getElementById('participantsSort');
            const paginationContainer = document.getElementById('paginationControls');

            const sponsorNamePattern = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
            const sponsorEmailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

            const sanitizeCompanyName = (value) => value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s'-]/g, '');
            const sanitizeEmail = (value) => value.replace(/\s+/g, '');

            let participantsData = Array.isArray(participantsInitial) ? participantsInitial.slice() : [];
            let participantSponsors = participantSponsorsInitial || {};
            let currentFilter = 'all';
            let currentSort = null;
            const ITEMS_PER_PAGE = 2;
            let currentPage = 1;

            function setError(input, errorEl, message) {
                if (!input || !errorEl) return;
                if (message) {
                    errorEl.textContent = message;
                    input.classList.add('invalid');
                } else {
                    errorEl.textContent = '';
                    input.classList.remove('invalid');
                }
            }

            function validateCompanyName() {
                if (!sponsorNameInput) return true;
                const value = sanitizeCompanyName(sponsorNameInput.value);
                if (value !== sponsorNameInput.value) {
                    sponsorNameInput.value = value;
                }
                const trimmed = value.trim();
                if (!trimmed) {
                    setError(sponsorNameInput, sponsorErrors.nom, "Le nom de l'entreprise est requis.");
                    return false;
                }
                if (!sponsorNamePattern.test(trimmed)) {
                    setError(sponsorNameInput, sponsorErrors.nom, "Uniquement des lettres et espaces.");
                    return false;
                }
                setError(sponsorNameInput, sponsorErrors.nom, '');
                return true;
            }

            function validateEmail() {
                if (!sponsorEmailInput) return true;
                const value = sanitizeEmail(sponsorEmailInput.value);
                if (value !== sponsorEmailInput.value) {
                    sponsorEmailInput.value = value;
                }
                const trimmed = value.trim();
                if (!trimmed) {
                    setError(sponsorEmailInput, sponsorErrors.email, "L'email est requis.");
                    return false;
                }
                if (!sponsorEmailPattern.test(trimmed)) {
                    setError(sponsorEmailInput, sponsorErrors.email, "Format d'email invalide.");
                    return false;
                }
                setError(sponsorEmailInput, sponsorErrors.email, '');
                return true;
            }

            if (sponsorNameInput) {
                sponsorNameInput.addEventListener('input', validateCompanyName);
            }
            if (sponsorEmailInput) {
                sponsorEmailInput.addEventListener('input', validateEmail);
            }

            if (sponsorForm) {
                sponsorForm.addEventListener('submit', (event) => {
                    const nameValid = validateCompanyName();
                    const emailValid = validateEmail();
                    if (!nameValid || !emailValid) {
                        event.preventDefault();
                    }
                });
            }

            const setCurrentDate = () => {
                if (!sponsorDateInput) return;
                const now = new Date();
                const localISO = new Date(now.getTime() - now.getTimezoneOffset() * 60000)
                    .toISOString()
                    .slice(0, 16);
                sponsorDateInput.value = localISO;
            };

            setCurrentDate();
            document.querySelectorAll('.sponsor-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const participantId = btn.dataset.id || '';
                    const participantName = btn.dataset.name || '';

                    if (sponsorForm) {
                        sponsorForm.reset();
                    }
                    setCurrentDate();
                    setError(sponsorNameInput, sponsorErrors.nom, '');
                    setError(sponsorEmailInput, sponsorErrors.email, '');
                    sponsorParticipantId.value = participantId;
                    sponsorParticipantLabel.textContent = participantName ? `Participant : ${participantName}` : '';

                    if (sponsorDialog && typeof sponsorDialog.showModal === 'function') {
                        sponsorDialog.showModal();
                    }
                });
            });

            const escapeHtml = (value) => {
                if (value === null || value === undefined) return '';
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const formatSponsorDate = (value) => {
                if (!value) return '—';
                const date = new Date(value);
                if (Number.isNaN(date.getTime())) {
                    return value;
                }
                return date.toLocaleDateString('fr-FR', { year: 'numeric', month: 'short', day: 'numeric' }) +
                    ' ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
            };

            const formatJoinDate = (value) => {
                if (!value) return '—';
                const date = new Date(value);
                if (Number.isNaN(date.getTime())) {
                    return value;
                }
                return date.toLocaleDateString('fr-FR');
            };

            const getSponsorsForParticipant = (participantId) => {
                if (!participantSponsors) return [];
                return participantSponsors[participantId] || participantSponsors[String(participantId)] || [];
            };

            const buildParticipantCard = (participant) => {
                const id = participant.id;
                const nom = escapeHtml(participant.nom || '');
                const prenom = escapeHtml(participant.prenom || '');
                const email = escapeHtml(participant.email || '');
                const pays = escapeHtml(participant.pays || '');
                const langue = escapeHtml(participant.langue_preferee || '');
                const temoignage = participant.temoignage ? escapeHtml(participant.temoignage).replace(/\n/g, '<br>') : '';
                const joinDate = formatJoinDate(participant.date_inscription);

                return `
                    <div class="participant-card">
                        <div class="card-header">
                            <div class="card-avatar">${escapeHtml((participant.nom || '').charAt(0) + (participant.prenom || '').charAt(0)).toUpperCase()}</div>
                            <div class="card-name">
                                <h3>${nom} ${prenom}</h3>
                                <p>${email}</p>
                            </div>
                        </div>
                        <div class="card-body">
                            ${pays ? `
                                <div class="card-info">
                                    <span class="card-info-icon">🌍</span>
                                    <span>${pays}</span>
                                </div>` : ''}
                            ${langue ? `
                                <div class="card-info">
                                    <span class="card-info-icon">🗣️</span>
                                    <span>${langue}</span>
                                </div>` : ''}
                            ${temoignage ? `
                                <div class="card-temoignage">"${temoignage}"</div>` : ''}
                        </div>
                        <div class="card-footer">
                            <span>Rejoint le ${joinDate}</span>
                            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                                <button type="button" class="qr-code-btn" data-id="${id}" data-nom="${nom}" data-prenom="${prenom}" data-email="${email}" data-pays="${pays}" data-langue="${langue}" data-temoignage="${escapeHtml(participant.temoignage || '')}" data-date="${escapeHtml(participant.date_inscription || '')}">
                                    <i class="fas fa-qrcode"></i> QR Code
                                </button>
                                <button type="button" class="view-sponsors-btn" data-id="${id}" data-name="${nom} ${prenom}">Voir sponsors</button>
                                <button type="button" class="sponsor-btn" data-id="${id}" data-name="${nom} ${prenom}">Sponsoriser</button>
                            </div>
                        </div>
                    </div>
                `;
            };

            const updateParticipantsCount = (count) => {
                if (!participantsCountLabel) return;
                participantsCountLabel.textContent = `${count} Participant${count > 1 ? 's' : ''}`;
            };

            const applyCurrentFilter = (list) => {
                if (currentFilter === 'sponsored') {
                    return list.filter((participant) => {
                        const sponsors = getSponsorsForParticipant(participant.id);
                        return sponsors && sponsors.length > 0;
                    });
                }
                if (currentFilter === 'unsponsored') {
                    return list.filter((participant) => {
                        const sponsors = getSponsorsForParticipant(participant.id);
                        return !sponsors || sponsors.length === 0;
                    });
                }
                return list;
            };

            const applyCurrentSort = (list) => {
                if (!currentSort) {
                    return list.slice();
                }
                const sorted = list.slice().sort((a, b) => {
                    const nameA = `${a.nom || ''} ${a.prenom || ''}`.toLowerCase();
                    const nameB = `${b.nom || ''} ${b.prenom || ''}`.toLowerCase();
                    if (nameA < nameB) return currentSort === 'asc' ? -1 : 1;
                    if (nameA > nameB) return currentSort === 'asc' ? 1 : -1;
                    return 0;
                });
                return sorted;
            };

            const renderPagination = (totalPages) => {
                if (!paginationContainer) return;
                if (totalPages <= 1) {
                    paginationContainer.innerHTML = '';
                    return;
                }
                paginationContainer.innerHTML = `
                    <button type="button" data-page="prev" ${currentPage === 1 ? 'disabled' : ''}>Précédent</button>
                    <span>Page ${currentPage} / ${totalPages}</span>
                    <button type="button" data-page="next" ${currentPage === totalPages ? 'disabled' : ''}>Suivant</button>
                `;
            };

            const renderParticipantsList = (list) => {
                const sorted = applyCurrentSort(list);
                const filtered = applyCurrentFilter(sorted);
                if (!participantsContainer) return;
                if (!filtered.length) {
                    participantsContainer.innerHTML = `
                        <div class="empty-state">
                            <h3>Aucun participant</h3>
                            <p>Aucun résultat ne correspond à votre recherche.</p>
                        </div>
                    `;
                    if (paginationContainer) {
                        paginationContainer.innerHTML = '';
                    }
                    updateParticipantsCount(filtered.length);
                    return;
                }

                const totalPages = Math.max(1, Math.ceil(filtered.length / ITEMS_PER_PAGE));
                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }
                const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
                const visible = filtered.slice(startIndex, startIndex + ITEMS_PER_PAGE);

                participantsContainer.innerHTML = `
                    <div class="cards-container">
                        ${visible.map(buildParticipantCard).join('')}
                    </div>
                `;
                updateParticipantsCount(filtered.length);
                renderPagination(totalPages);
            };

            const performSearch = (query) => {
                const params = new URLSearchParams({ ajax: 'participants', search: query });
                fetch(`mainpage.php?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.error) {
                            throw new Error(data.error);
                        }
                        participantsData = Array.isArray(data.participants) ? data.participants : [];
                        participantSponsors = data.sponsors || {};
                        currentPage = 1;
                        renderParticipantsList(participantsData);
                    })
                    .catch(() => {
                        if (participantsContainer) {
                            participantsContainer.innerHTML = '<p class="muted">Erreur lors de la recherche. Veuillez réessayer.</p>';
                        }
                    });
            };

            const openSponsorForm = (participantId, participantName) => {
                if (sponsorForm) {
                    sponsorForm.reset();
                }
                setCurrentDate();
                setError(sponsorNameInput, sponsorErrors.nom, '');
                setError(sponsorEmailInput, sponsorErrors.email, '');
                sponsorParticipantId.value = participantId;
                sponsorParticipantLabel.textContent = participantName ? `Participant : ${participantName}` : '';

                if (sponsorDialog && typeof sponsorDialog.showModal === 'function') {
                    sponsorDialog.showModal();
                }
            };

            const openSponsorListDialog = (participantId, participantName) => {
                const sponsors = getSponsorsForParticipant(participantId);

                sponsorListSubtitle.textContent = participantName ? `Participant : ${participantName}` : '';
                sponsorListContent.innerHTML = '';

                if (!sponsors.length) {
                    const emptyState = document.createElement('p');
                    emptyState.textContent = 'Aucun sponsor pour ce participant pour le moment.';
                    emptyState.className = 'muted';
                    sponsorListContent.appendChild(emptyState);
                } else {
                    sponsors.forEach((sponsor) => {
                        const entry = document.createElement('div');
                        entry.className = 'sponsor-entry';

                        const title = document.createElement('strong');
                        title.textContent = sponsor.company || 'Entreprise';
                        entry.appendChild(title);

                        const details = document.createElement('p');
                        details.style.margin = '6px 0 0';
                        details.innerHTML = `
                            <span>Email : ${escapeHtml(sponsor.email || '—')}</span><br>
                            <span>Pays : ${escapeHtml(sponsor.country || '—')}</span><br>
                            <span>Montant : ${sponsor.amount !== null && sponsor.amount !== undefined ? escapeHtml(sponsor.amount) + ' $' : '—'}</span><br>
                            <span>Date : ${formatSponsorDate(sponsor.date)}</span>
                        `;
                        entry.appendChild(details);

                        sponsorListContent.appendChild(entry);
                    });
                }

                if (typeof sponsorListDialog.showModal === 'function') {
                    sponsorListDialog.showModal();
                }
            };

            if (participantsContainer) {
                participantsContainer.addEventListener('click', (event) => {
                    const qrBtn = event.target.closest('.qr-code-btn');
                    if (qrBtn) {
                        const participantData = {
                            id: qrBtn.dataset.id || '',
                            nom: qrBtn.dataset.nom || '',
                            prenom: qrBtn.dataset.prenom || '',
                            email: qrBtn.dataset.email || '',
                            pays: qrBtn.dataset.pays || '—',
                            langue: qrBtn.dataset.langue || '—',
                            temoignage: qrBtn.dataset.temoignage || '—',
                            date: qrBtn.dataset.date || ''
                        };

                        qrCodeParticipantName.textContent = `${participantData.prenom} ${participantData.nom}`;

                        let dateFormatted = '—';
                        if (participantData.date) {
                            try {
                                const dateObj = new Date(participantData.date);
                                dateFormatted = dateObj.toLocaleDateString('fr-FR', { 
                                    year: 'numeric', 
                                    month: '2-digit', 
                                    day: '2-digit'
                                }) + ' ' + dateObj.toLocaleTimeString('fr-FR', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                            } catch (e) {
                                dateFormatted = participantData.date.substring(0, 16);
                            }
                        }

                        const baseText = `TOGETHER4PEACE
ID:${participantData.id}|N:${participantData.nom}|P:${participantData.prenom}
E:${participantData.email}|Pays:${participantData.pays}|L:${participantData.langue}
Date:${dateFormatted}
Temoignage:`;

                        const maxQRCodeLength = 2500;
                        const baseTextLength = baseText.length;
                        const maxTemoignageLength = Math.max(0, maxQRCodeLength - baseTextLength - 20);
                        
                        let temoignageForQR = participantData.temoignage || '—';
                        if (temoignageForQR !== '—' && temoignageForQR.length > maxTemoignageLength) {
                            temoignageForQR = temoignageForQR.substring(0, maxTemoignageLength) + '[...]';
                        }

                        let qrCodeText = baseText + temoignageForQR;
                        
                        if (qrCodeText.length > maxQRCodeLength) {
                            const reduction = qrCodeText.length - maxQRCodeLength + 50;
                            if (temoignageForQR !== '—') {
                                const newLength = Math.max(0, temoignageForQR.length - reduction);
                                temoignageForQR = temoignageForQR.substring(0, newLength) + '[...]';
                                qrCodeText = baseText + temoignageForQR;
                            }
                        }
                        
                        if (qrCodeText.length > maxQRCodeLength) {
                            qrCodeText = qrCodeText.substring(0, maxQRCodeLength - 10) + '[...]';
                        }

                        function generateQRCode() {
                            qrCodeContainer.innerHTML = '';
                            try {
                                new QRCode(qrCodeContainer, {
                                    text: qrCodeText,
                                    width: 256,
                                    height: 256,
                                    colorDark: '#002d62',
                                    colorLight: '#ffffff',
                                    correctLevel: QRCode.CorrectLevel.L
                                });
                            } catch (error) {
                                console.error('Erreur lors de la génération du QR code:', error);
                                qrCodeContainer.innerHTML = '<p style="color: red;">Erreur lors de la génération du QR code: ' + (error.message || String(error)) + '</p>';
                            }
                        }

                        if (typeof QRCode !== 'undefined') {
                            generateQRCode();
                        } else {
                            let attempts = 0;
                            const maxAttempts = 30;
                            const checkQRCode = setInterval(() => {
                                attempts++;
                                if (typeof QRCode !== 'undefined') {
                                    clearInterval(checkQRCode);
                                    generateQRCode();
                                } else if (attempts >= maxAttempts) {
                                    clearInterval(checkQRCode);
                                    qrCodeContainer.innerHTML = '<p style="color: red;">La bibliothèque QRCode n\'a pas pu être chargée.<br>Vérifiez votre connexion internet et rafraîchissez la page.</p>';
                                }
                            }, 100);
                        }

                        if (qrCodeDialog && typeof qrCodeDialog.showModal === 'function') {
                            qrCodeDialog.showModal();
                        }
                        return;
                    }
                    const sponsorBtn = event.target.closest('.sponsor-btn');
                    if (sponsorBtn) {
                        openSponsorForm(sponsorBtn.dataset.id || '', sponsorBtn.dataset.name || '');
                        return;
                    }
                    const viewBtn = event.target.closest('.view-sponsors-btn');
                    if (viewBtn) {
                        openSponsorListDialog(viewBtn.dataset.id || '', viewBtn.dataset.name || '');
                    }
                });
            }

            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const term = searchInput.value.trim();
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        performSearch(term);
                    }, 350);
                });
            }

            if (filtersContainer) {
                filtersContainer.addEventListener('click', (event) => {
                    const btn = event.target.closest('.filter-btn');
                    if (!btn) {
                        return;
                    }
                    const newFilter = btn.dataset.filter || 'all';
                    currentFilter = newFilter;
                    filtersContainer.querySelectorAll('.filter-btn').forEach((button) => {
                        button.classList.toggle('active', button === btn);
                    });
                    currentPage = 1;
                    renderParticipantsList(participantsData);
                });
            }

            if (sortContainer) {
                sortContainer.addEventListener('click', (event) => {
                    const btn = event.target.closest('.sort-btn');
                    if (!btn) return;
                    const sortValue = btn.dataset.sort;
                    if (currentSort === sortValue) {
                        currentSort = null;
                        sortContainer.querySelectorAll('.sort-btn').forEach((button) => button.classList.remove('active'));
                    } else {
                        currentSort = sortValue;
                        sortContainer.querySelectorAll('.sort-btn').forEach((button) => {
                            button.classList.toggle('active', button === btn);
                        });
                    }
                    currentPage = 1;
                    renderParticipantsList(participantsData);
                });
            }

            if (paginationContainer) {
                paginationContainer.addEventListener('click', (event) => {
                    const btn = event.target.closest('button[data-page]');
                    if (!btn || btn.disabled) return;
                    const action = btn.dataset.page;
                    if (action === 'prev' && currentPage > 1) {
                        currentPage -= 1;
                    } else if (action === 'next') {
                        currentPage += 1;
                    }
                    renderParticipantsList(participantsData);
                });
            }

            if (closeSponsorDialog && sponsorDialog) {
                closeSponsorDialog.addEventListener('click', () => sponsorDialog.close());
            }

            if (sponsorDialog) {
                sponsorDialog.addEventListener('close', () => {
                    if (sponsorForm) {
                        sponsorForm.reset();
                        setCurrentDate();
                    }
                    setError(sponsorNameInput, sponsorErrors.nom, '');
                    setError(sponsorEmailInput, sponsorErrors.email, '');
                    sponsorParticipantLabel.textContent = '';
                });
            }

            if (closeSponsorListDialog && sponsorListDialog) {
                closeSponsorListDialog.addEventListener('click', () => sponsorListDialog.close());
            }

            if (sponsorListDialog) {
                sponsorListDialog.addEventListener('close', () => {
                    sponsorListSubtitle.textContent = '';
                    sponsorListContent.innerHTML = '';
                });
            }

            if (closeQrCodeDialog && qrCodeDialog) {
                closeQrCodeDialog.addEventListener('click', () => {
                    qrCodeDialog.close();
                    if (qrCodeContainer) {
                        qrCodeContainer.innerHTML = '';
                    }
                });
            }

            renderParticipantsList(participantsData);

            // Chatbot functionality
            const chatbotDialog = document.getElementById('chatbotDialog');
            const openChatbotBtn = document.getElementById('openChatbot');
            const closeChatbotBtn = document.getElementById('closeChatbot');
            const chatbotMessages = document.getElementById('chatbotMessages');
            const chatbotInput = document.getElementById('chatbotInput');
            const chatbotSendBtn = document.getElementById('chatbotSend');

            // Base de connaissances du chatbot
            const chatbotKnowledge = {
                'salut': 'Bonjour ! Comment puis-je vous aider ?',
                'bonjour': 'Bonjour ! Je suis là pour répondre à vos questions sur Together4Peace.',
                'bonsoir': 'Bonsoir ! Comment puis-je vous aider ?',
                'qu\'est-ce que together4peace': 'Together4Peace est une plateforme qui rassemble des personnes engagées pour la paix et l\'inclusion. Notre mission est de bâtir des ponts, pas des murs.',
                'together4peace': 'Together4Peace est une plateforme qui rassemble des personnes engagées pour la paix et l\'inclusion. Notre mission est de bâtir des ponts, pas des murs.',
                'mission': 'Notre mission est de rassembler des voix inspirantes du monde entier pour promouvoir la paix, l\'inclusion et la compréhension mutuelle.',
                'comment s\'inscrire': 'Pour vous inscrire, cliquez sur le bouton "Ajouter un participant" en haut de la page ou allez sur la page d\'inscription. Remplissez le formulaire avec vos informations et votre témoignage.',
                's\'inscrire': 'Pour vous inscrire, cliquez sur le bouton "Ajouter un participant" en haut de la page ou allez sur la page d\'inscription. Remplissez le formulaire avec vos informations et votre témoignage.',
                'inscription': 'Pour vous inscrire, cliquez sur le bouton "Ajouter un participant" en haut de la page ou allez sur la page d\'inscription. Remplissez le formulaire avec vos informations et votre témoignage.',
                'comment sponsoriser': 'Pour sponsoriser un participant, cliquez sur le bouton "Sponsoriser" sur la carte du participant. Remplissez le formulaire avec les informations de votre entreprise et le montant du sponsoring.',
                'sponsoriser': 'Pour sponsoriser un participant, cliquez sur le bouton "Sponsoriser" sur la carte du participant. Remplissez le formulaire avec les informations de votre entreprise et le montant du sponsoring.',
                'sponsor': 'Pour sponsoriser un participant, cliquez sur le bouton "Sponsoriser" sur la carte du participant. Remplissez le formulaire avec les informations de votre entreprise et le montant du sponsoring.',
                'voir les participants': 'Vous pouvez voir tous les participants sur cette page. Utilisez la barre de recherche pour trouver un participant spécifique, ou les filtres pour voir les participants sponsorisés ou non sponsorisés.',
                'participants': 'Vous pouvez voir tous les participants sur cette page. Utilisez la barre de recherche pour trouver un participant spécifique, ou les filtres pour voir les participants sponsorisés ou non sponsorisés.',
                'recherche': 'Utilisez la barre de recherche en haut de la page pour rechercher un participant par son nom. La recherche se fait en temps réel.',
                'filtres': 'Vous pouvez filtrer les participants par statut de sponsoring : "Tous", "Sponsorisés" ou "Non sponsorisés". Utilisez les boutons de filtre en haut de la page.',
                'tri': 'Vous pouvez trier les participants par ordre alphabétique en utilisant les boutons "Nom A → Z" ou "Nom Z → A".',
                'pagination': 'Les participants sont affichés par pages de 2. Utilisez les boutons "Précédent" et "Suivant" pour naviguer entre les pages.',
                'qr code': 'Chaque participant a un QR code unique. Cliquez sur le bouton "QR Code" sur la carte du participant pour voir et scanner son QR code contenant toutes ses informations.',
                'contact': 'Pour nous contacter, vous pouvez utiliser les liens dans le footer de la page ou visiter notre page de contact.',
                'aide': 'Je suis là pour vous aider ! Posez-moi vos questions sur Together4Peace, l\'inscription, le sponsoring, ou toute autre question concernant le site.',
                'help': 'Je suis là pour vous aider ! Posez-moi vos questions sur Together4Peace, l\'inscription, le sponsoring, ou toute autre question concernant le site.',
                'merci': 'De rien ! N\'hésitez pas si vous avez d\'autres questions.',
                'au revoir': 'Au revoir ! Bonne continuation avec Together4Peace.',
                'bye': 'Au revoir ! Bonne continuation avec Together4Peace.',
            };

            function findParticipantByName(searchTerm) {
                if (!participantsData || participantsData.length === 0) {
                    return null;
                }
                
                const searchLower = searchTerm.toLowerCase().trim();
                if (searchLower.length < 2) {
                    return null;
                }
                
                // Rechercher dans tous les participants
                for (const participant of participantsData) {
                    const nom = (participant.nom || '').toLowerCase().trim();
                    const prenom = (participant.prenom || '').toLowerCase().trim();
                    const fullName = `${prenom} ${nom}`.trim();
                    const fullNameReverse = `${nom} ${prenom}`.trim();
                    
                    // Vérifier si le terme de recherche correspond au nom, prénom ou nom complet
                    // Recherche exacte ou partielle
                    if (nom === searchLower || 
                        prenom === searchLower ||
                        fullName === searchLower ||
                        fullNameReverse === searchLower ||
                        nom.includes(searchLower) || 
                        prenom.includes(searchLower) || 
                        fullName.includes(searchLower) ||
                        fullNameReverse.includes(searchLower) ||
                        searchLower.includes(nom) ||
                        searchLower.includes(prenom)) {
                        return participant;
                    }
                }
                
                return null;
            }

            function formatParticipantInfo(participant) {
                if (!participant) return '';
                
                const nom = participant.nom || '—';
                const prenom = participant.prenom || '—';
                const email = participant.email || '—';
                const pays = participant.pays || '—';
                const langue = participant.langue_preferee || '—';
                const temoignage = participant.temoignage || '—';
                
                let dateInscription = '—';
                if (participant.date_inscription) {
                    try {
                        const date = new Date(participant.date_inscription);
                        dateInscription = date.toLocaleDateString('fr-FR', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    } catch (e) {
                        dateInscription = participant.date_inscription;
                    }
                }
                
                // Vérifier les sponsors
                const sponsors = getSponsorsForParticipant(participant.id);
                const hasSponsors = sponsors && sponsors.length > 0;
                const sponsorCount = hasSponsors ? sponsors.length : 0;
                
                let info = `<strong>📋 Informations de ${prenom} ${nom}</strong><br><br>`;
                info += `📧 Email : ${email}<br>`;
                if (pays !== '—') {
                    info += `🌍 Pays : ${pays}<br>`;
                }
                if (langue !== '—') {
                    info += `🗣️ Langue préférée : ${langue}<br>`;
                }
                info += `📅 Date d'inscription : ${dateInscription}<br>`;
                info += `💰 Sponsors : ${sponsorCount} sponsor${sponsorCount > 1 ? 's' : ''}<br>`;
                
                if (temoignage !== '—') {
                    info += `<br><strong>Témoignage :</strong><br>${temoignage}`;
                }
                
                if (hasSponsors) {
                    info += `<br><br><strong>Liste des sponsors :</strong><br>`;
                    sponsors.forEach((sponsor, index) => {
                        info += `${index + 1}. ${sponsor.company || 'Entreprise'} - ${sponsor.amount ? sponsor.amount + ' $' : 'Montant non spécifié'}<br>`;
                    });
                }
                
                return info;
            }

            function getChatbotResponse(userMessage) {
                const message = userMessage.toLowerCase().trim();
                
                // D'abord, essayer de trouver un participant par nom
                // Extraire les mots qui pourraient être des noms (mots de 2+ caractères)
                const words = message.split(/\s+/).filter(word => word.length >= 2);
                
                // Essayer d'abord avec le message complet (pour les noms complets comme "Jean Dupont")
                let participant = findParticipantByName(message);
                if (participant) {
                    return formatParticipantInfo(participant);
                }
                
                // Ensuite, essayer avec des combinaisons de mots (2 premiers mots, 2 derniers mots)
                if (words.length >= 2) {
                    const firstTwo = words.slice(0, 2).join(' ');
                    const lastTwo = words.slice(-2).join(' ');
                    participant = findParticipantByName(firstTwo);
                    if (participant) {
                        return formatParticipantInfo(participant);
                    }
                    participant = findParticipantByName(lastTwo);
                    if (participant) {
                        return formatParticipantInfo(participant);
                    }
                }
                
                // Ensuite, essayer avec chaque mot individuellement
                for (const word of words) {
                    participant = findParticipantByName(word);
                    if (participant) {
                        return formatParticipantInfo(participant);
                    }
                }
                
                // Recherche dans la base de connaissances
                for (const [keyword, response] of Object.entries(chatbotKnowledge)) {
                    if (message.includes(keyword)) {
                        return response;
                    }
                }

                // Réponses par défaut selon le contexte
                if (message.includes('quoi') || message.includes('qu\'est-ce')) {
                    return 'Together4Peace est une plateforme qui rassemble des personnes engagées pour la paix. Vous pouvez vous inscrire, voir les participants, ou sponsoriser quelqu\'un. Que souhaitez-vous savoir de plus ?';
                }

                if (message.includes('comment')) {
                    return 'Pour vous aider, pouvez-vous préciser ce que vous voulez faire ? Par exemple : "Comment s\'inscrire ?" ou "Comment sponsoriser ?"';
                }

                if (message.includes('où')) {
                    return 'Vous êtes actuellement sur la page des participants. Pour vous inscrire, cliquez sur "Ajouter un participant" en haut de la page. Pour voir les participants, vous êtes déjà au bon endroit !';
                }

                // Si le message contient des mots qui pourraient être des noms mais aucun participant trouvé
                if (words.length > 0 && words.some(w => w.length >= 3)) {
                    return `Je n'ai pas trouvé de participant correspondant à "${userMessage.trim()}". Vérifiez l'orthographe du nom ou utilisez la barre de recherche en haut de la page pour trouver un participant.`;
                }

                // Réponse par défaut
                return 'Je comprends votre question. Together4Peace est une plateforme pour la paix où vous pouvez vous inscrire, voir les participants et les sponsoriser. Vous pouvez aussi me demander des informations sur un participant en tapant son nom. Pouvez-vous reformuler votre question ou utiliser les questions rapides ci-dessus ?';
            }

            function addMessage(content, isUser = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chatbot-message ${isUser ? 'user' : 'bot'}`;
                
                const avatar = document.createElement('div');
                avatar.className = 'message-avatar';
                avatar.innerHTML = isUser ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';
                
                const messageContent = document.createElement('div');
                messageContent.className = 'message-content';
                messageContent.innerHTML = `<p>${content}</p>`;
                
                messageDiv.appendChild(avatar);
                messageDiv.appendChild(messageContent);
                chatbotMessages.appendChild(messageDiv);
                
                // Scroll to bottom
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            }

            function sendMessage() {
                const message = chatbotInput.value.trim();
                if (!message) return;

                // Add user message
                addMessage(message, true);
                chatbotInput.value = '';

                // Simulate typing delay
                setTimeout(() => {
                    const response = getChatbotResponse(message);
                    addMessage(response);
                }, 500);
            }

            if (openChatbotBtn && chatbotDialog) {
                openChatbotBtn.addEventListener('click', () => {
                    chatbotDialog.showModal();
                    chatbotInput.focus();
                });
            }

            if (closeChatbotBtn && chatbotDialog) {
                closeChatbotBtn.addEventListener('click', () => {
                    chatbotDialog.close();
                });
            }

            if (chatbotSendBtn) {
                chatbotSendBtn.addEventListener('click', sendMessage);
            }

            if (chatbotInput) {
                chatbotInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        sendMessage();
                    }
                });
            }

            // Quick question buttons
            document.querySelectorAll('.quick-question-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const question = btn.dataset.question;
                    chatbotInput.value = question;
                    sendMessage();
                });
            });
        });
    </script>
</body>
</html>

