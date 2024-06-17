#!/bin/bash

echo "Starting release tasks..." > /tmp/deploy.log
php artisan storage:link >> /tmp/deploy.log 2>&1
echo "Release tasks completed." >> /tmp/deploy.log