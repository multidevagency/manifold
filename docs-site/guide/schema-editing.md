# Editing schema from the UI

In the **local environment only**, the admin can change the schema itself:

- **+ New collection** (sidebar) writes `app/Collections/<Name>.php`, registers it in `config/manifold.php`, and migrates.
- **+ Add field** (any list view) inserts the field into the collection class — imports sorted, modifiers included — and migrates.

The point: the UI writes **the same PHP you'd write by hand**. Code stays the single source of truth, the diff shows up in git, and nothing exists that the class doesn't declare.

## Safety

- Hard-gated to `local`/`testing` environments — a deployed admin returns `403`.
- Identifiers are strictly validated before touching any file.
- Migrations run in a subprocess, because the request that edited the class is still running the old code — PHP cannot reload a loaded class.
