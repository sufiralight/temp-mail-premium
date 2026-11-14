# Workspace authorization

The users.role column supports the Member, Admin and Super Admin role values member, admin and super_admin. The additive migration defaults existing and newly registered users to Member. Public signup accepts identity fields only.

The Laravel admin.access Gate permits Admin and Super Admin. Both console routes require the web session and auth.session. Members are rejected on direct requests. The account page renders its Admin Console link through the same Gate.

The current console provides read-only Overview and Users pages. Role writes, account activation, account deletion and audit logging remain pending. Apply the workspace role migration in a controlled release. Existing accounts require operator review before any administrative role is assigned. See [Deployment](deployment.md).

## Founder Super Admin

Founder is the protected original installer administrator identity, with Super Admin as its role. Founder provisioning and the last-active-Super-Admin safeguard remain planned. Implement both before exposing role or account lifecycle writes.
