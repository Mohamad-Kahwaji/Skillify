# 🛠️ Skillify

A location-based professional services platform built with Laravel, connecting skilled professionals and businesses with clients — featuring AI-powered identity verification, real-time chat, and portfolio showcasing.

---

## ✨ Features

- 🔐 **Authentication & Authorization** — Token-based auth via Laravel Sanctum with 3-tier role system
- 🤖 **AI Identity Verification** — Business account applicants verified using Google Gemini AI
- 📍 **Location-Based Discovery** — Find professionals and businesses by location
- 💼 **Business Account System** — Users can apply to upgrade to a verified Business Account
- 🖼️ **Portfolio Showcase** — Each professional has a dedicated work gallery
- 💬 **Real-Time Chat** — Live messaging between users and professionals
- 🔔 **Push Notifications** — Real-time in-app notification system
- 🌐 **REST API** — Full API coverage for mobile/frontend integration

---

## 👥 Role System

| Role | Description |
|------|-------------|
| **Super Admin** | Full platform control — manages admins and all platform data |
| **Admin** | Reviews business account applications, manages users and content |
| **User** | Default role on registration — can apply for a Business Account |

### Business Account Flow
```
Register → User Account → Apply for Business Account → Admin Review → Verified Professional
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.2 |
| Authentication | Laravel Sanctum |
| AI Verification | Google Gemini API |
| Real-Time | Pusher, Laravel Broadcasting |
| Location | GPS coordinates / Location tracking |
| Database | MySQL |

---

## 🚀 Getting Started

### Requirements

- PHP 8.2+
- Composer
- MySQL
- Node.js & npm

### Installation

```bash
# Clone the repository
git clone https://github.com/Mohamad-Kahwaji/Skillify.git
cd Skillify

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy environment file and configure
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Build frontend assets
npm run build

# Start the server
php artisan serve
```

### Environment Setup

Copy `.env.example` to `.env` and fill in:

```env
# Database
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Pusher (Real-Time)
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

# Google Gemini (AI Verification)
GEMINI_API_KEY=
```

---

## 📁 Project Structure

```
app/
├── Http/Controllers/     # API controllers
├── Models/               # Eloquent models
├── Notifications/        # Real-time notifications
resources/
├── views/                # Blade templates
routes/
├── api.php               # API routes
├── web.php               # Web routes
```

---

## 📌 API Overview

Key API endpoints:

- Auth (register, login, logout)
- Business Account (apply, review, approve/reject)
- Professionals (search by location, view portfolio)
- Portfolio (add/update work gallery)
- Chat (conversations, messages)
- Notifications (list, mark as read)
- Admin (manage users, applications, platform stats)

---

## 👨‍💻 Author

**Mohamad Kahwaji** — Laravel Backend Developer  
[GitHub](https://github.com/Mohamad-Kahwaji) · [LinkedIn](https://linkedin.com/in/mohamad-kahwaji)

---

## 📄 License

This project is open-source under the [MIT License](LICENSE).
