CREATE TABLE `who_room` (
    `id` int(5) NOT NULL AUTO_INCREMENT,
    `room` int(4) NOT NULL,
	`wodinum` int(2) NOT NULL,
    `wodi` char(20) NOT NULL,
    `word1` varchar(20) NOT NULL,
    `word2` varchar(20) NOT NULL,
    `now` int(2) NOT NULL,
    `total` int(2) NOT NULL,
    `post` int(1) NOT NULL,
    `postnum` varchar(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;