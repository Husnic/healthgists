#!/bin/bash
# Starts the local preview server. Run local-dev-setup.sh once first.

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SITE="$DIR/local-site"

if [ ! -d "$SITE" ]; then
  echo "No local-site found — run ./local-dev-setup.sh first."
  exit 1
fi

echo "Serving http://localhost:8988 — Ctrl+C to stop."
php -d memory_limit=512M -S localhost:8988 -t "$SITE"
