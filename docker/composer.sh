#!/usr/bin/env bash
docker-compose exec tractocamiones_back sh -c "cd //var/www/ && composer $@"
