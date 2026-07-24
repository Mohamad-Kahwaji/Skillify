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

Screenshots
<!-- Add 3-4 screenshots here: professional search results, a portfolio page, the chat interface, and the admin review queue. -->
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
