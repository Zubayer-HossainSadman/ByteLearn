import { BookOpen, Play, Clock, CheckCircle2, Trophy, Bell, TrendingUp, Award, MessageSquare, Bot, ArrowRight, Star, BarChart3 } from 'lucide-react';
import { ImageWithFallback } from './ImageWithFallback';

interface StudentDashboardProps {
    onNavigate?: (page: string) => void;
    user?: {
        id: number;
        name: string;
        email: string;
        role: 'student' | 'instructor' | null;
    } | null;
    data?: {
        enrolledCourses?: any[];
        completedCourses?: any[];
        notifications?: any[];
        stats?: {
            ongoingCourses: number;
            completedCourses: number;
            learningStreak: number;
            certificatesEarned: number;
        };
        courseProgress?: Record<number, number>;
        leaderboard?: {
            id: number;
            name: string;
            points: number;
            streak: number;
            lessonsCompleted: number;
        }[];
        currentUserPoints?: number;
    };
}

export function StudentDashboard({ onNavigate, user, data }: StudentDashboardProps) {
    // Use data passed from Laravel
    const enrolledCourses = data?.enrolledCourses || [];

    const completedCourses = data?.completedCourses || [];

    const notifications = data?.notifications || [];

    // Process leaderboard data
    const rawLeaderboard = data?.leaderboard || [];
    // If empty (e.g. fresh db), add current user placeholder if logged in
    const displayLeaderboard = rawLeaderboard.length > 0 ? rawLeaderboard.map((item, index) => ({
        rank: index + 1,
        name: item.id === user?.id ? "You" : item.name,
        points: item.points,
        avatar: item.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase(),
        isCurrentUser: item.id === user?.id,
    })) : [
        { rank: 1, name: "You", points: data?.currentUserPoints || 0, avatar: user?.name?.split(' ').map((n: string) => n[0]).join('').substring(0, 2).toUpperCase() || "ME", isCurrentUser: true }
    ];

    const stats = [
        { label: "Courses Enrolled", value: data?.stats?.ongoingCourses?.toString() || "0", icon: <BookOpen className="w-5 h-5" />, color: "bg-blue-100 text-blue-600" },
        { label: "Courses Completed", value: data?.stats?.completedCourses?.toString() || "0", icon: <CheckCircle2 className="w-5 h-5" />, color: "bg-green-100 text-green-600" },
        { label: "Learning Streak", value: data?.stats?.learningStreak?.toString() || "0 days", icon: <TrendingUp className="w-5 h-5" />, color: "bg-purple-100 text-purple-600" },
        { label: "Certificates Earned", value: data?.stats?.certificatesEarned?.toString() || "0", icon: <Award className="w-5 h-5" />, color: "bg-orange-100 text-orange-600" }
    ];

    const userName = user?.name || 'Student';

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Dashboard Header */}
            <div className="bg-white border-b border-gray-200">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div className="flex items-center justify-between mb-6">
                        <div>
                            <h1 className="text-3xl font-bold mb-2">Welcome back, {userName}! 👋</h1>
                            <p className="text-gray-600">Let's continue your learning journey</p>
                        </div>
                        <button
                            onClick={() => onNavigate?.('courses')}
                            className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            <BookOpen className="w-5 h-5" />
                            Browse Courses
                        </button>
                    </div>

                    {/* Stats Cards */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        {stats.map((stat, index) => (
                            <div key={index} className="bg-gray-50 rounded-lg p-4">
                                <div className={`${stat.color} rounded-lg p-2 w-fit mb-3`}>
                                    {stat.icon}
                                </div>
                                <div className="text-2xl font-bold mb-1">{stat.value}</div>
                                <div className="text-sm text-gray-600">{stat.label}</div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div className="grid lg:grid-cols-3 gap-8">
                    {/* Main Content */}
                    <div className="lg:col-span-2 space-y-8">
                        {/* Continue Learning */}
                        <section>
                            <div className="flex items-center justify-between mb-6">
                                <h2 className="text-2xl font-bold">Continue Learning</h2>
                                <button className="text-blue-600 hover:text-blue-700 flex items-center gap-1 font-medium">
                                    View All
                                    <ArrowRight className="w-4 h-4" />
                                </button>
                            </div>
                            <div className="space-y-4">
                                {enrolledCourses.length === 0 ? (
                                    <div className="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                                        <p className="text-gray-500 mb-2">You haven't enrolled in any courses yet.</p>
                                        <button onClick={() => onNavigate?.('courses')} className="text-blue-600 font-medium hover:underline">
                                            Browse Courses →
                                        </button>
                                    </div>
                                ) : (
                                    enrolledCourses.map((course) => (
                                        <div key={course.id} className="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                                            <div className="flex flex-col sm:flex-row gap-4 p-5">
                                                <div className="relative w-full sm:w-48 h-32 flex-shrink-0 rounded-lg overflow-hidden">
                                                    <ImageWithFallback
                                                        src={course.image}
                                                        alt={course.title}
                                                        className="w-full h-full object-cover"
                                                    />
                                                    <div className="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer">
                                                        <div className="bg-white rounded-full p-3">
                                                            <Play className="w-6 h-6 text-blue-600" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <div className="flex items-start justify-between gap-4 mb-3">
                                                        <div>
                                                            <h3 className="text-lg font-semibold mb-1">{course.title}</h3>
                                                            <p className="text-gray-600 text-sm">by {course.instructor}</p>
                                                        </div>
                                                        <div className="text-right">
                                                            <div className="text-2xl font-bold text-blue-600">{course.progress}%</div>
                                                            <div className="text-xs text-gray-500">Complete</div>
                                                        </div>
                                                    </div>

                                                    {/* Progress Bar */}
                                                    <div className="mb-3">
                                                        <div className="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                            <div
                                                                className="h-full bg-blue-600 rounded-full transition-all"
                                                                style={{ width: `${course.progress}%` }}
                                                            ></div>
                                                        </div>
                                                    </div>

                                                    <div className="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                                        <div className="flex items-center gap-1">
                                                            <BookOpen className="w-4 h-4" />
                                                            <span>{course.completedLessons}/{course.totalLessons} lessons</span>
                                                        </div>
                                                        <div className="flex items-center gap-1">
                                                            <Clock className="w-4 h-4" />
                                                            <span>{course.lastAccessed}</span>
                                                        </div>
                                                    </div>

                                                    <div className="flex flex-wrap gap-3">
                                                        <a
                                                            href={`/student/continue/${course.id}`}
                                                            className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
                                                        >
                                                            <Play className="w-4 h-4" />
                                                            Continue Learning
                                                        </a>
                                                        {course.nextQuiz && (
                                                            <button className="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                                                Take Quiz
                                                            </button>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>
                        </section>

                        {/* Completed Courses */}
                        <section>
                            <h2 className="text-2xl font-bold mb-6">Completed Courses</h2>
                            <div className="grid md:grid-cols-2 gap-4">
                                {completedCourses.map((course) => (
                                    <div key={course.id} className="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                                        <div className="flex items-start justify-between mb-3">
                                            <div className="flex-1">
                                                <h3 className="text-lg font-semibold mb-1">{course.title}</h3>
                                                <p className="text-gray-600 text-sm mb-2">by {course.instructor}</p>
                                                <p className="text-xs text-gray-500">Completed on {course.completedDate}</p>
                                            </div>
                                            <CheckCircle2 className="w-6 h-6 text-green-500 flex-shrink-0" />
                                        </div>
                                        <div className="flex items-center gap-1 mb-4">
                                            {[...Array(5)].map((_, i) => (
                                                <Star
                                                    key={i}
                                                    className={`w-4 h-4 ${i < course.rating
                                                        ? 'fill-yellow-400 text-yellow-400'
                                                        : 'text-gray-300'
                                                        }`}
                                                />
                                            ))}
                                        </div>
                                        {course.certificate && (
                                            <button className="w-full px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                                                <Award className="w-4 h-4" />
                                                View Certificate
                                            </button>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </section>
                    </div>

                    {/* Sidebar */}
                    <div className="lg:col-span-1 space-y-6">
                        {/* Notifications */}
                        <section className="bg-white rounded-xl shadow-sm p-5">
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="text-lg font-semibold">Notifications</h3>
                                <div className="relative">
                                    <Bell className="w-5 h-5 text-gray-400" />
                                    {notifications.filter(n => n.unread).length > 0 && (
                                        <span className="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                                            {notifications.filter(n => n.unread).length}
                                        </span>
                                    )}
                                </div>
                            </div>
                            <div className="space-y-3">
                                {notifications.map((notification) => (
                                    <div
                                        key={notification.id}
                                        className={`p-3 rounded-lg border ${notification.unread ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200'
                                            }`}
                                    >
                                        <p className="text-sm mb-1">{notification.message}</p>
                                        <p className="text-xs text-gray-500">{notification.time}</p>
                                    </div>
                                ))}
                            </div>
                            <button className="w-full mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium">
                                View All Notifications
                            </button>
                        </section>

                        {/* Leaderboard */}
                        <section className="bg-white rounded-xl shadow-sm p-5">
                            <div className="flex items-center gap-2 mb-4">
                                <Trophy className="w-5 h-5 text-yellow-500" />
                                <h3 className="text-lg font-semibold">Leaderboard</h3>
                            </div>
                            <div className="space-y-3">
                                {displayLeaderboard.map((user) => (
                                    <div
                                        key={user.rank}
                                        className={`flex items-center gap-3 p-3 rounded-lg ${user.isCurrentUser ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50'
                                            }`}
                                    >
                                        <div className={`flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold ${user.rank === 1 ? 'bg-yellow-500 text-white' :
                                            user.rank === 2 ? 'bg-gray-400 text-white' :
                                                user.rank === 3 ? 'bg-orange-600 text-white' :
                                                    'bg-gray-300 text-gray-700'
                                            }`}>
                                            {user.rank}
                                        </div>
                                        <div className="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center text-sm font-medium">
                                            {user.avatar}
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <div className="text-sm font-medium truncate">{user.name}</div>
                                            <div className="text-xs text-gray-500">{user.points} pts</div>
                                        </div>
                                        {user.isCurrentUser && (
                                            <TrendingUp className="w-4 h-4 text-blue-600" />
                                        )}
                                    </div>
                                ))}
                            </div>
                        </section>

                        {/* Quick Actions */}
                        <section className="bg-white rounded-xl shadow-sm p-5">
                            <h3 className="text-lg font-semibold mb-4">Quick Actions</h3>
                            <div className="space-y-2">
                                <a href="/student/certificates" className="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 hover:from-blue-100 hover:to-cyan-100 rounded-lg text-left flex items-center gap-3 transition-colors border border-blue-200">
                                    <Award className="w-5 h-5 text-blue-600" />
                                    <span className="font-medium text-blue-700">My Certificates</span>
                                </a>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    );
}
