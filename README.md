# Mediasite Disk Stats

This Web Service serves a single purpose: receive disk storage consumption per folder (i.e. org) as POST-data (JSON) and store this in a (MySQL) table.

One record per org per day.

### JSON

JSON object to be POSTed to this service (org : bytes):

```
{
  "token": "",
  "orgs": {
    "org1": 91194390784,
    "org2": 10544352032,
    "...": ...
  }
}
```

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

### Test

```
curl -X "POST" "https://url_to_service" \
	-d $'{
  "token": "...",
  "orgs": {
    "org1": 91194390784,
    "org2": 10544352032,
    "...": ...
  }
}'
```
