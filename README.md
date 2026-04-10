# Xophz Titan's Mitt

> **Category:** Wizard's Tower · **Version:** 1.0.0

Bring down heavy objects with ease using this bulk editor.

## Description

**Titan's Mitt** is a bulk editing and database orchestration tool for the COMPASS platform. It leverages a custom REST API to perform heavy-lifting operations, such as bulk updating posts, modifying metadata across thousands of records, and executing systemic "smush" operations.

### Core Capabilities

- **Bulk Editing** – Safely apply changes across massive datasets without timing out the server.
- **Smush Operations** – Provides REST endpoints (e.g., `/titans-mitt/smush`) to compress, clean, or recalculate site data in bulk.
- **Data Statistics** – Exposes endpoint (`/titans-mitt/stats`) for reviewing database volume before initiating bulk actions.

## Requirements

- **Xophz COMPASS** parent plugin (active)
- WordPress 5.8+, PHP 7.4+

## Installation

1. Ensure **Xophz COMPASS** is installed and active.
2. Upload `xophz-compass-titans-mitt` to `/wp-content/plugins/`.
3. Activate through the Plugins menu.
4. Access via the COMPASS dashboard → **Titan's Mitt**.

## PHP Class Map

| Class | File | Purpose |
|---|---|---|
| `Xophz_Compass_Titans_Mitt` | `class-xophz-compass-titans-mitt.php` | Core plugin hooks |
| `Xophz_Compass_Titans_Mitt_Rest` | `class-xophz-compass-titans-mitt-rest.php` | Handles bulk action and stats REST endpoints |

## Frontend Routes

| Route | View | Description |
|---|---|---|
| `/titans-mitt` | Dashboard | Interface for orchestrating bulk edits and smush operations |

## Changelog

### 1.0.0

- Initial release featuring bulk REST endpoints and statistical reporting.
