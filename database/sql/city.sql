DROP TABLE IF EXISTS `city`;
CREATE TABLE `city`(
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '城市ID',
    `name` varchar(255) not null DEFAULT '' COMMENT '城市名',
    `level` tinyint(4)  unsigned NOT NULL DEFAULT 0 COMMENT '层级',
    `pid` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT '父级ID',
    PRIMARY KEY (`id`),
    KEY `pid` (`pid`) USING BTREE
)ENGINE=InnoDB DEFAULT CHARSET =utf8 COMMENT '城市表';


INSERT INTO `city`(`id`,`name`,`level`,`pid`) VALUES
(1,'浙江省',1,0),
(2,'宁波市',2,1),
(3,'台州市',2,1),
(4,'新河镇',3,3),
(5,'上应村',4,4);


