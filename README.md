### Docker Run
```
docker run \
--detach \
--name plex-influxdb \
--env "PLEX_TOKEN=jBSPSJgdWQcPws9dTnfb" \
bmoorman/plex-influxdb:latest
```

### Docker Compose
```
version: "3.7"
services:
  plex-influxdb:
    image: bmoorman/plex-influxdb:latest
    container_name: plex-influxdb
    environment:
      - PLEX_TOKEN=jBSPSJgdWQcPws9dTnfb
```
