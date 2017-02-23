CREATE TABLE `{PREFIX}block` (
  `id` int(11) NOT NULL,
  `prefix` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL,
  `configurable` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `block_default` int(11) NOT NULL DEFAULT '0',
  `version` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `{PREFIX}block_lang` (
  `id_block` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `name` varchar(140) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `{PREFIX}configuration` (
  `id_configuration` int(11) NOT NULL,
  `name` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `{PREFIX}content` (
  `id` int(11) NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `id_author` int(11) NOT NULL,
  `id_editor` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `in_menu` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `active` int(1) NOT NULL,
  `in_trash` int(11) NOT NULL,
  `theme` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `{PREFIX}content_lang` (
  `id_content` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `meta_title` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci NOT NULL,
  `link_rewrite` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `menu_text` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `{PREFIX}language` (
  `id_lang` int(11) NOT NULL,
  `iso_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `{PREFIX}migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `{PREFIX}user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang` int(2) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `{PREFIX}block`
  ADD PRIMARY KEY (`id`);
  
 ALTER TABLE `{PREFIX}block`
   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
	
ALTER TABLE `{PREFIX}block_lang`
  ADD UNIQUE KEY `id_block` (`id_block`,`id_lang`);

ALTER TABLE `{PREFIX}configuration`
  ADD PRIMARY KEY (`id_configuration`);

 ALTER TABLE `{PREFIX}configuration`
  MODIFY `id_configuration` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{PREFIX}content`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{PREFIX}content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT; 
  
ALTER TABLE `{PREFIX}content_lang`
  ADD UNIQUE KEY `id_content` (`id_content`,`id_lang`);

ALTER TABLE `{PREFIX}language`
  ADD PRIMARY KEY (`id_lang`);
  
ALTER TABLE `{PREFIX}language`
 MODIFY `id_lang` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{PREFIX}migration`
  ADD PRIMARY KEY (`version`);

ALTER TABLE `{PREFIX}user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);
  
ALTER TABLE `{PREFIX}user`  
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
 
INSERT INTO `{PREFIX}block` (`id`, `prefix`, `active`, `configurable`, `position`, `version`, `block_default`, `date_upd`) VALUES
(1, 'call2action', 1, 1, 0, '0.1',1, '2016-04-06 09:21:36'),
(2, 'dailymotion', 1, 1, 0, '0.1',1, '2016-04-06 09:21:36'),
(3, 'gallery', 1, 1, 0, '0.1',1, '2016-04-06 09:21:36'),
(4, 'icon', 1, 1, 0, '0.1',1, '2016-04-06 09:21:36'),
(5, 'image', 1, 1, 0, '0.1',1, '2016-04-06 09:21:36'),
(6, 'list', 1, 1, 2, '0.1',1, '2016-04-06 09:21:36'),
(7, 'slider', 1, 1, 0, '0.1',1, '2016-04-06 09:21:36'),
(8, 'text', 1, 1, 1, '0.1',1, '2016-04-06 09:21:36'),
(9, 'vimeo', 1, 1, 0, '0.1',1, '2016-04-06 09:21:36'),
(10, 'youtube', 1, 1, 0, '0.1',1, '2016-04-06 09:21:36'),
(11, 'contactform', 1, 0, 0, '0.1', 1,'2016-04-06 09:23:05'),
(12, 'line', 1, 0, 0, '0.1',1, '2016-04-06 09:23:09'),
(13, 'googlemap', 1, 1, 0, '0.1',1, '2016-04-06 09:23:09'),
(14, 'customhtml', 1, 1, 0, '0.1',1, '2016-04-06 09:23:09'),
(15, 'contactdata', 1, 1, 0, '0.1',1, '2016-04-06 09:23:09');


INSERT INTO `{PREFIX}block_lang` (`id_block`, `id_lang`, `name`) VALUES
(1, 1, 'Call to action'),
(2, 1, 'Dailymotion'),
(3, 1, 'Galería'),
(4, 1, 'Icono'),
(5, 1, 'Imagen'),
(6, 1, 'Lista'),
(7, 1, 'Slider'),
(8, 1, 'Texto'),
(9, 1, 'Vimeo'),
(10, 1, 'Youtube'),
(11, 1, 'Formulario de contacto'),
(12, 1, 'Linea'),
(13, 1, 'Mapa Google'),
(14, 1, 'Custom Html'),
(15, 1, 'Datos de contacto'),
(1, 2, 'Call to action'),
(2, 2, 'Dailymotion'),
(3, 2, 'Gallery'),
(4, 2, 'Icon'),
(5, 2, 'Image'),
(6, 2, 'List'),
(7, 2, 'Slider'),
(8, 2, 'Text'),
(9, 2, 'Vimeo'),
(10, 2, 'Youtube'),
(11, 2, 'Contact form'),
(12, 2, 'Line'),
(13, 2, 'Google Map'),
(14, 2, 'Custom Html'),
(15,2, 'Contact data'),
(1, 3, 'Aufruf zum Handeln'),
(2, 3, 'Dailymotion'),
(3, 3, 'Galerie'),
(4, 3, 'Symbol'),
(5, 3, 'Image'),
(6, 3, 'Liste'),
(7, 3, 'Schieberegler'),
(8, 3, 'Text'),
(9, 3, 'Vimeo'),
(10, 3, 'Youtube'),
(11, 3, 'Kontakt Formular'),
(12, 3, 'Linie'),
(13, 3, 'Google Karte'),
(14, 3, 'Brauch Html'),
(15,3, 'Kontaktdaten'),
(1, 4, 'Appel à l&#39action'),
(2, 4, 'Dailymotion'),
(3, 4, 'Galerie'),
(4, 4, 'Icône'),
(5, 4, 'Image'),
(6, 4, 'Liste'),
(7, 4, 'Curseur'),
(8, 4, 'Texte'),
(9, 4, 'Vimeo'),
(10, 4, 'Youtube'),
(11, 4, 'Formulaire de contact'),
(12, 4, 'Ligne'),
(13, 4, 'Google Map'),
(14, 4, 'Html personnalisé'),
(15,4, 'Données de contact');

INSERT INTO `{PREFIX}configuration` (`id_configuration`, `name`, `value`) VALUES
(1, '_DEFAULT_THEME_', 'starter'),
(2, '_AUTOSAVE_', '1'),
(3, '_WEB_NAME_', 'Menapro'),
(4, '_FACEBOOK_', ''),
(5, '_TWITTER_', ''),
(6, '_INSTAGRAM_', ''),
(7, '_PINTEREST_', ''),
(8, '_YOUTUBE_', ''),
(9, '_EMAIL_', ''),
(10, '_ADDRESS_', ''),
(11, '_PHONE_', ''),
(12, '_MOBILE_PHONE_', ''),
(13, '_OPENING_HOURS_', ''),
(14, '_DEFAULT_LANG_', ''),
(15, '_UA_ANALYTICS_', ''),
(16, '_GMAP_API_KEY_', ''),
(17, '_COMPRESS_HTML_', '1'),
(18, '_BOOTSTRAP4_', '0'),
(19, '_COOKIES_NOTIFICATION_', '1'),
(20, '_ENABLE_CACHE_', '0');

INSERT INTO `{PREFIX}language` (`id_lang`, `iso_code`, `country_code`, `name`, `img`,`active`) VALUES
(1, 'es', 'ES', 'Castellano', 'es.png',0),
(2, 'en', 'US', 'English', 'en.png',0),
(3, 'de', 'DE', 'Deutsch', 'de.png',0),
(4, 'fr', 'FR', 'Français', 'fr.png',0);
