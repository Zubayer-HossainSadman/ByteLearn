

<?php $__env->startSection('styles'); ?>
<style>
    /* Welcome Section */
    .welcome-section {
        margin-bottom: 3rem;
    }

    .welcome-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .welcome-header h1 {
        font-size: 2.5rem;
        margin: 0 0 0.5rem 0;
        color: #1f2937;
    }

    .welcome-header p {
        color: #6b7280;
        margin: 0;
    }

    .browse-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .browse-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        padding: 1.75rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.5rem 0;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.95rem;
        margin: 0;
    }

    /* Continue Learning Section */
    .continue-section {
        margin-bottom: 3rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-header h2 {
        font-size: 1.75rem;
        margin: 0;
        color: #1f2937;
    }

    .view-all-link {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .view-all-link:hover {
        color: #2563eb;
    }

    /* Course Card */
    .course-card-large {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: grid;
        grid-template-columns: 250px 1fr;
        transition: box-shadow 0.3s;
    }

    .course-card-large:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .course-thumbnail {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        height: 200px;
        position: relative;
        overflow: hidden;
    }

    .course-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .course-info {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .course-header {
        margin-bottom: 1rem;
    }

    .course-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.5rem 0;
    }

    .course-instructor {
        color: #6b7280;
        margin: 0;
        font-size: 0.95rem;
    }

    .course-meta {
        display: flex;
        gap: 2rem;
        margin: 1.5rem 0;
        padding: 1rem 0;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .progress-container {
        margin-bottom: 1.5rem;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .progress-label {
        font-size: 0.9rem;
        color: #6b7280;
    }

    .progress-percent {
        font-weight: 600;
        color: #1f2937;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        transition: width 0.3s;
    }

    .course-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-primary {
        flex: 1;
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background: #2563eb;
    }

    .btn-secondary {
        flex: 1;
        background: white;
        color: #3b82f6;
        border: 2px solid #3b82f6;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        background: #f0f9ff;
    }

    /* Completed Courses Section */
    .completed-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .completed-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s;
    }

    .completed-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .completed-header {
        margin-bottom: 1rem;
    }

    .completed-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .completed-instructor {
        color: #6b7280;
        font-size: 0.9rem;
        margin: 0;
    }

    .completion-date {
        color: #6b7280;
        font-size: 0.85rem;
        margin: 1rem 0;
    }

    .rating {
        display: flex;
        gap: 0.25rem;
        margin: 1rem 0;
    }

    .star {
        font-size: 1.25rem;
        color: #fbbf24;
    }

    .star.empty {
        color: #e5e7eb;
    }

    .certificate-btn {
        width: 100%;
        background: white;
        color: #3b82f6;
        border: 2px solid #e5e7eb;
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .certificate-btn:hover {
        border-color: #3b82f6;
    }

    /* AI Assistant Section */
    .ai-section {
        background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-top: 3rem;
    }

    .ai-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .ai-icon {
        font-size: 3rem;
    }

    .ai-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .ai-description {
        color: rgba(255, 255, 255, 0.9);
        margin: 1rem 0;
        line-height: 1.6;
    }

    .btn-ai {
        background: white;
        color: #9333ea;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.95rem;
        transition: all 0.3s;
        margin-top: 1rem;
    }

    .btn-ai:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Notification Bell Button */
    .notification-bell {
        position: relative;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .notification-bell:hover {
        transform: scale(1.1);
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
    }

    /* Notification Popup */
    .notification-popup {
        position: fixed;
        top: 80px;
        right: 20px;
        width: 350px;
        max-height: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
        flex-direction: column;
        animation: slideIn 0.3s ease-out;
    }

    .notification-popup.active {
        display: flex;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notification-popup-header {
        padding: 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        color: #1f2937;
    }

    .notification-popup-content {
        flex: 1;
        overflow-y: auto;
        padding: 0;
    }

    .notification-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s;
    }

    .notification-item:hover {
        background-color: #f9fafb;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-title {
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
        font-size: 0.95rem;
    }

    .notification-message {
        color: #6b7280;
        font-size: 0.85rem;
        margin: 0 0 0.5rem 0;
        line-height: 1.4;
    }

    .notification-time {
        color: #9ca3af;
        font-size: 0.75rem;
        margin: 0;
    }

    .notification-empty {
        padding: 2rem;
        text-align: center;
        color: #9ca3af;
    }

    .notification-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 999;
        display: none;
    }

    .notification-overlay.active {
        display: block;
    }

    .empty-state {
        background: white;
        padding: 3rem;
        border-radius: 12px;
        text-align: center;
        color: #6b7280;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .course-card-large {
            grid-template-columns: 1fr;
        }

        .course-thumbnail {
            height: 150px;
        }

        .welcome-header {
            flex-direction: column;
        }

        .browse-btn {
            margin-top: 1rem;
        }

        .notification-popup {
            width: calc(100% - 40px);
            right: 20px;
            left: 20px;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-header">
            <div>
                <h1>Welcome back, <?php echo e($student->name ?? 'Student'); ?>! </h1>
                <p>Let's continue your learning journey</p>
            </div>
            <a href="<?php echo e(route('courses.index') ?? '#'); ?>" class="browse-btn">
                 Browse Courses
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"></div>
            <p class="stat-number"><?php echo e($ongoingCourses); ?></p>
            <p class="stat-label">Courses Enrolled</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"></div>
            <p class="stat-number"><?php echo e($completedCourses); ?></p>
            <p class="stat-label">Courses Completed</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"></div>
            <p class="stat-number"><?php echo e($learningStreak); ?></p>
            <p class="stat-label">Learning Streak</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏆</div>
            <p class="stat-number"><?php echo e($certificatesEarned); ?></p>
            <p class="stat-label">Certificates Earned</p>
        </div>
    </div>

    <!-- Continue Learning Section -->
    <?php if($enrolledCourses->count() > 0): ?>
        <div class="continue-section">
            <div class="section-header">
                <h2>Continue Learning</h2>
                <a href="#" class="view-all-link">View All →</a>
            </div>

            <?php $__currentLoopData = $enrolledCourses->take(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="course-card-large">
                    <div class="course-thumbnail">
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                            📖
                        </div>
                    </div>
                    <div class="course-info">
                        <div>
                            <div class="course-header">
                                <h3 class="course-title"><?php echo e($enrollment->course->title); ?></h3>
                                <p class="course-instructor">by <?php echo e($enrollment->course->instructor->name ?? 'Instructor'); ?></p>
                            </div>

                            <div class="course-meta">
                                <div class="meta-item">
                                    📖 <?php echo e($enrollment->course->lessons->count() ?? 0); ?>/48 lessons
                                </div>
                                <div class="meta-item">
                                    ⏱️ 2 hours ago
                                </div>
                            </div>

                            <div class="progress-container">
                                <div class="progress-header">
                                    <span class="progress-label">Progress</span>
                                    <span class="progress-percent"><?php echo e($courseProgress[$enrollment->course->id] ?? 0); ?>%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo e($courseProgress[$enrollment->course->id] ?? 0); ?>%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="course-actions">
                            <button class="btn-primary">▶ Continue: React Hooks Deep Dive</button>
                            <button class="btn-secondary">Take Quiz</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="empty-state" style="margin-bottom: 3rem;">
            <p>You haven't enrolled in any courses yet.</p>
            <a href="<?php echo e(route('courses.index') ?? '#'); ?>" class="browse-btn" style="margin-top: 1rem; display: inline-block;">Browse Courses</a>
        </div>
    <?php endif; ?>

    <!-- Completed Courses Section -->
    <?php if($completedCourses > 0): ?>
        <div style="margin-bottom: 3rem;">
            <div class="section-header">
                <h2>Completed Courses</h2>
            </div>
            <div class="completed-grid">
                <div class="completed-card">
                    <div class="completed-header">
                        <h3 class="completed-title">JavaScript Fundamentals</h3>
                        <p class="completed-instructor">by John Smith</p>
                    </div>
                    <p class="completion-date">Completed on Dec 10, 2024</p>
                    <div class="rating">
                        <span class="star">⭐</span>
                        <span class="star">⭐</span>
                        <span class="star">⭐</span>
                        <span class="star">⭐</span>
                        <span class="star">⭐</span>
                    </div>
                    <button class="certificate-btn">🎖️ View Certificate</button>
                </div>
                <div class="completed-card">
                    <div class="completed-header">
                        <h3 class="completed-title">Introduction to Python</h3>
                        <p class="completed-instructor">by Lisa Anderson</p>
                    </div>
                    <p class="completion-date">Completed on Nov 25, 2024</p>
                    <div class="rating">
                        <span class="star">⭐</span>
                        <span class="star">⭐</span>
                        <span class="star">⭐</span>
                        <span class="star">⭐</span>
                        <span class="star empty">☆</span>
                    </div>
                    <button class="certificate-btn">🎖️ View Certificate</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- AI Learning Assistant Section -->
    <div class="ai-section">
        <div class="ai-header">
            <div class="ai-icon">🎓</div>
            <h3 class="ai-title">AI Learning Assistant</h3>
        </div>
        <p class="ai-description">Get instant help with your courses. Ask questions about lesson content, generate practice quizzes, or clarify concepts.</p>
        <button class="btn-ai">Start Conversation</button>
    </div>

</div>

<!-- Notification Overlay -->
<div class="notification-overlay" id="notificationOverlay"></div>

<!-- Notification Popup -->
<div class="notification-popup" id="notificationPopup">
    <div class="notification-popup-header">
        Notifications
    </div>
    <div class="notification-popup-content">
        <?php if($notifications->count() > 0): ?>
            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="notification-item">
                    <p class="notification-title"><?php echo e($notification->title); ?></p>
                    <p class="notification-message"><?php echo e($notification->message); ?></p>
                    <p class="notification-time"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="notification-empty">
                Nothing to Show
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Notification Bell Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const bellButton = document.getElementById('notificationBell');
        const popup = document.getElementById('notificationPopup');
        const overlay = document.getElementById('notificationOverlay');

        if (bellButton) {
            bellButton.addEventListener('click', function(e) {
                e.stopPropagation();
                popup.classList.toggle('active');
                overlay.classList.toggle('active');
            });

            overlay.addEventListener('click', function() {
                popup.classList.remove('active');
                overlay.classList.remove('active');
            });

            popup.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\ByteLearn\resources\views/student/dashboard.blade.php ENDPATH**/ ?>