import React, { useEffect, useState } from "react";
import { Container, Row, Col, Card, Button } from "react-bootstrap";
import { useNavigate } from "react-router-dom";
import api, { setAuthToken } from "../../api/axios";

const Dashboard = () => {
    const [user, setUser] = useState(null);
    const navigate = useNavigate();

    useEffect(() => {
        const token = localStorage.getItem("token");
        if (!token) return navigate("/login");

        setAuthToken(token);

        api.get("/user-data")
            .then((res) => setUser(res.data))
            .catch(() => {
                localStorage.removeItem("token");
                navigate("/login");
            });
    }, [navigate]);

    const handleLogout = async () => {
        try {
            await api.post("/logout");
            localStorage.removeItem("token");
            navigate("/login");
        } catch (error) {
            console.error("Logout failed:", error);
        }
    };

    return (
        <Container className="py-5">
            <Row>
                <Col>
                    <h1 className="mb-4">Dashboard</h1>
                    <Card>
                        <Card.Body>
                            {user ? (
                                <>
                                    <Card.Title>
                                        Welcome, {user.name}
                                    </Card.Title>
                                    <Card.Text>Email: {user.email}</Card.Text>
                                    <Button
                                        variant="danger"
                                        onClick={handleLogout}
                                    >
                                        Logout
                                    </Button>
                                </>
                            ) : (
                                <Card.Text>Loading user data...</Card.Text>
                            )}
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </Container>
    );
};

export default Dashboard;
