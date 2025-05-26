# Content Scheduler

A full-stack application for scheduling and managing social media content across multiple platforms.

## Features

- 🔐 User Authentication (Login/Register)
- 📝 Create and Schedule Posts
- 🔄 Multiple Platform Integration
- 📊 Analytics Dashboard
- ⚙️ Platform Settings Management
- 📋 Activity Logs
- 🎯 Post Management

## Tech Stack

### Frontend
- React.js
- React Router for navigation
- React Bootstrap for UI components
- Axios for API requests
- Context API for state management

### Backend
- Laravel PHP Framework
- MySQL Database
- RESTful API Architecture

## Prerequisites

Before you begin, ensure you have the following installed:
- Node.js (v14 or higher)
- PHP (v8.0 or higher)
- Composer
- MySQL
- Git

## Installation

### Backend Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd content-scheduler
```

2. Install PHP dependencies:
```bash
composer install
```

3. Create and configure your `.env` file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in `.env`:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=content_scheduler
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations:
```bash
php artisan migrate fresh --seed
```

7. Start the Laravel development server:
```bash
php artisan serve
```

### Frontend Setup

1. Navigate to the frontend directory:
```bash
cd content-ui
```

2. Install dependencies:
```bash
npm install
```

3. Create `.env` file:
```bash
cp .env.example .env
```

4. Configure your API URL in `.env`:
```
VITE_API_URL=http://localhost:8000/api
```

5. Start the development server:
```bash
npm run dev
```

## Usage

1. Access the application at `http://localhost:3000`
2. Register a new account or login with existing credentials
3. Navigate through the application using the top navigation bar
4. Create and schedule posts using the Create Post form
5. Manage your platforms in the Settings section
6. View analytics and activity logs

## Project Structure

```
content-scheduler/
├── app/                    # Laravel backend
│   ├── Http/              # Controllers and Middleware
│   ├── Models/            # Database Models
│   └── Services/          # Business Logic
├── content-ui/            # React frontend
│   ├── src/
│   │   ├── components/    # React Components
│   │   ├── api/          # API Integration
│   │   └── router.jsx    # Route Configuration
└── database/             # Database Migrations
```

## API Endpoints

### Authentication
- POST `/api/login` - User login
- POST `/api/register` - User registration
- POST `/api/logout` - User logout

### Posts
- GET `/api/posts` - List all posts
- POST `/api/posts` - Create new post
- GET `/api/posts/{id}` - Get post details
- PUT `/api/posts/{id}` - Update post
- DELETE `/api/posts/{id}` - Delete post

### Platforms
- GET `/api/platforms` - List all platforms
- POST `/api/platforms` - Add new platform
- PUT `/api/platforms/{id}` - Update platform
- DELETE `/api/platforms/{id}` - Delete platform

## Development

### Running Tests
```bash
# Backend tests
php artisan test

# Frontend tests
npm test
```

### Code Style
```bash
# Backend
composer run lint

# Frontend
npm run lint
```

## Deployment

1. Build the frontend:
```bash
cd content-ui
npm run build
```

2. Configure your production environment variables
3. Set up your web server (Apache/Nginx)
4. Deploy the Laravel application
5. Configure SSL certificates
6. Set up database backups

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Security

- All API endpoints are protected with authentication
- Passwords are hashed using bcrypt
- CSRF protection enabled
- Input validation on all forms
- XSS protection
- Rate limiting on API endpoints

## Support

For support, email support@contentscheduler.com or create an issue in the repository.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
