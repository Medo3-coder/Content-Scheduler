import { useEffect, useState } from 'react';
import api, { setAuthToken } from '../../api/axios';

export default function Analytics() {
  const [data, setData] = useState(null);
  const [error, setError] = useState('');

  const fetchAnalytics = async () => {
    const token = localStorage.getItem('token');
    setAuthToken(token);

    try {
      const res = await api.get('/analytics');
      setData(res.data);
    } catch {
      setError('Failed to fetch analytics.');
    }
  };

  useEffect(() => {
    fetchAnalytics();
  }, []);

  if (error) return <div className="container mt-5"><div className="alert alert-danger">{error}</div></div>;
  if (!data) return <div className="container mt-5">Loading...</div>;

  return (
    <div className="container mt-5">
      <h2>Analytics Overview</h2>

      <div className="row mt-4">
        <div className="col-md-4">
          <div className="card text-white bg-info mb-3">
            <div className="card-body">
              <h5 className="card-title">Scheduled Posts</h5>
              <p className="card-text fs-4">{data.scheduled_count}</p>
            </div>
          </div>
        </div>
        <div className="col-md-4">
          <div className="card text-white bg-success mb-3">
            <div className="card-body">
              <h5 className="card-title">Published Posts</h5>
              <p className="card-text fs-4">{data.published_count}</p>
            </div>
          </div>
        </div>
        <div className="col-md-4">
          <div className="card text-white bg-dark mb-3">
            <div className="card-body">
              <h5 className="card-title">Success Rate</h5>
              <p className="card-text fs-4">{data.success_rate}%</p>
            </div>
          </div>
        </div>
      </div>

      <h4 className="mt-5">Posts Per Platform</h4>
      <table className="table table-bordered mt-3">
        <thead>
          <tr>
            <th>Platform</th>
            <th>Type</th>
            <th>Total Posts</th>
          </tr>
        </thead>
        <tbody>
          {data.posts_per_platform.map((p, i) => (
            <tr key={i}>
              <td>{p.platform}</td>
              <td>{p.type}</td>
              <td>{p.total_posts}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
