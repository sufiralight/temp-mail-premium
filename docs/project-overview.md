# Temp Mail SaaS

| Area | Status in this checkout |
| --- | --- |
| Install 4.1 | Database Configuration and System Requirements implemented. Web finalization, founder provisioning and completion handoff pending. |
| Authentication | Login, registration, logout and password reset implemented. Live mail delivery unverified. |
| Shared appearance and password input | Implemented across installer and authentication screens. |
| Administration, branding, media, localization and typography | Reference designs. Their module implementations are absent from this checkout. |
| Core Temp Mail | Planned. |

See [Installation](installation.md), [Authentication](authentication.md), [Deployment](deployment.md) and [Testing](testing.md) for current workflows and verification.

## Implementation priorities

Complete installer finalization, then verify live database and mail workflows.

### Core Temp Mail scope

Temporary address generation and inbox/IMAP synchronization are **Planned**. The core journey generates and displays a temporary address, receives messages, renders messages safely, and enforces access and expiry with loading, empty, error and expired states. The approved direction is hosting mailboxes with IMAP, multiple system and custom domains, simple domain CRUD and bounded shared-hosting scheduled synchronization. Automated DNS verification and MX/TXT management are optional outside this scope. Dashboard currently provides account and workspace functions.

See [Media Library](media-library.md) and [Localization](localization.md) for reference designs.
