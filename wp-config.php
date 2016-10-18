<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'wordpress');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '-|wX;EmyndZh:,4.M_ND./Pk FdBV;RK*s4=V5!d}4uXG24%ERMg/bbg+w=m9UKf');
define('SECURE_AUTH_KEY',  '1Mk1#4HYd%Lg>Je|,SqaY;>Y_y/U#,ll<9H<Q2fCe^T%xzL^>oro?RDS-&d_kr.D');
define('LOGGED_IN_KEY',    ':jM0Q;B[TIZxDFwVUx$ST`#QEn/GZA$?.GEgC=ZoB;j9O<{aQX,$,D{4C rj1P-$');
define('NONCE_KEY',        'A$5~(2.*5@NJ&trT]Q*5C#|.3?|b:22-OZqmWmI>6Y</2fX,M.56f$2V&nYELoZ/');
define('AUTH_SALT',        '}kx`cgBZ;SA$IhL-w#-vwDAb#0>=H2m,^<!0SR+VWft@^H7yl6qJl|i;E;y,5w<k');
define('SECURE_AUTH_SALT', 'oC>A0(}&~i3s$;wR4[/dpRnw:}f]A&n)-?E1C3Y/7##/@-GXiA7~j4O%rKth8z45');
define('LOGGED_IN_SALT',   'h8_0{db$zq!RC,ENguXqb0}COd6je>b|Kn%(>61qg |%xm(gxf*<eC_sf?(6J^,e');
define('NONCE_SALT',       'i ]Yww!3ZkA4YZbQ]MJ^]j5%li wOXJnD_)g>`5vus_*/+:4N!3D@m`&<qcaHU5?');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d'information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');