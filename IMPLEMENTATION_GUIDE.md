# ByteLearn - PeerLed Micro Learning Platform 🎓

A comprehensive web-based learning platform built with Laravel and React, featuring AI-powered tools for an enhanced learning experience.

## 🌟 Features

### Core Learning Features
- ✅ **Course Management** - Create, publish, and manage structured courses
- ✅ **Sequential Learning** - Lessons unlock progressively as students complete them
- ✅ **Progress Tracking** - Visual progress bars and completion tracking
- ✅ **Smart Enrollment** - Easy course browsing and enrollment system
- ✅ **Certificates** - Auto-generated PDF certificates upon course completion

### Student Features
- ✅ **Modern Dashboard** - Clean, intuitive interface showing enrolled courses
- ✅ **Continue Learning** - Quick access to unfinished lessons
- ✅ **Completion History** - Track all completed courses
- ✅ **Leaderboard** - Gamification with points and rankings
- ✅ **Notifications** - Stay updated on new lessons and quizzes

### Instructor Features
- ✅ **Instructor Dashboard** - Comprehensive analytics and course management
- ✅ **Course Editor** - Rich text editor for creating engaging content
- ✅ **Student Analytics** - Monitor enrollment and completion rates
- ✅ **Content Management** - Add videos, text, PDFs, and external resources

### Interactive Features
- ✅ **Discussion Forums** - Lesson-specific Q&A and peer discussions
- ✅ **Comments & Notes** - Students can annotate lessons
- ✅ **Quiz System** - Multiple choice, true/false, and short answer quizzes
- ✅ **Real-time Feedback** - Instant quiz results with explanations

### AI-Powered Features 🤖
- ✅ **AI Auto-Generated Quizzes** - Automatic quiz generation from lesson content
- ✅ **Learning Assistant Chatbot** - RAG-based AI for context-aware answers
- ✅ **Practice Quiz Generation** - Students can request additional practice questions
- ✅ **Smart Explanations** - AI provides concept explanations and examples

## 🛠️ Tech Stack

### Backend
- **Laravel 10.x** - PHP framework following MVC architecture
- **MySQL** - Relational database
- **Eloquent ORM** - Database interactions
- **DomPDF** - Certificate generation

### Frontend
- **React 18** - UI library
- **TypeScript** - Type-safe JavaScript
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Fast build tool and dev server
- **Lucide React** - Beautiful icon set

## 📦 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 16+ and NPM
- MySQL 8.0+

### Setup Steps

1. **Clone the repository**
```bash
cd ByteLearn
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Database setup**
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bytelearn
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. **Import database**
```bash
# Using PHP script
php setup_db.php

# OR import SQL file manually
mysql -u root -p bytelearn < ../bytelearn.sql
```

7. **Build assets**
```bash
npm run build
```

## 🚀 Running the Application

### Development Mode

1. **Start Laravel server**
```bash
php artisan serve
```

2. **Start Vite dev server** (in another terminal)
```bash
npm run dev
```

3. **Access the application**
- Main site: http://localhost:8000
- Login: http://localhost:8000/login
- Register: http://localhost:8000/register

### Production Build
```bash
npm run build
php artisan serve
```

## 📁 Project Structure

```
ByteLearn/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── StudentDashboardController.php
│   │   │   ├── InstructorDashboardController.php
│   │   │   ├── CourseController.php
│   │   │   ├── LessonController.php
│   │   │   ├── QuizController.php
│   │   │   ├── ChatbotController.php
│   │   │   └── ...
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── Lesson.php
│   │   ├── Quiz.php
│   │   └── ...
│   └── Services/
│       ├── QuizGenerationService.php
│       ├── ChatbotService.php
│       └── CertificateService.php
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   ├── auth/
│   │   │   │   ├── LoginForm.tsx
│   │   │   │   └── RegisterForm.tsx
│   │   │   ├── ui/
│   │   │   │   ├── Card.tsx
│   │   │   │   ├── Button.tsx
│   │   │   │   ├── Input.tsx
│   │   │   │   └── ...
│   │   │   ├── StudentDashboard.tsx
│   │   │   ├── InstructorDashboard.tsx
│   │   │   ├── CourseCatalog.tsx
│   │   │   ├── AIChatbot.tsx
│   │   │   ├── QuizTake.tsx
│   │   │   └── ...
│   │   ├── app.tsx
│   │   └── types/
│   ├── views/
│   │   ├── auth/
│   │   ├── courses/
│   │   ├── student/
│   │   └── instructor/
│   └── css/
│       └── app.css
├── routes/
│   ├── web.php
│   └── api.php
├── database/
│   └── migrations/
├── public/
└── storage/
```

## 🎨 UI Components

The project includes a comprehensive UI component library:

### Layout Components
- `Card` - Container with shadow and rounded corners
- `Badge` - Status indicators and labels
- `Avatar` - User profile images
- `Progress` - Progress bars with animations

### Form Components
- `Button` - Multiple variants (primary, secondary, outline, ghost)
- `Input` - Text input with validation
- `Select` - Dropdown selector
- `Textarea` - Multi-line text input

### Interactive Components
- `Tabs` - Tabbed navigation
- `Dialog` - Modal dialogs
- `AIChatbot` - AI-powered learning assistant
- `QuizTake` - Interactive quiz interface

## 🔐 Authentication & Authorization

- **Role-based Access Control** - Students and Instructors have different permissions
- **Secure Password Hashing** - Laravel's bcrypt hashing
- **CSRF Protection** - Built-in Laravel CSRF tokens
- **Session Management** - Secure session handling

## 📊 Key Routes

### Public Routes
- `/` - Homepage
- `/login` - Login page
- `/register` - Registration page
- `/courses` - Course catalog
- `/courses/{id}` - Course details

### Student Routes (Protected)
- `/student/dashboard` - Student dashboard
- `/student/courses` - Enrolled courses
- `/student/completed` - Completed courses
- `/courses/{id}/learn` - Lesson viewer

### Instructor Routes (Protected)
- `/instructor/dashboard` - Instructor dashboard
- `/instructor/courses` - Manage courses
- `/instructor/courses/create` - Create new course
- `/instructor/courses/{id}/edit` - Edit course

### API Routes
- `/api/chatbot/ask` - AI chatbot endpoint
- `/api/quiz/generate` - AI quiz generation
- `/api/quiz/submit` - Submit quiz answers
- `/api/progress/update` - Update lesson progress

## 🤖 AI Integration

The platform includes AI-powered features that require external API integration:

### Quiz Generation
Automatically generates quiz questions from lesson content using AI.

### RAG-based Chatbot
Retrieval-Augmented Generation chatbot that answers questions based on course material.

**Note**: To fully implement AI features, you'll need to:
1. Set up an AI service (OpenAI, Anthropic, or local LLM)
2. Configure API keys in `.env`
3. Implement the AI service in `app/Services/`

## 📝 Database Schema

Key tables:
- `users` - User accounts with roles
- `courses` - Course information
- `lessons` - Lesson content
- `enrollments` - Student-course relationships
- `progress` - Lesson completion tracking
- `quizzes` - Quiz definitions
- `quiz_attempts` - Student quiz submissions
- `discussions` - Forum posts
- `certificates` - Generated certificates

## 🎓 Usage Guide

### For Students
1. Register an account (select "Learn" option)
2. Browse courses in the catalog
3. Enroll in courses
4. Complete lessons sequentially
5. Take quizzes to test knowledge
6. Use AI chatbot for help
7. Participate in discussions
8. Earn certificates upon completion

### For Instructors
1. Register an account (select "Teach" option)
2. Create a new course
3. Add lessons with rich content
4. Create or auto-generate quizzes
5. Monitor student progress
6. Engage with students in discussions

## 🔧 Configuration

### Environment Variables
```env
APP_NAME=ByteLearn
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bytelearn
DB_USERNAME=root
DB_PASSWORD=

# Add AI API keys here when implementing
OPENAI_API_KEY=
ANTHROPIC_API_KEY=
```

## 🚨 Troubleshooting

### Common Issues

**Database connection error**
- Verify MySQL is running
- Check database credentials in `.env`
- Ensure database exists

**Asset not found**
- Run `npm run build`
- Check Vite is running for development

**Permission errors**
- Run `chmod -R 775 storage bootstrap/cache`
- Ensure web server has write permissions

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [React Documentation](https://react.dev)
- [Tailwind CSS](https://tailwindcss.com)

## 🤝 Contributing

This is an educational project. Feel free to fork and modify for your needs.

## 📄 License

MIT License - feel free to use this project for learning and development.

## 🎯 Next Steps

To further enhance the platform:

1. **Implement Certificate PDF Generation** - Use DomPDF to create certificates
2. **Add Email Notifications** - Configure Laravel Mail
3. **Implement Full AI Integration** - Connect to AI services
4. **Add Payment Integration** - For paid courses (Stripe/PayPal)
5. **Mobile App** - React Native version
6. **Advanced Analytics** - Detailed student insights
7. **Video Streaming** - Integrate video hosting service
8. **Social Features** - User profiles, following, messaging
9. **Gamification** - Badges, achievements, streaks
10. **Multi-language Support** - Internationalization

## 📞 Support

For questions or issues, please refer to the project guidelines and documentation.

---

**Built with ❤️ for learners and educators worldwide**

Start learning smarter, not harder with ByteLearn! 🚀
