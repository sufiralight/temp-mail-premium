# TEMP MAIL SAAS — INSTALL 4.0
## Secure Database-First Web Installer — Premium SaaS Implementation

Act as a Principal Laravel 13 Full-Stack Engineer, Application Security Architect, Database Reliability Engineer, and Senior SaaS UX Designer.

**Existing project root:** `C:\Users\ALP\Pictures\rar`

**Mission:** Audit, repair, and complete the existing Laravel 13 web installer so that a fresh Temp Mail SaaS installation works reliably on shared hosting and local development.

Implement actual working code directly in the existing repository.

Do not create a new Laravel project.

Do not stop after writing a plan.

Do not rebuild working application modules.

Preserve existing functionality, database data, permissions, and unrelated uncommitted changes.

---

## 1. Technology and hosting requirements

Preserve the approved stack:

- Laravel 13.
- PHP 8.5, subject to actual framework and dependency compatibility.
- MySQL/MariaDB.
- Blade Components.
- Tailwind CSS 4.
- Alpine.js.
- Vite assets compiled locally or in CI.
- Shared hosting/cPanel compatibility.
- No Livewire.
- No React, Vue, or Inertia.
- No Node.js/npm requirement on production hosting.
- No mandatory Redis, Docker, Supervisor, WebSockets, or long-running queue workers.

The installer must work with standard PHP hosting capabilities and ordinary HTTP requests.

Do not require shell access from the end user for a normal first installation.

## 2. Mandatory repository inspection

Before changing code:

1. Confirm the project root.
2. Inspect `git status` and preserve unrelated changes.
3. Read `AGENTS.md` and relevant files under `agents/` and `docs/`.
4. Inspect existing installer routes, controllers, services, middleware, views, and tests.
5. Inspect existing `.env` configuration handling.
6. Inspect database connection testing.
7. Inspect all current migrations and migration dependencies.
8. Inspect the authentication system.
9. Inspect the existing role and permission architecture.
10. Inspect Founder Super Admin identity handling.
11. Inspect the `workspace_installation` implementation.
12. Inspect existing schema recovery and update commands.
13. Inspect host validation and trusted proxy configuration.
14. Inspect the shared Blade design system.
15. Inspect deployment documentation.

Identify the root causes of previous installer failures before implementing changes.

Do not introduce duplicate installer controllers or competing installation-state mechanisms.

## 3. Required installation flow

The installer must follow this exact sequence:

**Step 1 — Database Configuration**

**Step 2 — System Requirements**

**Step 3 — First Super Admin**

**Step 4 — Installation Complete**

**Step 5 — Login**

Do not add a Setup Credential step.

Do not add an Authorization Code step.

Do not require the administrator to manually generate an application key.

Do not require manual Artisan commands during the normal successful installation flow.

### First-run behavior

When a genuinely uninstalled application is accessed, direct the authorized installation session to:

`/install`

The first visible installer screen must be Database Configuration.

Do not show an unnecessary welcome wizard before database configuration.

If the application is already installed, do not redirect normal visitors to the installer.

## 4. First-install security

A missing `.env` file or installation marker is not sufficient proof that a visitor is authorized to create the first Super Admin.

Implement secure first-install ownership controls.

Requirements:

- No user-facing Setup Credential form.
- No manually entered installation token.
- No public first-visitor-wins ownership.
- No reliance solely on localhost checks in production.
- No installer access after successful installation.
- No bypass through forged proxy headers.
- No installer state changes from unauthenticated background requests.

Use a hosting-compatible, server-side installation authorization mechanism.

It must establish legitimate installation ownership before accepting database credentials or creating the first administrator.

If the hosting environment cannot securely establish ownership without an additional prerequisite, fail closed and explain the required hosting-side action.

Do not silently weaken security to preserve a frictionless UI.

Keep any hosting-side authorization process outside the ordinary installer screens.

Document the exact trust assumptions and operational steps.

## 5. Localhost and 127.0.0.1 compatibility

Local development must support:

`http://localhost:8000/install`

and

`http://127.0.0.1:8000/install`

without requiring changes to `APP_URL` every time.

Accept both only when the request is verified as a direct local loopback development connection.

Do not trust arbitrary Host, Forwarded, or X-Forwarded-* headers.

Do not extend this local exception to production.

Test both local hostnames.

## 6. Database Configuration — Step 1

The first installer screen must request:

- Database host.
- Database port.
- Database name.
- Database username.
- Database password.

Use MySQL/MariaDB as the supported database engine.

Provide a working:

**Test Connection**

action.

### Test Connection behavior

The button must:

1. Validate submitted fields.
2. Attempt a real connection using the submitted credentials.
3. Use bounded connection timeouts.
4. Avoid modifying the existing application database.
5. Avoid creating tables.
6. Avoid running migrations.
7. Avoid logging credentials.
8. Return accurate success or failure feedback.
9. Preserve non-sensitive form values after failure.
10. Allow retry without restarting the installer.

Never display raw PDO exception details, passwords, or connection strings containing secrets.

Do not use SQLite as a silent fallback when MySQL connection fails.

## 7. Database connection UX

Design a premium, focused database configuration form.

Use:

- Clear labels.
- Helpful field descriptions.
- Visible required states.
- Inline validation.
- Accessible focus states.
- Password visibility toggle.
- Connection testing progress.
- Success confirmation.
- Recoverable error feedback.

Distinguish:

- Missing field.
- Invalid port.
- Connection refused.
- Authentication failure.
- Database unavailable.
- Unsupported server version.
- Connection timeout.

Expose safe user-facing explanations without leaking sensitive infrastructure details.

Do not falsely report success.

The Continue action must remain unavailable until the connection is validated against the current submitted settings.

Changing connection settings after a successful test must invalidate the previous test result.

## 8. System Requirements — Step 2

Inspect actual runtime requirements.

Verify:

- Supported PHP version.
- Required PHP extensions.
- PDO MySQL availability.
- Required writable directories.
- Session support.
- Cache compatibility.
- Required application files.
- Existing Vite build assets.
- Laravel runtime dependencies.
- Supported database engine and version.
- Ability to securely write required configuration.
- Required cryptographic capabilities.

Do not invent unsupported version requirements.

Show each requirement with:

- Name.
- Status.
- Clear explanation.
- Recovery guidance when failed.

Block progression only for genuine mandatory requirements.

Do not require Node.js/npm on the hosting server.

Do not require SSH or terminal access for ordinary installation.

## 9. Environment configuration

After successful database validation and requirements checks, securely persist the validated database settings.

Requirements:

- Preserve unrelated existing `.env` values.
- Never expose secrets to public responses.
- Never log database passwords.
- Avoid partial `.env` writes.
- Use atomic file replacement where supported.
- Restrict file permissions where supported.
- Prevent concurrent configuration writes.
- Clear stale configuration state safely.
- Verify the application can reconnect using persisted settings.
- Protect against malformed values and newline injection.

Do not regenerate an existing valid `APP_KEY`.

If `APP_KEY` is missing, generate it securely through the installation workflow without requiring the user to run Artisan manually.

Preserve encrypted application data whenever existing keys are present.

Do not place `.env` inside the publicly accessible web root.

If secure persistence is impossible, stop with an actionable error.

## 10. Database migration orchestration

The installer must initialize the complete database schema required for the current application version.

Inspect every migration and its dependencies.

The initial installation must not leave Admin Console modules in a broken state.

Pay particular attention to:

- Users.
- Roles and permissions.
- Workspace installation identity.
- Domain management.
- Posts.
- Pages.
- Categories.
- Tags.
- Languages.
- UI Translations.
- SEO.
- Fonts.
- Font styles.
- Script-level font defaults.
- Media.
- Branding.
- Audit logs.
- Other currently implemented modules.

Do not omit migrations simply because a module is not immediately visible in the installer.

### Fresh-install rules

For a genuinely new, empty application database:

1. Validate the connection.
2. Confirm the database is an authorized installation target.
3. Run required migrations in dependency order.
4. Run only safe required system initialization.
5. Verify required tables and constraints.
6. Confirm the current application schema is usable.
7. Proceed to first administrator creation.

Do not seed demo users or fake content.

### Existing-database rules

If the database already contains application tables or installation data:

- Do not treat it as an empty database.
- Do not overwrite users.
- Do not reset passwords.
- Do not recreate the founder.
- Do not run destructive migrations.
- Do not silently import or replace data.
- Do not claim a fresh installation succeeded.

Provide an appropriate recovery or existing-installation message.

Never run `migrate:fresh` or `db:wipe` against existing data.

## 11. Migration failure recovery

Handle partial migration failures safely.

Requirements:

- Record accurate migration progress.
- Prevent concurrent migration execution.
- Use database locks or equivalent supported mechanisms.
- Avoid assuming all MySQL DDL can be rolled back transactionally.
- Never declare installation complete after a partial schema failure.
- Preserve recoverable state.
- Allow controlled retry after the cause is resolved.
- Detect existing tables and constraints before attempting repairs.
- Avoid duplicating tables, columns, or indexes.

Do not mask migration failures with generic success messages.

If automatic recovery cannot be performed safely, stop and report the exact administrative recovery requirement.

## 12. First Super Admin — Step 3

After successful schema initialization, display the first Super Admin form.

Fields:

- Full name.
- Email address.
- Password.
- Confirm password.

Requirements:

- Server-side validation.
- Secure password hashing.
- Password strength guidance.
- Password visibility controls.
- Duplicate email prevention.
- CSRF protection.
- Rate limiting.
- Safe error handling.
- Accessible form controls.

Use the existing role system.

The initial administrator must receive the existing:

**Super Admin**

role.

Do not create a fourth role named Founder.

## 13. Founder identity protection

Preserve the existing founder identity mechanism.

The initial administrator must be recorded as the founder using the existing installation identity structure, reportedly:

`workspace_installation.initial_admin_id`

Verify the actual schema before implementation.

The founder is a protected identity, not an additional role.

Preserve existing rules:

- Founder cannot be demoted.
- Founder cannot be deactivated.
- Last active Super Admin cannot be removed.
- Last active Super Admin cannot be demoted.
- Authorization cannot be bypassed through direct HTTP requests.
- Denied administrative operations are audited where implemented.

Prevent concurrent first-admin creation.

Do not allow a second installer session to create another founder.

## 14. Installation completion

Mark the application installed only after:

1. Database configuration is valid.
2. Required schema is verified.
3. Required initialization succeeds.
4. The first Super Admin is created.
5. Founder identity is persisted.
6. Installation state is durably recorded.

Use a consistent installation-state model.

Do not rely exclusively on a removable local marker when authoritative database installation state is available.

Ensure installation completion is idempotent.

Handle interruptions and retries without duplicating the first administrator.

## 15. Installation locking

After successful installation:

- Disable installer mutation routes.
- Prevent installer re-entry.
- Prevent second-founder creation.
- Protect environment configuration.
- Redirect installer requests to an appropriate login or installed-state page.
- Avoid exposing installation internals.

### Missing `.env` recovery

If `.env` is deleted or becomes unavailable, the application must detect the configuration problem.

However, missing `.env` must not automatically authorize public reinstallation or overwrite an existing database.

Provide a secure configuration-recovery route that preserves existing installation identity and data.

Do not confuse configuration recovery with first-time installation.

Never create a new founder for an existing installation.

## 16. Authentication after installation

The installation must finish with a clear success screen.

Primary action:

**Go to Login**

The application must provide the existing:

- Login.
- Register.
- Forgot Password.

Registration creates an ordinary Member account.

It must not grant Admin or Super Admin privileges.

Password reset must use secure tokens and the configured mail delivery mechanism.

Do not expose password reset tokens in logs or public responses.

If outbound mail is not configured, display accurate delivery status rather than pretending an email was sent.

## 17. Premium installer UX

Use the existing centralized Blade design system.

Visual direction:

**Pure Zinc Monochrome Premium**

Use:

- Geist Sans.
- Geist Mono for technical values.
- Lucide icons.
- Zinc neutrals.
- Clear typography hierarchy.
- Refined borders.
- Restrained shadows.
- Accessible contrast.
- Generous but controlled spacing.
- Light/Dark/System support.
- Responsive layouts.

Do not reintroduce decorative gradients unless they are part of the current approved design tokens.

Do not create installer-only copies of buttons, inputs, alerts, or other global components.

### UX principles

**Vercel:** Clear hierarchy and obvious primary action.

**Vite:** Fast, uninterrupted progression.

**Stripe:** Accurate validation, trustworthy feedback, and recoverable failures.

Avoid:

- Unnecessary modals.
- Repeated confirmation screens.
- Decorative progress indicators.
- Fake connection tests.
- Generic database errors.
- Dead-end failure states.
- Inconsistent form components.

## 18. Installation progress and navigation

Show the current installation step clearly.

Do not allow skipping mandatory steps.

Do not allow direct URL access to later steps before prerequisites are satisfied.

Preserve validated state across legitimate navigation.

Do not store plaintext database credentials in browser local storage.

Do not expose secrets in query strings.

Use secure session handling.

After a successful step, provide immediate and accurate feedback.

## 19. Installer authorization and HTTP security

Apply:

- CSRF protection.
- Rate limiting.
- Secure session configuration.
- Appropriate cache-control headers.
- Sensitive response redaction.
- Strict installation-state checks.
- Trusted host validation.
- Trusted proxy validation.
- Server-side step enforcement.
- Concurrency protection.
- Safe redirects.

Prevent:

- Unauthorized first-admin creation.
- Installation replay.
- Host-header attacks.
- Proxy-header spoofing.
- Arbitrary environment writes.
- Duplicate founder creation.
- Session fixation.
- Installer state bypass.
- Public access to sensitive diagnostics.

Do not weaken production security to accommodate local development.

## 20. Automated tests

Add or extend tests for:

1. Fresh application redirects to installer.
2. Database Configuration is the first step.
3. Both supported local loopback hostnames work.
4. Production host validation rejects spoofing.
5. Missing database fields are rejected.
6. Invalid database credentials fail safely.
7. Test Connection performs a real connection attempt.
8. Database testing does not run migrations.
9. Changing credentials invalidates the previous test.
10. Requirements checks reflect actual runtime state.
11. Missing required extensions block progression.
12. Valid configuration is persisted securely.
13. Existing `APP_KEY` is preserved.
14. Missing key is generated safely.
15. Fresh database migrations execute correctly.
16. Required module schemas are verified.
17. Partial migration failures block completion.
18. Retry does not duplicate schema objects.
19. First Super Admin is created correctly.
20. Founder identity is persisted correctly.
21. Second founder creation is rejected.
22. Concurrent installer completion is protected.
23. Installer routes are locked after installation.
24. Missing `.env` does not permit account takeover.
25. Login is available after installation.
26. Registration creates Member accounts.
27. Forgot Password is available.
28. Unauthorized installer access is rejected.
29. Existing application data is preserved.
30. Founder and Last Active Super Admin protections remain intact.

Use isolated test databases.

Never execute destructive installer tests against the existing local MySQL database.

## 21. Browser verification

If browser automation is available, verify:

- Database Configuration screen.
- Test Connection success.
- Test Connection failure.
- Requirements screen.
- Super Admin creation.
- Installation completion.
- Login.
- Form validation.
- Password visibility.
- Keyboard navigation.
- Mobile responsiveness.
- Light and dark themes.
- Back-navigation behavior.
- Recovery from connection failure.
- Both local loopback hostnames.

Do not claim browser testing was completed unless an actual browser was used.

## 22. AGENTS and DOCS synchronization

Update the relevant existing documentation:

- `AGENTS.md`
- `agents/architecture.md`
- `agents/frontend.md`
- `agents/security.md`
- `agents/testing.md`
- `docs/project-overview.md`
- `docs/design-system.md`
- `docs/components.md`
- `docs/authorization.md`
- `docs/testing.md`
- Existing installation and deployment documentation.

Document:

- Database-first installer flow.
- First-install ownership security.
- Localhost compatibility.
- Environment persistence.
- Migration orchestration.
- Schema verification.
- Founder creation.
- Installation locking.
- Missing `.env` recovery.
- Shared-hosting requirements.
- Failure recovery.
- Automated and browser testing.
- Actual verified deployment status.

Preserve unrelated documentation.

Use relative Markdown links.

Do not claim unverified functionality is production-ready.

## 23. Quality gates

Run appropriate checks:

- Focused PHPUnit tests.
- Existing authorization regression tests.
- Installer security tests.
- Migration tests using isolated databases.
- Laravel Pint.
- Vite production build if frontend assets changed.
- `git diff --check`.
- Documentation link validation.

Report failed, skipped, and blocked checks honestly.

## 24. Final delivery report

Provide:

1. Confirmed repository.
2. Existing installer defects discovered.
3. Exact files created or modified.
4. Implemented installation flow.
5. Database connection verification.
6. Environment configuration behavior.
7. Migration execution and schema verification.
8. First Super Admin creation.
9. Founder identity protection.
10. Installer locking behavior.
11. Missing `.env` recovery.
12. Security controls.
13. Automated test results.
14. Browser QA results.
15. AGENTS/DOCS updates.
16. Remaining limitations.

## Definition of done

Install 4.0 is complete only when:

- The first screen is Database Configuration.
- Test Connection works with real MySQL credentials.
- System Requirements correctly checks the hosting environment.
- All required migrations run on a fresh database.
- All implemented modules have usable schema.
- First Super Admin creation works.
- Founder identity is correctly protected.
- No Setup Credential screen exists.
- No manual application key generation is required.
- No shell commands are required for a normal supported installation.
- Installation completion is durable and idempotent.
- Installer access is locked after installation.
- Existing installations cannot be hijacked through missing configuration.
- Login, Register, and Forgot Password routes work.
- Localhost and 127.0.0.1 are supported safely.
- The design system remains consistent.
- Automated security and functionality tests pass.
- Browser QA results are reported accurately.
- AGENTS/DOCS are synchronized.

**Final instruction:** Implement the secure Database-First Web Installer directly in the existing Temp Mail SaaS repository. Fix root causes rather than adding workarounds. Preserve all existing working modules and database data. Do not weaken security for convenience. Do not stop at planning. Report only verified implementation results.