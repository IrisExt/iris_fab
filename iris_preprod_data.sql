-- befor --
/*
bin/console doctrin:schema:drop --full-database --force
bin/console doctrin:schema:update --force
bin/console hautelook:fixtures:load
 */


INSERT INTO "tr_type_reunion" ("id_type_reunion", "lb_type_reunion") VALUES
(1,	'Bureau'),
(2,	'Plénière');

INSERT INTO "tr_type_part" ("typ_part", "lb_nom_fr") VALUES
('PUF',	'Publics Français'),
('PRF',	'Privés Français'),
('ETR',	'Etrangers');

INSERT INTO "tr_type_document" ("id_type_doc", "lb_type_doc") VALUES
(1,	'Pré-proposition'),
(2,	'Annexe à la pré-proposition');

INSERT INTO "tr_type_commande" ("cd_commande", "lb_commande") VALUES
('RLC',	'relance'),
('AJT',	'ajouter'),
('COM',	'commentaire');


INSERT INTO "tr_typ_id_ext" ("id_type_ref_ext", "lb_nom_fr") VALUES
('1',	'ORCID'),
('2',	'ResearchID'),
('3',	'idHal'),
('4',	'idRef');

INSERT INTO "tr_sts_sollicitation" ("cd_sollicitation", "lb_description", "action_sollicitation") VALUES
('SOL',	'Sollicité',	'Déclarer que l’évaluateur est sollicité'),
('RET',	'Retiré',	'Retirer l’évaluateur'),
('REF',	'Refusée',	'Déclarer l’évaluateur en refus'),
('ENC',	'En conflit',	'Déclarer l’évaluateur en conflit'),
('ACC',	'Acceptée',	'Déclarer que l’évaluateur a accepté'),
('PRO',	'Proposée',	'Déclarer que l''évaluateur est proposé');

INSERT INTO "tr_sts_evaluation" ("cd_sts_evaluation", "lb_description") VALUES
('AFR',	'A faire'),
('ENC',	'En cours'),
('SOM',	'Soumise');

INSERT INTO "tr_st_affect" ("id_st_affect", "symbole", "lb_nom", "ordre", "lb_couleur") VALUES
(1,	'R',	'Rapporteur',	10,	NULL),
(2,	'L',	'Lecteur',	20,	NULL),
(3,	'X',	'Conflit',	40,	'red'),
(4,	'O',	'Refus',	30,	'orange');


INSERT INTO "tr_pol_comp" ("id_pole_comp", "lb_pol_comp") VALUES
(1,	'ALPHA-RLH'),
(2,	'Alsace biovalley'),
(3,	'AVENIA'),
(4,	'ASTECH'),
(5,	'Aerospace valley'),
(6,	'Agri Sud-Ouest Innovation');

INSERT INTO "tr_phase" ("id_phase_ref", "lb_nom") VALUES
(1,	'Phase 1'),
(2,	'Phase 2'),
(3,	'Phase 3'),
(4,	'Phase 4'),
(5,	'Phase 5'),
(6,	'Phase 6');

INSERT INTO "tr_pays" ("cd_pays", "lb_pays", "lb_pays_en", "alpha2", "alpha3") VALUES
('4',	'Afghanistan',	'Afghanistan',	'AF',	'AFG'),
('8',	'Albanie',	'Albania',	'AL',	'ALB'),
('10',	'Antarctique',	'Antarctica',	'AQ',	'ATA'),
('12',	'Algérie',	'Algeria',	'DZ',	'DZA'),
('16',	'Samoa Américaines',	'American Samoa',	'AS',	'ASM'),
('20',	'Andorre',	'Andorra',	'AD',	'AND'),
('24',	'Angola',	'Angola',	'AO',	'AGO'),
('28',	'Antigua-et-Barbuda',	'Antigua and Barbuda',	'AG',	'ATG'),
('31',	'Azerbaïdjan',	'Azerbaijan',	'AZ',	'AZE'),
('32',	'Argentine',	'Argentina',	'AR',	'ARG'),
('36',	'Australie',	'Australia',	'AU',	'AUS'),
('40',	'Autriche',	'Austria',	'AT',	'AUT'),
('44',	'Bahamas',	'Bahamas',	'BS',	'BHS'),
('48',	'Bahreïn',	'Bahrain',	'BH',	'BHR'),
('50',	'Bangladesh',	'Bangladesh',	'BD',	'BGD'),
('51',	'Arménie',	'Armenia',	'AM',	'ARM'),
('52',	'Barbade',	'Barbados',	'BB',	'BRB'),
('56',	'Belgique',	'Belgium',	'BE',	'BEL'),
('60',	'Bermudes',	'Bermuda',	'BM',	'BMU'),
('64',	'Bhoutan',	'Bhutan',	'BT',	'BTN'),
('68',	'Bolivie',	'Bolivia',	'BO',	'BOL'),
('70',	'Bosnie-Herzégovine',	'Bosnia and Herzegovina',	'BA',	'BIH'),
('72',	'Botswana',	'Botswana',	'BW',	'BWA'),
('74',	'Île Bouvet',	'Bouvet Island',	'BV',	'BVT'),
('76',	'Brésil',	'Brazil',	'BR',	'BRA'),
('84',	'Belize',	'Belize',	'BZ',	'BLZ'),
('86',	'Territoire Britannique de l''Océan Indien',	'British Indian Ocean Territory',	'IO',	'IOT'),
('90',	'Îles Salomon',	'Solomon Islands',	'SB',	'SLB'),
('92',	'Îles Vierges Britanniques',	'British Virgin Islands',	'VG',	'VGB'),
('96',	'Brunéi Darussalam',	'Brunei Darussalam',	'BN',	'BRN'),
('100',	'Bulgarie',	'Bulgaria',	'BG',	'BGR'),
('104',	'Myanmar',	'Myanmar',	'MM',	'MMR'),
('108',	'Burundi',	'Burundi',	'BI',	'BDI'),
('112',	'Bélarus',	'Belarus',	'BY',	'BLR'),
('116',	'Cambodge',	'Cambodia',	'KH',	'KHM'),
('120',	'Cameroun',	'Cameroon',	'CM',	'CMR'),
('124',	'Canada',	'Canada',	'CA',	'CAN'),
('132',	'Cap-vert',	'Cape Verde',	'CV',	'CPV'),
('136',	'Îles Caïmanes',	'Cayman Islands',	'KY',	'CYM'),
('140',	'République Centrafricaine',	'Central African',	'CF',	'CAF'),
('144',	'Sri Lanka',	'Sri Lanka',	'LK',	'LKA'),
('148',	'Tchad',	'Chad',	'TD',	'TCD'),
('152',	'Chili',	'Chile',	'CL',	'CHL'),
('156',	'Chine',	'China',	'CN',	'CHN'),
('158',	'Taïwan',	'Taiwan',	'TW',	'TWN'),
('162',	'Île Christmas',	'Christmas Island',	'CX',	'CXR'),
('166',	'Îles Cocos (Keeling)',	'Cocos (Keeling) Islands',	'CC',	'CCK'),
('170',	'Colombie',	'Colombia',	'CO',	'COL'),
('174',	'Comores',	'Comoros',	'KM',	'COM'),
('175',	'Mayotte',	'Mayotte',	'YT',	'MYT'),
('178',	'République du Congo',	'Republic of the Congo',	'CG',	'COG'),
('180',	'République Démocratique du Congo',	'The Democratic Republic Of The Congo',	'CD',	'COD'),
('184',	'Îles Cook',	'Cook Islands',	'CK',	'COK'),
('188',	'Costa Rica',	'Costa Rica',	'CR',	'CRI'),
('191',	'Croatie',	'Croatia',	'HR',	'HRV'),
('192',	'Cuba',	'Cuba',	'CU',	'CUB'),
('196',	'Chypre',	'Cyprus',	'CY',	'CYP'),
('203',	'République Tchèque',	'Czech Republic',	'CZ',	'CZE'),
('204',	'Bénin',	'Benin',	'BJ',	'BEN'),
('208',	'Danemark',	'Denmark',	'DK',	'DNK'),
('212',	'Dominique',	'Dominica',	'DM',	'DMA'),
('214',	'République Dominicaine',	'Dominican Republic',	'DO',	'DOM'),
('218',	'Équateur',	'Ecuador',	'EC',	'ECU'),
('222',	'El Salvador',	'El Salvador',	'SV',	'SLV'),
('226',	'Guinée Équatoriale',	'Equatorial Guinea',	'GQ',	'GNQ'),
('231',	'Éthiopie',	'Ethiopia',	'ET',	'ETH'),
('232',	'Érythrée',	'Eritrea',	'ER',	'ERI'),
('233',	'Estonie',	'Estonia',	'EE',	'EST'),
('234',	'Îles Féroé',	'Faroe Islands',	'FO',	'FRO'),
('238',	'Îles (malvinas) Falkland',	'Falkland Islands',	'FK',	'FLK'),
('239',	'Géorgie du Sud et les Îles Sandwich du Sud',	'South Georgia and the South Sandwich Islands',	'GS',	'SGS'),
('242',	'Fidji',	'Fiji',	'FJ',	'FJI'),
('246',	'Finlande',	'Finland',	'FI',	'FIN'),
('248',	'Îles Åland',	'Åland Islands',	'AX',	'ALA'),
('254',	'Guyane Française',	'French Guiana',	'GF',	'GUF'),
('258',	'Polynésie Française',	'French Polynesia',	'PF',	'PYF'),
('260',	'Terres Australes Françaises',	'French Southern Territories',	'TF',	'ATF'),
('262',	'Djibouti',	'Djibouti',	'DJ',	'DJI'),
('266',	'Gabon',	'Gabon',	'GA',	'GAB'),
('268',	'Géorgie',	'Georgia',	'GE',	'GEO'),
('270',	'Gambie',	'Gambia',	'GM',	'GMB'),
('275',	'Territoire Palestinien Occupé',	'Occupied Palestinian Territory',	'PS',	'PSE'),
('276',	'Allemagne',	'Germany',	'DE',	'DEU'),
('288',	'Ghana',	'Ghana',	'GH',	'GHA'),
('292',	'Gibraltar',	'Gibraltar',	'GI',	'GIB'),
('296',	'Kiribati',	'Kiribati',	'KI',	'KIR'),
('300',	'Grèce',	'Greece',	'GR',	'GRC'),
('304',	'Groenland',	'Greenland',	'GL',	'GRL'),
('308',	'Grenade',	'Grenada',	'GD',	'GRD'),
('312',	'Guadeloupe',	'Guadeloupe',	'GP',	'GLP'),
('316',	'Guam',	'Guam',	'GU',	'GUM'),
('320',	'Guatemala',	'Guatemala',	'GT',	'GTM'),
('324',	'Guinée',	'Guinea',	'GN',	'GIN'),
('328',	'Guyana',	'Guyana',	'GY',	'GUY'),
('332',	'Haïti',	'Haiti',	'HT',	'HTI'),
('334',	'Îles Heard et Mcdonald',	'Heard Island and McDonald Islands',	'HM',	'HMD'),
('336',	'Saint-Siège (état de la Cité du Vatican)',	'Vatican City State',	'VA',	'VAT'),
('340',	'Honduras',	'Honduras',	'HN',	'HND'),
('344',	'Hong-Kong',	'Hong Kong',	'HK',	'HKG'),
('348',	'Hongrie',	'Hungary',	'HU',	'HUN'),
('352',	'Islande',	'Iceland',	'IS',	'ISL'),
('356',	'Inde',	'India',	'IN',	'IND'),
('360',	'Indonésie',	'Indonesia',	'ID',	'IDN'),
('364',	'République Islamique d''Iran',	'Islamic Republic of Iran',	'IR',	'IRN'),
('368',	'Iraq',	'Iraq',	'IQ',	'IRQ'),
('372',	'Irlande',	'Ireland',	'IE',	'IRL'),
('376',	'Israël',	'Israel',	'IL',	'ISR'),
('380',	'Italie',	'Italy',	'IT',	'ITA'),
('384',	'Côte d''Ivoire',	'Côte d''Ivoire',	'CI',	'CIV'),
('388',	'Jamaïque',	'Jamaica',	'JM',	'JAM'),
('392',	'Japon',	'Japan',	'JP',	'JPN'),
('398',	'Kazakhstan',	'Kazakhstan',	'KZ',	'KAZ'),
('400',	'Jordanie',	'Jordan',	'JO',	'JOR'),
('404',	'Kenya',	'Kenya',	'KE',	'KEN'),
('408',	'République Populaire Démocratique de Corée',	'Democratic People''s Republic of Korea',	'KP',	'PRK'),
('410',	'République de Corée',	'Republic of Korea',	'KR',	'KOR'),
('414',	'Koweït',	'Kuwait',	'KW',	'KWT'),
('417',	'Kirghizistan',	'Kyrgyzstan',	'KG',	'KGZ'),
('418',	'République Démocratique Populaire Lao',	'Lao People''s Democratic Republic',	'LA',	'LAO'),
('422',	'Liban',	'Lebanon',	'LB',	'LBN'),
('426',	'Lesotho',	'Lesotho',	'LS',	'LSO'),
('428',	'Lettonie',	'Latvia',	'LV',	'LVA'),
('430',	'Libéria',	'Liberia',	'LR',	'LBR'),
('434',	'Jamahiriya Arabe Libyenne',	'Libyan Arab Jamahiriya',	'LY',	'LBY'),
('438',	'Liechtenstein',	'Liechtenstein',	'LI',	'LIE'),
('440',	'Lituanie',	'Lithuania',	'LT',	'LTU'),
('442',	'Luxembourg',	'Luxembourg',	'LU',	'LUX'),
('446',	'Macao',	'Macao',	'MO',	'MAC'),
('450',	'Madagascar',	'Madagascar',	'MG',	'MDG'),
('454',	'Malawi',	'Malawi',	'MW',	'MWI'),
('458',	'Malaisie',	'Malaysia',	'MY',	'MYS'),
('462',	'Maldives',	'Maldives',	'MV',	'MDV'),
('466',	'Mali',	'Mali',	'ML',	'MLI'),
('470',	'Malte',	'Malta',	'MT',	'MLT'),
('474',	'Martinique',	'Martinique',	'MQ',	'MTQ'),
('478',	'Mauritanie',	'Mauritania',	'MR',	'MRT'),
('480',	'Maurice',	'Mauritius',	'MU',	'MUS'),
('484',	'Mexique',	'Mexico',	'MX',	'MEX'),
('492',	'Monaco',	'Monaco',	'MC',	'MCO'),
('496',	'Mongolie',	'Mongolia',	'MN',	'MNG'),
('498',	'République de Moldova',	'Republic of Moldova',	'MD',	'MDA'),
('500',	'Montserrat',	'Montserrat',	'MS',	'MSR'),
('504',	'Maroc',	'Morocco',	'MA',	'MAR'),
('508',	'Mozambique',	'Mozambique',	'MZ',	'MOZ'),
('512',	'Oman',	'Oman',	'OM',	'OMN'),
('516',	'Namibie',	'Namibia',	'NA',	'NAM'),
('520',	'Nauru',	'Nauru',	'NR',	'NRU'),
('524',	'Népal',	'Nepal',	'NP',	'NPL'),
('528',	'Pays-Bas',	'Netherlands',	'NL',	'NLD'),
('530',	'Antilles Néerlandaises',	'Netherlands Antilles',	'AN',	'ANT'),
('533',	'Aruba',	'Aruba',	'AW',	'ABW'),
('540',	'Nouvelle-Calédonie',	'New Caledonia',	'NC',	'NCL'),
('548',	'Vanuatu',	'Vanuatu',	'VU',	'VUT'),
('554',	'Nouvelle-Zélande',	'New Zealand',	'NZ',	'NZL'),
('558',	'Nicaragua',	'Nicaragua',	'NI',	'NIC'),
('562',	'Niger',	'Niger',	'NE',	'NER'),
('566',	'Nigéria',	'Nigeria',	'NG',	'NGA'),
('570',	'Niué',	'Niue',	'NU',	'NIU'),
('574',	'Île Norfolk',	'Norfolk Island',	'NF',	'NFK'),
('578',	'Norvège',	'Norway',	'NO',	'NOR'),
('580',	'Îles Mariannes du Nord',	'Northern Mariana Islands',	'MP',	'MNP'),
('581',	'Îles Mineures Éloignées des États-Unis',	'United States Minor Outlying Islands',	'UM',	'UMI'),
('583',	'États Fédérés de Micronésie',	'Federated States of Micronesia',	'FM',	'FSM'),
('584',	'Îles Marshall',	'Marshall Islands',	'MH',	'MHL'),
('585',	'Palaos',	'Palau',	'PW',	'PLW'),
('586',	'Pakistan',	'Pakistan',	'PK',	'PAK'),
('591',	'Panama',	'Panama',	'PA',	'PAN'),
('598',	'Papouasie-Nouvelle-Guinée',	'Papua New Guinea',	'PG',	'PNG'),
('600',	'Paraguay',	'Paraguay',	'PY',	'PRY'),
('604',	'Pérou',	'Peru',	'PE',	'PER'),
('608',	'Philippines',	'Philippines',	'PH',	'PHL'),
('612',	'Pitcairn',	'Pitcairn',	'PN',	'PCN'),
('616',	'Pologne',	'Poland',	'PL',	'POL'),
('620',	'Portugal',	'Portugal',	'PT',	'PRT'),
('624',	'Guinée-Bissau',	'Guinea-Bissau',	'GW',	'GNB'),
('626',	'Timor-Leste',	'Timor-Leste',	'TL',	'TLS'),
('630',	'Porto Rico',	'Puerto Rico',	'PR',	'PRI'),
('634',	'Qatar',	'Qatar',	'QA',	'QAT'),
('638',	'Réunion',	'Réunion',	'RE',	'REU'),
('642',	'Roumanie',	'Romania',	'RO',	'ROU'),
('643',	'Fédération de Russie',	'Russian Federation',	'RU',	'RUS'),
('646',	'Rwanda',	'Rwanda',	'RW',	'RWA'),
('654',	'Sainte-Hélène',	'Saint Helena',	'SH',	'SHN'),
('659',	'Saint-Kitts-et-Nevis',	'Saint Kitts and Nevis',	'KN',	'KNA'),
('660',	'Anguilla',	'Anguilla',	'AI',	'AIA'),
('662',	'Sainte-Lucie',	'Saint Lucia',	'LC',	'LCA'),
('666',	'Saint-Pierre-et-Miquelon',	'Saint-Pierre and Miquelon',	'PM',	'SPM'),
('670',	'Saint-Vincent-et-les Grenadines',	'Saint Vincent and the Grenadines',	'VC',	'VCT'),
('674',	'Saint-Marin',	'San Marino',	'SM',	'SMR'),
('678',	'Sao Tomé-et-Principe',	'Sao Tome and Principe',	'ST',	'STP'),
('682',	'Arabie Saoudite',	'Saudi Arabia',	'SA',	'SAU'),
('686',	'Sénégal',	'Senegal',	'SN',	'SEN'),
('690',	'Seychelles',	'Seychelles',	'SC',	'SYC'),
('694',	'Sierra Leone',	'Sierra Leone',	'SL',	'SLE'),
('702',	'Singapour',	'Singapore',	'SG',	'SGP'),
('703',	'Slovaquie',	'Slovakia',	'SK',	'SVK'),
('704',	'Viet Nam',	'Vietnam',	'VN',	'VNM'),
('705',	'Slovénie',	'Slovenia',	'SI',	'SVN'),
('706',	'Somalie',	'Somalia',	'SO',	'SOM'),
('710',	'Afrique du Sud',	'South Africa',	'ZA',	'ZAF'),
('716',	'Zimbabwe',	'Zimbabwe',	'ZW',	'ZWE'),
('724',	'Espagne',	'Spain',	'ES',	'ESP'),
('732',	'Sahara Occidental',	'Western Sahara',	'EH',	'ESH'),
('736',	'Soudan',	'Sudan',	'SD',	'SDN'),
('740',	'Suriname',	'Suriname',	'SR',	'SUR'),
('744',	'Svalbard etÎle Jan Mayen',	'Svalbard and Jan Mayen',	'SJ',	'SJM'),
('748',	'Swaziland',	'Swaziland',	'SZ',	'SWZ'),
('752',	'Suède',	'Sweden',	'SE',	'SWE'),
('756',	'Suisse',	'Switzerland',	'CH',	'CHE'),
('760',	'République Arabe Syrienne',	'Syrian Arab Republic',	'SY',	'SYR'),
('762',	'Tadjikistan',	'Tajikistan',	'TJ',	'TJK'),
('764',	'Thaïlande',	'Thailand',	'TH',	'THA'),
('768',	'Togo',	'Togo',	'TG',	'TGO'),
('772',	'Tokelau',	'Tokelau',	'TK',	'TKL'),
('776',	'Tonga',	'Tonga',	'TO',	'TON'),
('780',	'Trinité-et-Tobago',	'Trinidad and Tobago',	'TT',	'TTO'),
('784',	'Émirats Arabes Unis',	'United Arab Emirates',	'AE',	'ARE'),
('788',	'Tunisie',	'Tunisia',	'TN',	'TUN'),
('792',	'Turquie',	'Turkey',	'TR',	'TUR'),
('795',	'Turkménistan',	'Turkmenistan',	'TM',	'TKM'),
('796',	'Îles Turks et Caïques',	'Turks and Caicos Islands',	'TC',	'TCA'),
('798',	'Tuvalu',	'Tuvalu',	'TV',	'TUV'),
('800',	'Ouganda',	'Uganda',	'UG',	'UGA'),
('804',	'Ukraine',	'Ukraine',	'UA',	'UKR'),
('807',	'L''ex-République Yougoslave de Macédoine',	'The Former Yugoslav Republic of Macedonia',	'MK',	'MKD'),
('818',	'Égypte',	'Egypt',	'EG',	'EGY'),
('826',	'Royaume-Uni',	'United Kingdom',	'GB',	'GBR'),
('833',	'Île de Man',	'Isle of Man',	'IM',	'IMN'),
('834',	'République-Unie de Tanzanie',	'United Republic Of Tanzania',	'TZ',	'TZA'),
('840',	'États-Unis',	'United States',	'US',	'USA'),
('850',	'Îles Vierges des États-Unis',	'U.S. Virgin Islands',	'VI',	'VIR'),
('854',	'Burkina Faso',	'Burkina Faso',	'BF',	'BFA'),
('858',	'Uruguay',	'Uruguay',	'UY',	'URY'),
('860',	'Ouzbékistan',	'Uzbekistan',	'UZ',	'UZB'),
('862',	'Venezuela',	'Venezuela',	'VE',	'VEN'),
('876',	'Wallis et Futuna',	'Wallis and Futuna',	'WF',	'WLF'),
('882',	'Samoa',	'Samoa',	'WS',	'WSM'),
('887',	'Yémen',	'Yemen',	'YE',	'YEM'),
('891',	'Serbie-et-Monténégro',	'Serbia and Montenegro',	'CS',	'SCG'),
('894',	'Zambie',	'Zambia',	'ZM',	'ZMB'),
('250',	'France',	'France',	'FR',	'FRA');

INSERT INTO "tr_niveau_langue" ("id_niveau", "lb_niveau") VALUES
(1,	'Langue maternelle'),
(2,	'Courant'),
(3,	'Bon'),
(4,	'Moyen');


INSERT INTO "tr_niveau" ("id_type_niveu", "lb_nom") VALUES
(1,	'Soumission'),
(2,	'Evaluation'),
(3,	'Publication');


INSERT INTO public.tr_langue (id_langue, lb_langue, cd_langue) VALUES
(1, 'Français', 'FR'),
(2, 'Anglais', 'EN'),
(3, 'Allemand', 'GR'),
(4, 'Arabe', 'ARA'),
(5, 'Chinois (mandarin)', 'CHI'),
(6, 'Espagnol', 'ES'),
(7, 'Hindi /Ourdou', 'HI'),
(8, 'Italien', null),
(9, 'Japonais', null),
(10, 'Portugais', null),
(11, 'Russe', null),
(12, 'Bengali', null),
(13, 'Panjabi', null),
(14, 'Haoussa', null),
(15, 'Javanais', null),
(16, 'Télougou', null),
(17, 'Malais', null),
(18, 'Coréen', null),
(19, 'Marathi', null),
(20, 'Turc', null),
(21, 'Vietnamien', null),
(22, 'Tamoul', null),
(23, 'Persan', null),
(24, 'Thaï', null),
(25, 'Gujarati', null),
(26, 'Chinois (wu)', null),
(27, 'Chinois (cantonais)', null),
(28, 'Chinois (jing)', null),
(29, 'Chinois (min)', null),
(30, 'Chinois (xiang)', null),
(31, 'Chinois (gan)', null),
(32, 'Chinois (hakka)', null),
(33, 'Chinois (zhouang)', null),
(34, 'Chinois (min bei)', null),
(35, 'Polonais', null),
(36, 'Pachtou', null),
(37, 'Kannada', null),
(38, 'Malayalam', null),
(39, 'Soundanais', null),
(40, 'Oriya (odiya)', null),
(41, 'Birman', null),
(42, 'Ukrainien', null),
(43, 'Bhojpouri', null),
(44, 'Filipino (tagalog)', null),
(45, 'Yorouba', null),
(46, 'Maithili', null),
(47, 'Ouzbek', null),
(48, 'Sindhi', null),
(49, 'Amharique', null),
(50, 'Peul (fulani)', null),
(51, 'Roumain', null),
(52, 'Oromo', null),
(53, 'Igbo (ibo)', null),
(54, 'Azéri', null),
(55, 'Awadhi', null),
(56, 'Visayan (cibuano)', null),
(57, 'Néerlandais', null),
(58, 'Kurde', null),
(59, 'Malgache', null),
(60, 'Saraiki', null),
(61, 'Chittagonien', null),
(62, 'Khmer', null),
(63, 'Turkmène', null),
(64, 'Assamais', null),
(65, 'Madourais', null),
(66, 'Somali', null),
(67, 'Marwari', null),
(68, 'Magahi', null),
(69, 'Haryanvi', null),
(70, 'Hongrois', null),
(71, 'Chhattisgarhi', null),
(72, 'Grec', null),
(73, 'Chewa (nyanja)', null),
(74, 'Deccan', null),
(75, 'Akan', null),
(76, 'Kazakh', null),
(77, 'Sylheti', null),
(78, 'Zoulou', null),
(79, 'Tchèque', null),
(80, 'Créole haïtien', null);

INSERT INTO "tr_inst_fi" ("id_inst_fi", "lb_nom", "mnt_max", "mnt_min") VALUES
(1,	'Projet de recherche individuelle JCJC',	10000,	8000),
(3,	'Projet international bilatéral PRCI',	20500,	18000),
(2,	'Projet de recherche collaborative PRC',	12000,	10000),
(4,	'Projet entre entitès publiques et privées PRCE',	9000,	7500);

INSERT INTO "tr_info" ("cd_info", "lb_info") VALUES
('1',	'On cochant cette case, vous aurez la possibilité d''ajouter des pôles de compétitivité a ce projet, au moins un pôle doit être renseigné.'),
('2',	'On cochant cette case, vous aurez la possibilité de solliciter des cofinancement a ce projet, au moins un cofinancement doit être renseigné.'),
('3',	'On cochant cette case, vous aurez la possibilité de faire appel a une ou plusieurs infrastructures pour ce projet, au moins une infrastructure doit être renseignée.'),
('4',	'Les versions françaises et anglaises sont requises, Ces résumés ne sont pas confidentiels et pourrons ainsi être transmis à d''autres organismes (INCA,...).');


INSERT INTO "tr_inf_rech" ("id_inf_rech", "lb_inf_rech", "lb_nom_long") VALUES
(1,	'(IR) ACTRIS - France',	'Description concerne l''infrastructure de recherche ACTRIS - France'),
(2,	'(IR) ANAEE - France natura',	'Description concerne l''infrastructure de recherche - ANAEE- France'),
(3,	'(IR) APOLLON',	'Description concerne l''infrastructure de recherche - APOLLON- France'),
(4,	'(IR) CDS',	'Description concerne l''infrastructure de recherche - CDS- France'),
(5,	'(IR) DUNE',	'Description concerne l''infrastructure de recherche - DUNE- France');

INSERT INTO "tr_fonction" ("id_fonction", "lb_nom_fr", "lb_nom_en") VALUES
(1,	'test fonction 1',	'fonction 1'),
(2,	'test fonction 2',	'fonction'),
(3,	'test fonction 3',	'fonction'),
(4,	'test fonction 4',	'fonction');

INSERT INTO "tr_etat_sol" ("cd_etat_sollicitation", "lb_etat_sollicitation") VALUES
(1,	'Impossible'),
(2,	'Contrainte/Refus'),
(3,	'Neutre'),
(4,	'A contacter'),
(5,	'Contacté'),
(6,	'Accepté');

INSERT INTO "tr_dispo_comite" ("id_choix", "nb_ordre", "lb_nom") VALUES
('1',	1,	'Oui'),
('2',	2,	'Non'),
('3',	3,	'neutre');

-- ici --

INSERT INTO "tr_categorie_erc" ("id_cat_erc", "lb_cat_erc") VALUES
(1,	'SH - Social Sciences and Humanities'),
(2,	'SC - Social Sciences and culturels'),
(3,	'TT - Test'),
(4,	'SS - Mot clé 2'),
(5,	'SH - Mot clé 3'),
(6,	'SH - Mot clé 4'),
(7,	'SH - Mot clé 5');


INSERT INTO "tr_disc_erc" ("id_disc_erc", "id_cat_erc", "lb_disc_erc") VALUES
(1,	1,	'SH01 - Individuals, Markets and Organisations'),
(2,	2,	'SH02 - Institutions, Values, Environment and Space'),
(3,	3,	'SC03 - TEST'),
(4,	4,	'SC04 - TEST 4'),
(5,	5,	'SC05 - TEST 5'),
(6,	6,	'SC06 - TEST 6'),
(7,	7,	'SC07 - TEST 7');

INSERT INTO "tg_mot_cle_erc" ("id_mc_erc", "id_disc_erc", "lb_nom_fr") VALUES
(1, 1, 'SH01_01 Political systems, governance'),
(2, 2, 'SH02_01 Democratisation and social movements'),
(3, 3, 'SC03_01 TEST'),
(4, 4, 'SC07_01 TEST 5'),
(5, 5, 'SC06_01 TEST 4'),
(6, 6, 'SC04_01 TEST 2'),
(7, 7, 'SC05_01 TEST 3');


INSERT INTO "tr_departement" ("id_departement", "lb_court", "lb_long") VALUES
(1,	'NuMa',	'Numérique et mathématiques'),
(2,	'SPICE',	'Sciences physiques,ingénierie,chimie,énergie'),
(3,	'BS',	'Biologie Santé'),
(4,	'SHS',	'Sciences humaines et sociales'),
(5,	'EERB',	'Environnements,écosystèmes,ressources biologiques');


INSERT INTO "tr_co_fi" ("id_co_fi", "lb_co_fi") VALUES
(1,	'Caisse Nationale de Solidarité'),
(2,	'Direction Générale de l''Armement (DGA)'),
(3,	'Direction Générale de l''Offre de Soins (DGOS)'),
(4,	'Ministère de l''Agriculture, de l''Agroalimentaire'),
(5,	'Secrétariat Générale de la Défense et Sécurité');

INSERT INTO "tr_classe_formulaire" ("id_classe_formulaire", "lb_nom") VALUES
(1,	'Soumission'),
(2,	'Grille R'),
(3,	'Grille L'),
(4,	'Grille Expert'),
(5,	'Rebuttal'),
(6,	'Rapport final');

INSERT INTO "tr_choix_dispo_expert" ("id_choix_expert", "lb_nom_en", "nb_ordre", "lb_nom_fr") VALUES
('2',	'Non, je ne suis pas  disponible pour cette période',	2,	'Non, je ne suis pas  disponible pour cette période'),
('3',	'Aucun avis',	3,	'Aucun avis'),
('1',	'Oui, je suis disponible pour cette période',	1,	'Oui, je suis disponible pour cette période');

INSERT INTO "tr_cat_rd" ("id_cat_rd", "lb_categorie") VALUES
(1,	'Développement expérimental'),
(2,	'Développement culturel'),
(3,	'Test'),
(4,	'Test 2');

INSERT INTO "tr_avis_projet" ("cd_avis", "code_avis", "lb_nom_fr", "lb_nom_en", "cd_couleur") VALUES
(2,	'+',	'Favorable',	'Favourable',	'#CCFFCC'),
(1,	'++',	'Très favorable',	'Very favourable',	'#33CC33'),
(4,	'-',	'Défavorable',	'Unfavourable',	'#FFCCFF'),
(3,	'=',	'Indifférent',	'Indifferent',	'#FFFFFF'),
(5,	'X',	'Conflit',	'conflict',	'#FF0000'),
(6,	'O',	'Refus',	'Refus',	NULL);

INSERT INTO "tr_ag_fi" ("id_agence_fi", "lb_agenc_fi") VALUES
(1,	'Autriche (Austria) - FWF'),
(2,	'France (FRENSH) - AFF'),
(3,	'Allemagne (Germanie) - AAF'),
(4,	'Italie (Italy) - AIF');

-- after create data bdd ---
INSERT INTO "tl_profil_classe" ("id_classe_formulaire", "id_profil") VALUES
(1,	15),
(2,	9),
(3,	9),
(4,	16),
(5,	15),
(6,	9);
