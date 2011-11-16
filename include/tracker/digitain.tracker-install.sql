#
# OpenTracker
# revised 11-Sep-2004
# revised 23-Apr-2009: `info_hash` and `peer_id` are of BINARY type
#
# Dylan Powers
# revised 7/26/2011: 'ip' changed from int(11) to binary(17) to allow for ipv6
#                    added the alter table statement

DROP TABLE IF EXISTS digitain.tracker;
CREATE TABLE digitain.tracker (
  `info_hash` binary(20) NOT NULL,
  `ip` binary(17) NOT NULL,
  `port` smallint(5) unsigned NOT NULL,
  `peer_id` binary(20) NOT NULL,
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `left` bigint(20) unsigned NOT NULL default '0',
  `update_time` timestamp(14) NOT NULL,
  `expire_time` timestamp(14) NOT NULL,
  PRIMARY KEY  (`info_hash`,`ip`,`port`)
) ENGINE=MyISAM;

ALTER TABLE digitain.users ADD COLUMN last_ip binary(17) NOT NULL;