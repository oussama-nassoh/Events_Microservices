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
   - React.js web application
   - User interface for all operations

8. **Load Balancer** (Port 80)
   - Nginx load balancer
   - Request distribution
   - High availability

## System Architecture

```mermaid
graph TD
    LB[Load Balancer<br/>Nginx] --> AG[API Gateway]
    
    subgraph Services
        AG --> AS[Auth Service]
        AG --> US[User Service]
        AG --> ES[Event Service]
        AG --> TS[Ticket Service]
        AG --> NS[Notification Service]
        AG --> FE[Frontend<br/>React.js]
    end
    
    subgraph Databases
        AS --> ADB[(Auth DB)]
        US --> UDB[(User DB)]
        ES --> EDB[(Event DB)]
        TS --> TDB[(Ticket DB)]
        NS --> NDB[(Notification DB)]
    end

    classDef gateway fill:#f96,stroke:#333,stroke-width:2px
    classDef service fill:#58a,stroke:#333,stroke-width:2px
    classDef database fill:#eb4,stroke:#333,stroke-width:2px
    classDef loadbalancer fill:#7f7,stroke:#333,stroke-width:2px
    
    class LB loadbalancer
    class AG gateway
    class AS,US,ES,TS,NS,FE service
    class ADB,UDB,EDB,TDB,NDB database
```

## Features

- User authentication and authorization
- Event creation and management
- Secure ticket purchasing
- Automated email notifications after each purchase or cancel
- Payment processing
- Ticket validation
- User purchase history
- Admin dashboard

## Prerequisites

- Docker
- Docker Compose
- Git

## Installation

1. Clone the repository:
```bash
git clone https://github.com/chabbasaad/Events_Microservices &&
cd laravel_Microservices
```

2. Build and start the containers:
```bash
docker-compose up -d
```

3. run command for Queue work for notification
```bash
php artisan queue:work
```

## Service URLs

- Frontend: http://localhost:3000
- API Gateway: http://localhost:8000
- Auth Service: http://localhost:8001
- User Service: http://localhost:8002
- Event Service: http://localhost:8003
- Ticket Service: http://localhost:8004
- Notification Service: http://localhost:8005

## API Documentation

API documentation is available at the following URLs:
- Auth Service: http://localhost:8001/docs/api
- User Service: http://localhost:8002/docs/api
- Event Service: http://localhost:8003/docs/api
- Ticket Service: http://localhost:8004/docs/api

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

## Logs

- Each service maintains its own logs at `storage/logs/laravel.log`

## Testing

To run tests for all services:
```bash
./run-tests.sh
```
