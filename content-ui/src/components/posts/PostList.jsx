import { useEffect, useState } from 'react';
import api, { setAuthToken } from '../../api/axios';

export default function PostList() {
  const [posts, setPosts] = useState([]);
  const [filters, setFilters] = useState({ status: '', date: '' });
  const [loading, setLoading] = useState(true);

  const fetchPosts = async () => {
    const token = localStorage.getItem('token');
    setAuthToken(token);

    try {
      // Only include parameters if they have values
      const params = {};
      if (filters.status) params.status = filters.status;
      if (filters.date) params.date = filters.date;

      const res = await api.get('/posts', {
        params: Object.keys(params).length > 0 ? params : undefined
      });
      setPosts(res.data);
    } catch (err) {
      console.error('Failed to fetch posts');
    } finally {
      setLoading(false);
    }
  };

  const handlePlatformToggle = async (platformId) => {
    try {
      await api.post('/platforms/toggle', { platform_id: platformId });
      // Refresh posts after platform toggle
      fetchPosts();
    } catch (err) {
      console.error('Failed to toggle platform');
    }
  };

  useEffect(() => {
    fetchPosts();
  }, []);

  const handleChange = e => {
    setFilters({ ...filters, [e.target.name]: e.target.value });
  };

  const handleFilter = e => {
    e.preventDefault();
    fetchPosts();
  };

  return (
    <div className="container mt-5">
      <h2>Post Dashboard</h2>

      <form className="row g-3 mb-4" onSubmit={handleFilter}>
        <div className="col-md-3">
          <label>Status</label>
          <select className="form-select" name="status" onChange={handleChange} value={filters.status}>
            <option value="">All</option>
            <option value="draft">Draft</option>
            <option value="scheduled">Scheduled</option>
            <option value="published">Published</option>
          </select>
        </div>
        <div className="col-md-3">
          <label>Date</label>
          <input type="date" name="date" className="form-control" value={filters.date} onChange={handleChange} />
        </div>
        <div className="col-md-3 d-flex align-items-end">
          <button className="btn btn-primary">Filter</button>
        </div>
      </form>

      {loading ? (
        <p>Loading...</p>
      ) : posts.length === 0 ? (
        <div className="alert alert-info">No posts found.</div>
      ) : (
        <div className="list-group">
          {posts.map(post => (
            <div key={post.id} className="list-group-item">
              <h5>{post.title}</h5>
              <p>{post.content}</p>
              <p>
                <strong>Status:</strong> {post.status} <br />
                <strong>Scheduled:</strong> {new Date(post.scheduled_time).toLocaleString()}
              </p>
              <div>
                {post.platforms.map(p => (
                  <span
                    key={p.id}
                    className={`badge ${p.active ? 'bg-success' : 'bg-secondary'} me-1`}
                    style={{ cursor: 'pointer' }}
                    onClick={() => handlePlatformToggle(p.id)}
                  >
                    {p.name}
                  </span>
                ))}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
