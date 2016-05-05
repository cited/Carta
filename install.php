<?php

	$CurrentVersion = 6;
	$db = get_db();


        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->Carta` (
               `id` int(4) NOT NULL AUTO_INCREMENT,
                  `name` varchar(200) NOT NULL,
                  `width` varchar(100) NOT NULL,
                  `height` varchar(100) NOT NULL,
                  `zoom` tinyint(2) NOT NULL,
                  `baselayer` int(11) NOT NULL,
                  `layergroup` int(11) NOT NULL,
                  `pointers` longtext NOT NULL,
				  `geo_image_olverlays` longtext NOT NULL,
				  `show_measure` BOOLEAN NOT NULL DEFAULT TRUE,
				  `show_minimap` BOOLEAN NOT NULL DEFAULT TRUE,
				  `show_sidebar` BOOLEAN NOT NULL DEFAULT TRUE,
				  `show_legend` BOOLEAN NOT NULL DEFAULT FALSE,
				  `show_cluster` BOOLEAN NOT NULL DEFAULT FALSE,
				  `legend_content` longtext NOT NULL,
                  `latitude` varchar(100) NOT NULL DEFAULT '0',
                  `longitude` varchar(100) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

        $db->query($sql);


        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->CartaGroup` (
               `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(200) NOT NULL,
              `layer_id` varchar(100) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

        $db->query($sql);

          $sql = "
            CREATE TABLE IF NOT EXISTS `$db->CartaLayer` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(300) NOT NULL,
              `url` varchar(300) NOT NULL,
              `key` varchar(300) NOT NULL,
              `accesstoken` varchar(300) NOT NULL,
              `attribution` varchar(300) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

        $db->query($sql);


          $sql = "
            CREATE TABLE IF NOT EXISTS `$db->CartaItem` (
                 `id` int(11) NOT NULL AUTO_INCREMENT,
                  `item_id` int(11) NOT NULL,
                  `content` longtext NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

        $db->query($sql);

$sql = "INSERT INTO `$db->CartaLayer` (`id`, `name`, `url`, `key`, `attribution`) VALUES

(1, 'MapFig Bluewaters', 'https://{s}.tile.thunderforest.com/mapfig-bluewaters/{z}/{x}/{y}.png', '', '&copy; <a href=\"http://mapfig.org\" target=\"_blank\">MapFig </a> Bluewaters by <a href=\"http://thunderforest.com\" target=\"_blank\">Thunderforest,</a> Data by <a href=\"http://www.openstreetmap.org/copyright\" target=\"_blank\">OpenStreetMap</a>.'),
(2, 'Open Street Map', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', '', '&lt;a href=&quot;https://openstreetmap.org&quot; target=&quot;_blank&quot;&gt;OpenStreetMap&lt;/a&gt;'),
(3, 'MapQuest', 'https://otile3-s.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', '', '&lt;a href=&quot;https://openstreetmap.org&quot; target=&quot;_blank&quot;&gt;OpenStreetMap&lt;/a&gt;'),

(4, 'CartoDB Light', 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png', '', '<a href=\"https://CartoD.com\" target=\"_blank\">Map tiles by CartoDB, under CC BY 3.0.</a>'),
(5, 'CartoDB Dark', 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png', '', '<a href=\"https://CartoD.com\" target=\"_blank\">Map tiles by CartoDB, under CC BY 3.0.</a>'),
(6, 'Light No Labels', 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_nolabels/{z}/{x}/{y}.png', '', '<a href=\"https://CartoD.com\" target=\"_blank\">Map tiles by CartoDB, under CC BY 3.0.</a>'),
(7, 'Dark No Labels', 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}.png', '', '<a href=\"https://CartoD.com\" target=\"_blank\">Map tiles by CartoDB, under CC BY 3.0.</a>'),
(8, 'CartoDB Antique', 'https://cartocdn_{s}.global.ssl.fastly.net/base-antique/{z}/{x}/{y}.png', '', '<a href=\"https://CartoD.com\" target=\"_blank\">Map tiles by CartoDB, under CC BY 3.0.</a>'),
(9, 'CartoDB ECO', 'https://cartocdn_{s}.global.ssl.fastly.net/base-eco/{z}/{x}/{y}.png', '', '<a href=\"https://CartoD.com\" target=\"_blank\">Map tiles by CartoDB, under CC BY 3.0.</a>'),
(10, 'CartoDB Flatblue', 'https://cartocdn_{s}.global.ssl.fastly.net/base-flatblue/{z}/{x}/{y}.png', '', '<a href=\"https://CartoD.com\" target=\"_blank\">Map tiles by CartoDB, under CC BY 3.0.</a>'),
(11, 'CartoDB Midnight', 'https://cartocdn_{s}.global.ssl.fastly.net/base-midnight/{z}/{x}/{y}.png', '', '<a href=\"https://CartoD.com\" target=\"_blank\">Map tiles by CartoDB, under CC BY 3.0.</a>'),

(12, 'OpenCycleMap', 'https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png', '', '&copy; <a href=\"http://www.thunderforest.com\">Thunderforest</a>, Data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap contributors</a>'),
(13, 'Transport', 'https://{s}.tile.thunderforest.com/transport/{z}/{x}/{y}.png', '', '&copy; <a href=\"http://www.thunderforest.com\">Thunderforest</a>, Data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap contributors</a>'),
(14, 'Landscape', 'https://{s}.tile.thunderforest.com/landscape/{z}/{x}/{y}.png', '', '&copy; <a href=\"http://www.thunderforest.com\">Thunderforest</a>, Data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap contributors</a>'),
(15, 'Outdoors', 'https://{s}.tile.thunderforest.com/outdoors/{z}/{x}/{y}.png', '', '&copy; <a href=\"http://www.thunderforest.com\">Thunderforest</a>, Data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap contributors</a>'),
(16, 'Transport Dark', 'https://{s}.tile.thunderforest.com/transport-dark/{z}/{x}/{y}.png', '', '&copy; <a href=\"http://www.thunderforest.com\">Thunderforest</a>, Data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap contributors</a>'),
(17, 'Spinal Map', 'https://{s}.tile.thunderforest.com/spinal-map/{z}/{x}/{y}.png', '', '&copy; <a href=\"http://www.thunderforest.com\">Thunderforest</a>, Data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap contributors</a>'),
(18, 'Pioneer', 'https://{s}.tile.thunderforest.com/pioneer/{z}/{x}/{y}.png', '', '&copy; <a href=\"http://www.thunderforest.com\">Thunderforest</a>, Data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap contributors</a>'),

(19, 'Toner', 'https://stamen-tiles.a.ssl.fastly.net/toner/{z}/{x}/{y}.png', '', '&copy; Stamen Design, under a <a href=\"http://creativecommons.org/licenses/by/3.0\">Creative Commons Attribution (CC BY 3.0)</a> license.'),
(20, 'Water Color', 'https://stamen-tiles.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.png', '', '&copy; Stamen Design, under a <a href=\"http://creativecommons.org/licenses/by/3.0\">Creative Commons Attribution (CC BY 3.0)</a> license.'),

(21, 'MapQuest Satellite', 'http://otile1.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.png', '', '&lt;a href=&quot;https://openstreetmap.org&quot; target=&quot;_blank&quot;&gt;OpenStreetMap&lt;/a&gt;'),
(22, 'Esri Satellite', 'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', '', '&lt;a href=&quot;http://www.esri.com/&quot; target=&quot;_blank&quot;&gt;Esri&lt;/a&gt;')
";

$db->query($sql);


$sql = "INSERT INTO `$db->CartaGroup` (`id`, `name`, `layer_id`) VALUES
(1, 'Default SSL', '1,2,3'),
(2, 'CartoDB', '4,5,6,7,8,9,10,11'),
(3, 'Thunderforest', '12,13,14,15,16,17,18'),
(4, 'Stamen Design', '19,20'),
(5, 'Satellite', '21,22');
";

$db->query($sql);

$sql = "INSERT INTO `$db->Carta` (`id`, `name`, `width`, `height`, `zoom`, `baselayer`, `layergroup`, `pointers`) VALUES
(1, 'My First Map', '600', '500', 6, 1, 1, 'czoyMDQwOiJbeyJ0eXBlIjoiRmVhdHVyZSIsInByb3BlcnRpZXMiOlt7Im5hbWUiOiJOYW1lIiwidmFsdWUiOiJQYXJpcywgRnJhbmNlIiwiZGVmYXVsdFByb3BlcnR5Ijp0cnVlfSx7Im5hbWUiOiJEZXNjcmlwdGlvbiIsInZhbHVlIjoiPHA+VGhpcyBpcyBhIG1hcmtlci4gJm5ic3A7WW91IGNhbiBhZGQgbWFya2VycyBhcyB3ZWxsIGFzIHNldCBtYXJrZXIgaWNvbiBhbmQgc3R5bGUgYnkgZHJvcHBpbmcgYSBtYXJrZXIgb250byBtYXAgY2FudmFzLiAmbmJzcDtUeXBpbmcgdGhlIGxvY2F0aW9uIGluIHRoZSBOYW1lIGZpZWxkIHdpbGwgc2V0IHRoZSBtYXJrZXIgcG9zaXRpb24uICZuYnNwOyBZb3UgY2FuIGFsc28gYWRkIGltYWdlcyBhbmQgb3RoZXIgbWVkaWEgaW50byB0aGUgYm94IGFzIHdlbGwuJm5ic3A7PC9wPiIsImRlZmF1bHRQcm9wZXJ0eSI6dHJ1ZX1dLCJnZW9tZXRyeSI6eyJ0eXBlIjoiUG9pbnQiLCJjb29yZGluYXRlcyI6WzIuMzUyMjIxOTAwMDAwMDE3Nyw0OC44NTY2MTRdfSwiY3VzdG9tUHJvcGVydGllcyI6eyJnZXRfZGlyZWN0aW9uIjpmYWxzZSwic2hvd19hZGRyZXNzX29uX3BvcHVwIjp0cnVlLCJoaWRlX2xhYmVsIjp0cnVlfSwic3R5bGUiOnsiaWNvbiI6ImNvZmZlZSIsInByZWZpeCI6ImZhIiwibWFya2VyQ29sb3IiOiJjYWRldGJsdWUifX0seyJ0eXBlIjoiRmVhdHVyZSIsInByb3BlcnRpZXMiOlt7Im5hbWUiOiJOYW1lIiwidmFsdWUiOiJQb2x5Z29uIiwiZGVmYXVsdFByb3BlcnR5Ijp0cnVlfSx7Im5hbWUiOiJEZXNjcmlwdGlvbiIsInZhbHVlIjoiPHA+VGhpcyBpcyBhJm5ic3A7UG9seWdvbi4gJm5ic3A7WW91IGNhbiBkcmF3IHBvbHlnb25zIGJ5IGNsaWNraW5nIHRoZSZuYnNwO1BvbHlnb24gaWNvbiBhbmQgc3RhcnQgZHJhd2luZy4gJm5ic3A7WW91IGNhbiBhbHNvIGFkZCBpbWFnZXMgYW5kIG90aGVyIG1lZGlhIHRvIHRoaXMgYm94LjwvcD4iLCJkZWZhdWx0UHJvcGVydHkiOnRydWV9XSwiZ2VvbWV0cnkiOnsidHlwZSI6IlBvbHlnb24iLCJjb29yZGluYXRlcyI6W1tbMy40Mzg3MjA3MDMxMjUsNDguNDI5MjAwNTU1NTY4NDFdLFs1LjA2NDY5NzI2NTYyNSw0OS4xOTYwNjQwMDA3MjM3OTRdLFs2Ljc3ODU2NDQ1MzEyNSw0OC43NjM0MzExMzc5MTc5Nl0sWzQuODIyOTk4MDQ2ODc1LDQ3LjUwMjM1ODk1MTk2ODU5Nl0sWzMuMTA5MTMwODU5Mzc0OTk5Niw0Ny40MjgwODcyNjE3MTQyNzVdLFszLjQzODcyMDcwMzEyNSw0OC40MjkyMDA1NTU1Njg0MV1dXX0sImN1c3RvbVByb3BlcnRpZXMiOnsiZ2V0X2RpcmVjdGlvbiI6ZmFsc2UsInNob3dfYWRkcmVzc19vbl9wb3B1cCI6dHJ1ZSwiaGlkZV9sYWJlbCI6dHJ1ZX0sInN0eWxlIjp7ImNvbG9yIjoiIzljMGUwZSIsIm9wYWNpdHkiOiIwLjUiLCJ3ZWlnaHQiOiI1IiwiZmlsbENvbG9yIjoiIzAwZmZhNiIsImZpbGxPcGFjaXR5IjoiMC4yIn19LHsidHlwZSI6IkZlYXR1cmUiLCJwcm9wZXJ0aWVzIjpbeyJuYW1lIjoiTmFtZSIsInZhbHVlIjoiQSBMaW5lIiwiZGVmYXVsdFByb3BlcnR5Ijp0cnVlfSx7Im5hbWUiOiJEZXNjcmlwdGlvbiIsInZhbHVlIjoiPHA+VGhpcyBpcyBhIExpbmUuICZuYnNwO1lvdSBjYW4gZHJhdyBwb2x5Z29ucyBieSBjbGlja2luZyB0aGUgTGluZSBpY29uIGFuZCBzdGFydCBkcmF3aW5nLiAmbmJzcDtZb3UgY2FuIGFsc28gYWRkIGltYWdlcyBhbmQgb3RoZXIgbWVkaWEgdG8gdGhpcyBib3guPC9wPiIsImRlZmF1bHRQcm9wZXJ0eSI6dHJ1ZX1dLCJnZW9tZXRyeSI6eyJ0eXBlIjoiTGluZVN0cmluZyIsImNvb3JkaW5hdGVzIjpbWzAuMjk2NjMwODU5Mzc1LDQ4LjQ4NzQ4NjQ3OTg4NDE1XSxbMS41OTMwMTc1NzgxMjUsNDguMTIyMTAxMDI4MTkwODA1XV19LCJjdXN0b21Qcm9wZXJ0aWVzIjp7ImdldF9kaXJlY3Rpb24iOmZhbHNlLCJzaG93X2FkZHJlc3Nfb25fcG9wdXAiOnRydWUsImhpZGVfbGFiZWwiOnRydWV9LCJzdHlsZSI6eyJjb2xvciI6IiM3ODc4ZTMiLCJvcGFjaXR5IjoiMC41Iiwid2VpZ2h0IjoiNSIsImZpbGxDb2xvciI6IiNiZjdhN2EiLCJmaWxsT3BhY2l0eSI6IjAuMiJ9fV0iOw==');
";

$db->query($sql);


$db->query("INSERT INTO `$db->ElementSets` (name, description) VALUES ('_carta_version', '{$CurrentVersion}')");
?>