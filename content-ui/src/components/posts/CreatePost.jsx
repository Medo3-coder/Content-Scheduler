import { useEffect, useState } from 'react';
import api, { setAuthToken } from '../../api/axios';
import { useNavigate } from 'react-router-dom';

export default function CreatePost() {
    const [form, setForm] = useState({
        title: '',
        content: '',
        image_url: '',
        scheduled_time: '',
        platform_ids: [],
    });

    const [platforms, setPlatforms] = useState([]);
    const [message, setMessage] = useState('');
    const [errors, setErrors] = useState({});
    const navigate = useNavigate();

    useEffect(() => {
        const token = localStorage.getItem('token');
        setAuthToken(token);

        api.get('/platforms')
            .then(res => setPlatforms(res.data))
            .catch(() => setMessage('Failed to load platforms'));
    }, []);

    const handleChange = e => setForm({ ...form, [e.target.name]: e.target.value });

    const handleCheckbox = id => {
        const updated = new Set(form.platform_ids);
        updated.has(id) ? updated.delete(id) : updated.add(id);
        setForm({ ...form, platform_ids: Array.from(updated) });
    };

    const handleSubmit = async e => {
        e.preventDefault();
        setMessage('');
        setErrors({});

        try {
            await api.post('/posts', form);
            setMessage('Post created!');
            setForm({ title: '', content: '', image_url: '', scheduled_time: '', platform_ids: [] });
            setTimeout(() => navigate('/posts'), 1500);
        } catch (err) {
            if (err.response?.data?.errors) {
                setErrors(err.response.data.errors);
            } else {
                setMessage(err.response?.data?.message || 'Something went wrong.');
            }
        }
    };

    return (
        <div className="container mt-5" style={{ maxWidth: '700px' }}>
            <h2>Create New Post</h2>

            {message && <div className="alert alert-info">{message}</div>}

            <form onSubmit={handleSubmit}>
                <div className="mb-3">
                    <label>Title</label>
                    <input name="title" className="form-control" value={form.title} onChange={handleChange} />
                    {errors.title && <small className="text-danger">{errors.title[0]}</small>}
                </div>

                <div className="mb-3">
                    <label>Content</label>
                    <textarea name="content" className="form-control" rows="4" value={form.content} onChange={handleChange} />
                    {errors.content && <small className="text-danger">{errors.content[0]}</small>}
                </div>

                <div className="mb-3">
                    <label>Image URL</label>
                    <input name="image_url" className="form-control" value={form.image_url} onChange={handleChange} />
                </div>

                <div className="mb-3">
                    <label>Scheduled Time</label>
                    <input type="datetime-local" name="scheduled_time" className="form-control" value={form.scheduled_time} onChange={handleChange} />
                    {errors.scheduled_time && <small className="text-danger">{errors.scheduled_time[0]}</small>}
                </div>

                <div className="mb-3">
                    <label>Platforms</label>
                    <div className="form-check">
                        {platforms.map(p => (
                            <div key={p.id}>
                                <input
                                    type="checkbox"
                                    className="form-check-input"
                                    id={`platform-${p.id}`}
                                    checked={form.platform_ids.includes(p.id)}
                                    onChange={() => handleCheckbox(p.id)}
                                />
                                <label className="form-check-label" htmlFor={`platform-${p.id}`}>{p.name}</label>
                            </div>
                        ))}
                    </div>
                    {errors.platform_ids && <small className="text-danger">{errors.platform_ids[0]}</small>}
                </div>

                <button className="btn btn-success w-100">Schedule Post</button>
            </form>
        </div>
    );
}
