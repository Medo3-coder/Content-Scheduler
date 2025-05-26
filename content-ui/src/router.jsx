import { createBrowserRouter, Navigate } from 'react-router-dom';
import Login from './components/auth/Login';
import Register from './components/auth/Register';
import Dashboard from './components/dashboard/Dashboard';
import CreatePost from './components/posts/CreatePost';
import PostList from './components/posts/PostList';
import Layout from './components/Layout';
import PlatformSettings from './components/settings/PlatformSettings';
import Analytics from './components/analytics/Analytics';
import ActivityLogs from './components/logs/ActivityLogs';
import { useAuth } from './components/auth/AuthContext';

const ProtectedRoute = ({ children }) => {
    const { token } = useAuth();
    return token ? children : <Navigate to="/login" />;
  };

  const GuestRoute = ({ children }) => {
    const { token } = useAuth();
    return !token ? children : <Navigate to="/dashboard" />;
  };

export const router = createBrowserRouter([
  {
    path: '/',
    element: <Layout />,
    children: [
      { path: '/', element: <ProtectedRoute><PostList /></ProtectedRoute> },
      { path: '/posts', element: <ProtectedRoute><PostList /></ProtectedRoute> },
      { path: '/dashboard', element: <ProtectedRoute><Dashboard /></ProtectedRoute> },
      { path: '/create', element: <ProtectedRoute><CreatePost /></ProtectedRoute> },
      { path: '/settings', element: <ProtectedRoute><PlatformSettings /></ProtectedRoute> },
      { path: '/analytics', element: <ProtectedRoute><Analytics /></ProtectedRoute> },
      { path: '/logs', element: <ProtectedRoute><ActivityLogs /></ProtectedRoute> },
      { path: '/login', element: <GuestRoute><Login /></GuestRoute> },
      { path: '/register', element: <GuestRoute><Register /></GuestRoute> },
    ]
  }

]);
