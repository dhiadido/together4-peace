<?php
require_once dirname(__DIR__) . '/controller/config.php';
require_once dirname(__DIR__) . '/controller/participantC.php';

$message = '';
$listError = '';

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
    ['code' => 'IO', 'name' => 'Territoire britannique de l\'océan Indien', 'flag' => '🇮🇴'],
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
    ['code' => 'ZW', 'name' => 'Zimbabwe', 'flag' => '🇿🇼']
];

$languages = [
    ['value' => 'Arabe', 'label' => '🇸🇦 Arabe'],
    ['value' => 'Allemand', 'label' => '🇩🇪 Allemand'],
    ['value' => 'Anglais', 'label' => '🇬🇧 Anglais'],
    ['value' => 'Chinois', 'label' => '🇨🇳 Chinois'],
    ['value' => 'Espagnol', 'label' => '🇪🇸 Espagnol'],
    ['value' => 'Français', 'label' => '🇫🇷 Français'],
    ['value' => 'Hindi', 'label' => '🇮🇳 Hindi'],
    ['value' => 'Italien', 'label' => '🇮🇹 Italien'],
    ['value' => 'Japonais', 'label' => '🇯🇵 Japonais'],
    ['value' => 'Portugais', 'label' => '🇵🇹 Portugais'],
    ['value' => 'Russe', 'label' => '🇷🇺 Russe'],
    ['value' => 'Turc', 'label' => '🇹🇷 Turc'],
    ['value' => 'Autre', 'label' => '🌍 Autre']
];

$participantController = new ParticipantController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';

    try {
        if ($action === 'update') {
            $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
            if (!$id) {
                throw new InvalidArgumentException("L'identifiant du participant est requis pour la mise à jour.");
            }

            $participant = new Participant(
                $_POST['nom'] ?? '',
                $_POST['prenom'] ?? '',
                $_POST['email'] ?? '',
                $_POST['pays'] ?? null,
                $_POST['langue_preferee'] ?? null,
                $_POST['temoignage'] ?? null,
                $_POST['date_inscription'] ?? null
            );

            $participant->setId($id);
            $participantController->updateParticipant($participant);
            $message = 'Participant mis à jour avec succès.';
        } else {
            $participant = new Participant(
                $_POST['nom'] ?? '',
                $_POST['prenom'] ?? '',
                $_POST['email'] ?? '',
                $_POST['pays'] ?? null,
                $_POST['langue_preferee'] ?? null,
                $_POST['temoignage'] ?? null,
                null
            );

            $participantController->createParticipant($participant);
            $message = 'Participant ajouté avec succès.';
        }
    } catch (Exception $e) {
        $message = 'Erreur : ' . $e->getMessage();
    }
}

$participantsList = [];

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->query("SELECT * FROM participant ORDER BY date_inscription DESC, id DESC");
    $participantsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $listError = 'Impossible de charger les participants : ' . $e->getMessage();
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Arial:wght@400;500;700&display=swap">
    <style>
        /* Additional styles for form and dialogs */
        .main-content {
            max-width: 700px;
            margin: 40px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        
        .main-content h1 {
            text-align: center;
            color: var(--color-primary);
            margin-bottom: 30px;
        }
        
        form {
            display: flex;
            flex-direction: column;
        }
        
        label {
            margin: 15px 0 5px;
            font-weight: bold;
            color: var(--color-dark);
        }
        
        input, textarea, button, select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 1rem;
            font-family: var(--font-family);
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .main-content button[type="submit"] {
            background-color: var(--color-primary);
            color: white;
            border: 2px solid var(--color-primary);
            padding: 12px 24px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .main-content button[type="submit"]:hover {
            background-color: #004080;
        }
        
        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            padding: 15px;
            border-radius: 5px;
        }
        
        .message:not(.error) {
            color: var(--color-accent);
            background-color: rgba(38, 166, 154, 0.1);
        }
        
        .error {
            color: #d9534f;
            background-color: rgba(217, 83, 79, 0.1);
        }
        
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 25px;
        }
        
        .error-text {
            color: #d9534f;
            font-size: 0.85rem;
            margin-top: -8px;
            margin-bottom: 10px;
            min-height: 18px;
            display: block;
        }
        
        .invalid {
            border-color: #d9534f;
            background-color: #fff5f5;
        }
        
        dialog {
            border: none;
            border-radius: 12px;
            padding: 0;
            width: min(900px, 95vw);
        }
        
        dialog::backdrop {
            background: rgba(0, 0, 0, 0.4);
        }
        
        .dialog-content {
            padding: 30px;
        }
        
        .dialog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--color-light);
            padding-bottom: 15px;
        }
        
        .dialog-header h2 {
            color: var(--color-primary);
            margin: 0;
        }
        
        .ghost-button {
            background: transparent;
            border: none;
            font-size: 2rem;
            line-height: 1;
            cursor: pointer;
            color: var(--color-dark);
            transition: color 0.3s;
        }
        
        .ghost-button:hover {
            color: var(--color-primary);
        }
        
        .table-wrapper {
            overflow-x: auto;
            max-height: 60vh;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
        }
        
        th, td {
            border: 1px solid #e6e6e6;
            padding: 12px;
            text-align: left;
        }
        
        th {
            background-color: var(--color-light);
            color: var(--color-primary);
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        
        tr:hover {
            background-color: rgba(0, 45, 98, 0.05);
        }
        
        .edit-btn {
            background-color: var(--color-secondary);
            color: var(--color-dark);
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .edit-btn:hover {
            background-color: #e0a800;
        }
        .qr-code-btn {
            background-color: var(--color-accent);
            color: #fff;
        }
        .qr-code-btn:hover {
            background-color: #1e8e85;
        }
        
        .dialog-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .dialog-actions button {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
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
                <li><a href="#form">Accueil</a></li>
                <li><a href="#participants">Participants</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <a href="back.php" class="btn btn-donate">Espace Admin</a>
    </header>

    <section class="hero-section">
        <div class="hero-content">
            <h1>Bâtir des Ponts. Non des Murs.</h1>
            <p>Ensemble, agissons pour un monde de Paix et d'Inclusion.</p>
            <div class="hero-actions">
                <a href="#form" class="btn btn-primary">Rejoindre Together4Peace</a>
                <a href="#participants" class="btn btn-secondary">Voir les Participants</a>
            </div>
        </div>
    </section>

    <main class="main-content" id="form">
        <div class="toolbar">
            <button type="button" id="openParticipants" class="btn btn-primary">Voir les participants</button>
            <button type="button" id="openTestimonials" class="btn btn-secondary">Témoignages</button>
        </div>

        <h1>Ajouter un participant</h1>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Erreur') === 0 ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="createForm" novalidate>
            <input type="hidden" name="action" value="create">

            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom">
            <small class="error-text" id="create_nom_error"></small>

            <label for="prenom">Prénom *</label>
            <input type="text" id="prenom" name="prenom">
            <small class="error-text" id="create_prenom_error"></small>

            <label for="email">Email *</label>
            <input type="text" id="email" name="email">
            <small class="error-text" id="create_email_error"></small>

            <label for="pays">Pays</label>
            <select id="pays" name="pays">
                <option value="">--Choisir un pays--</option>
                <?php foreach ($countries as $country): ?>
                    <option value="<?php echo htmlspecialchars($country['name']); ?>">
                        <?php echo htmlspecialchars($country['flag'] . ' ' . $country['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="langue_preferee">Langue préférée</label>
            <select id="langue_preferee" name="langue_preferee">
                <option value="">--Choisir une langue--</option>
                <?php foreach ($languages as $language): ?>
                    <option value="<?php echo htmlspecialchars($language['value']); ?>">
                        <?php echo htmlspecialchars($language['label']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="temoignage">Témoignage</label>
            <textarea id="temoignage" name="temoignage" placeholder="Partagez votre expérience..."></textarea>
            <small class="error-text" id="create_temoignage_error"></small>

            <button type="submit">Ajouter</button>
        </form>

        <?php if (!empty($listError)): ?>
            <p class="message error"><?php echo htmlspecialchars($listError); ?></p>
        <?php endif; ?>
    </main>

    <dialog id="participantsDialog">
        <div class="dialog-content">
            <div class="dialog-header">
                <h2>Participants enregistrés</h2>
                <button type="button" id="closeParticipants" class="ghost-button" aria-label="Fermer">&times;</button>
            </div>
            <?php if (empty($participantsList)): ?>
                <p>Aucun participant enregistré pour le moment.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Pays</th>
                                <th>Langue</th>
                                <th>Témoignage</th>
                                <th>Date d'inscription</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participantsList as $participantRow): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($participantRow['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['email']); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['pays'] ?? '—'); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['langue_preferee'] ?? '—'); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($participantRow['temoignage'] ?? '—')); ?></td>
                                    <td>
                                        <?php
                                            $dateValue = $participantRow['date_inscription'] ?? '';
                                            echo $dateValue ? htmlspecialchars(date('d/m/Y H:i', strtotime($dateValue))) : '—';
                                        ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                            <button
                                                type="button"
                                                class="edit-btn qr-code-btn"
                                                data-id="<?php echo htmlspecialchars($participantRow['id']); ?>"
                                                data-nom="<?php echo htmlspecialchars($participantRow['nom'], ENT_QUOTES); ?>"
                                                data-prenom="<?php echo htmlspecialchars($participantRow['prenom'], ENT_QUOTES); ?>"
                                                data-email="<?php echo htmlspecialchars($participantRow['email'], ENT_QUOTES); ?>"
                                                data-pays="<?php echo htmlspecialchars($participantRow['pays'] ?? '', ENT_QUOTES); ?>"
                                                data-langue="<?php echo htmlspecialchars($participantRow['langue_preferee'] ?? '', ENT_QUOTES); ?>"
                                                data-temoignage="<?php echo htmlspecialchars($participantRow['temoignage'] ?? '', ENT_QUOTES); ?>"
                                                data-date="<?php echo htmlspecialchars($participantRow['date_inscription'] ?? '', ENT_QUOTES); ?>"
                                            >
                                                <i class="fas fa-qrcode"></i> QR Code
                                            </button>
                                            <button
                                                type="button"
                                                class="edit-btn"
                                                data-id="<?php echo htmlspecialchars($participantRow['id']); ?>"
                                                data-nom="<?php echo htmlspecialchars($participantRow['nom'], ENT_QUOTES); ?>"
                                                data-prenom="<?php echo htmlspecialchars($participantRow['prenom'], ENT_QUOTES); ?>"
                                                data-email="<?php echo htmlspecialchars($participantRow['email'], ENT_QUOTES); ?>"
                                                data-pays="<?php echo htmlspecialchars($participantRow['pays'] ?? '', ENT_QUOTES); ?>"
                                                data-langue="<?php echo htmlspecialchars($participantRow['langue_preferee'] ?? '', ENT_QUOTES); ?>"
                                                data-temoignage="<?php echo htmlspecialchars($participantRow['temoignage'] ?? '', ENT_QUOTES); ?>"
                                                data-date="<?php echo htmlspecialchars($participantRow['date_inscription'] ?? '', ENT_QUOTES); ?>"
                                            >
                                                Modifier
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </dialog>

    <dialog id="testimonialsDialog">
        <div class="dialog-content">
            <div class="dialog-header">
                <h2>Témoignages</h2>
                <button type="button" id="closeTestimonials" class="ghost-button" aria-label="Fermer">&times;</button>
            </div>
            <?php
                $testimonials = array_filter($participantsList, function ($participantRow) {
                    return !empty(trim($participantRow['temoignage'] ?? ''));
                });
            ?>
            <?php if (empty($testimonials)): ?>
                <p>Aucun témoignage pour le moment.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Témoignage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($testimonials as $participantRow): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($participantRow['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($participantRow['prenom']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($participantRow['temoignage'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </dialog>

    <dialog id="editDialog">
        <div class="dialog-content">
            <div class="dialog-header">
                <h2>Modifier le participant</h2>
                <button type="button" id="cancelEdit" class="ghost-button" aria-label="Fermer">&times;</button>
            </div>
            <form method="POST" id="editForm" novalidate>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="date_inscription" id="edit_date_inscription">

                <label for="edit_nom">Nom *</label>
                <input type="text" id="edit_nom" name="nom">
                <small class="error-text" id="edit_nom_error"></small>

                <label for="edit_prenom">Prénom *</label>
                <input type="text" id="edit_prenom" name="prenom">
                <small class="error-text" id="edit_prenom_error"></small>

                <label for="edit_email">Email *</label>
                <input type="text" id="edit_email" name="email">
                <small class="error-text" id="edit_email_error"></small>

                <label for="edit_pays">Pays</label>
                <select id="edit_pays" name="pays">
                    <option value="">--Choisir un pays--</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo htmlspecialchars($country['name']); ?>">
                            <?php echo htmlspecialchars($country['flag'] . ' ' . $country['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="edit_langue_preferee">Langue préférée</label>
                <select id="edit_langue_preferee" name="langue_preferee">
                    <option value="">--Choisir une langue--</option>
                    <?php foreach ($languages as $language): ?>
                        <option value="<?php echo htmlspecialchars($language['value']); ?>">
                            <?php echo htmlspecialchars($language['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="edit_temoignage">Témoignage</label>
                <textarea id="edit_temoignage" name="temoignage" placeholder="Partagez votre expérience..."></textarea>
                <small class="error-text" id="edit_temoignage_error"></small>

                <div class="dialog-actions">
                    <button type="button" id="cancelEditBtn" class="btn btn-secondary" style="background-color: transparent; color: var(--color-primary); border: 2px solid var(--color-primary);">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="qrCodeDialog">
        <div class="dialog-content">
            <div class="dialog-header">
                <h2>QR Code du Participant</h2>
                <button type="button" id="closeQrCodeDialog" class="ghost-button" aria-label="Fermer">&times;</button>
            </div>
            <div style="text-align: center; padding: 20px;">
                <p id="qrCodeParticipantName" style="font-size: 1.1rem; font-weight: bold; color: var(--color-primary); margin-bottom: 20px;"></p>
                <div style="display: inline-block; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <div id="qrcode"></div>
                </div>
                <p style="margin-top: 20px; color: #666; font-size: 0.9rem;">
                    Scannez ce QR code pour obtenir les détails du participant
                </p>
            </div>
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
                    <li><a href="#form">Notre Mission</a></li>
                    <li><a href="#participants">Les Participants</a></li>
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
        document.addEventListener('DOMContentLoaded', () => {
            const participantsDialog = document.getElementById('participantsDialog');
            const openParticipants = document.getElementById('openParticipants');
            const closeParticipants = document.getElementById('closeParticipants');

            const testimonialsDialog = document.getElementById('testimonialsDialog');
            const openTestimonials = document.getElementById('openTestimonials');
            const closeTestimonials = document.getElementById('closeTestimonials');

            const editDialog = document.getElementById('editDialog');
            const cancelEdit = document.getElementById('cancelEdit');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const createForm = document.getElementById('createForm');
            const editFormElement = document.getElementById('editForm');
            const validationFields = ['nom', 'prenom', 'email', 'temoignage'];

            const editFields = {
                id: document.getElementById('edit_id'),
                nom: document.getElementById('edit_nom'),
                prenom: document.getElementById('edit_prenom'),
                email: document.getElementById('edit_email'),
                pays: document.getElementById('edit_pays'),
                langue: document.getElementById('edit_langue_preferee'),
                temoignage: document.getElementById('edit_temoignage'),
                date: document.getElementById('edit_date_inscription')
            };

            const namePattern = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
            const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

            const validators = {
                nom: (value) => {
                    const trimmed = value.trim();
                    if (!trimmed) {
                        return { valid: false, message: 'Le nom est requis.' };
                    }
                    if (!namePattern.test(trimmed)) {
                        return { valid: false, message: 'Le nom doit contenir uniquement des lettres.' };
                    }
                    return { valid: true };
                },
                prenom: (value) => {
                    const trimmed = value.trim();
                    if (!trimmed) {
                        return { valid: false, message: 'Le prénom est requis.' };
                    }
                    if (!namePattern.test(trimmed)) {
                        return { valid: false, message: 'Le prénom doit contenir uniquement des lettres.' };
                    }
                    return { valid: true };
                },
                email: (value) => {
                    const trimmed = value.trim();
                    if (!trimmed) {
                        return { valid: false, message: 'L\'email est requis.' };
                    }
                    if (!emailPattern.test(trimmed)) {
                        return { valid: false, message: 'Format d\'email invalide.' };
                    }
                    return { valid: true };
                },
                temoignage: (value) => {
                    const trimmed = value.trim();
                    if (!trimmed) {
                        return { valid: false, message: 'Le témoignage est requis.' };
                    }
                    if (trimmed.length > 30) {
                        return { valid: false, message: 'Maximum 30 caractères autorisés.' };
                    }
                    return { valid: true };
                }
            };

            const sanitizers = {
                nom: (value) => value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s'-]/g, ''),
                prenom: (value) => value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s'-]/g, ''),
                email: (value) => value.replace(/\s+/g, ''),
                temoignage: (value) => value.length > 30 ? value.slice(0, 30) : value
            };

            function validateField(input, errorEl, validator) {
                const result = validator(input.value);
                if (!result.valid) {
                    errorEl.textContent = result.message;
                    input.classList.add('invalid');
                } else {
                    errorEl.textContent = '';
                    input.classList.remove('invalid');
                }
                return result.valid;
            }

            function setupValidation(form, prefix) {
                if (!form) return;
                const fieldConfigs = validationFields.map((name) => ({
                    name,
                    input: form.querySelector(`[name="${name}"]`),
                    errorEl: document.getElementById(`${prefix}_${name}_error`),
                    validator: validators[name],
                    sanitizer: sanitizers[name]
                }));

                fieldConfigs.forEach(({ input, errorEl, validator, sanitizer }) => {
                    if (!input || !errorEl || !validator) return;
                    input.addEventListener('input', () => {
                        if (typeof sanitizer === 'function') {
                            const sanitized = sanitizer(input.value);
                            if (sanitized !== input.value) {
                                input.value = sanitized;
                            }
                        }
                        validateField(input, errorEl, validator);
                    });
                });

                form.addEventListener('submit', (event) => {
                    let allValid = true;
                    fieldConfigs.forEach(({ input, errorEl, validator }) => {
                        if (!input || !errorEl || !validator) return;
                        if (!validateField(input, errorEl, validator)) {
                            allValid = false;
                        }
                    });

                    if (!allValid) {
                        event.preventDefault();
                        const firstInvalid = form.querySelector('.invalid');
                        if (firstInvalid) {
                            firstInvalid.focus();
                        }
                    }
                });
            }

            setupValidation(createForm, 'create');
            setupValidation(editFormElement, 'edit');

            if (openParticipants && participantsDialog) {
                openParticipants.addEventListener('click', () => participantsDialog.showModal());
            }

            if (closeParticipants && participantsDialog) {
                closeParticipants.addEventListener('click', () => participantsDialog.close());
            }

            if (cancelEdit && editDialog) {
                cancelEdit.addEventListener('click', () => editDialog.close());
            }

            if (cancelEditBtn && editDialog) {
                cancelEditBtn.addEventListener('click', () => editDialog.close());
            }

            if (openTestimonials && testimonialsDialog) {
                openTestimonials.addEventListener('click', () => testimonialsDialog.showModal());
            }

            if (closeTestimonials && testimonialsDialog) {
                closeTestimonials.addEventListener('click', () => testimonialsDialog.close());
            }

            // Gestion du dialogue QR Code
            const qrCodeDialog = document.getElementById('qrCodeDialog');
            const closeQrCodeDialog = document.getElementById('closeQrCodeDialog');
            const qrCodeContainer = document.getElementById('qrcode');
            const qrCodeParticipantName = document.getElementById('qrCodeParticipantName');

            if (closeQrCodeDialog && qrCodeDialog) {
                closeQrCodeDialog.addEventListener('click', () => {
                    qrCodeDialog.close();
                    // Nettoyer le conteneur QR code
                    qrCodeContainer.innerHTML = '';
                });
            }

            // Gestion des boutons QR Code
            document.querySelectorAll('.qr-code-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const participantData = {
                        id: btn.dataset.id || '',
                        nom: btn.dataset.nom || '',
                        prenom: btn.dataset.prenom || '',
                        email: btn.dataset.email || '',
                        pays: btn.dataset.pays || '—',
                        langue: btn.dataset.langue || '—',
                        temoignage: btn.dataset.temoignage || '—',
                        date: btn.dataset.date || ''
                    };

                    // Afficher le nom du participant
                    qrCodeParticipantName.textContent = `${participantData.prenom} ${participantData.nom}`;

                    // Formater la date de manière plus compacte
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
                            dateFormatted = participantData.date.substring(0, 16); // Limiter la date
                        }
                    }

                    // Créer le texte de base (sans témoignage) - format compact
                    const baseText = `TOGETHER4PEACE
ID:${participantData.id}|N:${participantData.nom}|P:${participantData.prenom}
E:${participantData.email}|Pays:${participantData.pays}|L:${participantData.langue}
Date:${dateFormatted}
Temoignage:`;

                    // Calculer l'espace disponible pour le témoignage
                    // Avec correction d'erreur niveau L, on peut avoir environ 2900 caractères
                    // Réduire à 2500 pour être sûr
                    const maxQRCodeLength = 2500;
                    const baseTextLength = baseText.length;
                    const maxTemoignageLength = Math.max(0, maxQRCodeLength - baseTextLength - 20); // 20 caractères de marge
                    
                    // Limiter la longueur du témoignage
                    let temoignageForQR = participantData.temoignage || '—';
                    if (temoignageForQR !== '—' && temoignageForQR.length > maxTemoignageLength) {
                        temoignageForQR = temoignageForQR.substring(0, maxTemoignageLength) + '[...]';
                    }

                    // Créer le texte formaté pour le QR code (version compacte)
                    let qrCodeText = baseText + temoignageForQR;
                    
                    // Vérifier et réduire si nécessaire
                    if (qrCodeText.length > maxQRCodeLength) {
                        const reduction = qrCodeText.length - maxQRCodeLength + 50;
                        if (temoignageForQR !== '—') {
                            const newLength = Math.max(0, temoignageForQR.length - reduction);
                            temoignageForQR = temoignageForQR.substring(0, newLength) + '[...]';
                            qrCodeText = baseText + temoignageForQR;
                        }
                    }
                    
                    // Vérification finale stricte
                    if (qrCodeText.length > maxQRCodeLength) {
                        qrCodeText = qrCodeText.substring(0, maxQRCodeLength - 10) + '[...]';
                    }

                    // Fonction pour générer le QR code
                    function generateQRCode() {
                        // Nettoyer le conteneur avant de générer un nouveau QR code
                        qrCodeContainer.innerHTML = '';

                        // Générer le QR code avec la bibliothèque qrcodejs (davidshimjs)
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

                    // Vérifier si la bibliothèque est chargée
                    if (typeof QRCode !== 'undefined') {
                        generateQRCode();
                    } else {
                        // Attendre que le script soit chargé (maximum 3 secondes)
                        let attempts = 0;
                        const maxAttempts = 30; // 30 tentatives x 100ms = 3 secondes
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

                    // Ouvrir le dialogue
                    qrCodeDialog.showModal();
                });
            });

            // Gestion des boutons Modifier (sans la classe qr-code-btn)
            document.querySelectorAll('.edit-btn:not(.qr-code-btn)').forEach((btn) => {
                btn.addEventListener('click', () => {
                    editFields.id.value = btn.dataset.id || '';
                    editFields.nom.value = btn.dataset.nom || '';
                    editFields.prenom.value = btn.dataset.prenom || '';
                    editFields.email.value = btn.dataset.email || '';
                    editFields.pays.value = btn.dataset.pays || '';
                    editFields.langue.value = btn.dataset.langue || '';
                    editFields.temoignage.value = btn.dataset.temoignage || '';
                    editFields.date.value = btn.dataset.date || '';
                    if (editFormElement) {
                        validationFields.forEach((fieldName) => {
                            const input = editFormElement.querySelector(`[name="${fieldName}"]`);
                            if (input) {
                                input.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                        });
                    }
                    editDialog.showModal();
                });
            });
        });
    </script>
</body>
</html>