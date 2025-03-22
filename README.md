# Event Ticketing System - Microservices Architecture

A scalable event ticketing system built with Laravel microservices architecture, designed to handle both small-scale events and large international concerts.

## Architecture Overview

The system is composed of the following microservices:

1. **API Gateway** (Port 8000)
   - Entry point for all client requests
   - Handles request routing and load balancing
   - Manages service discovery

2. **Auth Service** (Port 8001)
   - Handles authentication and authorization
   - JWT token management
   - User roles: Admin, EventCreator, Operator, User

3. **User Service** (Port 8002)
   - User profile management
   - User purchase history
   - Account settings

4. **Event Service** (Port 8003)
   - Event CRUD operations
   - Event capacity management
   - Event details and scheduling

5. **Ticket Service** (Port 8004)
   - Ticket purchasing and management
   - Payment processing
   - Ticket validation and verification

6. **Notification Service** (Port 8005)
   - Handles email/SMS notifications
   - Purchase confirmations
   - Event updates

7. **Frontend** (Port 3000)
   - React/Vue.js web application
   - User interface for all operations

8. **Load Balancer** (Port 80)
   - Nginx load balancer
   - Request distribution
   - High availability

## System Architecture Diagram

```
                                    ┌─────────────────┐
                                    │   Load Balancer │
                                    │     (Nginx)     │
                                    └────────┬────────┘
                                             │
                                    ┌────────┴────────┐
                                    │   API Gateway   │
                                    └────────┬────────┘
                                             │
                 ┌───────────────────────────┼───────────────────────────┐
                 │                           │                           │
        ┌────────┴────────┐        ┌────────┴────────┐         ┌───────┴────────┐
        │  Auth Service   │        │  User Service   │         │  Event Service  │
        └────────┬────────┘        └────────┬────────┘         └───────┬────────┘
                 │                           │                           │
        ┌────────┴────────┐        ┌────────┴────────┐         ┌───────┴────────┐
        │    Auth DB      │        │    User DB      │         │    Event DB    │
        └────────────────┘         └────────────────┘          └───────────────┘

                 ┌───────────────────────────┼───────────────────────────┐
                 │                           │                           │
        ┌────────┴────────┐        ┌────────┴────────┐         ┌───────┴────────┐
        │ Ticket Service  │        │  Notification   │         │    Frontend     │
        └────────┬────────┘        │    Service      │         │     (Web)      │
                 │                 └────────┬────────┘          └───────────────┘
        ┌────────┴────────┐               │
        │   Ticket DB     │      ┌────────┴────────┐
        └────────────────┘       │ Notification DB │
                                └────────────────┘
```

## Features

- User authentication and authorization
- Event creation and management
- Secure ticket purchasing
- Automated email/SMS notifications
- Payment processing
- Ticket validation
- User purchase history
- Admin dashboard
- Event analytics

## Prerequisites

- Docker
- Docker Compose
- Git

## Installation

1. Clone the repository:
\`\`\`bash
git clone [repository-url]
cd laravel_Microservices
\`\`\`

2. Build and start the containers:
\`\`\`bash
docker-compose up -d
\`\`\`

3. Initialize the databases (this will run migrations and seeders):
\`\`\`bash
./init-services.sh
\`\`\`

## Service URLs

- Frontend: http://localhost:3000
- API Gateway: http://localhost:8000
- Auth Service: http://localhost:8001
- User Service: http://localhost:8002
- Event Service: http://localhost:8003
- Ticket Service: http://localhost:8004
- Notification Service: http://localhost:8005

## API Documentation

Each service has its own Swagger/OpenAPI documentation available at:
\`http://localhost:{PORT}/api/documentation\`

## Security Features

- JWT-based authentication
- Password encryption
- Rate limiting
- CORS protection
- Database backup scheduling
- Secure payment processing
- Ticket-user matching for validation

## Error Handling

- Comprehensive error logging in each service
- Asynchronous notification system for failed operations
- Transaction rollback for failed purchases
- Automatic retry mechanism for failed notifications

## Monitoring and Logs

- Each service maintains its own logs at \`storage/logs/laravel.log\`
- Centralized logging system for debugging
- Real-time error monitoring
- Performance metrics tracking

## Testing

To run tests for all services:
\`\`\`bash
./run-tests.sh
\`\`\`

## Contributing

Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the LICENSE.md file for details
