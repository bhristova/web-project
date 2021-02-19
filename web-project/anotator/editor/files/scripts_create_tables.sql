CREATE TABLE `citation` (
 `id` varchar(36) NOT NULL,
 `authorFirstName` varchar(100) NOT NULL DEFAULT '',
 `authorLastName` varchar(100) NOT NULL DEFAULT '',
 `source` varchar(200) NOT NULL DEFAULT '',
 `containerTitle` varchar(200) NOT NULL DEFAULT '',
 `otherContributors` varchar(200) NOT NULL DEFAULT '',
 `version` varchar(50) NOT NULL DEFAULT '',
 `number` varchar(50) NOT NULL DEFAULT '',
 `publisher` varchar(100) NOT NULL DEFAULT '',
 `publicationDate` varchar(50) NOT NULL DEFAULT '',
 `location` varchar(200) NOT NULL DEFAULT '',
 `annotationType` varchar(50) NOT NULL DEFAULT '',
 `sourceType` varchar(50) NOT NULL DEFAULT '',
 `projectId` varchar(36) NOT NULL,
 `formattedCitation` varchar(1000) NOT NULL DEFAULT '',
 `inTextCitation` varchar(1000) NOT NULL DEFAULT '',
 `quote` varchar(1000) NOT NULL DEFAULT '',
 `page` varchar(50) NOT NULL DEFAULT '',
 `dateOfAccess` varchar(50) NOT NULL DEFAULT '',
 `titleOfWebsite` varchar(100) NOT NULL DEFAULT '',
 `linkOnline` varchar(200) NOT NULL DEFAULT '',
 `linkArchive` varchar(300) NOT NULL DEFAULT '',
 `linkLibrary` varchar(200) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

CREATE TABLE `user` (
 `id` varchar(36) CHARACTER SET utf8mb4 NOT NULL,
 `username` varchar(100) NOT NULL,
 `email` varchar(100) NOT NULL,
 `password` varchar(500) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `project` (
 `id` varchar(36) NOT NULL,
 `name` varchar(100) NOT NULL,
 `annotationType` varchar(50) NOT NULL,
 `content` text NOT NULL,
 `createdBy` varchar(36) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `createdBy` (`createdBy`),
 CONSTRAINT `project_ibfk_1` FOREIGN KEY (`createdBy`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

CREATE TABLE `l_citationtypes` (
 `id` varchar(36) NOT NULL,
 `name` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `l_citationsources` (
 `id` varchar(36) NOT NULL,
 `name` varchar(36) CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci NOT NULL,
 `inTextCitation` text NOT NULL DEFAULT '',
 `bibliographyCitation` text NOT NULL DEFAULT '',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `l_citationtypes_l_citationsources` (
 `id1_L_citationType` varchar(36) NOT NULL,
 `id2_L_citationSource` varchar(36) NOT NULL,
 `config` text CHARACTER SET cp1251 COLLATE cp1251_bulgarian_ci NOT NULL,
 KEY `id1_L_citationType` (`id1_L_citationType`),
 KEY `id2_L_citationSource` (`id2_L_citationSource`),
 CONSTRAINT `l_citationtypes_l_citationsources_ibfk_1` FOREIGN KEY (`id1_L_citationType`) REFERENCES `l_citationtypes` (`id`),
 CONSTRAINT `l_citationtypes_l_citationsources_ibfk_2` FOREIGN KEY (`id2_L_citationSource`) REFERENCES `l_citationsources` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1