## Docker setup
```bash
docker run --rm -it --volume $PWD:/app composer install
docker run -d --volume $PWD:/app -p 8080:8080 -w /app php:cli-alpine -S 0.0.0.0:8080 -t web web/index.php
```

## Routes
```
GET /
GET /survey/
GET /survey/:code
```

## Note
The challenge have been done in 11~12h. 
