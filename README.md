Skillify

A location-based marketplace that connects clients with skilled tradespeople, built with Laravel 12 as a server-rendered web application.

Clients search for professionals near them, browse their work, and message them directly. Professionals register as regular users and apply for a verified Business Account — an application that is screened by an AI identity check before an admin approves it.

Final-year graduation project — Computer Engineering Technologies (Software), University of Homs.

Core features

AI-assisted identity verification Business Account applicants submit identity documents, which are processed through the Google Gemini API before reaching an admin for final review. This filters out obviously invalid submissions and keeps the manual review queue small.

Location-based discovery Professionals are indexed by geographic coordinates, so clients see the nearest relevant tradespeople first rather than a flat, unordered list.

Real-time messaging Live chat between clients and professionals over Pusher and Laravel Broadcasting — no page refresh, delivered through Laravel's event broadcasting layer.

In-app notifications Real-time notifications for new messages and for application status changes (approved / rejected).

Portfolio galleries Every verified professional has a work gallery for showcasing previous jobs, which is what a client actually judges before making contact.

Account flow
Register  →  User account  →  Apply for Business Account
                                       ↓
                          AI identity check (Gemini API)
                                       ↓
                              Admin review queue
                                       ↓
                            Verified professional
Role	Permissions
Super Admin	Full platform control, manages admin accounts and all data
Admin	Reviews Business Account applications, manages users and content
User	Default role on registration; browses professionals, chats, and can apply for a Business Account
Tech stack
Layer	Choice
Framework	Laravel 12 (PHP 8.2)
Interface	Server-rendered Blade views
Authentication	Laravel session-based authentication with role authorization
Real-time	Pusher + Laravel Broadcasting
AI	Google Gemini API
Database	MySQL

Scope note: Skillify is a web application. Its interface is rendered server-side with Blade — there is no public REST API or mobile client.

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

Running locally

Requires PHP 8.2+, Composer, MySQL, and Node.js.

bash
git clone https://github.com/Mohamad-Kahwaji/Skillify.git
cd Skillify

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate --seed

npm run build
php artisan serve

Then fill in the following in .env:

env
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

GEMINI_API_KEY=
Author

Mohamad Kahwaji — Laravel backend developer GitHub · LinkedIn
