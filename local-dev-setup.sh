#!/bin/bash
# One-time local WordPress setup for previewing the healthgists theme.
# Uses SQLite (no MySQL needed). Safe to re-run — wipes and rebuilds ./local-site.

set -e

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SITE="$DIR/local-site"
THEME_SRC="$DIR/wp-theme"
URL="http://localhost:8988"

wpcli() { php -d memory_limit=512M -d error_reporting="E_ALL & ~E_DEPRECATED" "$(which wp)" --path="$SITE" "$@"; }

echo "==> Removing old local-site (if any)"
rm -rf "$SITE"
mkdir -p "$SITE"

echo "==> Downloading WordPress core"
php -d memory_limit=512M -d error_reporting="E_ALL & ~E_DEPRECATED" "$(which wp)" core download --path="$SITE" --quiet

echo "==> Installing SQLite database integration (no MySQL needed)"
curl -sL -o /tmp/sqlite-plugin.zip "https://downloads.wordpress.org/plugin/sqlite-database-integration.zip"
unzip -q -o /tmp/sqlite-plugin.zip -d "$SITE/wp-content/plugins/"
cp "$SITE/wp-content/plugins/sqlite-database-integration/db.copy" "$SITE/wp-content/db.php"

echo "==> Creating wp-config.php"
php -d memory_limit=512M -d error_reporting="E_ALL & ~E_DEPRECATED" "$(which wp)" config create --path="$SITE" \
  --dbname=healthgists --dbuser=healthgists --dbpass=healthgists --skip-check --extra-php <<PHP
define( 'WP_HOME', '$URL' );
define( 'WP_SITEURL', '$URL' );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
PHP

echo "==> Installing WordPress"
wpcli core install --url="$URL" --title="Healthgists (local)" \
  --admin_user=admin --admin_password=admin --admin_email=admin@example.com --skip-email

wpcli plugin activate sqlite-database-integration

echo "==> Linking theme (edits in wp-theme/ show up immediately, no copying)"
ln -s "$THEME_SRC" "$SITE/wp-content/themes/healthgists"
wpcli theme activate healthgists

echo "==> Pretty permalinks"
wpcli rewrite structure '/%postname%/'
wpcli rewrite flush

echo "==> Seeding categories, sample posts, and Home/About/Contact/Blog pages"
wpcli eval-file "$THEME_SRC/bin/seed-site.php"

echo ""
echo "Done. Start it with:"
echo "  ./local-dev-serve.sh"
echo ""
echo "Then visit:      $URL"
echo "wp-admin login:  $URL/wp-admin  (admin / admin)"
