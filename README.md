# Skillify

A location-based marketplace that connects clients with skilled tradespeople and professionals, built with **Laravel 13** on the backend and **Inertia.js + React** on the frontend.

Clients search for professionals near them, browse their work, and message them directly in real time. Professionals register as regular users and apply for a verified **Business Account** — an application screened by an AI identity check before an admin approves it.

Final-year graduation project — Computer Engineering Technologies (Software), University of Homs.

## Features

- **AI-assisted identity verification** — Business Account applicants submit identity documents, which are analyzed through the Google Gemini API before reaching an admin for final review. This filters out obviously invalid submissions and keeps the manual review queue small.
- **Location-based discovery** — professionals set their location on an interactive map (Leaflet), so clients can find the nearest relevant tradespeople instead of a flat, unordered list.
- **Real-time messaging** — live chat between clients and professionals over a self-hosted **Laravel Reverb** WebSocket server, with instant delivery and no page refresh.
- **Real-time notifications** — for new messages, application status changes (approved / rejected), and platform announcements.
- **Portfolio & service galleries** — every verified professional has a gallery of services and past work, with images, pricing, and location.
- **Community feed** — a public feed where users can post job offers, requests, and general listings, with sponsored ads interleaved into the feed.
- **Ads management** — Admins and Super Admins can create, schedule, and manage sponsored ads shown on the landing page, dashboard, and community feed.
- **Self-service account management** — users can update their profile, change their password (with re-authentication), and permanently delete their business account or entire account (cascading to their services, gallery, and files).
- **Granular admin roles & permissions** — beyond the three top-level roles below, Admin accounts can be assigned fine-grained permissions (e.g. `verifier`, `support`, `content_moderator`) scoped to specific actions.
- **User moderation** — Admins and Super Admins can block/unblock user accounts.

## Account & Verification Flow

```
Register  →  User account  →  Apply for Business Account
                                       │
                                       ▼
                          AI identity check (Gemini API)
                                       │
                                       ▼
                              Admin review queue
                                       │
                                       ▼
                            Verified professional
```

## Roles & Permissions

| Role | Permissions |
|---|---|
| **Super Admin** | Full platform control — manages Admin accounts, roles & permissions, and all platform data |
| **Admin** | Reviews Business Account applications, manages users, content, and ads (permissions can be scoped further per admin) |
| **User** | Default role on registration; browses professionals, chats, posts to the community feed, and can apply for a Business Account |

## Tech Stack

| Layer | Choice |
|---|---|
| Framework | Laravel 13 (PHP 8.3) |
| Frontend | Inertia.js + React 19, Tailwind CSS v4 |
| Authentication | Session-based, with three separate guards (`users`, `admins`, `super_admins`) |
| Authorization | Spatie Laravel Permission (roles & granular permissions) |
| Real-time | Laravel Reverb (self-hosted WebSocket server, Pusher-protocol compatible) |
| Maps | Leaflet |
| AI | Google Gemini API |
| Database | MySQL |

## Screenshots

### Public

<table>
<tr>
<td width="50%"><img src="docs/screenshots/landing-page.png" alt="Landing page" /><p align="center"><sub>Landing page</sub></p></td>
<td width="50%"><img src="docs/screenshots/login-page.png" alt="Login page" /><p align="center"><sub>Login page</sub></p></td>
</tr>
<tr>
<td width="50%"><img src="docs/screenshots/register-page.png" alt="Register page" /><p align="center"><sub>Account registration</sub></p></td>
<td width="50%"><img src="docs/screenshots/forgot-password-page.png" alt="Forgot password page" /><p align="center"><sub>Password recovery via WhatsApp OTP</sub></p></td>
</tr>
</table>

### User panel

<table>
<tr>
<td width="50%"><img src="docs/screenshots/user-dashboard.png" alt="User dashboard" /><p align="center"><sub>User dashboard</sub></p></td>
<td width="50%"><img src="docs/screenshots/user-profile.png" alt="User profile" /><p align="center"><sub>User profile</sub></p></td>
</tr>
<tr>
<td width="50%"><img src="docs/screenshots/user-explore.png" alt="Explore professionals" /><p align="center"><sub>Discovering professionals near you</sub></p></td>
<td width="50%"><img src="docs/screenshots/user-community-posts.png" alt="Community posts" /><p align="center"><sub>Community posts (with sponsored ads in feed)</sub></p></td>
</tr>
<tr>
<td width="50%"><img src="docs/screenshots/user-service-details.png" alt="Service details" /><p align="center"><sub>Service details</sub></p></td>
<td width="50%"></td>
</tr>
</table>

### Super Admin panel

<table>
<tr>
<td width="50%"><img src="docs/screenshots/superadmin-dashboard.png" alt="Super Admin dashboard" /><p align="center"><sub>Dashboard overview</sub></p></td>
<td width="50%"><img src="docs/screenshots/superadmin-users.png" alt="Super Admin users" /><p align="center"><sub>User management</sub></p></td>
</tr>
<tr>
<td width="50%"><img src="docs/screenshots/superadmin-businesses.png" alt="Super Admin businesses" /><p align="center"><sub>Business accounts</sub></p></td>
<td width="50%"><img src="docs/screenshots/superadmin-identity-verification.png" alt="Identity verification queue" /><p align="center"><sub>AI-assisted identity verification queue</sub></p></td>
</tr>
<tr>
<td width="50%"><img src="docs/screenshots/superadmin-admins.png" alt="Admin accounts" /><p align="center"><sub>Admin account management</sub></p></td>
<td width="50%"><img src="docs/screenshots/superadmin-permissions.png" alt="Roles and permissions" /><p align="center"><sub>Roles &amp; permissions</sub></p></td>
</tr>
<tr>
<td width="50%"><img src="docs/screenshots/superadmin-ads.png" alt="Super Admin ads" /><p align="center"><sub>Ads management</sub></p></td>
<td width="50%"><img src="docs/screenshots/superadmin-profile.png" alt="Super Admin profile" /><p align="center"><sub>Profile settings</sub></p></td>
</tr>
</table>

## Getting Started

Requires PHP 8.3+, Composer, MySQL, and Node.js.

```bash
git clone https://github.com/Mohamad-Kahwaji/Skillify.git
cd Skillify

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

Fill in the following in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

GEMINI_API_KEY=
```

Then, in three separate terminals:

```bash
php artisan reverb:start   # WebSocket server
npm run build              # or `npm run dev` while developing
php artisan serve
```

## Author

Mohamad Kahwaji — Laravel backend developer
[GitHub](https://github.com/Mohamad-Kahwaji) · LinkedIn
