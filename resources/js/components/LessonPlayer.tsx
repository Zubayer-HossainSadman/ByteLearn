import React, { useEffect, useState, useRef } from 'react';
import axios from 'axios';
import Plyr from 'plyr';
import 'plyr/dist/plyr.css';
import { Button } from './Button';
import { Course, Lesson } from '../types';

interface LessonPlayerProps {
    courseId: number;
    initialLessonId?: number;
    user?: any;
    onNavigate: (page: string) => void;
}

export const LessonPlayer: React.FC<LessonPlayerProps> = ({ courseId, initialLessonId, user, onNavigate }) => {
    const [course, setCourse] = useState<Course | null>(null);
    const [loading, setLoading] = useState(true);
    const [currentLesson, setCurrentLesson] = useState<Lesson | null>(null);
    const [isVideoCompleted, setIsVideoCompleted] = useState(false);
    const playerRef = useRef<HTMLDivElement>(null);
    const isInstructor = user?.role === 'instructor';

    useEffect(() => {
        const fetchCourse = async () => {
            try {
                const response = await axios.get(`/api/courses/${courseId}`);
                setCourse(response.data);

                if (response.data.lessons && response.data.lessons.length > 0) {
                    const sortedLessons = response.data.lessons.sort((a: Lesson, b: Lesson) => a.sequence_number - b.sequence_number);
                    if (initialLessonId) {
                        const found = sortedLessons.find((l: Lesson) => l.id === initialLessonId);
                        setCurrentLesson(found || sortedLessons[0]);
                    } else {
                        setCurrentLesson(sortedLessons[0]);
                    }
                }
                setLoading(false);
            } catch (error) {
                console.error('Error fetching course:', error);
                setLoading(false);
            }
        };
        fetchCourse();
    }, [courseId, initialLessonId]);

    // Initialize Plyr
    useEffect(() => {
        let player: any = null;
        if (currentLesson && currentLesson.video_url && playerRef.current) {
            setIsVideoCompleted(false);
            const videoId = getYouTubeId(currentLesson.video_url);

            if (videoId) {
                player = new Plyr(playerRef.current, {
                    controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
                    youtube: { noCookie: true, rel: 0, showinfo: 0, modestbranding: 1 }
                });

                player.on('ended', () => setIsVideoCompleted(true));
                player.on('play', () => setIsVideoCompleted(false));
            }
        }
        return () => {
            if (player) player.destroy();
        };
    }, [currentLesson]);

    const getYouTubeId = (url: string) => {
        if (!url) return null;
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    };

    if (loading) return <div className="p-12 text-center text-gray-500">Loading lesson...</div>;
    if (!course) return <div className="p-12 text-center text-red-500">Course not found.</div>;
    if (!currentLesson) return <div className="p-12 text-center text-gray-500">No lessons available.</div>;

    const sortedLessons = [...(course.lessons || [])].sort((a, b) => a.sequence_number - b.sequence_number);
    const currentIndex = sortedLessons.findIndex(l => l.id === currentLesson.id);
    const prevLesson = currentIndex > 0 ? sortedLessons[currentIndex - 1] : null;
    const nextLesson = currentIndex < sortedLessons.length - 1 ? sortedLessons[currentIndex + 1] : null;

    const handleLessonChange = (lesson: Lesson) => {
        setCurrentLesson(lesson);
    };

    const updateProgress = async () => {
        if (isInstructor) return; // Don't track progress for instructors
        try {
            const total = sortedLessons.length;
            const finishedCount = currentIndex + 1;
            const progressPercent = Math.round((finishedCount / total) * 100);
            await axios.post('/api/enrollments/progress', {
                course_id: courseId,
                progress: progressPercent
            });
        } catch (err) { console.error(err); }
    };

    return (
        <div className="flex h-[calc(100vh-64px)] overflow-hidden bg-white">
            {/* Sidebar */}
            <div className="w-80 border-r border-gray-200 bg-gray-50 overflow-y-auto flex-shrink-0">
                <div className="p-6 border-b border-gray-200">
                    <button onClick={() => onNavigate(isInstructor ? 'course-editor' : 'student-dashboard')} className="text-sm text-gray-500 hover:text-gray-900 mb-2 block">
                        ← Back to {isInstructor ? 'Editor' : 'Dashboard'}
                    </button>
                    <h2 className="text-lg font-bold text-gray-900 leading-tight">{course.title}</h2>
                </div>
                <div className="p-3 space-y-1">
                    {sortedLessons.map((lesson, idx) => (
                        <div
                            key={lesson.id}
                            onClick={() => handleLessonChange(lesson)}
                            className={`p-3 rounded-lg cursor-pointer transition-colors flex items-center gap-3 ${lesson.id === currentLesson.id
                                ? 'bg-indigo-600 text-white shadow-sm'
                                : 'hover:bg-gray-200 text-gray-700'
                                }`}
                        >
                            <span className={`text-xs font-mono opacity-80 ${lesson.id === currentLesson.id ? 'text-indigo-200' : 'text-gray-500'}`}>
                                {String(idx + 1).padStart(2, '0')}.
                            </span>
                            <span className="text-sm font-medium truncate">{lesson.title}</span>
                        </div>
                    ))}
                </div>
            </div>

            {/* Content */}
            <div className="flex-1 overflow-y-auto p-8 lg:p-12">
                <div className="max-w-4xl mx-auto">
                    <h1 className="text-3xl font-bold text-gray-900 mb-6">{currentLesson.title}</h1>

                    {/* Video */}
                    {currentLesson.video_url && (
                        <div className="mb-8 relative rounded-xl overflow-hidden shadow-lg bg-black aspect-video">
                            {getYouTubeId(currentLesson.video_url) ? (
                                <div className="plyr__video-embed" ref={playerRef} data-plyr-provider="youtube" data-plyr-embed-id={getYouTubeId(currentLesson.video_url)} />
                            ) : (
                                <video src={currentLesson.video_url} controls className="w-full h-full" />
                            )}

                            {isVideoCompleted && !isInstructor && (
                                <div className="absolute inset-0 bg-black/80 flex flex-col items-center justify-center z-20 text-white">
                                    <h3 className="text-2xl font-bold mb-4">Lesson Completed! 🎉</h3>
                                    <div className="flex gap-4">
                                        <button onClick={() => setIsVideoCompleted(false)} className="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg backdrop-blur">
                                            Replay
                                        </button>
                                        {nextLesson && (
                                            <button
                                                onClick={() => { handleLessonChange(nextLesson); updateProgress(); }}
                                                className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg"
                                            >
                                                Next Lesson →
                                            </button>
                                        )}
                                    </div>
                                </div>
                            )}
                        </div>
                    )}

                    {/* Content */}
                    {currentLesson.content && (
                        <div className="prose prose-indigo max-w-none text-gray-800" dangerouslySetInnerHTML={{ __html: currentLesson.content }} />
                    )}

                    {/* Links & Resources */}
                    <div className="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        {currentLesson.pdf_url && (
                            <a href={currentLesson.pdf_url} download target="_blank" rel="noreferrer" className="block p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-md transition-all group bg-white">
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-xl group-hover:scale-110 transition-transform">📄</div>
                                    <div>
                                        <div className="font-semibold text-gray-900">PDF Resource</div>
                                        <div className="text-sm text-gray-500">Click to download</div>
                                    </div>
                                </div>
                            </a>
                        )}
                        {currentLesson.external_link && (
                            <a href={currentLesson.external_link} target="_blank" rel="noreferrer" className="block p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-md transition-all group bg-white">
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xl group-hover:scale-110 transition-transform">🔗</div>
                                    <div>
                                        <div className="font-semibold text-gray-900">{currentLesson.external_link_label || 'External Link'}</div>
                                        <div className="text-sm text-gray-500">Visit resource</div>
                                    </div>
                                </div>
                            </a>
                        )}
                    </div>

                    {/* Pagination - Hide Complete for Instructor */}
                    <div className="mt-12 pt-8 border-t border-gray-100 flex justify-between items-center">
                        {prevLesson ? (
                            <Button variant="outline" onClick={() => handleLessonChange(prevLesson)}>← Previous</Button>
                        ) : <div />}

                        {nextLesson ? (
                            <Button onClick={() => { handleLessonChange(nextLesson); updateProgress(); }}>Next Lesson →</Button>
                        ) : (
                            !isInstructor && (
                                <form method="POST" action={`/student/course/${courseId}/complete`}>
                                    <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''} />
                                    <Button type="submit" className="bg-green-600 hover:bg-green-700">
                                        🎓 Complete Course & Get Certificate
                                    </Button>
                                </form>
                            )
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};
