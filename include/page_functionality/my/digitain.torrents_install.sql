CREATE TABLE digitain.torrents (
	torrent_id int unsigned not null primary key auto_increment,
	name char(100) not null, INDEX (name),
	category char(50),
	uploader char(50) not null,
	opt_trackers text not null,
	upload_datetime timestamp not null DEFAULT CURRENT_TIMESTAMP,
	file_name char(108) not null,
	file_size int unsigned not null,
	file_type char(30) not null,
	data mediumblob not null
	);
	
