import { useEffect, useState } from "react";
import api, { setAuthToken } from "../../api/axios";
import { Container, Card, Button, Alert } from "react-bootstrap";

export default function PlatformSettings() {
    const [platforms, setPlatforms] = useState([]);
    const [loading, setLoading] = useState(true);
    const [message, setMessage] = useState("");

    useEffect(() => {
        const token = localStorage.getItem("token");
        setAuthToken(token);
        fetchPlatforms();
    }, []);

    const fetchPlatforms = async () => {
        try {
            const res = await api.get("/platforms");
            setPlatforms(res.data);
        } catch (err) {
            setMessage("Failed to load platforms");
        } finally {
            setLoading(false);
        }
    };

    const handleToggle = async (platformId, currentStatus) => {
        try {
            await api.post("/platforms/toggle", {
                platform_id: platformId,
                active: !currentStatus,
            });
            setMessage("Platform status updated successfully");
            fetchPlatforms();
        } catch (err) {
            setMessage(
                err.response?.data?.message ||
                    "Failed to update platform status"
            );
        }
    };

    if (loading) {
        return (
            <Container className="mt-5">
                <div className="text-center">
                    <div className="spinner-border" role="status">
                        <span className="visually-hidden">Loading...</span>
                    </div>
                </div>
            </Container>
        );
    }

    return (
        <Container className="mt-5">
            <h2 className="mb-4">Platform Settings</h2>

            {message && (
                <Alert
                    variant="info"
                    dismissible
                    onClose={() => setMessage("")}
                    className="mb-4"
                >
                    {message}
                </Alert>
            )}

            <div className="row">
                {platforms.map((platform) => (
                    <div key={platform.id} className="col-md-4 mb-4">
                        <Card className="h-100 shadow-sm">
                            <Card.Body>
                                <Card.Title>{platform.name}</Card.Title>
                                <Card.Text>
                                    <span className="d-block mb-2">
                                        Type: <strong>{platform.type}</strong>
                                    </span>
                                    Status:
                                    <span
                                        className={`badge ${
                                            platform.active
                                                ? "bg-success"
                                                : "bg-secondary"
                                        } ms-2`}
                                    >
                                        {platform.active
                                            ? "Active"
                                            : "Inactive"}
                                    </span>
                                </Card.Text>
                                <Button
                                    variant={
                                        platform.active ? "danger" : "success"
                                    }
                                    onClick={() =>
                                        handleToggle(
                                            platform.id,
                                            platform.active
                                        )
                                    }
                                    className="w-100"
                                >
                                    {platform.active
                                        ? "Deactivate"
                                        : "Activate"}
                                </Button>
                            </Card.Body>
                        </Card>
                    </div>
                ))}
            </div>
        </Container>
    );
}
