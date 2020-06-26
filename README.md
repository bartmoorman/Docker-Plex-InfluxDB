### Docker Run
```
docker run \
--detach \
--name plex-influxdb \
--restart unless-stopped \
bmoorman/plex-influxdb:latest
```

### Docker Compose
```
version: "3.7"
services:
  plex-influxdb:
    image: bmoorman/plex-influxdb:latest
    container_name: plex-influxdb
    restart: unless-stopped
```

### Environment Variables
|Variable|Description|Default|
|--------|-----------|-------|
|TZ|Sets the timezone|`America/Denver`|
|INTERVAL|Sets the collection frequency|`10`|
|PLEX_HOST|Sets the Plex host|`plex`|
|PLEX_PORT|Sets the Plex port|`32400`|
|PLEX_TOKEN|Sets the Plex token|`<empty>`|
|INFLUXDB_HOST|Sets the InfluxDB host|`influxdb`|
|INFLUXDB_PORT|Sets the InfluxDB port|`8086`|
|INFLUXDB_USERNAME|Sets the InfluxDB username|`<empty>`|
|INFLUXDB_PASSWORD|Sets the InfluxDB password|`<empty>`|
|INFLUXDB_DATABASE|Sets the InfluxDB database|`plex`|
