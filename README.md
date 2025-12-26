# ByteLearn - A PeerLed Micro Learning Platform

A comprehensive web-based learning platform built with Laravel MVC that helps instructors create structured courses and supports students with interactive learning features.

## Features

### Core Learning Features
- **Courses**: Instructors create courses with multiple lessons including video, text, and external resources
- **Sequential Learning**: Lessons must be completed in order before unlocking the next
- **Progress Tracking**: Visual progress bars and completion status for each lesson
- **Certificates**: Auto-generated PDF certificates upon course completion

### Student Features
- Course browsing and enrollment
- Personalized dashboard with enrolled courses
- Continue Learning functionality
- Completion history tracking
- Leaderboard for top learners

### Instructor Features
- Create, edit, and publish courses
- Manage student enrollments
- View completion rates and learner statistics
- Monitor course engagement

### Interactive Learning
- Lesson-specific Q&A sections
- Peer-led discussion threads
- Student comments and notes on lessons
- Quizzes with multiple-choice and true/false formats

### AI-Powered Features
- **AI Auto-Generated Quizzes**: Automatic quiz generation from lesson content
- **Learning Assistant Chatbot**: RAG-based chatbot for context-aware answers about course material
- **Practice Quiz Generation**: Students can request AI-generated practice questions

## Tech Stack

- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: HTML5, CSS3
- **Database**: MySQL 8.0+
- **PDF Generation**: DomPDF
- **ORM**: Eloquent

## Project Structure

```
ByteLearn/
├── app/
│   ├── Models/              # Eloquent Models
│   ├── Http/
│   │   ├── Controllers/     # MVC Controllers
│   │   └── Middleware/      # Authentication & Authorization
│   └── Services/            # Business Logic
├── resources/
│   └── views/               # Blade templates
├── database/
│   └── migrations/          # Database migrations
├── public/
│   ├── css/                 # Stylesheets
│   ├── js/                  # JavaScript
│   └── images/              # Static images
├── routes/
│   └── web.php              # Web routes
└── storage/
    └── uploads/             # User uploads
```

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Node.js (optional, for frontend build tools)

### Setup Steps

1. **Clone/Setup Project**
   ```bash
   cd ByteLearn
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   - Update `.env` with your MySQL credentials
   ```
   DB_DATABASE=bytelearn
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```
   
   Access the application at `http://localhost:8000`

## Database Models

### Core Models
- **User**: Student and Instructor accounts (with role)
- **Course**: Course information and metadata
- **Lesson**: Individual lesson content within courses
- **Enrollment**: Student course enrollments
- **Progress**: Track lesson completion status
- **Quiz**: Quiz questions and answers
- **Discussion**: Q&A threads and comments
- **Certificate**: Course completion certificates
- **ChatMessage**: AI chatbot interactions

## API Routes

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `GET /api/profile` - Get user profile

### Courses
- `GET /api/courses` - List all courses
- `POST /api/courses` - Create course (Instructor)
- `GET /api/courses/{id}` - Course details
- `POST /api/courses/{id}/enroll` - Enroll in course

### Lessons
- `GET /api/courses/{courseId}/lessons` - Course lessons
- `POST /api/lessons/{id}/complete` - Mark lesson complete
- `GET /api/lessons/{id}/progress` - Lesson progress

### Quizzes
- `GET /api/quizzes/{lessonId}` - Get quiz questions
- `POST /api/quizzes/{quizId}/submit` - Submit quiz answers
- `POST /api/quizzes/generate` - Generate AI quiz

### AI Chatbot
- `POST /api/chat` - Send message to learning assistant

## Key Functions

### User Authentication
- Secure password hashing using bcrypt
- Role-based access control (Student/Instructor)
- Protected routes using middleware

### Progress Tracking
- Automatic lesson completion detection
- Progress percentage calculation
- Completion certificate generation

### AI Features
- Quiz question generation from content
- Context-aware chatbot using RAG
- Practice question generation

## Configuration

### Database
Configure MySQL connection in `.env`:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bytelearn
DB_USERNAME=root
DB_PASSWORD=password
```

### Mail (Optional)
For email notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

## Development Guidelines

### MVC Structure
- **Models**: Database entities in `app/Models`
- **Views**: Blade templates in `resources/views`
- **Controllers**: Route handlers in `app/Http/Controllers`
- **Services**: Business logic in `app/Services`

### Code Style
- Follow PSR-12 coding standards
- Use Eloquent ORM for database queries
- Implement SOLID principles
- Use dependency injection

### Security
- Always validate and sanitize user input
- Use prepared statements (Eloquent ORM handles this)
- Implement CSRF protection
- Use authorization gates for sensitive operations

## Performance Optimization

- Database query optimization with eager loading
- Caching for frequently accessed data
- Pagination for large datasets
- Proper indexing on database tables

## Scalability

- Modular service-oriented architecture
- Easy to add new features without major redesign
- Support for multiple database backends
- Queue system for background tasks

## Support & Documentation

For detailed documentation on each feature, refer to the specific controller and model files in the project structure.

## License

MIT License - feel free to use this project for learning purposes.
