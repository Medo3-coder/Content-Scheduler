import React from 'react';
import { Outlet, useNavigate } from 'react-router-dom';
import { Navbar, Nav, Container, Button } from 'react-bootstrap';
import { useAuth } from './auth/AuthContext';

const Layout = () => {
    const { token, logout } = useAuth();
    const navigate = useNavigate();

    const handleLogout = () => {
        logout();
        navigate('/login', { replace: true });
    };

    return (
        <>
            <Navbar bg="dark" variant="dark" expand="lg">
                <Container>
                    <Navbar.Brand href="/">Content Scheduler</Navbar.Brand>
                    <Navbar.Toggle aria-controls="basic-navbar-nav" />
                    <Navbar.Collapse id="basic-navbar-nav">
                        {token ? (
                            <>
                                <Nav className="me-auto">
                                    <Nav.Link href="/posts">Posts</Nav.Link>
                                    <Nav.Link href="/create">Create Post</Nav.Link>
                                    <Nav.Link href="/settings">Settings</Nav.Link>
                                    <Nav.Link href="/analytics">Analytics</Nav.Link>
                                    <Nav.Link href="/logs">Logs</Nav.Link>
                                </Nav>
                                <Button variant="outline-light" onClick={handleLogout}>
                                    Logout
                                </Button>
                            </>
                        ) : (
                            <Nav className="ms-auto">
                                <Nav.Link href="/login">Login</Nav.Link>
                                <Nav.Link href="/register">Register</Nav.Link>
                            </Nav>
                        )}
                    </Navbar.Collapse>
                </Container>
            </Navbar>
            <Container className="py-4">
                <Outlet />
            </Container>
        </>
    );
};

export default Layout;
