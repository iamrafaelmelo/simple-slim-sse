# Simple Slim SSE

This repository contains a basic implementation of [SSE (Server-Side Events)](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events)
using Slim Framework for stusies purposes. Feel free to clone and modify, enjoy!

> This implementation is limited to up to 6 connections per browser/domain, because isn't use HTTP/2 (see more in [notes](#notes)).

![Application demo](/.github/images/screenshot.png)

## Requirements

- PHP >= 8.2
- Composer
- Docker
- Make (optional)

## Get started

**With docker**

```bash
docker compose up || docker-compose up
```

**With make**

```bash
make up
```

And access `http://127.0.0.1:80` on your browser.

**Accessing docker container**

```bash
docker exec -it server-side-events-app sh
# or
make container
```

## Notes

- NGINX was used due to the connection limit of PHP's built-in server (where only 1 connection is supported at a time)
- Does not support binary data like Websocket
- It is unidirectional (depending on the type of application this may not be a problem)
- SSE has a limit of six open connections per browser when HTTP/2 is not used, reaching up to 100
- The connection limit is per browser and domain
