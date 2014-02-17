CREATE TABLE `kill_room` (
    `id` int(5) NOT NULL AUTO_INCREMENT,
    `room` int(4) NOT NULL,
	`kill` varchar(20) NOT NULL,
    `plice` varchar(20) NOT NULL,
    `common` varchar(20) NOT NULL,
    `doctor` varchar(20) NOT NULL,
    `num` char(20) NOT NULL,
    `now` int(2) NOT NULL,
    `total` int(2) NOT NULL,
    `post` int(1) NOT NULL,
    `postnum` varchar(50) NOT NULL,
    `dead` char(30) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;