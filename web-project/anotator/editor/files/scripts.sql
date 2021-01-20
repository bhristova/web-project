CREATE TABLE `citation` (
 `id` varchar(36) NOT NULL,
 `authorFirstName` varchar(100) NOT NULL,
 `authorLastName` varchar(100) NOT NULL,
 `source` varchar(200) NOT NULL,
 `containerTitle` varchar(200) NOT NULL,
 `otherContributors` varchar(200) NOT NULL,
 `version` varchar(50) NOT NULL,
 `number` varchar(50) NOT NULL,
 `publisher` varchar(100) NOT NULL,
 `publicationDate` varchar(50) NOT NULL,
 `location` varchar(200) NOT NULL,
 `annotationType` varchar(50) NOT NULL,
 `sourceType` varchar(50) NOT NULL,
 `projectId` varchar(36) NOT NULL,
 `formattedCitation` varchar(1000) NOT NULL,
 `inTextCitation` varchar(1000) NOT NULL,
 `quote` varchar(1000) NOT NULL,
 `page` varchar(50) NOT NULL,
 `dateOfAccess` varchar(50) NOT NULL,
 `titleOfWebsite` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

CREATE TABLE `project` (
 `id` varchar(36) NOT NULL,
 `name` varchar(100) NOT NULL,
 `annotationType` varchar(50) NOT NULL,
 `content` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

