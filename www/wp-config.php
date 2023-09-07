<?php

/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', '');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', '');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', '');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ik9^|Cao&kIx,kH(h(uyq}<b*%8,-!p2xiBpVOv54Cactq2>H=^A ;/qQ~5q=G,P');
define('SECURE_AUTH_KEY',  '$Y6r`.$!YoMtvt#0Z-f7K0%!,X5mJbJpAwssR=PCJ[yNmQT)mpxdM VYWsy]=6kQ');
define('LOGGED_IN_KEY',    '|Yf}{bJ[B$9c??&Gd>{sOrOYB#rO4-`m$4M6(}ba*^=k1K_=aj6lis37c-WD4+#S');
define('NONCE_KEY',        '^:_Yn}c+0UkqxHY;Cy~S~qk+sxj7gz]qt1u_`b-zI~r/&:Vf8k?&#,}_H h:<z{m');
define('AUTH_SALT',        'Ir`m~?h=)1M]fN}&X.4V~hE.Cq)9UMqVNAdr#o3HQql+_-]*CmRb{(@ML(=PfdV&');
define('SECURE_AUTH_SALT', '6<;n<jlNv3o|F*Cg=V+T9Z.>]A^z9NkT0g0f0byFao(JK!#Z]{7YhQa&[]R$R(V<');
define('LOGGED_IN_SALT',   '706/?n0MSMTVv&MVc>~Y*Zc.z?S[(lcgYA}D0f=XYzlL&^kdI^JV-+pG%v$;%0Ak');
define('NONCE_SALT',       'Go-+Jt)6Mu2ond-U3DpaZ[}D>9T^,k:5S}ELeiJ$NUdi/2#.-tw(BRjc*$AHLNwF');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if (!defined('ABSPATH'))
  define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
