<?php

namespace Faker\Provider\fr_FR;

class Address extends \Faker\Provider\Address
{
    protected static $citySuffix = array('Ville', 'Bourg', '-les-Bains', '-sur-Mer', '-la-Forêt', 'boeuf', 'nec', 'dan');
    protected static $streetPrefix = array('rue', 'rue', 'chemin', 'avenue', 'boulevard', 'place', 'impasse');
    protected static $cityFormats = array(
        '{{lastName}}',
        '{{lastName}}',
        '{{lastName}}',
        '{{lastName}}',
        '{{lastName}}{{citySuffix}}',
        '{{lastName}}{{citySuffix}}',
        '{{lastName}}{{citySuffix}}',
        '{{lastName}}-sur-{{lastName}}',
    );
    protected static $streetNameFormats = array(
        '{{streetPrefix}} {{lastName}}',
        '{{streetPrefix}} {{firstName}} {{lastName}}',
        '{{streetPrefix}} de {{lastName}}',
    );
    protected static $streetAddressFormats = array(
        '{{streetName}}',
        '{{buildingNumber}}, {{streetName}}',
        '{{buildingNumber}}, {{streetName}}',
        '{{buildingNumber}}, {{streetName}}',
        '{{buildingNumber}}, {{streetName}}',
        '{{buildingNumber}}, {{streetName}}',
    );
    protected static $addressFormats = array(
        "{{streetAddress}}\n{{postcode}} {{city}}",
    );

    protected static $buildingNumber = array('%', '%#', '%#', '%#', '%##');
    protected static $postcode = array('#####', '## ###');

    protected static $country = array(
        'Afghanistan', 'Afrique du sud', 'Albanie', 'Algérie', 'Allemagne', 'Andorre', 'Angola', 'Anguilla', 'Antarctique', 'Antigua et Barbuda', 'Antilles néerlandaises', 'Arabie saoudite', 'Argentine', 'Arménie', 'Aruba', 'Australie', 'Autriche', 'Azerbaïdjan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Belgique', 'Belize', 'Benin', 'Bermudes (Les)', 'Bhoutan', 'Biélorussie', 'Bolivie', 'Bosnie-Herzégovine', 'Botswana', 'Bouvet (Îles)', 'Brunei', 'Brésil', 'Bulgarie', 'Burkina Faso', 'Burundi', 'Cambodge', 'Cameroun', 'Canada', 'Cap Vert', 'Cayman (Îles)', 'Chili', 'Chine (Rép. pop.)', 'Christmas (Île)', 'Chypre', 'Cocos (Îles)', 'Colombie', 'Comores', 'Cook (Îles)', 'Corée du Nord', 'Corée, Sud', 'Costa Rica', 'Croatie', 'Cuba', 'Côte d\'Ivoire', 'Danemark', 'Djibouti', 'Dominique', 'Égypte', 'El Salvador', 'Émirats arabes unis', 'Équateur', 'Érythrée', 'Espagne', 'Estonie', 'États-Unis', 'Ethiopie', 'Falkland (Île)', 'Fidji (République des)', 'Finlande', 'France', 'Féroé (Îles)', 'Gabon',
        'Gambie', 'Ghana', 'Gibraltar', 'Grenade', 'Groenland', 'Grèce', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinée', 'Guinée Equatoriale', 'Guinée-Bissau', 'Guyane', 'Guyane française', 'Géorgie', 'Géorgie du Sud et Sandwich du Sud (Îles)', 'Haïti', 'Heard et McDonald (Îles)', 'Honduras', 'Hong Kong', 'Hongrie', 'Îles Mineures Éloignées des États-Unis', 'Inde', 'Indonésie', 'Irak', 'Iran', 'Irlande', 'Islande', 'Israël', 'Italie', 'Jamaïque', 'Japon', 'Jordanie', 'Kazakhstan', 'Kenya', 'Kirghizistan', 'Kiribati', 'Koweit', 'La Barbad', 'Laos', 'Lesotho', 'Lettonie', 'Liban', 'Libye', 'Libéria', 'Liechtenstein', 'Lithuanie', 'Luxembourg', 'Macau', 'Macédoine', 'Madagascar', 'Malaisie', 'Malawi', 'Maldives (Îles)', 'Mali', 'Malte', 'Mariannes du Nord (Îles)', 'Maroc', 'Marshall (Îles)', 'Martinique', 'Maurice', 'Mauritanie', 'Mayotte', 'Mexique', 'Micronésie (États fédérés de)', 'Moldavie', 'Monaco', 'Mongolie', 'Montserrat', 'Mozambique', 'Myanmar', 'Namibie', 'Nauru', 'Nepal',
        'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk (Îles)', 'Norvège', 'Nouvelle Calédonie', 'Nouvelle-Zélande', 'Oman', 'Ouganda', 'Ouzbékistan', 'Pakistan', 'Palau', 'Panama', 'Papouasie-Nouvelle-Guinée', 'Paraguay', 'Pays-Bas', 'Philippines', 'Pitcairn (Îles)', 'Pologne', 'Polynésie française', 'Porto Rico', 'Portugal', 'Pérou', 'Qatar', 'Roumanie', 'Royaume-Uni', 'Russie', 'Rwanda', 'Rép. Dém. du Congo', 'République centrafricaine', 'République Dominicaine', 'République tchèque', 'Réunion (La)', 'Sahara Occidental', 'Saint Pierre et Miquelon', 'Saint Vincent et les Grenadines', 'Saint-Kitts et Nevis', 'Saint-Marin (Rép. de)', 'Sainte Hélène', 'Sainte Lucie', 'Samoa', 'Samoa', 'Seychelles', 'Sierra Leone', 'Singapour', 'Slovaquie', 'Slovénie', 'Somalie', 'Soudan', 'Sri Lanka', 'Suisse', 'Suriname', 'Suède', 'Svalbard et Jan Mayen (Îles)', 'Swaziland', 'Syrie', 'São Tomé et Príncipe (Rép.)', 'Sénégal', 'Tadjikistan', 'Taiwan', 'Tanzanie', 'Tchad',
        'Territoire britannique de l\'océan Indien', 'Territoires français du sud', 'Thailande', 'Timor', 'Togo', 'Tokelau', 'Tonga', 'Trinité et Tobago', 'Tunisie', 'Turkménistan', 'Turks et Caïques (Îles)', 'Turquie', 'Tuvalu', 'Ukraine', 'Uruguay', 'Vanuatu', 'Vatican (Etat du)', 'Venezuela', 'Vierges (Îles)', 'Vierges britanniques (Îles)', 'Vietnam', 'Wallis et Futuna (Îles)', 'Yemen', 'Yougoslavie', 'Zambie', 'Zaïre', 'Zimbabwe'
    );

    private static $regions = array(
        'Alsace', 'Aquitaine', 'Auvergne', 'Bourgogne', 'Bretagne', 'Centre', 'Champagne-Ardenne',
        'Corse', 'Franche-Comté', 'Île-de-France', 'Languedoc-Roussillon', 'Limousin',
        'Lorraine', 'Midi-Pyrénées', 'Nord-Pas-de-Calais', 'Basse-Normandie', 'Haute-Normandie',
        'Pays-de-Loire', 'Picardie', 'Poitou-Charentes', "Provence-Alpes-Côte d'Azur", 'Rhone-Alpes',
        'Guadeloupe', 'Martinique', 'Guyane', 'Réunion', 'Saint-Pierre-et-Miquelon', 'Mayotte',
        'Saint-Barthélémy', 'Saint-Martin', 'Wallis-et-Futuna', 'Polynésie française', 'Nouvelle-Calédonie'
    );

    private static $departments = array(
        array('01' => 'Ain'), array('02' => 'Aisne'), array('03' => 'Allier'), array('04' => 'Alpes-de-Haute-Provence'), array('05' => 'Hautes-Alpes'),
        array('06' => 'Alpes-Maritimes'), array('07' => 'Ardèche'), array('08' => 'Ardennes'), array('09' => 'Ariège'), array('10' => 'Aube'),
        array('11' => 'Aude'), array('12' => 'Aveyron'), array('13' => 'Bouches-du-Rhône'), array('14' => 'Calvados'), array('15' => 'Cantal'),
        array('16' => 'Charente'), array('17' => 'Charente-Maritime'), array('18' => 'Cher'), array('19' => 'Corrèze'), array('2A' => 'Corse-du-Sud'),
        array('2B' => 'Haute-Corse'), array('21' => "Côte-d'Or"), array('22' => "Côtes-d'Armor"), array('23' => 'Creuse'), array('24' => 'Dordogne'),
        array('25' => 'Doubs'), array('26' => 'Drôme'), array('27' => 'Eure'), array('28' => 'Eure-et-Loir'), array('29' => 'Finistère'), array('30' => 'Gard'),
        array('31' => 'Haute-Garonne'), array('32' => 'Gers'), array('33' => 'Gironde'), array('34' => 'Hérault'), array('35' => 'Ille-et-Vilaine'),
        array('36' => 'Indre'), array('37' => 'Indre-et-Loire'), array('38' => 'Isère'), array('39' => 'Jura'), array('40' => 'Landes'), array('41' => 'Loir-et-Cher'),
        array('42' => 'Loire'), array('43' => 'Haute-Loire'), array('44' => 'Loire-Atlantique'), array('45' => 'Loiret'), array('46' => 'Lot'),
        array('47' => 'Lot-et-Garonne'), array('48' => 'Lozère'), array('49' => 'Maine-et-Loire'), array('50' => 'Manche'), array('51' => 'Marne'),
        array('52' => 'Haute-Marne'), array('53' => 'Mayenne'), array('54' => 'Meurthe-et-Moselle'), array('55' => 'Meuse'), array('56' => 'Morbihan'),
        array('57' => 'Moselle'), array('58' => 'Nièvre'), array('59' => 'Nord'), array('60' => 'Oise'), array('61' => 'Orne'), array('62' => 'Pas-de-Calais'),
        array('63' => 'Puy-de-Dôme'), array('64' => 'Pyrénées-Atlantiques'), array('65' => 'Hautes-Pyrénées'), array('66' => 'Pyrénées-Orientales'),
        array('67' => 'Bas-Rhin'), array('68' => 'Haut-Rhin'), array('69' => 'Rhône'), array('70' => 'Haute-Saône'), array('71' => 'Saône-et-Loire'),
        array('72' => 'Sarthe'), array('73' => 'Savoie'), array('74' => 'Haute-Savoie'), array('75' => 'Paris'), array('76' => 'Seine-Maritime'),
        array('77' => 'Seine-et-Marne'), array('78' => 'Yvelines'), array('79' => 'Deux-Sèvres'), array('80' => 'Somme'), array('81' => 'Tarn'),
        array('82' => 'Tarn-et-Garonne'), array('83' => 'Var'), array('84' => 'Vaucluse'), array('85' => 'Vendée'), array('86' => 'Vienne'),
        array('87' => 'Haute-Vienne'), array('88' => 'Vosges'), array('89' => 'Yonne'), array('90' => 'Territoire de Belfort'), array('91' => 'Essonne'),
        array('92' => 'Hauts-de-Seine'), array('93' => 'Seine-Saint-Denis'), array('94' => 'Val-de-Marne'), array('95' => "Val-d'Oise"),
        array('971' => 'Guadeloupe'), array('972' => 'Martinique'), array('973' => 'Guyane'), array('974' => 'La Réunion'), array('976' => 'Mayotte')
     );

    protected static $secondaryAddressFormats = array('Apt. ###', 'Suite ###', 'Étage ###', "Bât. ###", "Chambre ###");

    /**
     * @example 'Appt. 350'
     */
    public static function secondaryAddress()
    {
        return static::numerify(static::randomElement(static::$secondaryAddressFormats));
    }

     /**
     * @example 'rue'
     */
    public static function streetPrefix()
    {
        return static::randomElement(static::$streetPrefix);
    }

    /**
     * Randomly returns a french region.
     *
     * @example 'Guadeloupe'
     *
     * @return string
     */
    public static function region()
    {
        return static::randomElement(static::$regions);
    }

    /**
     * Randomly returns a french department ('departmentNumber' => 'departmentName').
     *
     * @example array('2B' => 'Haute-Corse')
     *
     * @return array
     */
    public static function department()
    {
        return static::randomElement(static::$departments);
    }

    /**
     * Randomly returns a french department name.
     *
     * @example 'Ardèche'
     *
     * @return string
     */
    public static function departmentName()
    {
        $randomDepartmentName = array_values(static::department());

        return $randomDepartmentName[0];
    }

    /**
     * Randomly returns a french department number.
     *
     * @example '59'
     *
     * @return string
     */
    public static function departmentNumber()
    {
        $randomDepartmentNumber = array_keys(static::department());

        return $randomDepartmentNumber[0];
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php

namespace Faker\Provider\fr_FR;

use Faker\Calculator\Luhn;

class Company extends \Faker\Provider\Company
{
    /**
     * @var array French company name formats.
     */
    protected static $formats = array(
        '{{lastName}} {{companySuffix}}',
        '{{lastName}} {{lastName}} {{companySuffix}}',
        '{{lastName}}',
        '{{lastName}}',
    );

    /**
     * @var array French catch phrase formats.
     */
    protected static $catchPhraseFormats = array(
        '{{catchPhraseNoun}} {{catchPhraseVerb}} {{catchPhraseAttribute}}',
    );

    /**
     * @var array French nouns (used by the catch phrase format).
     */
    protected static $noun = array(
        'la sécurité', 'le plaisir', 'le confort', 'la simplicité', "l'assurance", "l'art", 'le pouvoir', 'le droit',
        'la possibilité', "l'avantage", 'la liberté'
    );

    /**
     * @var array French verbs (used by the catch phrase format).
     */
    protected static $verb = array(
        'de rouler', "d'avancer", "d'évoluer", 'de changer', "d'innover", 'de louer', "d'atteindre vos buts",
        'de concrétiser vos projets'
    );

    /**
     * @var array End of sentences (used by the catch phrase format).
     */
    protected static $attribute = array(
        'de manière efficace', 'plus rapidement', 'plus facilement', 'plus simplement', 'en toute tranquilité',
        'avant-tout', 'autrement', 'naturellement', 'à la pointe', 'sans soucis', "à l'état pur",
        'à sa source', 'de manière sûre', 'en toute sécurité'
    );

    /**
     * @var array Company suffixes.
     */
    protected static $companySuffix = array('SA', 'S.A.', 'SARL', 'S.A.R.L.', 'SAS', 'S.A.S.', 'et Fils');

    protected static $siretNicFormats = array('####', '0###', '00#%');

    /**
     * Returns a random catch phrase noun.
     *
     * @return string
     */
    public function catchPhraseNoun()
    {
        return static::randomElement(static::$noun);
    }

    /**
     * Returns a random catch phrase attribute.
     *
     * @return string
     */
    public function catchPhraseAttribute()
    {
        return static::randomElement(static::$attribute);
    }

    /**
     * Returns a random catch phrase verb.
     *
     * @return string
     */
    public function catchPhraseVerb()
    {
        return static::randomElement(static::$verb);
    }

    /**
     * Generates a french catch phrase.
     *
     * @return string
     */
    public function catchPhrase()
    {
        do {
            $format = static::randomElement(static::$catchPhraseFormats);
            $catchPhrase = ucfirst($this->generator->parse($format));

            if ($this->isCatchPhraseValid($catchPhrase)) {
                break;
            }
        } while (true);

        return $catchPhrase;
    }

    /**
     * Generates a siret number (14 digits) that passes the Luhn check.
     *
     * @see http://fr.wikipedia.org/wiki/Syst%C3%A8me_d'identification_du_r%C3%A9pertoire_des_%C3%A9tablissements
     * @return string
     */
    public function siret($formatted = true)
    {
        $siret = self::siren(false);
        $nicFormat = static::randomElement(static::$siretNicFormats);
        $siret .= $this->numerify($nicFormat);
        $siret .= Luhn::computeCheckDigit($siret);
        if ($formatted) {
            $siret = substr($siret, 0, 3) . ' ' . substr($siret, 3, 3) . ' ' . substr($siret, 6, 3) . ' ' . substr($siret, 9, 5);
        }

        return $siret;
    }

    /**
     * Generates a siren number (9 digits) that passes the Luhn check.
     *
     * @see http://fr.wikipedia.org/wiki/Syst%C3%A8me_d%27identification_du_r%C3%A9pertoire_des_entreprises
     * @return string
     */
    public static function siren($formatted = true)
    {
        $siren = self::numerify('%#######');
        $siren .= Luhn::computeCheckDigit($siren);
        if ($formatted) {
            $siren = substr($siren, 0, 3) . ' ' . substr($siren, 3, 3) . ' ' . substr($siren, 6, 3);
        }

        return $siren;
    }

    /**
     * @var array An array containing string which should not appear twice in a catch phrase.
     */
    protected static $wordsWhichShouldNotAppearTwice = array('sécurité', 'simpl');

    /**
     * Validates a french catch phrase.
     *
     * @param string $catchPhrase The catch phrase to validate.
     *
     * @return boolean (true if valid, false otherwise)
     */
    protected static function isCatchPhraseValid($catchPhrase)
    {
        foreach (static::$wordsWhichShouldNotAppearTwice as $word) {
            // Fastest way to check if a piece of word does not appear twice.
            $beginPos = strpos($catchPhrase, $word);
            $endPos = strrpos($catchPhrase, $word);

            if ($beginPos !== false && $beginPos != $endPos) {
                return false;
            }
        }

        return true;
    }

    /**
     * @link http://www.pole-emploi.fr/candidat/le-code-rome-et-les-fiches-metiers-@/article.jspz?id=60702
     * @note Randomly took 300 from this list
     */
    protected static $jobTitleFormat = array(
        'Agent d\'accueil',
        'Agent d\'enquêtes',
        'Agent d\'entreposage',
        'Agent de curage',
        'Agro-économiste',
        'Aide couvreur',
        'Aide à domicile',
        'Aide-déménageur',
        'Ambassadeur',
        'Analyste télématique',
        'Animateur d\'écomusée',
        'Animateur web',
        'Appareilleur-gazier',
        'Archéologue',
        'Armurier d\'art',
        'Armurier spectacle',
        'Artificier spectacle',
        'Artiste dramatique',
        'Aspigiculteur',
        'Assistant de justice',
        'Assistant des ventes',
        'Assistant logistique',
        'Assistant styliste',
        'Assurance',
        'Auteur-adaptateur',
        'Billettiste voyages',
        'Brigadier',
        'Bruiteur',
        'Bâtonnier d\'art',
        'Bûcheron',
        'Cameraman',
        'Capitaine de pêche',
        'Carrier',
        'Caviste',
        'Chansonnier',
        'Chanteur',
        'Chargé de recherche',
        'Chasseur-bagagiste',
        'Chef de fabrication',
        'Chef de scierie',
        'Chef des ventes',
        'Chef du personnel',
        'Chef géographe',
        'Chef monteur son',
        'Chef porion',
        'Chiropraticien',
        'Choréologue',
        'Chromiste',
        'Cintrier-machiniste',
        'Clerc hors rang',
        'Coach sportif',
        'Coffreur béton armé',
        'Coffreur-ferrailleur',
        'Commandant de police',
        'Commandant marine',
        'Commis de coupe',
        'Comptable unique',
        'Conception et études',
        'Conducteur de jumbo',
        'Conseiller culinaire',
        'Conseiller funéraire',
        'Conseiller relooking',
        'Consultant ergonome',
        'Contrebassiste',
        'Convoyeur garde',
        'Copiste offset',
        'Corniste',
        'Costumier-habilleur',
        'Coutelier d\'art',
        'Cueilleur de cerises',
        'Céramiste concepteur',
        'Danse',
        'Danseur',
        'Data manager',
        'Dee-jay',
        'Designer produit',
        'Diététicien conseil',
        'Diététique',
        'Doreur sur métaux',
        'Décorateur-costumier',
        'Défloqueur d\'amiante',
        'Dégustateur',
        'Délégué vétérinaire',
        'Délégué à la tutelle',
        'Désamianteur',
        'Détective',
        'Développeur web',
        'Ecotoxicologue',
        'Elagueur-botteur',
        'Elagueur-grimpeur',
        'Elastiqueur',
        'Eleveur d\'insectes',
        'Eleveur de chats',
        'Eleveur de volailles',
        'Embouteilleur',
        'Employé d\'accueil',
        'Employé d\'étage',
        'Employé de snack-bar',
        'Endivier',
        'Endocrinologue',
        'Epithésiste',
        'Essayeur-retoucheur',
        'Etainier',
        'Etancheur',
        'Etancheur-bardeur',
        'Etiqueteur',
        'Expert back-office',
        'Exploitant de tennis',
        'Extraction',
        'Facteur',
        'Facteur de clavecins',
        'Facteur de secteur',
        'Fantaisiste',
        'Façadier-bardeur',
        'Façadier-ravaleur',
        'Feutier',
        'Finance',
        'Flaconneur',
        'Foreur pétrole',
        'Formateur d\'italien',
        'Fossoyeur',
        'Fraiseur',
        'Fraiseur mouliste',
        'Frigoriste maritime',
        'Fromager',
        'Galeriste',
        'Gardien de résidence',
        'Garçon de chenil',
        'Garçon de hall',
        'Gendarme mobile',
        'Guitariste',
        'Gynécologue',
        'Géodésien',
        'Géologue prospecteur',
        'Géomètre',
        'Géomètre du cadastre',
        'Gérant d\'hôtel',
        'Gérant de tutelle',
        'Gériatre',
        'Hydrothérapie',
        'Hématologue',
        'Hôte de caisse',
        'Ingénieur bâtiment',
        'Ingénieur du son',
        'Ingénieur géologue',
        'Ingénieur géomètre',
        'Ingénieur halieute',
        'Ingénieur logistique',
        'Instituteur',
        'Jointeur de placage',
        'Juge des enfants',
        'Juriste financier',
        'Kiwiculteur',
        'Lexicographe',
        'Liftier',
        'Litigeur transport',
        'Logistique',
        'Logopède',
        'Magicien',
        'Manager d\'artiste',
        'Mannequin détail',
        'Maquilleur spectacle',
        'Marbrier-poseur',
        'Marin grande pêche',
        'Matelassier',
        'Maçon',
        'Maçon-fumiste',
        'Maçonnerie',
        'Maître de ballet',
        'Maïeuticien',
        'Menuisier',
        'Miroitier',
        'Modéliste industriel',
        'Moellonneur',
        'Moniteur de sport',
        'Monteur audiovisuel',
        'Monteur de fermettes',
        'Monteur de palettes',
        'Monteur en siège',
        'Monteur prototypiste',
        'Monteur-frigoriste',
        'Monteur-truquiste',
        'Mouleur sable',
        'Mouliste drapeur',
        'Mécanicien-armurier',
        'Médecin du sport',
        'Médecin scolaire',
        'Médiateur judiciaire',
        'Médiathécaire',
        'Net surfeur surfeuse',
        'Oenologue',
        'Opérateur de plateau',
        'Opérateur du son',
        'Opérateur géomètre',
        'Opérateur piquage',
        'Opérateur vidéo',
        'Ouvrier d\'abattoir',
        'Ouvrier serriste',
        'Ouvrier sidérurgiste',
        'Palefrenier',
        'Paléontologue',
        'Pareur en abattoir',
        'Parfumeur',
        'Parqueteur',
        'Percepteur',
        'Photographe d\'art',
        'Pilote automobile',
        'Pilote de soutireuse',
        'Pilote fluvial',
        'Piqueur en ganterie',
        'Pisteur secouriste',
        'Pizzaïolo',
        'Plaquiste enduiseur',
        'Plasticien',
        'Plisseur',
        'Poissonnier-traiteur',
        'Pontonnier',
        'Porion',
        'Porteur de hottes',
        'Porteur de journaux',
        'Portier',
        'Poseur de granit',
        'Posticheur spectacle',
        'Potier',
        'Praticien dentaire',
        'Praticiens médicaux',
        'Premier clerc',
        'Preneur de son',
        'Primeuriste',
        'Professeur d\'italien',
        'Projeteur béton armé',
        'Promotion des ventes',
        'Présentateur radio',
        'Pyrotechnicien',
        'Pédicure pour bovin',
        'Pédologue',
        'Pédopsychiatre',
        'Quincaillier',
        'Radio chargeur',
        'Ramasseur d\'asperges',
        'Ramasseur d\'endives',
        'Ravaleur-ragréeur',
        'Recherche',
        'Recuiseur',
        'Relieur-doreur',
        'Responsable de salle',
        'Responsable télécoms',
        'Revenue Manager',
        'Rippeur spectacle',
        'Rogneur',
        'Récupérateur',
        'Rédacteur des débats',
        'Régleur funéraire',
        'Régleur sur tour',
        'Sapeur-pompier',
        'Scannériste',
        'Scripte télévision',
        'Sculpteur sur verre',
        'Scénariste',
        'Second de cuisine',
        'Secrétaire juridique',
        'Semencier',
        'Sertisseur',
        'Services funéraires',
        'Solier-moquettiste',
        'Sommelier',
        'Sophrologue',
        'Staffeur',
        'Story boarder',
        'Stratifieur',
        'Stucateur',
        'Styliste graphiste',
        'Surjeteur-raseur',
        'Séismologue',
        'Technicien agricole',
        'Technicien bovin',
        'Technicien géomètre',
        'Technicien plateau',
        'Technicien énergie',
        'Terminologue',
        'Testeur informatique',
        'Toiliste',
        'Topographe',
        'Toréro',
        'Traducteur d\'édition',
        'Traffic manager',
        'Trieur de métaux',
        'Turbinier',
        'Téléconseiller',
        'Tôlier-traceur',
        'Vendeur carreau',
        'Vendeur en lingerie',
        'Vendeur en meubles',
        'Vendeur en épicerie',
        'Verrier d\'art',
        'Verrier à la calotte',
        'Verrier à la main',
        'Verrier à main levée',
        'Vidéo-jockey',
        'Vitrier',
    );
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php

namespace Faker\Provider\fr_FR;

class Payment extends \Faker\Provider\Payment
{
    /**
     * Value Added Tax (VAT)
     *
     * @example 'FR12123456789', ('spaced') 'FR 12 123 456 789'
     *
     * @see http://ec.europa.eu/taxation_customs/vies/faq.html?locale=en#item_11
     * @see http://www.iecomputersystems.com/ordering/eu_vat_numbers.htm
     * @see http://en.wikipedia.org/wiki/VAT_identification_number
     *
     * @param bool $spacedNationalPrefix
     *
     * @return string VAT Number
     */
    public function vat($spacedNationalPrefix = true)
    {
        $siren = Company::siren(false);
        $key = (12 + 3 * ($siren % 97)) % 97;
        $pattern = "%s%'.02d%s";
        if ($spacedNationalPrefix) {
            $siren = trim(chunk_split($siren, 3, ' '));
            $pattern = "%s %'.02d %s";
        }
        return sprintf($pattern, 'FR', $key, $siren);
    }

    /**
     * International Bank Account Number (IBAN)
     * @link http://en.wikipedia.org/wiki/International_Bank_Account_Number
     * @param  string  $prefix      for generating bank account number of a specific bank
     * @param  string  $countryCode ISO 3166-1 alpha-2 country code
     * @param  integer $length      total length without country code and 2 check digits
     * @return string
     */
    public static function bankAccountNumber($prefix = '', $countryCode = 'FR', $length = null)
    {
        return static::iban($countryCode, $prefix, $length);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php

namespace Faker\Provider\fr_FR;

class Person extends \Faker\Provider\Person
{
    protected static $maleNameFormats = array(
        '{{firstNameMale}} {{lastName}}',
        '{{firstNameMale}} {{lastName}}',
        '{{firstNameMale}} {{lastName}}',
        '{{firstNameMale}} {{lastName}}',
        '{{firstNameMale}} {{lastName}}',
        '{{firstNameMale}} {{prefix}} {{lastName}}',
        '{{firstNameMale}} {{lastName}}-{{lastName}}',
        '{{firstNameMale}}-{{firstNameMale}} {{lastName}}',
    );

    protected static $femaleNameFormats = array(
        '{{firstNameFemale}} {{lastName}}',
        '{{firstNameFemale}} {{lastName}}',
        '{{firstNameFemale}} {{lastName}}',
        '{{firstNameFemale}} {{lastName}}',
        '{{firstNameFemale}} {{lastName}}',
        '{{firstNameFemale}} {{prefix}} {{lastName}}',
        '{{firstNameFemale}} {{lastName}}-{{lastName}}',
        '{{firstNameFemale}}-{{firstNameFemale}} {{lastName}}',
    );

    protected static $firstNameMale = array(
        'Adrien', 'Aimé', 'Alain', 'Alexandre', 'Alfred', 'Alphonse', 'André', 'Antoine', 'Arthur', 'Auguste', 'Augustin',
        'Benjamin', 'Benoît', 'Bernard', 'Bertrand', 'Charles', 'Christophe', 'Daniel', 'David', 'Denis', 'Édouard', 'Émile',
        'Emmanuel', 'Éric', 'Étienne', 'Eugène', 'François', 'Franck', 'Frédéric', 'Gabriel', 'Georges', 'Gérard', 'Gilbert',
        'Gilles', 'Grégoire', 'Guillaume', 'Guy', 'William', 'Henri', 'Honoré', 'Hugues', 'Isaac', 'Jacques', 'Jean', 'Jérôme',
        'Joseph', 'Jules', 'Julien', 'Laurent', 'Léon', 'Louis', 'Luc', 'Lucas', 'Marc', 'Marcel', 'Martin', 'Matthieu',
        'Maurice', 'Michel', 'Nicolas', 'Noël', 'Olivier', 'Patrick', 'Paul', 'Philippe', 'Pierre', 'Raymond', 'Rémy', 'René',
        'Richard', 'Robert', 'Roger', 'Roland', 'Sébastien', 'Stéphane', 'Théodore', 'Théophile', 'Thibaut', 'Thibault', 'Thierry',
        'Thomas', 'Timothée', 'Tristan', 'Victor', 'Vincent', 'Xavier', 'Yves', 'Zacharie', 'Claude', 'Dominique'
    );

    protected static $firstNameFemale = array(
        'Adélaïde', 'Adèle', 'Adrienne', 'Agathe', 'Agnès', 'Aimée', 'Alexandrie', 'Alix', 'Alexandria', 'Alex', 'Alice',
        'Amélie', 'Anaïs', 'Anastasie', 'Andrée', 'Anne', 'Anouk', 'Antoinette', 'Arnaude', 'Astrid', 'Audrey', 'Aurélie',
        'Aurore', 'Bernadette', 'Brigitte', 'Capucine', 'Caroline', 'Catherine', 'Cécile', 'Céline', 'Célina', 'Chantal',
        'Charlotte', 'Christelle', 'Christiane', 'Christine', 'Claire', 'Claudine', 'Clémence', 'Colette', 'Constance',
        'Corinne', 'Danielle', 'Denise', 'Diane', 'Dorothée', 'Édith', 'Éléonore', 'Élisabeth', 'Élise', 'Élodie', 'Émilie',
        'Emmanuelle', 'Françoise', 'Frédérique', 'Gabrielle', 'Geneviève', 'Hélène', 'Henriette', 'Hortense', 'Inès', 'Isabelle',
        'Jacqueline', 'Jeanne', 'Jeannine', 'Joséphine', 'Josette', 'Julie', 'Juliette', 'Laetitia', 'Laure', 'Laurence',
        'Lorraine', 'Louise', 'Luce', 'Lucie', 'Lucy', 'Madeleine', 'Manon', 'Marcelle', 'Margaux', 'Margaud', 'Margot',
        'Marguerite', 'Margot', 'Margaret', 'Maggie', 'Marianne', 'Marie', 'Marine', 'Marthe', 'Martine', 'Maryse',
        'Mathilde', 'Michèle', 'Michelle', 'Michelle', 'Monique', 'Nathalie', 'Nath', 'Nathalie', 'Nicole', 'Noémi', 'Océane',
        'Odette', 'Olivie', 'Patricia', 'Paulette', 'Pauline', 'Pénélope', 'Philippine', 'Renée', 'Sabine', 'Simone', 'Sophie',
        'Stéphanie', 'Susanne', 'Suzanne', 'Susan', 'Suzanne', 'Sylvie', 'Thérèse', 'Valentine', 'Valérie', 'Véronique',
        'Victoire', 'Virginie', 'Zoé', 'Camille', 'Dominique'
    );

    protected static $lastName = array(
        'Martin', 'Bernard', 'Thomas', 'Robert', 'Petit', 'Dubois', 'Richard', 'Garcia', 'Durand', 'Moreau', 'Lefebvre', 'Simon', 'Laurent', 'Michel', 'Leroy', 'Martinez', 'David', 'Fontaine', 'Da Silva', 'Morel', 'Fournier', 'Dupont', 'Bertrand', 'Lambert', 'Rousseau', 'Girard', 'Roux', 'Vincent', 'Lefevre', 'Boyer', 'Lopez', 'Bonnet', 'Andre', 'Francois', 'Mercier', 'Muller', 'Guerin', 'Legrand', 'Sanchez', 'Garnier', 'Chevalier', 'Faure', 'Perez', 'Clement', 'Fernandez', 'Blanc', 'Robin', 'Morin', 'Gauthier', 'Pereira', 'Perrin', 'Roussel', 'Henry', 'Duval', 'Gautier', 'Nicolas', 'Masson', 'Marie', 'Noel', 'Ferreira', 'Lemaire', 'Mathieu', 'Riviere', 'Denis', 'Marchand', 'Rodriguez', 'Dumont', 'Payet', 'Lucas', 'Dufour', 'Dos Santos', 'Joly', 'Blanchard', 'Meunier', 'Rodrigues', 'Caron', 'Gerard', 'Fernandes', 'Brunet', 'Meyer', 'Barbier', 'Leroux', 'Renard', 'Goncalves', 'Gaillard', 'Brun', 'Roy', 'Picard', 'Giraud', 'Roger', 'Schmitt', 'Colin', 'Arnaud', 'Vidal', 'Gonzalez', 'Lemoine', 'Roche', 'Aubert', 'Olivier', 'Leclercq', 'Pierre', 'Philippe', 'Bourgeois', 'Renaud', 'Martins', 'Leclerc', 'Guillaume', 'Lacroix', 'Lecomte', 'Benoit', 'Fabre', 'Carpentier', 'Vasseur', 'Louis', 'Hubert', 'Jean', 'Dumas', 'Rolland', 'Grondin', 'Rey', 'Huet', 'Gomez', 'Dupuis', 'Guillot', 'Berger', 'Moulin', 'Hoarau', 'Menard', 'Deschamps', 'Fleury', 'Adam', 'Boucher', 'Poirier', 'Bertin', 'Charles', 'Aubry', 'Da Costa', 'Royer', 'Dupuy', 'Maillard', 'Paris', 'Baron', 'Lopes', 'Guyot', 'Carre', 'Jacquet', 'Renault', 'Herve', 'Charpentier', 'Klein', 'Cousin', 'Collet', 'Leger', 'Ribeiro', 'Hernandez', 'Bailly', 'Schneider', 'Le Gall', 'Ruiz', 'Langlois', 'Bouvier', 'Gomes', 'Prevost', 'Julien', 'Lebrun', 'Breton', 'Germain', 'Millet', 'Boulanger', 'Remy', 'Le Roux', 'Daniel', 'Marques', 'Maillot', 'Leblanc', 'Le Goff', 'Barre', 'Perrot', 'Leveque', 'Marty', 'Benard', 'Monnier', 'Hamon', 'Pelletier', 'Alves', 'Etienne', 'Marchal', 'Poulain', 'Tessier', 'Lemaitre', 'Guichard', 'Besson', 'Mallet', 'Hoareau', 'Gillet', 'Weber', 'Jacob', 'Collin', 'Chevallier', 'Perrier', 'Michaud', 'Carlier', 'Delaunay', 'Chauvin', 'Alexandre', 'Marechal', 'Antoine', 'Lebon', 'Cordier', 'Lejeune', 'Bouchet', 'Pasquier', 'Legros', 'Delattre', 'Humbert', 'De Oliveira', 'Briand', 'Lamy', 'Launay', 'Gilbert', 'Perret', 'Lesage', 'Gay', 'Nguyen', 'Navarro', 'Besnard', 'Pichon', 'Hebert', 'Cohen', 'Pons', 'Lebreton', 'Sauvage', 'De Sousa', 'Pineau', 'Albert', 'Jacques', 'Pinto', 'Barthelemy', 'Turpin', 'Bigot', 'Lelievre', 'Georges', 'Reynaud', 'Ollivier', 'Martel', 'Voisin', 'Leduc', 'Guillet', 'Vallee', 'Coulon', 'Camus', 'Marin', 'Teixeira', 'Costa', 'Mahe', 'Didier', 'Charrier', 'Gaudin', 'Bodin', 'Guillou', 'Gregoire', 'Gros', 'Blanchet', 'Buisson', 'Blondel', 'Paul', 'Dijoux', 'Barbe', 'Hardy', 'Laine', 'Evrard', 'Laporte', 'Rossi', 'Joubert', 'Regnier', 'Tanguy', 'Gimenez', 'Allard', 'Devaux', 'Morvan', 'Levy', 'Dias', 'Courtois', 'Lenoir', 'Berthelot', 'Pascal', 'Vaillant', 'Guilbert', 'Thibault', 'Moreno', 'Duhamel', 'Colas', 'Masse', 'Baudry', 'Bruneau', 'Verdier', 'Delorme', 'Blin', 'Guillon', 'Mary', 'Coste', 'Pruvost', 'Maury', 'Allain', 'Valentin', 'Godard', 'Joseph', 'Brunel', 'Marion', 'Texier', 'Seguin', 'Raynaud', 'Bourdon', 'Raymond', 'Bonneau', 'Chauvet', 'Maurice', 'Legendre', 'Loiseau', 'Ferrand', 'Toussaint', 'Techer', 'Lombard', 'Lefort', 'Couturier', 'Bousquet', 'Diaz', 'Riou', 'Clerc', 'Weiss', 'Imbert', 'Jourdan', 'Delahaye', 'Gilles', 'Guibert', 'Begue', 'Descamps', 'Delmas', 'Peltier', 'Dupre', 'Chartier', 'Martineau', 'Laroche', 'Leconte', 'Maillet', 'Parent', 'Labbe', 'Potier', 'Bazin', 'Normand', 'Pottier', 'Torres', 'Lagarde', 'Blot', 'Jacquot', 'Lemonnier', 'Grenier', 'Rocher', 'Bonnin', 'Boutin', 'Fischer', 'Munoz', 'Neveu', 'Lacombe', 'Mendes', 'Delannoy', 'Auger', 'Wagner', 'Fouquet', 'Mace', 'Ramos', 'Pages', 'Petitjean', 'Chauveau', 'Foucher', 'Peron', 'Guyon', 'Gallet', 'Rousset', 'Traore', 'Bernier', 'Vallet', 'Letellier', 'Bouvet', 'Hamel', 'Chretien', 'Faivre', 'Boulay', 'Thierry', 'Samson', 'Ledoux', 'Salmon', 'Gosselin', 'Lecoq', 'Pires', 'Leleu', 'Becker', 'Diallo', 'Merle', 'Valette'
    );

    protected static $titleMale = array('M.', 'Dr.', 'Pr.', 'Me.');

    protected static $titleFemale = array('Mme.', 'Mlle', 'Dr.', 'Pr.', 'Me.');

    protected static $prefix = array('de', 'de la', 'Le', 'du');

    public static function prefix()
    {
        return static::randomElement(static::$prefix);
    }

    /**
     * Generates a NIR / Sécurité Sociale number (13 digits + 2 digits for the key)
     *
     * @see https://fr.wikipedia.org/wiki/Num%C3%A9ro_de_s%C3%A9curit%C3%A9_sociale_en_France
     * @return string
     */
    public function nir($gender = null, $formatted = false)
    {
        // Gender
        if ($gender === static::GENDER_MALE) {
            $nir = 1;
        } elseif ($gender === static::GENDER_FEMALE) {
            $nir = 2;
        } else {
            $nir = $this->numberBetween(1, 2);
        }

        $nir .=
            // Year of birth (aa)
            $this->numerify('##') .
            // Mont of birth (mm)
            sprintf('%02d', $this->numberBetween(1, 12));

        // Department
        $department = key(Address::department());
        $nir .= $department;

        // Town number, depends on department length
        if (strlen($department) === 2) {
            $nir .= $this->numerify('###');
        } elseif (strlen($department) === 3) {
            $nir .= $this->numerify('##');
        }

        // Born number (depending of town and month of birth)
        $nir .= $this->numerify('###');

        /**
         * The key for a given NIR is `97 - 97 % NIR`
         * NIR has to be an integer, so we have to do a little replacment
         * for departments 2A and 2B
         */
        if ($department === '2A') {
            $nirInteger = str_replace('2A', '19', $nir);
        } elseif ($department === '2B') {
            $nirInteger = str_replace('2B', '18', $nir);
        } else {
            $nirInteger = $nir;
        }
        $nir .= sprintf('%02d', 97 - $nirInteger % 97);

        // Format is x xx xx xx xxx xxx xx
        if ($formatted) {
            $nir = substr($nir, 0, 1) . ' ' . substr($nir, 1, 2) . ' ' . substr($nir, 3, 2) . ' ' . substr($nir, 5, 2) . ' ' . substr($nir, 7, 3). ' ' . substr($nir, 10, 3). ' ' . substr($nir, 13, 2);
        }

        return $nir;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <?php

namespace Faker\Provider\fr_FR;

class PhoneNumber extends \Faker\Provider\PhoneNumber
{
    // Phone numbers can't start by 00 in France
    // 01 is the most common prefix
    protected static $formats = array(
        '+33 (0)1 ## ## ## ##',
        '+33 (0)1 ## ## ## ##',
        '+33 (0)2 ## ## ## ##',
        '+33 (0)3 ## ## ## ##',
        '+33 (0)4 ## ## ## ##',
        '+33 (0)5 ## ## ## ##',
        '+33 (0)6 ## ## ## ##',
        '+33 (0)7 {{phoneNumber07WithSeparator}}',
        '+33 (0)8 {{phoneNumber08WithSeparator}}',
        '+33 (0)9 ## ## ## ##',
        '+33 1 ## ## ## ##',
        '+33 1 ## ## ## ##',
        '+33 2 ## ## ## ##',
        '+33 3 ## ## ## ##',
        '+33 4 ## ## ## ##',
        '+33 5 ## ## ## ##',
        '+33 6 ## ## ## ##',
        '+33 7 {{phoneNumber07WithSeparator}}',
        '+33 8 {{phoneNumber08WithSeparator}}',
        '+33 9 ## ## ## ##',
        '01########',
        '01########',
        '02########',
        '03########',
        '04########',
        '05########',
        '06########',
        '07{{phoneNumber07}}',
        '08{{phoneNumber08}}',
        '09########',
        '01 ## ## ## ##',
        '01 ## ## ## ##',
        '02 ## ## ## ##',
        '03 ## ## ## ##',
        '04 ## ## ## ##',
        '05 ## ## ## ##',
        '06 ## ## ## ##',
        '07 {{phoneNumber07WithSeparator}}',
        '08 {{phoneNumber08WithSeparator}}',
        '09 ## ## ## ##',
    );

    // Mobile phone numbers start by 06 and 07
    // 06 is the most common prefix
    protected static $mobileFormats  = array(
        '+33 (0)6 ## ## ## ##',
        '+33 6 ## ## ## ##',
        '+33 (0)7 {{phoneNumber07WithSeparator}}',
        '+33 7 {{phoneNumber07WithSeparator}}',
        '06########',
        '07{{phoneNumber07}}',
        '06 ## ## ## ##',
        '07 {{phoneNumber07WithSeparator}}',
    );

    protected static $serviceFormats = array(
        '+33 (0)8 {{phoneNumber08WithSeparator}}',
        '+33 8 {{phoneNumber08WithSeparator}}',
        '08 {{phoneNumber08WithSeparator}}',
        '08{{phoneNumber08}}',
    );

    public function phoneNumber07()
    {
        $phoneNumber = $this->phoneNumber07WithSeparator();
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        return $phoneNumber;
    }

    /**
     * Only 073 to 079 are acceptable prefixes with 07
     *
     * @see http://www.arcep.fr/index.php?id=8146
     */
    public function phoneNumber07WithSeparator()
    {
        $phoneNumber = $this->generator->numberBetween(3, 9);
        $phoneNumber .= $this->numerify('# ## ## ##');
        return $phoneNumber;
    }

    public function phoneNumber08()
    {
        $phoneNumber = $this->phoneNumber08WithSeparator();
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        return $phoneNumber;
    }

    /**
     *  Valid formats for 08:
     *
     *  0# ## ## ##
     *  1# ## ## ##
     *  2# ## ## ##
     *  91 ## ## ##
     *  92 ## ## ##
     *  93 ## ## ##
     *  97 ## ## ##
     *  98 ## ## ##
     *  99 ## ## ##
     *
     *  Formats 089(4|6)## ## ## are valid, but will be
     *  attributed when other 089 resource ranges are exhausted.
     *
     * @see https://www.arcep.fr/index.php?id=8146#c9625
     * @see https://issuetracker.google.com/u/1/issues/73269839
     */
    public function phoneNumber08WithSeparator()
    {
        $regex = '([012]{1}\d{1}|(9[1-357-9])( \d{2}){3}';
        return $this->regexify($regex);
    }

    /**
     * @example '0601020304'
     */
    public function mobileNumber()
    {
        $format = static::randomElement(static::$mobileFormats);

        return static::numerify($this->generator->parse($format));
    }
    /**
     * @example '0891951357'
     */
    public function serviceNumber()
    {
        $format = static::randomElement(static::$serviceFormats);

        return static::numerify($this->generator->parse($format));
    }
}
                                                                                                                                                                  <?php

namespace Faker\Provider\fr_FR;

class Text extends \Faker\Provider\Text
{
    /**
     * The Project Gutenberg EBook of Madame Bovary, by Gustave Flaubert
     *
     * This eBook is for the use of anyone anywhere at no cost and with
     * almost no restrictions whatsoever.  You may copy it, give it away or
     * re-use it under the terms of the Project Gutenberg License included
     * with this eBook or online at www.gutenberg.net
     *
     * Title: Madame Bovary
     * Author: Gustave Flaubert
     * Release Date: November 26, 2004 [EBook #14155]
     * [Last updated: November 28, 2011]
     * Language: French
     *
     * *** START OF THIS PROJECT GUTENBERG EBOOK MADAME BOVARY ***
     *
     * Produced by Ebooks libres et gratuits at http://www.ebooksgratuits.com
     *
     * Gustave Flaubert
     * MADAME BOVARY
     * (1857)
     *
     * @see http://www.gutenberg.org/cache/epub/14155/pg14155.txt
     * @var string
     */
    protected static $baseText = <<<'EOT'
PREMIÈRE PARTIE


I

Nous étions à l'Étude, quand le Proviseur entra, suivi d'un
nouveau habillé en bourgeois et d'un garçon de classe qui portait
un grand pupitre. Ceux qui dormaient se réveillèrent, et chacun se
leva comme surpris dans son travail.

Le Proviseur nous fit signe de nous rasseoir; puis, se tournant
vers le maître d'études:

-- Monsieur Roger, lui dit-il à demi-voix, voici un élève que je
vous recommande, il entre en cinquième. Si son travail et sa
conduite sont méritoires, il passera dans les grands, où l'appelle
son âge.

Resté dans l'angle, derrière la porte, si bien qu'on l'apercevait
à peine, le nouveau était un gars de la campagne, d'une quinzaine
d'années environ, et plus haut de taille qu'aucun de nous tous. Il
avait les cheveux coupés droit sur le front, comme un chantre de
village, l'air raisonnable et fort embarrassé. Quoiqu'il ne fût
pas large des épaules, son habit-veste de drap vert à boutons
noirs devait le gêner aux entournures et laissait voir, par la
fente des parements, des poignets rouges habitués à être nus. Ses
jambes, en bas bleus, sortaient d'un pantalon jaunâtre très tiré
par les bretelles. Il était chaussé de souliers forts, mal cirés,
garnis de clous.

On commença la récitation des leçons. Il les écouta de toutes ses
oreilles, attentif comme au sermon, n'osant même croiser les
cuisses, ni s'appuyer sur le coude, et, à deux heures, quand la
cloche sonna, le maître d'études fut obligé de l'avertir, pour
qu'il se mît avec nous dans les rangs.

Nous avions l'habitude, en entrant en classe, de jeter nos
casquettes par terre, afin d'avoir ensuite nos mains plus libres;
il fallait, dès le seuil de la porte, les lancer sous le banc, de
façon à frapper contre la muraille en faisant beaucoup de
poussière; c'était là le genre.

Mais, soit qu'il n'eût pas remarqué cette manoeuvre ou qu'il n'eut
osé s'y soumettre, la prière était finie que le nouveau tenait
encore sa casquette sur ses deux genoux. C'était une de ces
coiffures d'ordre composite, où l'on retrouve les éléments du
bonnet à poil, du chapska, du chapeau rond, de la casquette de
loutre et du bonnet de coton, une de ces pauvres choses, enfin,
dont la laideur muette a des profondeurs d'expression comme le
visage d'un imbécile. Ovoïde et renflée de baleines, elle
commençait par trois boudins circulaires; puis s'alternaient,
séparés par une bande rouge, des losanges de velours et de poils
de lapin; venait ensuite une façon de sac qui se terminait par un
polygone cartonné, couvert d'une broderie en soutache compliquée,
et d'où pendait, au bout d'un long cordon trop mince, un petit
croisillon de fils d'or, en manière de gland. Elle était neuve; la
visière brillait.

-- Levez-vous, dit le professeur.

Il se leva; sa casquette tomba. Toute la classe se mit à rire.

Il se baissa pour la reprendre. Un voisin la fit tomber d'un coup
de coude, il la ramassa encore une fois.

-- Débarrassez-vous donc de votre casque, dit le professeur, qui
était un homme d'esprit.

Il y eut un rire éclatant des écoliers qui décontenança le pauvre
garçon, si bien qu'il ne savait s'il fallait garder sa casquette à
la main, la laisser par terre ou la mettre sur sa tête. Il se
rassit et la posa sur ses genoux.

-- Levez-vous, reprit le professeur, et dites-moi votre nom.

Le nouveau articula, d'une voix bredouillante, un nom
inintelligible.

-- Répétez!

Le même bredouillement de syllabes se fit entendre, couvert par
les huées de la classe.

-- Plus haut! cria le maître, plus haut!

Le nouveau, prenant alors une résolution extrême, ouvrit une
bouche démesurée et lança à pleins poumons, comme pour appeler
quelqu'un, ce mot: Charbovari.

Ce fut un vacarme qui s'élança d'un bond, monta en crescendo, avec
des éclats de voix aigus (on hurlait, on aboyait, on trépignait,
on répétait: Charbovari! Charbovari!), puis qui roula en notes
isolées, se calmant à grand-peine, et parfois qui reprenait tout à
coup sur la ligne d'un banc où saillissait encore çà et là, comme
un pétard mal éteint, quelque rire étouffé.

Cependant, sous la pluie des pensums, l'ordre peu à peu se
rétablit dans la classe, et le professeur, parvenu à saisir le nom
de Charles Bovary, se l'étant fait dicter, épeler et relire,
commanda tout de suite au pauvre diable d'aller s'asseoir sur le
banc de paresse, au pied de la chaire. Il se mit en mouvement,
mais, avant de partir, hésita.

-- Que cherchez-vous? demanda le professeur.

-- Ma cas... fit timidement le nouveau, promenant autour de lui
des regards inquiets.

-- Cinq cents vers à toute la classe! exclamé d'une voix furieuse,
arrêta, comme le _Quos ego_, une bourrasque nouvelle. -- Restez
donc tranquilles! continuait le professeur indigné, et s'essuyant
le front avec son mouchoir qu'il venait de prendre dans sa toque:
Quant à vous, le nouveau, vous me copierez vingt fois le verbe
_ridiculus sum_.

Puis, d'une voix plus douce:

-- Eh! vous la retrouverez, votre casquette; on ne vous l'a pas
volée!

Tout reprit son calme. Les têtes se courbèrent sur les cartons, et
le nouveau resta pendant deux heures dans une tenue exemplaire,
quoiqu'il y eût bien, de temps à autre, quelque boulette de papier
lancée d'un bec de plume qui vînt s'éclabousser sur sa figure.
Mais il s'essuyait avec la main, et demeurait immobile, les yeux
baissés.

Le soir, à l'Étude, il tira ses bouts de manches de son pupitre,
mit en ordre ses petites affaires, régla soigneusement son papier.
Nous le vîmes qui travaillait en conscience, cherchant tous les
mots dans le dictionnaire et se donnant beaucoup de mal. Grâce,
sans doute, à cette bonne volonté dont il fit preuve, il dut de ne
pas descendre dans la classe inférieure; car, s'il savait
passablement ses règles, il n'avait guère d'élégance dans les
tournures. C'était le curé de son village qui lui avait commencé
le latin, ses parents, par économie, ne l'ayant envoyé au collège
que le plus tard possible.

Son père, M. Charles-Denis-Bartholomé Bovary, ancien aide-
chirurgien-major, compromis, vers 1812, dans des affaires de
conscription, et forcé, vers cette époque, de quitter le service,
avait alors profité de ses avantages personnels pour saisir au
passage une dot de soixante mille francs, qui s'offrait en la
fille d'un marchand bonnetier, devenue amoureuse de sa tournure.
Bel homme, hâbleur, faisant sonner haut ses éperons, portant des
favoris rejoints aux moustaches, les doigts toujours garnis de
bagues et habillé de couleurs voyantes, il avait l'aspect d'un
brave, avec l'entrain facile d'un commis voyageur. Une fois marié,
il vécut deux ou trois ans sur la fortune de sa femme, dînant
bien, se levant tard, fumant dans de grandes pipes en porcelaine,
ne rentrant le soir qu'après le spectacle et fréquentant les
cafés. Le beau-père mourut et laissa peu de chose; il en fut
indigné, se lança dans la fabrique, y perdit quelque argent, puis
se retira dans la campagne, où il voulut faire valoir. Mais, comme
il ne s'entendait guère plus en culture qu'en indiennes, qu'il
montait ses chevaux au lieu de les envoyer au labour, buvait son
cidre en bouteilles au lieu de le vendre en barriques, mangeait
les plus belles volailles de sa cour et graissait ses souliers de
chasse avec le lard de ses cochons, il ne tarda point à
s'apercevoir qu'il valait mieux planter là toute spéculation.

Moyennant deux cents francs par an, il trouva donc à louer dans un
village, sur les confins du pays de Caux et de la Picardie, une
sorte de logis moitié ferme, moitié maison de maître; et, chagrin,
rongé de regrets, accusant le ciel, jaloux contre tout le monde,
il s'enferma dès l'âge de quarante-cinq ans, dégoûté des hommes,
disait-il, et décidé à vivre en paix.

Sa femme avait été folle de lui autrefois; elle l'avait aimé avec
mille servilités qui l'avaient détaché d'elle encore davantage.
Enjouée jadis, expansive et tout aimante, elle était, en
vieillissant, devenue (à la façon du vin éventé qui se tourne en
vinaigre) d'humeur difficile, piaillarde, nerveuse. Elle avait
tant souffert, sans se plaindre, d'abord, quand elle le voyait
courir après toutes les gotons de village et que vingt mauvais
lieux le lui renvoyaient le soir, blasé et puant l'ivresse! Puis
l'orgueil s'était révolté. Alors elle s'était tue, avalant sa rage
dans un stoïcisme muet, qu'elle garda jusqu'à sa mort. Elle était
sans cesse en courses, en affaires. Elle allait chez les avoués,
chez le président, se rappelait l'échéance des billets, obtenait
des retards; et, à la maison, repassait, cousait, blanchissait,
surveillait les ouvriers, soldait les mémoires, tandis que, sans
s'inquiéter de rien, Monsieur, continuellement engourdi dans une
somnolence boudeuse dont il ne se réveillait que pour lui dire des
choses désobligeantes, restait à fumer au coin du feu, en crachant
dans les cendres.

Quand elle eut un enfant, il le fallut mettre en nourrice. Rentré
chez eux, le marmot fut gâté comme un prince. Sa mère le
nourrissait de confitures; son père le laissait courir sans
souliers, et, pour faire le philosophe, disait même qu'il pouvait
bien aller tout nu, comme les enfants des bêtes. À l'encontre des
tendances maternelles, il avait en tête un certain idéal viril de
l'enfance, d'après lequel il tâchait de former son fils, voulant
qu'on l'élevât durement, à la spartiate, pour lui faire une bonne
constitution. Il l'envoyait se coucher sans feu, lui apprenait à
boire de grands coups de rhum et à insulter les processions. Mais,
naturellement paisible, le petit répondait mal à ses efforts. Sa
mère le traînait toujours après elle; elle lui découpait des
cartons, lui racontait des histoires, s'entretenait avec lui dans
des monologues sans fin, pleins de gaietés mélancoliques et de
chatteries babillardes. Dans l'isolement de sa vie, elle reporta
sur cette tête d'enfant toutes ses vanités éparses, brisées. Elle
rêvait de hautes positions, elle le voyait déjà grand, beau,
spirituel, établi, dans les ponts et chaussées ou dans la
magistrature. Elle lui apprit à lire, et même lui enseigna, sur un
vieux piano qu'elle avait, à chanter deux ou trois petites
romances. Mais, à tout cela, M. Bovary, peu soucieux des lettres,
disait que ce n'était pas la peine! Auraient-ils jamais de quoi
l'entretenir dans les écoles du gouvernement, lui acheter une
charge ou un fonds de commerce? D'ailleurs, avec du toupet, un
homme réussit toujours dans le monde. Madame Bovary se mordait les
lèvres, et l'enfant vagabondait dans le village.

Il suivait les laboureurs, et chassait, à coups de motte de terre,
les corbeaux qui s'envolaient. Il mangeait des mûres le long des
fossés, gardait les dindons avec une gaule, fanait à la moisson,
courait dans le bois, jouait à la marelle sous le porche de
l'église les jours de pluie, et, aux grandes fêtes, suppliait le
bedeau de lui laisser sonner les cloches, pour se pendre de tout
son corps à la grande corde et se sentir emporter par elle dans sa
volée.

Aussi poussa-t-il comme un chêne. Il acquit de fortes mains, de
belles couleurs.

À douze ans, sa mère obtint que l'on commençât ses études. On en
chargea le curé. Mais les leçons étaient si courtes et si mal
suivies, qu'elles ne pouvaient servir à grand-chose. C'était aux
moments perdus qu'elles se donnaient, dans la sacristie, debout, à
la hâte, entre un baptême et un enterrement; ou bien le curé
envoyait chercher son élève après l'Angélus, quand il n'avait pas
à sortir. On montait dans sa chambre, on s'installait: les
moucherons et les papillons de nuit tournoyaient autour de la
chandelle. Il faisait chaud, l'enfant s'endormait; et le bonhomme,
s'assoupissant les mains sur son ventre, ne tardait pas à ronfler,
la bouche ouverte. D'autres fois, quand M. le curé, revenant de
porter le viatique à quelque malade des environs, apercevait
Charles qui polissonnait dans la campagne, il l'appelait, le
sermonnait un quart d'heure et profitait de l'occasion pour lui
faire conjuguer son verbe au pied d'un arbre. La pluie venait les
interrompre, ou une connaissance qui passait. Du reste, il était
toujours content de lui, disait même que le jeune homme avait
beaucoup de mémoire.

Charles ne pouvait en rester là. Madame fut énergique. Honteux, ou
fatigué plutôt, Monsieur céda sans résistance, et l'on attendit
encore un an que le gamin eût fait sa première communion.

Six mois se passèrent encore; et, l'année d'après, Charles fut
définitivement envoyé au collège de Rouen, où son père l'amena
lui-même, vers la fin d'octobre, à l'époque de la foire Saint-
Romain.

Il serait maintenant impossible à aucun de nous de se rien
rappeler de lui. C'était un garçon de tempérament modéré, qui
jouait aux récréations, travaillait à l'étude, écoutant en classe,
dormant bien au dortoir, mangeant bien au réfectoire. Il avait
pour correspondant un quincaillier en gros de la rue Ganterie, qui
le faisait sortir une fois par mois, le dimanche, après que sa
boutique était fermée, l'envoyait se promener sur le port à
regarder les bateaux, puis le ramenait au collège dès sept heures,
avant le souper. Le soir de chaque jeudi, il écrivait une longue
lettre à sa mère, avec de l'encre rouge et trois pains à cacheter;
puis il repassait ses cahiers d'histoire, ou bien lisait un vieux
volume d'Anacharsis qui traînait dans l'étude. En promenade, il
causait avec le domestique, qui était de la campagne comme lui.

À force de s'appliquer, il se maintint toujours vers le milieu de
la classe; une fois même, il gagna un premier accessit d'histoire
naturelle. Mais à la fin de sa troisième, ses parents le
retirèrent du collège pour lui faire étudier la médecine,
persuadés qu'il pourrait se pousser seul jusqu'au baccalauréat.

Sa mère lui choisit une chambre, au quatrième, sur l'Eau-de-Robec,
chez un teinturier de sa connaissance: Elle conclut les
arrangements pour sa pension, se procura des meubles, une table et
deux chaises, fit venir de chez elle un vieux lit en merisier, et
acheta de plus un petit poêle en fonte, avec la provision de bois
qui devait chauffer son pauvre enfant. Puis elle partit au bout de
la semaine, après mille recommandations de se bien conduire,
maintenant qu'il allait être abandonné à lui-même.

Le programme des cours, qu'il lut sur l'affiche, lui fit un effet
d'étourdissement: cours d'anatomie, cours de pathologie, cours de
physiologie, cours de pharmacie, cours de chimie, et de botanique,
et de clinique, et de thérapeutique, sans compter l'hygiène ni la
matière médicale, tous noms dont il ignorait les étymologies et
qui étaient comme autant de portes de sanctuaires pleins
d'augustes ténèbres.

Il n'y comprit rien; il avait beau écouter, il ne saisissait pas.
Il travaillait pourtant, il avait des cahiers reliés, il suivait
tous les cours; il ne perdait pas une seule visite. Il
accomplissait sa petite tâche quotidienne à la manière du cheval
de manège, qui tourne en place les yeux bandés, ignorant de la
besogne qu'il broie.

Pour lui épargner de la dépense, sa mère lui envoyait chaque
semaine, par le messager, un morceau de veau cuit au four, avec
quoi il déjeunait le matin; quand il était rentré de l'hôpital,
tout en battant la semelle contre le mur. Ensuite il fallait
courir aux leçons, à l'amphithéâtre, à l'hospice, et revenir chez
lui, à travers toutes les rues. Le soir, après le maigre dîner de
son propriétaire, il remontait à sa chambre et se remettait au
travail, dans ses habits mouillés qui fumaient sur son corps,
devant le poêle rougi.

Dans les beaux soirs d'été; à l'heure où les rues tièdes sont
vides, quand les servantes, jouent au volant sur le seuil des
portes, il ouvrait sa fenêtre et s'accoudait. La rivière, qui fait
de ce quartier de Rouen comme une ignoble petite Venise, coulait
en bas, sous lui, jaune, violette ou bleue, entre ses ponts et ses
grilles. Des ouvriers, accroupis au bord, lavaient leurs bras dans
l'eau. Sur des perches partant du haut des greniers, des écheveaux
de coton séchaient à l'air. En face, au-delà des toits, le grand
ciel pur s'étendait, avec le soleil rouge se couchant. Qu'il
devait faire bon là-bas! Quelle fraîcheur sous la hêtraie! Et il
ouvrait les narines pour aspirer les bonnes odeurs de la campagne,
qui ne venaient pas jusqu'à lui.

Il maigrit, sa taille s'allongea, et sa figure prit une sorte
d'expression dolente qui la rendit presque intéressante.

Naturellement, par nonchalance; il en vint à se délier de toutes
les résolutions qu'il s'était faites. Une fois, il manqua la
visite, le lendemain son cours, et, savourant la paresse, peu à
peu, n'y retourna plus.

Il prit l'habitude du cabaret, avec la passion des dominos.
S'enfermer chaque soir dans un sale appartement public, pour y
taper sur des tables de marbre de petits os de mouton marqués de
points noirs, lui semblait un acte précieux de sa liberté, qui le
rehaussait d'estime vis-à-vis de lui-même. C'était comme
l'initiation au monde, l'accès des plaisirs défendus; et, en
entrant, il posait la main sur le bouton de la porte avec une joie
presque sensuelle. Alors, beaucoup de choses comprimées en lui, se
dilatèrent; il apprit par coeur des couplets qu'il chantait aux
bienvenues, s'enthousiasma pour Béranger, sut faire du punch et
connut enfin l'amour.

Grâce à ces travaux préparatoires, il échoua complètement à son
examen d'officier de santé. On l'attendait le soir même à la
maison pour fêter son succès.

Il partit à pied et s'arrêta vers l'entrée du village, où il fit
demander sa mère, lui conta tout. Elle l'excusa, rejetant l'échec
sur l'injustice des examinateurs, et le raffermit un peu, se
chargeant d'arranger les choses. Cinq ans plus tard seulement,
M. Bovary connut la vérité; elle était vieille, il l'accepta, ne
pouvant d'ailleurs supposer qu'un homme issu de lui fût un sot.

Charles se remit donc au travail et prépara sans discontinuer les
matières de son examen, dont il apprit d'avance toutes les
questions par coeur. Il fut reçu avec une assez bonne note. Quel
beau jour pour sa mère! On donna un grand dîner.

Où irait-il exercer son art? À Tostes. Il n'y avait là qu'un vieux
médecin. Depuis longtemps madame Bovary guettait sa mort, et le
bonhomme n'avait point encore plié bagage, que Charles était
installé en face, comme son successeur.

Mais ce n'était pas tout que d'avoir élevé son fils, de lui avoir
fait apprendre la médecine et découvert Tostes pour l'exercer: il
lui fallait une femme. Elle lui en trouva une: la veuve d'un
huissier de Dieppe, qui avait quarante-cinq ans et douze cents
livres de rente.

Quoiqu'elle fût laide, sèche comme un cotret, et bourgeonnée comme
un printemps, certes madame Dubuc ne manquait pas de partis à
choisir. Pour arriver à ses fins, la mère Bovary fut obligée de
les évincer tous, et elle déjoua même fort habilement les
intrigues d'un charcutier qui était soutenu par les prêtres.

Charles avait entrevu dans le mariage l'avènement d'une condition
meilleure, imaginant qu'il serait plus libre et pourrait disposer
de sa personne et de son argent. Mais sa femme fut le maître; il
devait devant le monde dire ceci, ne pas dire cela, faire maigre
tous les vendredis, s'habiller comme elle l'entendait, harceler
par son ordre les clients qui ne payaient pas. Elle décachetait
ses lettres, épiait ses démarches, et l'écoutait, à travers la
cloison, donner ses consultations dans son cabinet, quand il y
avait des femmes.

Il lui fallait son chocolat tous les matins, des égards à n'en
plus finir. Elle se plaignait sans cesse de ses nerfs, de sa
poitrine, de ses humeurs. Le bruit des pas lui faisait mal; on
s'en allait, la solitude lui devenait odieuse; revenait-on près
d'elle, c'était pour la voir mourir, sans doute. Le soir, quand
Charles rentrait, elle sortait de dessous ses draps ses longs bras
maigres, les lui passait autour du cou, et, l'ayant fait asseoir
au bord du lit, se mettait à lui parler de ses chagrins: il
l'oubliait, il en aimait une autre! On lui avait bien dit qu'elle
serait malheureuse; et elle finissait en lui demandant quelque
sirop pour sa santé et un peu plus d'amour.


II

Une nuit, vers onze heures, ils furent réveillés par le bruit d'un
cheval qui s'arrêta juste à la porte. La bonne ouvrit la lucarne
du grenier et parlementa quelque temps avec un homme resté en bas,
dans la rue. Il venait chercher le médecin; il avait une lettre.
Nastasie descendit les marches en grelottant, et alla ouvrir la
serrure et les verrous, l'un après l'autre. L'homme laissa son
cheval, et, suivant la bonne, entra tout à coup derrière elle. Il
tira de dedans son bonnet de laine à houppes grises, une lettre
enveloppée dans un chiffon, et la présenta délicatement à Charles,
qui s'accouda sur l'oreiller pour la lire. Nastasie, près du lit,
tenait la lumière. Madame, par pudeur, restait tournée vers la
ruelle et montrait le dos.

Cette lettre, cachetée d'un petit cachet de cire bleue, suppliait
M. Bovary de se rendre immédiatement à la ferme des Bertaux, pour
remettre une jambe cassée. Or il y a, de Tostes aux Bertaux, six
bonnes lieues de traverse, en passant par Longueville et Saint-
Victor. La nuit était noire. Madame Bovary jeune redoutait les
accidents pour son mari. Donc il fut décidé que le valet d'écurie
prendrait les devants. Charles partirait trois heures plus tard,
au lever de la lune. On enverrait un gamin à sa rencontre, afin de
lui montrer le chemin de la ferme et d'ouvrir les clôtures devant
lui.

Vers quatre heures du matin, Charles, bien enveloppé dans son
manteau, se mit en route pour les Bertaux. Encore endormi par la
chaleur du sommeil, il se laissait bercer au trot pacifique de sa
bête. Quand elle s'arrêtait d'elle-même devant ces trous entourés
d'épines que l'on creuse au bord des sillons, Charles se
réveillant en sursaut, se rappelait vite la jambe cassée, et il
tâchait de se remettre en mémoire toutes les fractures qu'il
savait. La pluie ne tombait plus; le jour commençait à venir, et,
sur les branches des pommiers sans feuilles, des oiseaux se
tenaient immobiles, hérissant leurs petites plumes au vent froid
du matin. La plate campagne s'étalait à perte de vue, et les
bouquets d'arbres autour des fermes faisaient, à intervalles
éloignés, des taches d'un violet noir sur cette grande surface
grise, qui se perdait à l'horizon dans le ton morne du ciel.
Charles, de temps à autre, ouvrait les yeux; puis, son esprit se
fatiguant et le sommeil revenant de soi-même, bientôt il entrait
dans une sorte d'assoupissement où, ses sensations récentes se
confondant avec des souvenirs, lui-même se percevait double, à la
fois étudiant et marié, couché dans son lit comme tout à l'heure,
traversant une salle d'opérés comme autrefois. L'odeur chaude des
cataplasmes se mêlait dans sa tête à la verte odeur de la rosée;
il entendait rouler sur leur tringle les anneaux de fer des lits
et sa femme dormir... Comme il passait par Vassonville, il
aperçut, au bord d'un fossé, un jeune garçon assis sur l'herbe.

-- Êtes-vous le médecin? demanda l'enfant.

Et, sur la réponse de Charles, il prit ses sabots à ses mains et
se mit à courir devant lui.

L'officier de santé, chemin faisant, comprit aux discours de son
guide que M. Rouault devait être un cultivateur des plus aisés. Il
s'était cassé la jambe, la veille au soir, en revenant de faire
les Rois, chez un voisin. Sa femme était morte depuis deux ans. Il
n'avait avec lui que sa demoiselle, qui l'aidait à tenir la
maison.

Les ornières devinrent plus profondes. On approchait des Bertaux.
Le petit gars, se coulant alors par un trou de haie, disparut,
puis, il revint au bout d'une cour en ouvrir la barrière. Le
cheval glissait sur l'herbe mouillée; Charles se baissait pour
passer sous les branches. Les chiens de garde à la niche aboyaient
en tirant sur leur chaîne. Quand il entra dans les Bertaux, son
cheval eut peur et fit un grand écart.

C'était une ferme de bonne apparence. On voyait dans les écuries,
par le dessus des portes ouvertes, de gros chevaux de labour qui
mangeaient tranquillement dans des râteliers neufs. Le long des
bâtiments s'étendait un large fumier, de la buée s'en élevait, et,
parmi les poules et les dindons, picoraient dessus cinq ou six
paons, luxe des basses-cours cauchoises. La bergerie était longue,
la grange était haute, à murs lisses comme la main. Il y avait
sous le hangar deux grandes charrettes et quatre charrues, avec
leurs fouets, leurs colliers, leurs équipages complets, dont les
toisons de laine bleue se salissaient à la poussière fine qui
tombait des greniers. La cour allait en montant; plantée d'arbres
symétriquement espacés, et le bruit gai d'un troupeau d'oies
retentissait près de la mare.

Une jeune femme, en robe de mérinos bleu garnie de trois volants,
vint sur le seuil de la maison pour recevoir M. Bovary, qu'elle
fit entrer dans la cuisine, où flambait un grand feu. Le déjeuner
des gens bouillonnait alentour, dans des petits pots de taille
inégale. Des vêtements humides séchaient dans l'intérieur de la
cheminée. La pelle, les pincettes et le bec du soufflet, tous de
proportion colossale, brillaient comme de l'acier poli, tandis que
le long des murs s'étendait une abondante batterie de cuisine, où
miroitait inégalement la flamme claire du foyer, jointe aux
premières lueurs du soleil arrivant par les carreaux.

Charles monta, au premier, voir le malade. Il le trouva dans son
lit, suant sous ses couvertures et ayant rejeté bien loin son
bonnet de coton. C'était un gros petit homme de cinquante ans, à
la peau blanche, à l'oeil bleu, chauve sur le devant de la tête,
et qui portait des boucles d'oreilles. Il avait à ses côtés, sur
une chaise, une grande carafe d'eau-de-vie, dont il se versait de
temps à autre pour se donner du coeur au ventre; mais, dès qu'il
vit le médecin, son exaltation tomba, et, au lieu de sacrer comme
il faisait depuis douze heures, il se prit à geindre faiblement.

La fracture était simple, sans complication d'aucune espèce.
Charles n'eût osé en souhaiter de plus facile. Alors, se rappelant
les allures de ses maîtres auprès du lit des blessés, il
réconforta le patient avec toutes sortes de bons mots; caresses
chirurgicales qui sont comme l'huile dont on graisse les
bistouris. Afin d'avoir des attelles, on alla chercher, sous la
charreterie, un paquet de lattes. Charles en choisit une, la coupa
en morceaux et la polit avec un éclat de vitre, tandis que la
servante déchirait des draps pour faire des bandes, et que
mademoiselle Emma tâchait à coudre des coussinets. Comme elle fut
longtemps avant de trouver son étui, son père s'impatienta; elle
ne répondit rien; mais, tout en cousant, elle se piquait les
doigts, qu'elle portait ensuite à sa bouche pour les sucer.

Charles fut surpris de la blancheur de ses ongles. Ils étaient
brillants, fins du bout, plus nettoyés que les ivoires de Dieppe,
et taillés en amande. Sa main pourtant n'était pas belle, point
assez pâle peut-être, et un peu sèche aux phalanges; elle était
trop longue aussi, et sans molles inflexions de lignes sur les
contours. Ce qu'elle avait de beau, c'étaient les yeux; quoiqu'ils
fussent bruns, ils semblaient noirs à cause des cils, et son
regard arrivait franchement à vous avec une hardiesse candide.

Une fois le pansement fait, le médecin fut invité, par M. Rouault
lui-même, à prendre un morceau avant de partir.

Charles descendit dans la salle, au rez-de-chaussée. Deux
couverts, avec des timbales d'argent, y étaient mis sur une petite
table, au pied d'un grand lit à baldaquin revêtu d'une indienne à
personnages représentant des Turcs. On sentait une odeur d'iris et
de draps humides, qui s'échappait de la haute armoire en bois de
chêne, faisant face à la fenêtre. Par terre, dans les angles,
étaient rangés, debout, des sacs de blé. C'était le trop-plein du
grenier proche, où l'on montait par trois marches de pierre. Il y
avait, pour décorer l'appartement, accrochée à un clou, au milieu
du mur dont la peinture verte s'écaillait sous le salpêtre, une
tête de Minerve au crayon noir, encadrée de dorure, et qui portait
au bas, écrit en lettres gothiques: «À mon cher papa.»

On parla d'abord du malade, puis du temps qu'il faisait, des
grands froids, des loups qui couraient les champs, la nuit.
Mademoiselle Rouault ne s'amusait guère à la campagne, maintenant
surtout qu'elle était chargée presque à elle seule des soins de la
ferme. Comme la salle était fraîche, elle grelottait tout en
mangeant, ce qui découvrait un peu ses lèvres charnues, qu'elle
avait coutume de mordillonner à ses moments de silence.

Son cou sortait d'un col blanc, rabattu. Ses cheveux, dont les
deux bandeaux noirs semblaient chacun d'un seul morceau, tant ils
étaient lisses, étaient séparés sur le milieu de la tête par une
raie fine, qui s'enfonçait légèrement selon la courbe du crâne;
et, laissant voir à peine le bout de l'oreille, ils allaient se
confondre par derrière en un chignon abondant, avec un mouvement
ondé vers les tempes, que le médecin de campagne remarqua là pour
la première fois de sa vie. Ses pommettes étaient roses. Elle
portait, comme un homme, passé entre deux boutons de son corsage,
un lorgnon d'écaille.

Quand Charles, après être monté dire adieu au père Rouault, rentra
dans la salle avant de partir, il la trouva debout, le front
contre la fenêtre, et qui regardait dans le jardin, où les échalas
des haricots avaient été renversés par le vent. Elle se retourna.

-- Cherchez-vous quelque chose? demanda-t-elle.

-- Ma cravache, s'il vous plaît, répondit-il.

Et il se mit à fureter sur le lit, derrière les portes, sous les
chaises; elle était tombée à terre, entre les sacs et la muraille.
Mademoiselle Emma l'aperçut; elle se pencha sur les sacs de blé.
Charles, par galanterie, se précipita et, comme il allongeait
aussi son bras dans le même mouvement, il sentit sa poitrine
effleurer le dos de la jeune fille, courbée sous lui. Elle se
redressa toute rouge et le regarda par-dessus l'épaule, en lui
tendant son nerf de boeuf.

Au lieu de revenir aux Bertaux trois jours après, comme il l'avait
promis, c'est le lendemain même qu'il y retourna, puis deux fois
la semaine régulièrement, sans compter les visites inattendues
qu'il faisait de temps à autre, comme par mégarde.

Tout, du reste, alla bien; la guérison s'établit selon les règles,
et quand, au bout de quarante-six jours, on vit le père Rouault
qui s'essayait à marcher seul dans sa masure, on commença à
considérer M. Bovary comme un homme de grande capacité. Le père
Rouault disait qu'il n'aurait pas été mieux guéri par les premiers
médecins d'Yvetot ou même de Rouen.

Quant à Charles, il ne chercha point à se demander pourquoi il
venait aux Bertaux avec plaisir. Y eût-il songé, qu'il aurait sans
doute attribué son zèle à la gravité du cas, ou peut-être au
profit qu'il en espérait. Était-ce pour cela, cependant, que ses
visites à la ferme faisaient, parmi les pauvres occupations de sa
vie, une exception charmante? Ces jours-là il se levait de bonne
heure, partait au galop, poussait sa bête, puis il descendait pour
s'essuyer les pieds sur l'herbe, et passait ses gants noirs avant
d'entrer. Il aimait à se voir arriver dans la cour, à sentir
contre son épaule la barrière qui tournait, et le coq qui chantait
sur le mur, les garçons qui venaient à sa rencontre. Il aimait la
grange et les écuries; il aimait le père Rouault; qui lui tapait
dans la main en l'appelant son sauveur; il aimait les petits
sabots de mademoiselle Emma sur les dalles lavées de la cuisine;
ses talons hauts la grandissaient un peu, et, quand elle marchait
devant lui, les semelles de bois, se relevant vite, claquaient
avec un bruit sec contre le cuir de la bottine.

Elle le reconduisait toujours jusqu'à la première marche du
perron. Lorsqu'on n'avait pas encore amené son cheval, elle
restait là. On s'était dit adieu, on ne parlait plus; le grand air
l'entourait, levant pêle-mêle les petits cheveux follets de sa
nuque, ou secouant sur sa hanche les cordons de son tablier, qui
se tortillaient comme des banderoles. Une fois, par un temps de
dégel, l'écorce des arbres suintait dans la cour, la neige sur les
couvertures des bâtiments se fondait. Elle était sur le seuil;
elle alla chercher son ombrelle, elle l'ouvrit. L'ombrelle, de
soie gorge de pigeon, que traversait le soleil, éclairait de
reflets mobiles la peau blanche de sa figure. Elle souriait là-
dessous à la chaleur tiède; et on entendait les gouttes d'eau, une
à une, tomber sur la moire tendue.

Dans les premiers temps que Charles fréquentait les Bertaux,
madame Bovary jeune ne manquait pas de s'informer du malade, et
même sur le livre qu'elle tenait en partie double, elle avait
choisi pour M. Rouault une belle page blanche. Mais quand elle sut
qu'il avait une fille, elle alla aux informations; et elle apprit
que mademoiselle Rouault, élevée au couvent, chez les Ursulines,
avait reçu, comme on dit, une belle éducation, qu'elle savait, en
conséquence, la danse, la géographie, le dessin, faire de la
tapisserie et toucher du piano. Ce fut le comble!

-- C'est donc pour cela, se disait-elle, qu'il a la figure si
épanouie quand il va la voir, et qu'il met son gilet neuf, au
risque de l'abîmer à la pluie? Ah! cette femme! cette femme!...

Et elle la détesta, d'instinct. D'abord, elle se soulagea par des
allusions, Charles ne les comprit pas; ensuite, par des réflexions
incidentes qu'il laissait passer de peur de l'orage; enfin, par
des apostrophes à brûle-pourpoint auxquelles il ne savait que
répondre.

-- D'où vient qu'il retournait aux Bertaux, puisque M. Rouault
était guéri et que ces gens-là n'avaient pas encore payé? Ah!
c'est qu'il y avait là-bas une personne, quelqu'un qui savait
causer, une brodeuse, un bel esprit. C'était là ce qu'il aimait:
il lui fallait des demoiselles de ville! -- Et elle reprenait:

-- La fille au père Rouault, une demoiselle de ville! Allons donc!
leur grand-père était berger, et ils ont un cousin qui a failli
passer par les assises pour un mauvais coup, dans une dispute. Ce
n'est pas la peine de faire tant de fla-fla, ni de se montrer le
dimanche à l'église avec une robe de soie, comme une comtesse.
Pauvre bonhomme, d'ailleurs, qui sans les colzas de l'an passé,
eût été bien embarrassé de payer ses arrérages!

Par lassitude, Charles cessa de retourner aux Bertaux. Héloïse lui
avait fait jurer qu'il n'irait plus, la main sur son livre de
messe, après beaucoup de sanglots et de baisers, dans une grande
explosion d'amour. Il obéit donc; mais la hardiesse de son désir
protesta contre la servilité de sa conduite, et, par une sorte
d'hypocrisie naïve, il estima que cette défense de la voir était
pour lui comme un droit de l'aimer. Et puis la veuve était maigre;
elle avait les dents longues; elle portait en toute saison un
petit châle noir dont la pointe lui descendait entre les
omoplates; sa taille dure était engainée dans des robes en façon
de fourreau, trop courtes, qui découvraient ses chevilles, avec
les rubans de ses souliers larges s'entrecroisant sur des bas
gris.

La mère de Charles venait les voir de temps à autre; mais, au bout
de quelques jours, la bru semblait l'aiguiser à son fil; et alors,
comme deux couteaux, elles étaient à le scarifier par leurs
réflexions et leurs observations. Il avait tort de tant manger!
Pourquoi toujours offrir la goutte au premier venu? Quel
entêtement que de ne pas vouloir porter de flanelle!

Il arriva qu'au commencement du printemps, un notaire
d'Ingouville, détenteur de fonds de la veuve Dubuc, s'embarqua,
par une belle marée, emportant avec lui tout l'argent de son
étude. Héloïse, il est vrai, possédait encore, outre une part de
bateau évaluée six mille francs, sa maison de la rue Saint-
François; et cependant, de toute cette fortune que l'on avait fait
sonner si haut, rien, si ce n'est un peu de mobilier et quelques
nippes, n'avait paru dans le ménage. Il fallut tirer la chose au
clair. La maison de Dieppe se trouva vermoulue d'hypothèques
jusque dans ses pilotis; ce qu'elle avait mis chez le notaire,
Dieu seul le savait, et la part de barque n'excéda point mille
écus. Elle avait donc menti, la bonne dame! Dans son exaspération,
M. Bovary père, brisant une chaise contre les pavés, accusa sa
femme d'avoir fait le malheur de leur fils en l'attelant à une
haridelle semblable, dont les harnais ne valaient pas la peau. Ils
vinrent à Tostes. On s'expliqua. Il y eut des scènes. Héloïse, en
pleurs, se jetant dans les bras de son mari, le conjura de la
défendre de ses parents. Charles voulut parler pour elle. Ceux-ci
se fâchèrent, et ils partirent.

Mais le coup était porté. Huit jours après, comme elle étendait du
linge dans sa cour, elle fut prise d'un crachement de sang, et le
lendemain, tandis que Charles avait le dos tourné pour fermer le
rideau de la fenêtre, elle dit: «Ah! mon Dieu!» poussa un soupir
et s'évanouit. Elle était morte! Quel étonnement!

Quand tout fut fini au cimetière, Charles rentra chez lui. Il ne
trouva personne en bas; il monta au premier, dans la chambre, vit
sa robe encore accrochée au pied de l'alcôve; alors, s'appuyant
contre le secrétaire, il resta jusqu'au soir perdu dans une
rêverie douloureuse. Elle l'avait aimé, après tout.


III

Un matin, le père Rouault vint apporter à Charles le payement de
sa jambe remise: soixante et quinze francs en pièces de quarante
sous, et une dinde. Il avait appris son malheur, et l'en consola
tant qu'il put.

-- Je sais ce que c'est! disait-il en lui frappant sur l'épaule;
j'ai été comme vous, moi aussi! Quand j'ai eu perdu ma pauvre
défunte, j'allais dans les champs pour être tout seul; je tombais
au pied d'un arbre, je pleurais, j'appelais le bon Dieu, je lui
disais des sottises; j'aurais voulu être comme les taupes, que je
voyais aux branches, qui avaient des vers leur grouillant dans le
ventre, crevé, enfin. Et quand je pensais que d'autres, à ce
moment-là, étaient avec leurs bonnes petites femmes à les tenir
embrassées contre eux, je tapais de grands coups par terre avec
mon bâton; j'étais quasiment fou, que je ne mangeais plus; l'idée
d'aller seulement au café me dégoûtait, vous ne croiriez pas. Eh
bien, tout doucement, un jour chassant l'autre, un printemps sur
un hiver et un automne par-dessus un été, ça a coulé brin à brin,
miette à miette; ça s'en est allé, c'est parti, c'est descendu, je
veux dire, car il vous reste toujours quelque chose au fond, comme
qui dirait... un poids, là, sur la poitrine! Mais, puisque c'est
notre sort à tous, on ne doit pas non plus se laisser dépérir, et,
parce que d'autres sont morts, vouloir mourir... Il faut vous
secouer, monsieur Bovary; ça se passera! Venez nous voir; ma fille
pense à vous de temps à autre, savez-vous bien, et elle dit comme
ça que vous l'oubliez. Voilà le printemps bientôt; nous vous
ferons tirer un lapin dans la garenne, pour vous dissiper un peu.

Charles suivit son conseil. Il retourna aux Bertaux; il retrouva
tout comme la veille, comme il y avait cinq mois, c'est-à-dire.
Les poiriers déjà étaient en fleur, et le bonhomme Rouault, debout
maintenant, allait et venait, ce qui rendait la ferme plus animée.

Croyant qu'il était de son devoir de prodiguer au médecin le plus
de politesses possible, à cause de sa position douloureuse, il le
pria de ne point se découvrir la tête, lui parla à voix basse,
comme s'il eût été malade, et même fit semblant de se mettre en
colère de ce que l'on n'avait pas apprêté à son intention quelque
chose d'un peu plus léger que tout le reste, tels que des petits
pots de crème ou des poires cuites. Il conta des histoires.
Charles se surprit à rire; mais le souvenir de sa femme, lui
revenant tout à coup, l'assombrit.

On apporta le café; il n'y pensa plus.

Il y pensa moins, à mesure qu'il s'habituait à vivre seul.
L'agrément nouveau de l'indépendance lui rendit bientôt la
solitude plus supportable. Il pouvait changer maintenant les
heures de ses repas, rentrer ou sortir sans donner de raisons, et,
lorsqu'il était bien fatigué, s'étendre de ses quatre membres,
tout en large, dans son lit. Donc, il se choya, se dorlota et
accepta les consolations qu'on lui donnait. D'autre part, la mort
de sa femme ne l'avait pas mal servi dans son métier, car on avait
répété durant un mois: «Ce pauvre jeune homme! quel malheur!» Son
nom s'était répandu, sa clientèle s'était accrue; et puis il
allait aux Bertaux tout à son aise. Il avait un espoir sans but,
un bonheur vague; il se trouvait la figure plus agréable en
brossant ses favoris devant son miroir.

Il arriva un jour vers trois heures; tout le monde était aux
champs; il entra dans la cuisine, mais n'aperçut point d'abord
Emma; les auvents étaient fermés. Par les fentes du bois, le
soleil allongeait sur les pavés de grandes raies minces, qui se
brisaient à l'angle des meubles et tremblaient au plafond. Des
mouches, sur la table, montaient le long des verres qui avaient
servi, et bourdonnaient en se noyant au fond, dans le cidre resté.
Le jour qui descendait par la cheminée, veloutant la suie de la
plaque, bleuissait un peu les cendres froides. Entre la fenêtre et
le foyer, Emma cousait; elle n'avait point de fichu, on voyait sur
ses épaules nues de petites gouttes de sueur.

Selon la mode de la campagne, elle lui proposa de boire quelque
chose. Il refusa, elle insista, et enfin lui offrit, en riant, de
prendre un verre de liqueur avec elle. Elle alla donc chercher
dans l'armoire une bouteille de curaçao, atteignit deux petits
verres, emplit l'un jusqu'au bord, versa à peine dans l'autre, et,
après avoir trinqué, le porta à sa bouche. Comme il était presque
vide, elle se renversait pour boire; et, la tête en arrière, les
lèvres avancées, le cou tendu, elle riait de ne rien sentir,
tandis que le bout de sa langue, passant entre ses dents fines,
léchait à petits coups le fond du verre.

Elle se rassit et elle reprit son ouvrage, qui était un bas de
coton blanc où elle faisait des reprises; elle travaillait le
front baissé; elle ne parlait pas, Charles non plus. L'air,
passant par le dessous de la porte, poussait un peu de poussière
sur les dalles; il la regardait se traîner, et il entendait
seulement le battement intérieur de sa tête, avec le cri d'une
poule, au loin, qui pondait dans les cours. Emma, de temps à
autre, se rafraîchissait les joues en y appliquant la paume de ses
mains; qu'elle refroidissait après cela sur la pomme de fer des
grands chenets.

Elle se plaignit d'éprouver, depuis le commencement de la saison,
des étourdissements; elle demanda si les bains de mer lui seraient
utiles; elle se mit à causer du couvent, Charles de son collège,
les phrases leur vinrent. Ils montèrent dans sa chambre. Elle lui
fit voir ses anciens cahiers de musique, les petits livres qu'on
lui avait donnés en prix et les couronnes en feuilles de chêne,
abandonnées dans un bas d'armoire. Elle lui parla encore de sa
mère, du cimetière, et même lui montra dans le jardin la plate-
bande dont elle cueillait les fleurs, tous les premiers vendredis
de chaque mois, pour les aller mettre sur sa tombe. Mais le
jardinier qu'ils avaient n'y entendait rien; on était si mal
servi! Elle eût bien voulu, ne fût-ce au moins que pendant
l'hiver, habiter la ville, quoique la longueur des beaux jours
rendît peut-être la campagne plus ennuyeuse encore durant l'été; -
- et, selon ce qu'elle disait, sa voix était claire, aiguë, ou se
couvrant de langueur tout à coup, traînait des modulations qui
finissaient presque en murmures, quand elle se parlait à elle-
même, -- tantôt joyeuse, ouvrant des yeux naïfs, puis les
paupières à demi closes, le regard noyé d'ennui, la pensée
vagabondant.

Le soir, en s'en retournant, Charles reprit une à une les phrases
qu'elle avait dites, tâchant de se les rappeler, d'en compléter le
sens, afin de se faire la portion d'existence qu'elle avait vécu
dans le temps qu'il ne la connaissait pas encore. Mais jamais il
ne put la voir en sa pensée, différemment qu'il ne l'avait vue la
première fois, ou telle qu'il venait de la quitter tout à l'heure.
Puis il se demanda ce qu'elle deviendrait, si elle se marierait,
et à qui? hélas! le père Rouault était bien riche, et elle!... si
belle! Mais la figure d'Emma revenait toujours se placer devant
ses yeux, et quelque chose de monotone comme le ronflement d'une
toupie bourdonnait à ses oreilles: «Si tu te mariais, pourtant! si
tu te mariais!» La nuit, il ne dormit pas, sa gorge était serrée,
il avait soif; il se leva pour aller boire à son pot à l'eau et il
ouvrit la fenêtre; le ciel était couvert d'étoiles, un vent chaud
passait, au loin des chiens aboyaient. Il tourna la tête du côté
des Bertaux.

Pensant qu'après tout l'on ne risquait rien, Charles se promit de
faire la demande quand l'occasion s'en offrirait; mais, chaque
fois qu'elle s'offrit, la peur de ne point trouver les mots
convenables lui collait les lèvres.

Le père Rouault n'eût pas été fâché qu'on le débarrassât de sa
fille, qui ne lui servait guère dans sa maison. Il l'excusait
intérieurement, trouvant qu'elle avait trop d'esprit pour la
culture, métier maudit du ciel, puisqu'on n'y voyait jamais de
millionnaire. Loin d'y avoir fait fortune, le bonhomme y perdait
tous les ans; car, s'il excellait dans les marchés, où il se
plaisait aux ruses du métier, en revanche la culture proprement
dite, avec le gouvernement intérieur de la ferme, lui convenait
moins qu'à personne. Il ne retirait pas volontiers ses mains de
dedans ses poches, et n'épargnait point la dépense pour tout ce
qui regardait sa vie, voulant être bien nourri, bien chauffé, bien
couché. Il aimait le gros cidre, les gigots saignants, les glorias
longuement battus. Il prenait ses repas dans la cuisine, seul, en
face du feu, sur une petite table qu'on lui apportait toute
servie, comme au théâtre.

Lorsqu'il s'aperçut donc que Charles avait les pommettes rouges
près de sa fille, ce qui signifiait qu'un de ces jours on la lui
demanderait en mariage, il rumina d'avance toute l'affaire. Il le
trouvait bien un peu gringalet, et ce n'était pas là un gendre
comme il l'eût souhaité; mais on le disait de bonne conduite,
économe, fort instruit, et sans doute qu'il ne chicanerait pas
trop sur la dot. Or, comme le père Rouault allait être forcé de
vendre vingt-deux acres de son bien, qu'il devait beaucoup au
maçon, beaucoup au bourrelier, que l'arbre du pressoir était à
remettre:

-- S'il me la demande, se dit-il; je la lui donne.

À l'époque de la Saint-Michel, Charles était venu passer trois
jours aux Bertaux. La dernière journée s'était écoulée comme les
précédentes, à reculer de quart d'heure en quart d'heure. Le père
Rouault lui fit la conduite; ils marchaient dans un chemin creux,
ils s'allaient quitter; c'était le moment. Charles se donna
jusqu'au coin de la haie, et enfin, quand on l'eut dépassée:

-- Maître Rouault, murmura-t-il, je voudrais bien vous dire
quelque chose.

Ils s'arrêtèrent. Charles se taisait.

-- Mais contez-moi votre histoire! est-ce que je ne sais pas tout?
dit le père Rouault, en riant doucement.

-- Père Rouault..., père Rouault..., balbutia Charles.

-- Moi, je ne demande pas mieux, continua le fermier. Quoique sans
doute la petite soit de mon idée, il faut pourtant lui demander
son avis. Allez-vous-en donc; je m'en vais retourner chez nous. Si
c'est oui, entendez-moi bien, vous n'aurez pas besoin de revenir,
à cause du monde, et, d'ailleurs, ça la saisirait trop. Mais pour
que vous ne vous mangiez pas le sang, je pousserai tout grand
l'auvent de la fenêtre contre le mur: vous pourrez le voir par
derrière, en vous penchant sur la haie.

Et il s'éloigna.

Charles attacha son cheval à un arbre. Il courut se mettre dans le
sentier; il attendit. Une demi-heure se passa, puis il compta dix-
neuf minutes à sa montre. Tout à coup un bruit se fit contre le
mur; l'auvent s'était rabattu, la cliquette tremblait encore.

Le lendemain, dès neuf heures, il était à la ferme. Emma rougit
quand il entra, tout en s'efforçant de rire un peu; par
contenance. Le père Rouault embrassa son futur gendre. On remit à
causer des arrangements d'intérêt; on avait, d'ailleurs, du temps
devant soi, puisque le mariage ne pouvait décemment avoir lieu
avant la fin du deuil de Charles, c'est-à-dire vers le printemps
de l'année prochaine.

L'hiver se passa dans cette attente. Mademoiselle Rouault s'occupa
de son trousseau. Une partie en fut commandée à Rouen, et elle se
confectionna des chemises et des bonnets de nuit, d'après des
dessins de modes qu'elle emprunta. Dans les visites que Charles
faisait à la ferme, on causait des préparatifs de la noce; on se
demandait dans quel appartement se donnerait le dîner; on rêvait à
la quantité de plats qu'il faudrait et quelles seraient les
entrées.

Emma eût, au contraire, désiré se marier à minuit, aux flambeaux;
mais le père Rouault ne comprit rien à cette idée. Il y eut donc
une noce, où vinrent quarante-trois personnes, où l'on resta seize
heures à table, qui recommença le lendemain et quelque peu les
jours suivants.


IV

Les conviés arrivèrent de bonne heure dans des voitures, carrioles
à un cheval, chars à bancs à deux roues, vieux cabriolets sans
capote, tapissières à rideaux de cuir, et les jeunes gens des
villages les plus voisins dans des charrettes où ils se tenaient
debout, en rang, les mains appuyées sur les ridelles pour ne pas
tomber, allant au trot et secoués dur. Il en vint de dix lieues
loin, de Goderville, de Normanville, et de Cany. On avait invité
tous les parents des deux familles, on s'était raccommodé avec les
amis brouillés, on avait écrit à des connaissances perdues de vue
depuis longtemps.

De temps à autre, on entendait des coups de fouet derrière la
haie; bientôt la barrière s'ouvrait: c'était une carriole qui
entrait. Galopant jusqu'à la première marche du perron, elle s'y
arrêtait court, et vidait son monde, qui sortait par tous les
côtés en se frottant les genoux et en s'étirant les bras. Les
dames, en bonnet, avaient des robes à la façon de la ville, des
chaînes de montre en or, des pèlerines à bouts croisés dans la
ceinture, ou de petits fichus de couleur attachés dans le dos avec
une épingle, et qui leur découvraient le cou par derrière. Les
gamins, vêtus pareillement à leurs papas, semblaient incommodés
par leurs habits neufs (beaucoup même étrennèrent ce jour-là la
première paire de bottes de leur existence), et l'on voyait à côté
d'eux, ne soufflant mot dans la robe blanche de sa première
communion rallongée pour la circonstance, quelque grande fillette
de quatorze ou seize ans, leur cousine ou leur soeur aînée sans
doute, rougeaude, ahurie, les cheveux gras de pommade à la rose,
et ayant bien peur de salir ses gants. Comme il n'y avait point
assez de valets d'écurie pour dételer toutes les voitures, les
messieurs retroussaient leurs manches et s'y mettaient eux-mêmes.
Suivant leur position sociale différente, ils avaient des habits,
des redingotes, des vestes, des habits-vestes: -- bons habits,
entourés de toute la considération d'une famille, et qui ne
sortaient de l'armoire que pour les solennités; redingotes à
grandes basques flottant au vent, à collet cylindrique, à poches
larges comme des sacs; vestes de gros drap, qui accompagnaient
ordinairement quelque casquette cerclée de cuivre à sa visière;
habits-vestes très courts, ayant dans le dos deux boutons
rapprochés comme une paire d'yeux, et dont les pans semblaient
avoir été coupés à même un seul bloc, par la hache du charpentier.
Quelques-uns encore (mais ceux-là, bien sûr, devaient dîner au bas
bout de la table) portaient des blouses de cérémonie, c'est-à-dire
dont le col était rabattu sur les épaules, le dos froncé à petits
plis et la taille attachée très bas par une ceinture cousue.

Et les chemises sur les poitrines bombaient comme des cuirasses!
Tout le monde était tondu à neuf, les oreilles s'écartaient des
têtes, on était rasé de près; quelques-uns même qui s'étaient
levés dès avant l'aube, n'ayant pas vu clair à se faire la barbe,
avaient des balafres en diagonale sous le nez, ou, le long des
mâchoires, des pelures d'épiderme larges comme des écus de trois
francs, et qu'avait enflammées le grand air pendant la route, ce
qui marbrait un peu de plaques roses toutes ces grosses faces
blanches épanouies.

La mairie se trouvant à une demi-lieue de la ferme, on s'y rendit
à pied, et l'on revint de même, une fois la cérémonie faite à
l'église. Le cortège, d'abord uni comme une seule écharpe de
couleur, qui ondulait dans la campagne, le long de l'étroit
sentier serpentant entre les blés verts, s'allongea bientôt et se
coupa en groupes différents, qui s'attardaient à causer. Le
ménétrier allait en tête, avec son violon empanaché de rubans à la
coquille; les mariés venaient ensuite, les parents, les amis tout
au hasard, et les enfants restaient derrière, s'amusant à arracher
les clochettes des brins d'avoine, ou à se jouer entre eux, sans
qu'on les vît. La robe d'Emma, trop longue, traînait un peu par le
bas; de temps à autre, elle s'arrêtait pour la tirer, et alors
délicatement, de ses doigts gantés, elle enlevait les herbes rudes
avec les petits dards des chardons, pendant que Charles, les mains
vides, attendait qu'elle eût fini. Le père Rouault, un chapeau de
soie neuf sur la tête et les parements de son habit noir lui
couvrant les mains jusqu'aux ongles, donnait le bras à madame
Bovary mère. Quant à M. Bovary père, qui, méprisant au fond tout
ce monde-là, était venu simplement avec une redingote à un rang de
boutons d'une coupe militaire, il débitait des galanteries
d'estaminet à une jeune paysanne blonde. Elle saluait, rougissait,
ne savait que répondre. Les autres gens de la noce causaient de
leurs affaires ou se faisaient des niches dans le dos, s'excitant
d'avance à la gaieté; et, en y prêtant l'oreille, on entendait
toujours le crin-crin du ménétrier qui continuait à jouer dans la
campagne. Quand il s'apercevait qu'on était loin derrière lui, il
s'arrêtait à reprendre haleine, cirait longuement de colophane son
archet, afin que les cordes grinçassent mieux, et puis il se
remettait à marcher, abaissant et levant tour à tour le manche de
son violon, pour se bien marquer la mesure à lui-même. Le bruit de
l'instrument faisait partir de loin les petits oiseaux.

C'était sous le hangar de la charreterie que la table était
dressée. Il y avait dessus quatre aloyaux, six fricassées de
poulets, du veau à la casserole, trois gigots, et, au milieu, un
joli cochon de lait rôti, flanqué de quatre andouilles à
l'oseille. Aux angles, se dressait l'eau de vie dans des carafes.
Le cidre doux en bouteilles poussait sa mousse épaisse autour des
bouchons, et tous les verres, d'avance, avaient été remplis de vin
jusqu'au bord. De grands plats de crème jaune, qui flottaient
d'eux-mêmes au moindre choc de la table, présentaient, dessinés
sur leur surface unie, les chiffres des nouveaux époux en
arabesques de nonpareille. On avait été chercher un pâtissier à
Yvetot, pour les tourtes et les nougats. Comme il débutait dans le
pays, il avait soigné les choses; et il apporta, lui-même, au
dessert, une pièce montée qui fit pousser des cris. À la base,
d'abord, c'était un carré de carton bleu figurant un temple avec
portiques, colonnades et statuettes de stuc tout autour, dans des
niches constellées d'étoiles en papier doré; puis se tenait au
second étage un donjon en gâteau de Savoie, entouré de menues
fortifications en angélique, amandes, raisins secs, quartiers
d'oranges; et enfin, sur la plate-forme supérieure, qui était une
prairie verte où il y avait des rochers avec des lacs de
confitures et des bateaux en écales de noisettes, on voyait un
petit Amour, se balançant à une escarpolette de chocolat, dont les
deux poteaux étaient terminés par deux boutons de rose naturels,
en guise de boules, au sommet.

Jusqu'au soir, on mangea. Quand on était trop fatigué d'être
assis, on allait se promener dans les cours ou jouer une partie de
bouchon dans la grange; puis on revenait à table. Quelques-uns,
vers la fin, s'y endormirent et ronflèrent. Mais, au café, tout se
ranima; alors on entama des chansons, on fit des tours de force,
on portait des poids, on passait sous son pouce, on essayait à
soulever les charrettes sur ses épaules, on disait des gaudrioles;
on embrassait les dames. Le soir, pour partir, les chevaux gorgés
d'avoine jusqu'aux naseaux, eurent du mal à entrer dans les
brancards; ils ruaient, se cabraient, les harnais se cassaient,
leurs maîtres juraient ou riaient; et toute la nuit, au clair de
la lune, par les routes du pays, il y eut des carrioles emportées
qui couraient au grand galop, bondissant dans les saignées,
sautant par-dessus les mètres de cailloux, s'accrochant aux talus,
avec des femmes qui se penchaient en dehors de la portière pour
saisir les guides.

Ceux qui restèrent aux Bertaux passèrent la nuit à boire dans la
cuisine. Les enfants s'étaient endormis sous les bancs.

La mariée avait supplié son père qu'on lui épargnât les
plaisanteries d'usage. Cependant, un mareyeur de leurs cousins
(qui même avait apporté, comme présent de noces, une paire de
soles) commençait à souffler de l'eau avec sa bouche par le trou
de la serrure, quand le père Rouault arriva juste à temps pour
l'en empêcher, et lui expliqua que la position grave de son gendre
ne permettait pas de telles inconvenances. Le cousin, toutefois,
céda difficilement à ces raisons. En dedans de lui-même, il accusa
le père Rouault d'être fier, et il alla se joindre dans un coin à
quatre ou cinq autres des invités qui, ayant eu par hasard
plusieurs fois de suite à table les bas morceaux des viandes,
trouvaient aussi qu'on les avait mal reçus, chuchotaient sur le
compte de leur hôte et souhaitaient sa ruine à mots couverts.

Madame Bovary mère n'avait pas desserré les dents de la journée.
On ne l'avait consultée ni sur la toilette de la bru, ni sur
l'ordonnance du festin; elle se retira de bonne heure. Son époux,
au lieu de la suivre, envoya chercher des cigares à Saint-Victor
et fuma jusqu'au jour, tout en buvant des grogs au kirsch, mélange
inconnu à la compagnie, et qui fut pour lui comme la source d'une
considération plus grande encore.

Charles n'était point de complexion facétieuse, il n'avait pas
brillé pendant la noce. Il répondit médiocrement aux pointes,
calembours, mots à double entente, compliments et gaillardises que
l'on se fit un devoir de lui décocher dès le potage.

Le lendemain, en revanche, il semblait un autre homme. C'est lui
plutôt que l'on eût pris pour la vierge de la veille, tandis que
la mariée ne laissait rien découvrir où l'on pût deviner quelque
chose. Les plus malins ne savaient que répondre, et ils la
considéraient, quand elle passait près d'eux, avec des tensions
d'esprit démesurées. Mais Charles ne dissimulait rien. Il
l'appelait ma femme, la tutoyait, s'informait d'elle à chacun, la
cherchait partout, et souvent il l'entraînait dans les cours, où
on l'apercevait de loin, entre les arbres, qui lui passait le bras
sous la taille et continuait à marcher à demi penché sur elle, en
lui chiffonnant avec sa tête la guimpe de son corsage.

Deux jours après la noce, les époux s'en allèrent: Charles, à
cause de ses malades, ne pouvait s'absenter plus longtemps. Le
père Rouault les fit reconduire dans sa carriole et les accompagna
lui-même jusqu'à Vassonville. Là, il embrassa sa fille une
dernière fois, mit pied à terre et reprit sa route. Lorsqu'il eut
fait cent pas environ, il s'arrêta, et, comme il vit la carriole
s'éloignant, dont les roues tournaient dans la poussière, il
poussa un gros soupir. Puis il se rappela ses noces, son temps
d'autrefois, la première grossesse de sa femme; il était bien
joyeux, lui aussi, le jour qu'il l'avait emmenée de chez son père
dans sa maison, quand il la portait en croupe en trottant sur la
neige; car on était aux environs de Noël et la campagne était
toute blanche; elle le tenait par un bras, à l'autre était
accroché son panier; le vent agitait les longues dentelles de sa
coiffure cauchoise, qui lui passaient quelquefois sur la bouche,
et, lorsqu'il tournait la tête, il voyait près de lui, sur son
épaule, sa petite mine rosée qui souriait silencieusement, sous la
plaque d'or de son bonnet. Pour se réchauffer les doigts, elle les
lui mettait, de temps en temps, dans la poitrine. Comme c'était
vieux tout cela! Leur fils, à présent, aurait trente ans! Alors il
regarda derrière lui, il n'aperçut rien sur la route. Il se sentit
triste comme une maison démeublée; et, les souvenirs tendres se
mêlant aux pensées noires dans sa cervelle obscurcie par les
vapeurs de la bombance, il eut bien envie un moment d'aller faire
un tour du côté de l'église. Comme il eut peur, cependant, que
cette vue ne le rendît plus triste encore, il s'en revint tout
droit chez lui.

M. et madame Charles arrivèrent à Tostes, vers six heures. Les
voisins se mirent aux fenêtres pour voir la nouvelle femme de leur
médecin.

La vieille bonne se présenta, lui fit ses salutations, s'excusa de
ce que le dîner n'était pas prêt, et engagea Madame, en attendant,
à prendre connaissance de sa maison.


V

La façade de briques était juste à l'alignement de la rue, ou de
la route plutôt. Derrière la porte se trouvaient accrochés un
manteau à petit collet, une bride, une casquette de cuir noir, et,
dans un coin, à terre, une paire de houseaux encore couverts de
boue sèche. À droite était la salle, c'est-à-dire l'appartement où
l'on mangeait et où l'on se tenait. Un papier jaune-serin, relevé
dans le haut par une guirlande de fleurs pâles, tremblait tout
entier sur sa toile mal tendue; des rideaux de calicot blanc,
bordés d'un galon rouge, s'entrecroisaient le long des fenêtres,
et sur l'étroit chambranle de la cheminée resplendissait une
pendule à tête d'Hippocrate, entre deux flambeaux d'argent plaqué,
sous des globes de forme ovale. De l'autre côté du corridor était
le cabinet de Charles, petite pièce de six pas de large environ,
avec une table, trois chaises et un fauteuil de bureau. Les tomes
du Dictionnaire des sciences médicales, non coupés, mais dont la
brochure avait souffert dans toutes les ventes successives par où
ils avaient passé, garnissaient presque à eux seuls, les six
rayons d'une bibliothèque en bois de sapin. L'odeur des roux
pénétrait à travers la muraille, pendant les consultations, de
même que l'on entendait de la cuisine, les malades tousser dans le
cabinet et débiter toute leur histoire. Venait ensuite, s'ouvrant
immédiatement sur la cour, où se trouvait l'écurie, une grande
pièce délabrée qui avait un four, et qui servait maintenant de
bûcher, de cellier, de garde-magasin, pleine de vieilles
ferrailles, de tonneaux vides, d'instruments de culture hors de
service, avec quantité d'autres choses poussiéreuses dont il était
impossible de deviner l'usage.

Le jardin, plus long que large, allait, entre deux murs de bauge
couverts d'abricots en espalier, jusqu'à une haie d'épines qui le
séparait des champs. Il y avait au milieu un cadran solaire en
ardoise, sur un piédestal de maçonnerie; quatre plates-bandes
garnies d'églantiers maigres entouraient symétriquement le carré
plus utile des végétations sérieuses. Tout au fond, sous les
sapinettes, un curé de plâtre lisait son bréviaire.

Emma monta dans les chambres. La première n'était point meublée;
mais la seconde, qui était la chambre conjugale, avait un lit
d'acajou dans une alcôve à draperie rouge. Une boîte en
coquillages décorait la commode; et, sur le secrétaire, près de la
fenêtre, il y avait, dans une carafe, un bouquet de fleurs
d'oranger, noué par des rubans de satin blanc. C'était un bouquet
de mariée, le bouquet de l'autre! Elle le regarda. Charles s'en
aperçut, il le prit et l'alla porter au grenier, tandis qu'assise
dans un fauteuil (on disposait ses affaires autour d'elle), Emma
songeait à son bouquet de mariage, qui était emballé dans un
carton, et se demandait, en rêvant, ce qu'on en ferait; si par
hasard elle venait à mourir.

Elle s'occupa, les premiers jours, à méditer des changements dans
sa maison. Elle retira les globes des flambeaux, fit coller des
papiers neufs, repeindre l'escalier et faire des bancs dans le
jardin, tout autour du cadran solaire; elle demanda même comment
s'y prendre pour avoir un bassin à jet d'eau avec des poissons.
Enfin son mari, sachant qu'elle aimait à se promener en voiture,
trouva un boc d'occasion, qui, ayant une fois des lanternes neuves
et des gardes-crotte en cuir piqué, ressembla presque à un
tilbury.

Il était donc heureux et sans souci de rien au monde. Un repas en
tête-à-tête, une promenade le soir sur la grande route, un geste
de sa main sur ses bandeaux, la vue de son chapeau de paille
accroché à l'espagnolette d'une fenêtre, et bien d'autres choses
encore où Charles n'avait jamais soupçonné de plaisir, composaient
maintenant la continuité de son bonheur. Au lit, le matin, et côte
à côté sur l'oreiller, il regardait la lumière du soleil passer
parmi le duvet de ses joues blondes, que couvraient à demi les
pattes escalopées de son bonnet. Vus de si près, ses yeux lui
paraissaient agrandis, surtout quand elle ouvrait plusieurs fois
de suite ses paupières en s'éveillant; noirs à l'ombre et bleu
foncé au grand jour, ils avaient comme des couches de couleurs
successives, et qui plus épaisses dans le fond, allaient en
s'éclaircissant vers la surface de l'émail. Son oeil, à lui, se
perdait dans ces profondeurs, et il s'y voyait en petit jusqu'aux
épaules, avec le foulard qui le coiffait et le haut de sa chemise
entrouvert. Il se levait. Elle se mettait à la fenêtre pour le
voir partir; et elle restait accoudée sur le bord, entre deux pots
de géraniums, vêtue de son peignoir, qui était lâche autour
d'elle. Charles, dans la rue, bouclait ses éperons sur la borne;
et elle continuait à lui parler d'en haut, tout en arrachant avec
sa bouche quelque bribe de fleur ou de verdure qu'elle soufflait
vers lui, et qui voltigeant, se soutenant, faisant dans l'air des
demi-cercles comme un oiseau, allait, avant de tomber, s'accrocher
aux crins mal peignés de la vieille jument blanche, immobile à la
porte. Charles, à cheval, lui envoyait un baiser; elle répondait
par un signe, elle refermait la fenêtre, il partait. Et alors, sur
la grande route qui étendait sans en finir son long ruban de
poussière, par les chemins creux où les arbres se courbaient en
berceaux, dans les sentiers dont les blés lui montaient jusqu'aux
genoux, avec le soleil sur ses épaules et l'air du matin à ses
narines, le coeur plein des félicités de la nuit, l'esprit
tranquille, la chair contente, il s'en allait ruminant son
bonheur, comme ceux qui mâchent encore, après dîner, le goût des
truffes qu'ils digèrent.

Jusqu'à présent, qu'avait-il eu de bon dans l'existence? Était-ce
son temps de collège, où il restait enfermé entre ces hauts murs,
seul au milieu de ses camarades plus riches ou plus forts que lui
dans leurs classes, qu'il faisait rire par son accent, qui se
moquaient de ses habits, et dont les mères venaient au parloir
avec des pâtisseries dans leur manchon? Était-ce plus tard,
lorsqu'il étudiait la médecine et n'avait jamais la bourse assez
ronde pour payer la contredanse à quelque petite ouvrière qui fût
devenue sa maîtresse? Ensuite il avait vécu pendant quatorze mois
avec la veuve, dont les pieds, dans le lit, étaient froids comme
des glaçons. Mais, à présent, il possédait pour la vie cette jolie
femme qu'il adorait. L'univers, pour lui, n'excédait pas le tour
soyeux de son jupon; et il se reprochait de ne pas l'aimer, il
avait envie de la revoir; il s'en revenait vite, montait
l'escalier; le coeur battant. Emma, dans sa chambre, était à faire
sa toilette; il arrivait à pas muets, il la baisait dans le dos,
elle poussait un cri.

Il ne pouvait se retenir de toucher continuellement à son peigne,
à ses bagues, à son fichu; quelquefois, il lui donnait sur les
joues de gros baisers à pleine bouche, ou c'étaient de petits
baisers à la file tout le long de son bras nu, depuis le bout des
doigts jusqu'à l'épaule; et elle le repoussait, à demi souriante
et ennuyée, comme on fait à un enfant qui se pend après vous.

Avant qu'elle se mariât, elle avait cru avoir de l'amour; mais le
bonheur qui aurait dû résulter de cet amour n'étant pas venu, il
fallait qu'elle se fût trompée, songeait-elle. Et Emma cherchait à
savoir ce que l'on entendait au juste dans la vie par les mots de
félicité, de passion et d'ivresse, qui lui avaient paru si beaux
dans les livres.


VI

Elle avait lu Paul et Virginie et elle avait rêvé la maisonnette
de bambous, le nègre Domingo, le chien Fidèle, mais surtout
l'amitié douce de quelque bon petit frère, qui va chercher pour
vous des fruits rouges dans des grands arbres plus hauts que des
clochers, ou qui court pieds nus sur le sable, vous apportant un
nid d'oiseau.

Lorsqu'elle eut treize ans, son père l'amena lui-même à la ville,
pour la mettre au couvent. Ils descendirent dans une auberge du
quartier Saint-Gervais, où ils eurent à leur souper des assiettes
peintes qui représentaient l'histoire de mademoiselle de la
Vallière. Les explications légendaires, coupées çà et là par
l'égratignure des couteaux, glorifiaient toutes la religion, les
délicatesses du coeur et les pompes de la Cour.

Loin de s'ennuyer au couvent les premiers temps, elle se plut dans
la société des bonnes soeurs, qui, pour l'amuser, la conduisaient
dans la chapelle, où l'on pénétrait du réfectoire par un long
corridor. Elle jouait fort peu durant les récréations, comprenait
bien le catéchisme, et c'est elle qui répondait toujours à M. le
vicaire dans les questions difficiles. Vivant donc sans jamais
sortir de la tiède atmosphère des classes et parmi ces femmes au
teint blanc portant des chapelets à croix de cuivre, elle
s'assoupit doucement à la langueur mystique qui s'exhale des
parfums de l'autel, de la fraîcheur des bénitiers et du
rayonnement des cierges. Au lieu de suivre la messe, elle
regardait dans son livre les vignettes pieuses bordées d'azur, et
elle aimait la brebis malade, le Sacré-Coeur percé de flèches
aiguës, ou le pauvre Jésus, qui tombe en marchant sur sa croix.
Elle essaya, par mortification, de rester tout un jour sans
manger. Elle cherchait dans sa tête quelque voeu à accomplir.

Quand elle allait à confesse, elle inventait de petits péchés afin
de rester là plus longtemps, à genoux dans l'ombre, les mains
jointes, le visage à la grille sous le chuchotement du prêtre. Les
comparaisons de fiancé, d'époux, d'amant céleste et de mariage
éternel qui reviennent dans les sermons lui soulevaient au fond de
l'âme des douceurs inattendues.

Le soir, avant la prière, on faisait dans l'étude une lecture
religieuse. C'était, pendant la semaine, quelque résumé d'Histoire
sainte ou les Conférences de l'abbé Frayssinous, et, le dimanche,
des passages du Génie du christianisme, par récréation. Comme elle
écouta, les premières fois, la lamentation sonore des mélancolies
romantiques se répétant à tous les échos de la terre et de
l'éternité! Si son enfance se fût écoulée dans l'arrière-boutique
d'un quartier marchand, elle se serait peut-être ouverte alors aux
envahissements lyriques de la nature, qui, d'ordinaire, ne nous
arrivent que par la traduction des écrivains. Mais elle
connaissait trop la campagne; elle savait le bêlement des
troupeaux, les laitages, les charrues. Habituée aux aspects
calmes, elle se tournait, au contraire, vers les accidentés. Elle
n'aimait la mer qu'à cause de ses tempêtes, et la verdure
seulement lorsqu'elle était clairsemée parmi les ruines. Il
fallait qu'elle pût retirer des choses une sorte de profit
personnel; et elle rejetait comme inutile tout ce qui ne
contribuait pas à la consommation immédiate de son coeur, -- étant
de tempérament plus sentimentale qu'artiste, cherchant des
émotions et non des paysages.

Il y avait au couvent une vieille fille qui venait tous les mois,
pendant huit jours, travailler à la lingerie. Protégée par
l'archevêché comme appartenant à une ancienne famille de
gentilshommes ruinés sous la Révolution, elle mangeait au
réfectoire à la table des bonnes soeurs, et faisait avec elles,
après le repas, un petit bout de causette avant de remonter à son
ouvrage. Souvent les pensionnaires s'échappaient de l'étude pour
l'aller voir. Elle savait par coeur des chansons galantes du
siècle passé, qu'elle chantait à demi-voix, tout en poussant son
aiguille. Elle contait des histoires, vous apprenait des
nouvelles, faisait en ville vos commissions, et prêtait aux
grandes, en cachette, quelque roman qu'elle avait toujours dans
les poches de son tablier, et dont la bonne demoiselle elle-même
avalait de longs chapitres, dans les intervalles de sa besogne. Ce
n'étaient qu'amours, amants, amantes, dames persécutées
s'évanouissant dans des pavillons solitaires, postillons qu'on tue
à tous les relais, chevaux qu'on crève à toutes les pages, forêts
sombres, troubles du coeur, serments, sanglots, larmes et baisers,
nacelles au clair de lune, rossignols dans les bosquets, messieurs
braves comme des lions, doux comme des agneaux, vertueux comme on
ne l'est pas, toujours bien mis, et qui pleurent comme des urnes.
Pendant six mois, à quinze ans, Emma se graissa donc les mains à
cette poussière des vieux cabinets de lecture. Avec Walter Scott,
plus tard, elle s'éprit de choses historiques, rêva bahuts, salle
des gardes et ménestrels. Elle aurait voulu vivre dans quelque
vieux manoir, comme ces châtelaines au long corsage, qui, sous le
trèfle des ogives, passaient leurs jours, le coude sur la pierre
et le menton dans la main, à regarder venir du fond de la campagne
un cavalier à plume blanche qui galope sur un cheval noir. Elle
eut dans ce temps-là le culte de Marie Stuart, et des vénérations
enthousiastes à l'endroit des femmes illustres ou infortunées.
Jeanne d'Arc, Héloïse, Agnès Sorel, la belle Ferronnière et
Clémence Isaure, pour elle, se détachaient comme des comètes sur
l'immensité ténébreuse de l'histoire, où saillissaient encore çà
et là, mais plus perdus dans l'ombre et sans aucun rapport entre
eux, saint Louis avec son chêne, Bayard mourant, quelques
férocités de Louis XI, un peu de Saint-Barthélemy, le panache du
Béarnais, et toujours le souvenir des assiettes peintes où Louis
XIV était vanté.

À la classe de musique, dans les romances qu'elle chantait, il
n'était question que de petits anges aux ailes d'or, de madones,
de lagunes, de gondoliers, pacifiques compositions qui lui
laissaient entrevoir, à travers la niaiserie du style et les
imprudences de la note, l'attirante fantasmagorie des réalités
sentimentales. Quelques-unes de ses camarades apportaient au
couvent les keepsakes qu'elles avaient reçus en étrennes. Il les
fallait cacher, c'était une affaire; on les lisait au dortoir.
Maniant délicatement leurs belles reliures de satin, Emma fixait
ses regards éblouis sur le nom des auteurs inconnus qui avaient
signé, le plus souvent, comtes ou vicomtes, au bas de leurs
pièces.

Elle frémissait, en soulevant de son haleine le papier de soie des
gravures, qui se levait à demi plié et retombait doucement contre
la page. C'était, derrière la balustrade d'un balcon, un jeune
homme en court manteau qui serrait dans ses bras une jeune fille
en robe blanche, portant une aumônière à sa ceinture; ou bien les
portraits anonymes des ladies anglaises à boucles blondes, qui,
sous leur chapeau de paille rond, vous regardent avec leurs grands
yeux clairs. On en voyait d'étalées dans des voitures, glissant au
milieu des parcs, où un lévrier sautait devant l'attelage que
conduisaient au trot deux petits postillons en culotte blanche.
D'autres, rêvant sur des sofas près d'un billet décacheté,
contemplaient la lune, par la fenêtre entrouverte, à demi drapée
d'un rideau noir. Les naïves, une larme sur la joue, becquetaient
une tourterelle à travers les barreaux d'une cage gothique, ou,
souriant la tête sur l'épaule, effeuillaient une marguerite de
leurs doigts pointus, retroussés comme des souliers à la poulaine.
Et vous y étiez aussi, sultans à longues pipes, pâmés sous des
tonnelles, aux bras des bayadères, djiaours, sabres turcs, bonnets
grecs, et vous surtout, paysages blafards des contrées
dithyrambiques, qui souvent nous montrez à la fois des palmiers,
des sapins, des tigres à droite, un lion à gauche, des minarets
tartares à l'horizon, au premier plan des ruines romaines, puis
des chameaux accroupis; -- le tout encadré d'une forêt vierge bien
nettoyée, et avec un grand rayon de soleil perpendiculaire
tremblotant dans l'eau, où se détachent en écorchures blanches,
sur un fond d'acier gris, de loin en loin, des cygnes qui nagent.

Et l'abat-jour du quinquet, accroché dans la muraille au-dessus de
la tête d'Emma, éclairait tous ces tableaux du monde, qui
passaient devant elle les uns après les autres, dans le silence du
dortoir et au bruit lointain de quelque fiacre attardé qui roulait
encore sur les boulevards.

Quand sa mère mourut, elle pleura beaucoup les premiers jours.
Elle se fit faire un tableau funèbre avec les cheveux de la
défunte, et, dans une lettre qu'elle envoyait aux Bertaux, toute
pleine de réflexions tristes sur la vie, elle demandait qu'on
l'ensevelît plus tard dans le même tombeau. Le bonhomme la crut
malade et vint la voir. Emma fut intérieurement satisfaite de se
sentir arrivée du premier coup à ce rare idéal des existences
pâles, où ne parviennent jamais les coeurs médiocres. Elle se
laissa donc glisser dans les méandres lamartiniens, écouta les
harpes sur les lacs, tous les chants de cygnes mourants, toutes
les chutes de feuilles, les vierges pures qui montent au ciel, et
la voix de l'Éternel discourant dans les vallons. Elle s'en
ennuya, n'en voulut point convenir, continua par habitude, ensuite
par vanité, et fut enfin surprise de se sentir apaisée, et sans
plus de tristesse au coeur que de rides sur son front.

Les bonnes religieuses, qui avaient si bien présumé de sa
vocation, s'aperçurent avec de grands étonnements que mademoiselle
Rouault semblait échapper à leur soin. Elles lui avaient, en
effet, tant prodigué les offices, les retraites, les neuvaines et
les sermons, si bien prêché le respect que l'on doit aux saints et
aux martyrs, et donné tant de bons conseils pour la modestie du
corps et le salut de son âme, qu'elle fit comme les chevaux que
l'on tire par la bride: elle s'arrêta court et le mors lui sortit
des dents. Cet esprit, positif au milieu de ses enthousiasmes, qui
avait aimé l'église pour ses fleurs, la musique pour les paroles
des romances, et la littérature pour ses excitations
passionnelles, s'insurgeait devant les mystères de la foi, de même
qu'elle s'irritait davantage contre la discipline, qui était
quelque chose d'antipathique à sa constitution. Quand son père la
retira de pension, on ne fut point fâché de la voir partir. La
supérieure trouvait même qu'elle était devenue, dans les derniers
temps, peu révérencieuse envers la communauté.

Emma, rentrée chez elle, se plut d'abord au commandement des
domestiques, prit ensuite la campagne en dégoût et regretta son
couvent. Quand Charles vint aux Bertaux pour la première fois,
elle se considérait comme fort désillusionnée, n'ayant plus rien à
apprendre, ne devant plus rien sentir.

Mais l'anxiété d'un état nouveau, ou peut-être l'irritation causée
par la présence de cet homme, avait suffi à lui faire croire
qu'elle possédait enfin cette passion merveilleuse qui jusqu'alors
s'était tenue comme un grand oiseau au plumage rose planant dans
la splendeur des ciels poétiques; -- et elle ne pouvait s'imaginer
à présent que ce calme où elle vivait fût le bonheur qu'elle avait
rêvé.


VII

Elle songeait quelquefois que c'étaient là pourtant les plus beaux
jours de sa vie, la lune de miel, comme on disait. Pour en goûter
la douceur, il eût fallu, sans doute, s'en aller vers ces pays à
noms sonores où les lendemains de mariage ont de plus suaves
paresses! Dans des chaises de poste, sous des stores de soie
bleue, on monte au pas des routes escarpées, écoutant la chanson
du postillon, qui se répète dans la montagne avec les clochettes
des chèvres et le bruit sourd de la cascade. Quand le soleil se
couche, on respire au bord des golfes le parfum des citronniers;
puis, le soir, sur la terrasse des villas, seuls et les doigts
confondus, on regarde les étoiles en faisant des projets. Il lui
semblait que certains lieux sur la terre devaient produire du
bonheur, comme une plante particulière au sol et qui pousse mal
tout autre part. Que ne pouvait-elle s'accouder sur le balcon des
chalets suisses ou enfermer sa tristesse dans un cottage écossais,
avec un mari vêtu d'un habit de velours noir à longues basques, et
qui porte des bottes molles, un chapeau pointu et des manchettes!

Peut-être aurait-elle souhaité faire à quelqu'un la confidence de
toutes ces choses. Mais comment dire un insaisissable malaise, qui
change d'aspect comme les nuées, qui tourbillonne comme le vent?
Les mots lui manquaient donc, l'occasion, la hardiesse.

Si Charles l'avait voulu cependant, s'il s'en fût douté, si son
regard, une seule fois, fût venu à la rencontre de sa pensée, il
lui semblait qu'une abondance subite se serait détachée de son
coeur, comme tombe la récolte d'un espalier quand on y porte la
main. Mais, à mesure que se serrait davantage l'intimité de leur
vie; un détachement intérieur se faisait qui la déliait de lui.

La conversation de Charles était plate comme un trottoir de rue,
et les idées de tout le monde y défilaient dans leur costume
ordinaire, sans exciter d'émotion, de rire ou de rêverie. Il
n'avait jamais été curieux, disait-il, pendant qu'il habitait
Rouen, d'aller voir au théâtre les acteurs de Paris. Il ne savait
ni nager, ni faire des armes, ni tirer le pistolet, et il ne put,
un jour, lui expliquer un terme d'équitation qu'elle avait
rencontré dans un roman.

Un homme, au contraire, ne devait-il pas, tout connaître, exceller
en des activités multiples, vous initier aux énergies de la
passion, aux raffinements de la vie, à tous les mystères? Mais il
n'enseignait rien, celui-là, ne savait rien, ne souhaitait rien.
Il la croyait heureuse; et elle lui en voulait de ce calme si bien
assis, de cette pesanteur sereine, du bonheur même qu'elle lui
donnait.

Elle dessinait quelquefois; et c'était pour Charles un grand
amusement que de rester là, tout debout à la regarder penchée sur
son carton, clignant des yeux afin de mieux voir son ouvrage, ou
arrondissant, sur son pouce, des boulettes de mie de pain. Quant
au piano, plus les doigts y couraient vite, plus il
s'émerveillait. Elle frappait sur les touches avec aplomb, et
parcourait du haut en bas tout le clavier sans s'interrompre.
Ainsi secoué par elle, le vieil instrument, dont les cordes
frisaient, s'entendait jusqu'au bout du village si la fenêtre
était ouverte, et souvent le clerc de l'huissier qui passait sur
la grande route, nu-tête et en chaussons, s'arrêtait à l'écouter,
sa feuille de papier à la main.

Emma, d'autre part; savait conduire sa maison. Elle envoyait aux
malades le compte des visites, dans des lettres bien tournées, qui
ne sentaient pas la facture. Quand ils avaient, le dimanche,
quelque voisin à dîner, elle trouvait moyen d'offrir un plat
coquet, s'entendait à poser sur des feuilles de vigne les
pyramides de reines-claudes, servait renversés les pots de
confitures dans une assiette, et même elle parlait d'acheter des
rince-bouche pour le dessert. Il rejaillissait de tout cela
beaucoup de considération sur Bovary.

Charles finissait par s'estimer davantage de ce qu'il possédait
une pareille femme. Il montrait avec orgueil, dans la salle, deux
petits croquis d'elle, à la mine de plomb, qu'il avait fait
encadrer de cadres très larges et suspendus contre le papier de la
muraille à de longs cordons verts. Au sortir de la messe, on le
voyait sur sa porte avec de belles pantoufles en tapisserie.

Il rentrait tard, à dix heures, minuit quelquefois. Alors il
demandait à manger, et, comme la bonne était couchée, c'était Emma
qui le servait. Il retirait sa redingote pour dîner plus à son
aise. Il disait les uns après les autres tous les gens qu'il avait
rencontrés, les villages où il avait été, les ordonnances qu'il
avait écrites, et satisfait de lui-même, il mangeait le reste du
miroton, épluchait son fromage, croquait une pomme, vidait sa
carafe, puis s'allait mettre au lit, se couchait sur le dos et
ronflait.

Comme il avait eu longtemps l'habitude du bonnet de coton, son
foulard ne lui tenait pas aux oreilles; aussi ses cheveux, le
matin, étaient rabattus pêle-mêle sur sa figure et blanchis par le
duvet de son oreiller, dont les cordons se dénouaient pendant la
nuit. Il portait toujours de fortes bottes, qui avaient au cou-de-
pied deux plis épais obliquant vers les chevilles, tandis que le
reste de l'empeigne se continuait en ligne droite, tendu comme par
un pied de bois. Il disait que c'était bien assez bon pour la
campagne.

Sa mère l'approuvait en cette économie; car elle le venait voir
comme autrefois, lorsqu'il y avait eu chez elle quelque bourrasque
un peu violente; et cependant madame Bovary mère semblait prévenue
contre sa bru. Elle lui trouvait un genre trop relevé pour leur
position de fortune; le bois, le sucre et la chandelle filaient
comme dans une grande maison, et la quantité de braise qui se
brûlait à la cuisine aurait suffi pour vingt-cinq plats! Elle
rangeait son linge dans les armoires et lui apprenait à surveiller
le boucher quand il apportait la viande. Emma recevait ces leçons;
madame Bovary les prodiguait; et les mots de ma fille et de ma
mère s'échangeaient tout le long du jour, accompagnés d'un petit
frémissement des lèvres, chacune lançant des paroles douces d'une
voix tremblante de colère.

Du temps de madame Dubuc, la vieille femme se sentait encore la
préférée; mais, à présent, l'amour de Charles pour Emma lui
semblait une désertion de sa tendresse, un envahissement sur ce
qui lui appartenait; et elle observait le bonheur de son fils avec
un silence triste, comme quelqu'un de ruiné qui regarde, à travers
les carreaux, des gens attablés dans son ancienne maison. Elle lui
rappelait, en manière de souvenirs, ses peines et ses sacrifices,
et, les comparant aux négligences d'Emma, concluait qu'il n'était
point raisonnable de l'adorer d'une façon si exclusive.

Charles ne savait que répondre; il respectait sa mère, et il
aimait infiniment sa femme; il considérait le jugement de l'une
comme infaillible, et cependant il trouvait l'autre irréprochable.
Quand madame Bovary était partie, il essayait de hasarder
timidement, et dans les mêmes termes, une ou deux des plus
anodines observations qu'il avait entendu faire à sa maman; Emma,
lui prouvant d'un mot qu'il se trompait, le renvoyait à ses
malades.

Cependant, d'après des théories qu'elle croyait bonnes, elle
voulut se donner de l'amour. Au clair de lune, dans le jardin,
elle récitait tout ce qu'elle savait par coeur de rimes
passionnées et lui chantait en soupirant des adagios
mélancoliques; mais elle se trouvait ensuite aussi calme
qu'auparavant, et Charles n'en paraissait ni plus amoureux ni plus
remué.

Quand elle eut ainsi un peu battu le briquet sur son coeur sans en
faire jaillir une étincelle, incapable, du reste, de comprendre ce
qu'elle n'éprouvait pas, comme de croire à tout ce qui ne se
manifestait point par des formes convenues, elle se persuada sans
peine que la passion de Charles n'avait plus rien d'exorbitant.
Ses expansions étaient devenues régulières; il l'embrassait à de
certaines heures. C'était une habitude parmi les autres, et comme
un dessert prévu d'avance, après la monotonie du dîner.

Un garde-chasse, guéri par Monsieur, d'une fluxion de poitrine,
avait donné à Madame une petite levrette d'Italie; elle la prenait
pour se promener, car elle sortait quelquefois, afin d'être seule
un instant et de n'avoir plus sous les yeux l'éternel jardin avec
la route poudreuse.

Elle allait jusqu'à la hêtraie de Banneville, près du pavillon
abandonné qui fait l'angle du mur, du côté des champs. Il y a dans
le saut-de-loup, parmi les herbes, de longs roseaux à feuilles
coupantes.

Elle commençait par regarder tout alentour, pour voir si rien
n'avait changé depuis la dernière fois qu'elle était venue. Elle
retrouvait aux mêmes places les digitales et les ravenelles, les
bouquets d'orties entourant les gros cailloux, et les plaques de
lichen le long des trois fenêtres, dont les volets toujours clos
s'égrenaient de pourriture, sur leurs barres de fer rouillées. Sa
pensée, sans but d'abord, vagabondait au hasard, comme sa
levrette, qui faisait des cercles dans la campagne, jappait après
les papillons jaunes, donnait la chasse aux musaraignes; ou
mordillait les coquelicots sur le bord d'une pièce de blé. Puis
ses idées peu à peu se fixaient, et, assise sur le gazon, qu'elle
fouillait à petits coups avec le bout de son ombrelle, Emma se
répétait:

-- Pourquoi, mon Dieu! me suis-je mariée?

Elle se demandait s'il n'y aurait pas eu moyen, par d'autres
combinaisons du hasard, de rencontrer un autre homme; et elle
cherchait à imaginer quels eussent été ces événements non
survenus, cette vie différente, ce mari qu'elle ne connaissait
pas. Tous, en effet, ne ressemblaient pas à celui-là. Il aurait pu
être beau, spirituel, distingué, attirant, tels qu'ils étaient
sans doute, ceux qu'avaient épousés ses anciennes camarades du
couvent. Que faisaient-elles maintenant? À la ville, avec le bruit
des ru