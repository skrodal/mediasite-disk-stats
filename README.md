# Mediasite Disk Stats

_This API is tailor-made for UNINETT AS for a specific use-case. As it does **not** access Sonic Foundry's official Mediasite API, its re-usability is limited._

Dette er en veldig enkel Web Service som gjør kun en ting: ta imot diskforbruk per folder (org) som POST-data (JSON) og lagre dette in en MySQL tabell. 

Ett innslag (record) per org per dag.

Script som dumper data til denne service'n kjøres en gang i døgnet (cron?) og er administrert av noen(tm) i 4etg. 

### JSON

JSON-objektet som POSTes til denne service'n må ha følgende format (org : bytes):

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

...der en hemmelig `token` (definert i config) styrer aksept av innslag. Script som POSTer må altså sende med samme token.

### Tabell

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

Obs: byte storage blir konvertert til `mib` før innslag blir lagret i tabellen:

> 'storage_mib' int(11) muliggjør en maksverdi av 4294967295mib (== 4095tib), noe som burde holde en god stund...

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
