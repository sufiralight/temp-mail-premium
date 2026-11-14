# Installation

The compact installer layout keeps controls at least 44 pixels high and allows the document to scroll when space is limited.

## Current workflow

The web installer in this checkout provides **Database Configuration** and **System Requirements**. A successful connection test opens the requirements page. The requirements page displays runtime checks and links back to database configuration. Returning to configuration or submitting a new test clears the previous verification. Verification expires after 15 minutes and is tied to the gate configuration.

The intended sequence continues with **First Super Admin**, **Installation Complete**, and **Login**. The first two of those steps and the handoff are pending. Login is available independently through the existing [authentication routes](authentication.md).

## Open a new installation

The hosting operator creates `storage/app/private/installer/enable.json` outside the public document root. Restrict write access to the operator and the PHP service account. For direct loopback development in the local application environment use:

```json
{"mode":"local"}
```

Use either `http://localhost:8000/install` or `http://127.0.0.1:8000/install`. Keep one hostname throughout the session. The default local database endpoint is `127.0.0.1`, and `localhost` is normalized to that address. An operator can specify `database_host` and `database_port` in the local gate.

For production, create the gate through the authenticated hosting control panel:

```json
{
  "mode": "production",
  "host": "mail.example.com",
  "operator_ip": "203.0.113.17",
  "database_host": "db.example.com",
  "database_port": 3306
}
```

Use direct HTTPS and a dedicated operator IP exposed as the raw network peer. The gate validates the request host separately from the database host, rejects forwarding headers, and closes installer access if the private `installed` file exists. Leave the gate closed if the host cannot establish this boundary. See [Deployment](deployment.md).

## Verify the database

Choose **MySQL** or **MariaDB** and provide the approved database hostname, port, database name, username and password. Host and port are editable. The initial port is 3306 unless the gate supplies another. Both engines require PDO MySQL. PostgreSQL and SQLite appear as disabled choices pending installer verification.

**Test Connection** uses the selected endpoint and checks the server-reported engine, selected database and absence of tables or views. It uses bounded PDO connection settings and runs read-only checks. Failures give a safe reason and retain non-sensitive form values for retry. The password is never flashed or populated into the form.

The current flow uses the application's configured Laravel session and cache. It requires a working application key and working session and cache stores. Automatic key generation and database-independent installer bootstrap remain pending.

## System Requirements

After verification, the page reports PHP, PDO MySQL, OpenSSL, session and cache directory writability, environment directory writability and Vite manifest availability. The checks do not persist database settings or run migrations.

## Finalization and recovery

Configuration persistence, initial schema migration, transactional founder creation and an automatic completion marker remain pending. Restore the original environment and key from a trusted backup if configuration is lost. Preserve existing data and keep the operator gate closed while investigating. See [schema recovery](deployment.md#schema-recovery).

## Shared interface

Both installer screens reuse the global Light, Dark and System control with Sun, Moon and Monitor icons. The selected mode is stored per browser origin and System follows the OS preference. The database password uses the [shared password field](components.md) with Eye and Eye-off icons. It starts masked and the toggle preserves the value and keyboard focus.

## Verification

Run `php artisan test --filter=InstallerTest` for loopback access, forwarding-header rejection, engine choices, verification invalidation, safe error handling and shared controls. Connection outcomes in the feature suite are mocked. Live MySQL and MariaDB tests and browser interaction checks remain open.

Product identification checks server version and distribution metadata plus the Aria engine inventory. Both products use PDO MySQL. Unknown products and confirmed selection mismatches require operator review. The verification probe is read-only.
