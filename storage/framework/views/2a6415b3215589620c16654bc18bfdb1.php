<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByteLearn - Peer-led Micro Learning Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        header {
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        nav a {
            text-decoration: none;
            color: #333;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #667eea;
        }

        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6rem 2rem;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 4rem 2rem;
            background: white;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features h2 {
            grid-column: 1 / -1;
            text-align: center;
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #333;
        }

        .feature-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .feature-card h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            nav ul {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <header>
        <nav>
            <div class="logo">ByteLearn</div>
            <ul>
                <li><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li><a href="<?php echo e(route('courses.index')); ?>">Courses</a></li>
                <li><a href="<?php echo e(route('login')); ?>">Login</a></li>
                <li><a href="<?php echo e(route('register')); ?>">Register</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to ByteLearn</h1>
            <p>A peer-led micro learning platform that empowers instructors and students</p>
            <div class="hero-buttons">
                <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Get Started</a>
                <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-secondary">Browse Courses</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2>Why Choose ByteLearn?
        <br> <p>Experience the future of online education with our innovative features</p> </h2>
        


        <div class="feature-card">
            <h3>📚 Structured Learning</h3>
            <p>Create and manage organized courses with sequential lessons and comprehensive content.</p>
        </div>

        <div class="feature-card">
            <h3>✅ Interactive Quizzes</h3>
            <p>Test your knowledge with AI-generated quizzes and get immediate feedback.</p>
        </div>

        <div class="feature-card">
            <h3>🤖 AI Learning Assistant</h3>
            <p>Get instant answers to your questions with our context-aware chatbot.</p>
        </div>

        <div class="feature-card">
            <h3>💬 Community Learning</h3>
            <p>Engage in discussions, share knowledge, and learn from peers.</p>
        </div>

        <div class="feature-card">
            <h3>📝 Personal Notes</h3>
            <p>Take notes, bookmark content, and organize your learning journey.</p>
        </div>

        <div class="feature-card">
            <h3>🏆 Certificates</h3>
            <p>Earn verifiable certificates upon course completion to showcase your achievements.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 ByteLearn. All rights reserved.</p>
    </footer>
</body>
</html>
<?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\ByteLearn\resources\views/welcome.blade.php ENDPATH**/ ?>