# Mediasite Disk Stats

This Web Service serves a single purpose: receive disk storage consumption per org as POST-data (JSON) and store this in a MySQL table.

One record per org per day.

### JSON

JSON object to be POSTed to this service (org : bytes):

{
  "org_1_name": 911990784,
  "org_2_name": 1054732032,
  ..., 
  ...
}

### Table

```
CREATE TABLE `TABLE_NAME` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `org` varchar(30) NOT NULL DEFAULT '',
  `storage_mib` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### Record insert

Note: byte storage is converted to mib before insert:

> 'storage_mib' int(11) allows a max value of 4294967295mib (== 4095tib), which should suffice for the foreseable future...