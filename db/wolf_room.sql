CREATE TABLE `wolf_room` (
    `id` int(5) NOT NULL AUTO_INCREMENT,
    `room` int(4) NOT NULL,

	`wolf` varchar(20) NOT NULL,
    `common` varchar(20) NOT NULL,
    `witch` varchar(20) NOT NULL,
    `prophet` varchar(20) NOT NULL,

    `cupid` varchar(20) NOT NULL,
    `doctor` varchar(20) NOT NULL,
    `ward` varchar(20) NOT NULL,
    `eldest` varchar(20) NOT NULL,

    `hunter` varchar(20) NOT NULL,
    `girl` varchar(20) NOT NULL,
    `idiot` varchar(20) NOT NULL,
    `flute` varchar(20) NOT NULL,

    `num` char(20) NOT NULL,
    `now` int(2) NOT NULL,
    `total` int(2) NOT NULL,
    `post` int(1) NOT NULL,
    `postnum` varchar(50) NOT NULL,
    `dead` char(30) NOT NULL,
    
    `cp` char(6) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;