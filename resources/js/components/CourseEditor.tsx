import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import { Plus, Trash2, Edit, Save, X, GripVertical, ChevronUp, ChevronDown } from 'lucide-react';
import { Button } from './Button';
import RichTextEditor from '../components/RichTextEditor';
import { Course, Lesson } from '../types';

interface CourseEditorProps {
    courseId: number;
    onNavigate: (page: string) => void;
}

export const CourseEditor: React.FC<CourseEditorProps> = ({ courseId, onNavigate }) => {
    // Course State
    const [course, setCourse] = useState<Course | null>(null);
    const [loading, setLoading] = useState(true);
    const [isEditingCourse, setIsEditingCourse] = useState(false);
    const [courseFormData, setCourseFormData] = useState({ title: '', description: '', category: '' });

    // Lesson State
    const [showLessonForm, setShowLessonForm] = useState(false);
    const [editingLessonId, setEditingLessonId] = useState<number | null>(null);
    const [lessonData, setLessonData] = useState({
        title: '',
        content: '',
        video_url: '',
        pdf_url: '',
        external_link: '',
        external_link_label: ''
    });

    const lessonFormRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        fetchCourse();
    }, [courseId]);

    const fetchCourse = async () => {
        try {
            const response = await axios.get(`/api/courses/${courseId}`);
            setCourse(response.data);
            setCourseFormData({
                title: response.data.title,
                description: response.data.description,
                category: response.data.category
            });
            setLoading(false);
        } catch (error) {
            console.error('Error fetching course:', error);
            alert('Course not found');
            onNavigate('instructor-dashboard');
        }
    };

    const handleUpdateCourse = async (e: React.FormEvent) => {
        e.preventDefault();
        try {
            await axios.put(`/api/courses/${courseId}`, courseFormData);
            setCourse(prev => prev ? { ...prev, ...courseFormData } : null);
            setIsEditingCourse(false);
        } catch (error) {
            console.error('Error updating course:', error);
            alert('Failed to update course details');
        }
    };

    const handleToggleStatus = async () => {
        try {
            const response = await axios.post(`/api/courses/${courseId}/toggle-status`);
            setCourse(prev => prev ? { ...prev, status: response.data.status } : null);
        } catch (error) {
            console.error('Error toggling status:', error);
            alert('Failed to update status');
        }
    };

    const openAddLesson = () => {
        setLessonData({ title: '', content: '', video_url: '', pdf_url: '', external_link: '', external_link_label: '' });
        setEditingLessonId(null);
        setShowLessonForm(true);
        setTimeout(() => {
            lessonFormRef.current?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    };

    const openEditLesson = (lesson: Lesson) => {
        setLessonData({
            title: lesson.title,
            content: lesson.content || '',
            video_url: lesson.video_url || '',
            pdf_url: lesson.pdf_url || '',
            external_link: lesson.external_link || '',
            external_link_label: lesson.external_link_label || ''
        });
        setEditingLessonId(lesson.id);
        setShowLessonForm(true);
        setTimeout(() => {
            lessonFormRef.current?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    };

    const handleSaveLesson = async (e: React.FormEvent) => {
        e.preventDefault();
        try {
            const payload = {
                course_id: courseId,
                title: lessonData.title,
                content: lessonData.content,
                video_url: lessonData.video_url || null,
                pdf_url: lessonData.pdf_url || null,
                external_link: lessonData.external_link || null,
                external_link_label: lessonData.external_link_label || null,
                sequence_number: editingLessonId
                    ? course?.lessons?.find(l => l.id === editingLessonId)?.sequence_number
                    : ((course?.lessons?.length || 0) + 1)
            };

            if (editingLessonId) {
                const res = await axios.put(`/api/lessons/${editingLessonId}`, payload);
                setCourse(prev => prev ? {
                    ...prev,
                    lessons: prev.lessons?.map(l => l.id === editingLessonId ? res.data : l)
                } : null);
            } else {
                const res = await axios.post('/api/lessons', payload);
                setCourse(prev => prev ? {
                    ...prev,
                    lessons: [...(prev.lessons || []), res.data]
                } : null);
                alert('Lesson added!');
            }

            setShowLessonForm(false);
            setLessonData({ title: '', content: '', video_url: '', pdf_url: '', external_link: '', external_link_label: '' });
        } catch (error) {
            console.error('Error saving lesson:', error);
            alert('Failed to save lesson');
        }
    };

    const handleDeleteLesson = async (lessonId: number) => {
        if (!window.confirm('Are you sure you want to delete this lesson?')) return;
        try {
            await axios.delete(`/api/lessons/${lessonId}`);
            setCourse(prev => prev ? {
                ...prev,
                lessons: prev.lessons?.filter(l => l.id !== lessonId)
            } : null);
            setShowLessonForm(false);
        } catch (error) {
            console.error('Error deleting lesson:', error);
            alert('Failed to delete lesson');
        }
    };

    if (loading) return <div className="p-12 text-center">Loading...</div>;
    if (!course) return null;

    return (
        <div className="container mx-auto px-6 py-8 pb-12">
            <button onClick={() => onNavigate('instructor-dashboard')} className="mb-6 text-gray-500 hover:text-gray-900 transition-colors">
                ← Back to Dashboard
            </button>

            <div className="mb-8">
                {isEditingCourse ? (
                    <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <form onSubmit={handleUpdateCourse} className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium mb-1">Course Title</label>
                                <input
                                    type="text" required
                                    className="w-full p-2 border border-gray-300 rounded-lg"
                                    value={courseFormData.title}
                                    onChange={e => setCourseFormData({ ...courseFormData, title: e.target.value })}
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Description</label>
                                <textarea
                                    className="w-full p-2 border border-gray-300 rounded-lg h-24"
                                    value={courseFormData.description}
                                    onChange={e => setCourseFormData({ ...courseFormData, description: e.target.value })}
                                />
                            </div>
                            <div className="flex gap-4">
                                <Button type="submit">Save Changes</Button>
                                <button type="button" onClick={() => setIsEditingCourse(false)} className="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                            </div>
                        </form>
                    </div>
                ) : (
                    <div className="flex justify-between items-start">
                        <div>
                            <div className="flex items-center gap-3 mb-2">
                                <h1 className="text-3xl font-bold text-gray-900">{course.title}</h1>
                                <span className={`px-2 py-1 rounded text-xs font-medium ${course.status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}`}>
                                    {course.status === 'published' ? 'Published' : 'Draft'}
                                </span>
                            </div>
                            <p className="text-lg text-gray-600 mb-2">{course.description}</p>
                            <button onClick={() => setIsEditingCourse(true)} className="text-indigo-600 hover:underline">
                                Edit Details
                            </button>
                        </div>
                        <div className="flex gap-3">
                            <Button onClick={handleToggleStatus} variant="outline" className={course.status === 'published' ? 'text-orange-600 border-orange-200 hover:bg-orange-50' : 'text-green-600 border-green-200 hover:bg-green-50'}>
                                {course.status === 'published' ? 'Unpublish' : 'Publish'}
                            </Button>
                            <Button onClick={openAddLesson}>+ Add Lesson</Button>
                        </div>
                    </div>
                )}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Left Column: Lessons */}
                <div className="lg:col-span-2">
                    <h2 className="text-2xl font-semibold mb-6">Course Content</h2>

                    {showLessonForm && (
                        <div ref={lessonFormRef} className="bg-white p-6 rounded-xl border border-indigo-100 shadow-sm mb-8 ring-2 ring-indigo-50">
                            <h3 className="text-lg font-semibold mb-4">{editingLessonId ? 'Edit Lesson' : 'New Lesson'}</h3>
                            <form onSubmit={handleSaveLesson} className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Lesson Title</label>
                                    <input
                                        type="text" required
                                        className="w-full p-2 border border-gray-300 rounded-lg"
                                        value={lessonData.title}
                                        onChange={e => setLessonData({ ...lessonData, title: e.target.value })}
                                    />
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium mb-1">Video URL (Optional)</label>
                                        <input
                                            type="url"
                                            className="w-full p-2 border border-gray-300 rounded-lg"
                                            value={lessonData.video_url}
                                            onChange={e => setLessonData({ ...lessonData, video_url: e.target.value })}
                                            placeholder="YouTube URL"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium mb-1">PDF URL (Optional)</label>
                                        <input
                                            type="url"
                                            className="w-full p-2 border border-gray-300 rounded-lg"
                                            value={lessonData.pdf_url}
                                            onChange={e => setLessonData({ ...lessonData, pdf_url: e.target.value })}
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label className="block text-sm font-medium mb-1">Content</label>
                                    <RichTextEditor value={lessonData.content} onChange={(content) => setLessonData({ ...lessonData, content })} />
                                </div>

                                <div className="flex justify-end gap-3 pt-2">
                                    {editingLessonId && (
                                        <button type="button" onClick={() => handleDeleteLesson(editingLessonId)} className="mr-auto text-red-500 hover:text-red-700">Delete Lesson</button>
                                    )}
                                    <button type="button" onClick={() => setShowLessonForm(false)} className="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                                    <Button type="submit">{editingLessonId ? 'Update Lesson' : 'Save Lesson'}</Button>
                                </div>
                            </form>
                        </div>
                    )}

                    <div className="space-y-3">
                        {course.lessons?.sort((a, b) => a.sequence_number - b.sequence_number).map((lesson, index) => (
                            <div key={lesson.id} className="bg-white p-4 rounded-lg border border-gray-200 flex justify-between items-center hover:border-indigo-200 transition-colors group">
                                <div className="flex items-center gap-4">
                                    <div className="flex flex-col items-center justify-center w-8 gap-1">
                                        <button
                                            onClick={async () => {
                                                if (index === 0) return;
                                                const lessons = [...(course.lessons || [])].sort((a, b) => a.sequence_number - b.sequence_number);
                                                const current = lessons[index];
                                                const prev = lessons[index - 1];

                                                // Swap sequences
                                                const temp = current.sequence_number;
                                                current.sequence_number = prev.sequence_number;
                                                prev.sequence_number = temp;

                                                // Optimistic update
                                                setCourse(prevCourse => prevCourse ? { ...prevCourse, lessons: lessons } : null);

                                                try {
                                                    await axios.post('/api/lessons/reorder', {
                                                        lessons: [
                                                            { id: current.id, sequence_number: current.sequence_number },
                                                            { id: prev.id, sequence_number: prev.sequence_number }
                                                        ]
                                                    });
                                                } catch (e) {
                                                    console.error(e);
                                                    alert('Failed to save order');
                                                }
                                            }}
                                            className="text-gray-400 hover:text-indigo-600 disabled:opacity-30"
                                            disabled={index === 0}
                                        >
                                            <ChevronUp className="w-4 h-4" />
                                        </button>
                                        <span className="text-sm font-bold">{index + 1}</span>
                                        <button
                                            onClick={async () => {
                                                if (index === (course.lessons?.length || 0) - 1) return;
                                                const lessons = [...(course.lessons || [])].sort((a, b) => a.sequence_number - b.sequence_number);
                                                const current = lessons[index];
                                                const next = lessons[index + 1];

                                                // Swap sequences
                                                const temp = current.sequence_number;
                                                current.sequence_number = next.sequence_number;
                                                next.sequence_number = temp;

                                                // Optimistic update
                                                setCourse(prevCourse => prevCourse ? { ...prevCourse, lessons: lessons } : null);

                                                try {
                                                    await axios.post('/api/lessons/reorder', {
                                                        lessons: [
                                                            { id: current.id, sequence_number: current.sequence_number },
                                                            { id: next.id, sequence_number: next.sequence_number }
                                                        ]
                                                    });
                                                } catch (e) {
                                                    console.error(e);
                                                    alert('Failed to save order');
                                                }
                                            }}
                                            className="text-gray-400 hover:text-indigo-600 disabled:opacity-30"
                                            disabled={index === (course.lessons?.length || 0) - 1}
                                        >
                                            <ChevronDown className="w-4 h-4" />
                                        </button>
                                    </div>
                                    <div>
                                        <h3 className="font-semibold text-gray-900">{lesson.title}</h3>
                                        <span className="text-xs text-gray-500 uppercase tracking-wider">{lesson.content_type}</span>
                                    </div>
                                </div>
                                <div className="flex gap-2">
                                    <a
                                        href={`/courses/${courseId}/learn/lessons/${lesson.id}`}
                                        className="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                    >
                                        Preview
                                    </a>
                                    <Button variant="outline" size="sm" onClick={() => openEditLesson(lesson)}>Edit</Button>
                                </div>
                            </div>
                        ))}
                        {(!course.lessons || course.lessons.length === 0) && (
                            <div className="text-center p-8 bg-gray-50 rounded-lg border border-dashed border-gray-300 text-gray-500">
                                No lessons yet. Click "Add Lesson" to start.
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};
