
# Query Bot API

## Description
A Laravel-based API for handling user queries with authentication.
This Query Bot is using a simple NLP (Natural Language Proccess), so it can recognise the query text.

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/your-username/query-bot-api.git
    cd query-bot-api
    ```

2. Install dependencies:
    ```bash
    composer install
    npm install
    npm run dev
    ```

3. Set up the environment:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Run migrations:
    ```bash
    php artisan migrate
    ```

5. Run Seeders:
    ```bash
    php artisan db:seed
    ```

## Usage
### Register a User
Api endpoint:
```bash
POST /api/register
```
```bash
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```
### Login
Api endpoint:
```bash
POST /api/login
```
```bash
{
    "email": "john@example.com",
    "password": "password",
}
```

### Start Querying
Api endpoint:
```bash
POST /api/query-bot
```
• Request:
```bash
{
    "query": "hello",
}
```
• Response:
```bash
{
    "message": "Welcome to Query Bot, How can I help you?"
}
```
### Examples
#### Query about missed calls
Api endpoint:
```bash
POST /api/query-bot
```
• Request:
```bash
{
    "query": "give me the latest missed calls",
}
```
• Response:
```bash
{
    "callLogs": {
        "0": {
            "id": 256,
            "agent_id": "44",
            "call_status": "missed",
            "call_duration": "00:41",
            "created_at": "2024-09-05T19:55:29.000000Z",
            "updated_at": "2024-09-05T19:55:29.000000Z"
        },
        "1": {
            "id": 258,
            "agent_id": "38398",
            "call_status": "missed",
            "call_duration": "03:48",
            "created_at": "2024-09-05T19:55:29.000000Z",
            "updated_at": "2024-09-05T19:55:29.000000Z"
        }
}
```

#### Query about the performance of an agent

Api endpoint:
```bash
POST /api/query-bot
```
• Request:
```bash
{
    "query": "give me the the performance for agent id 88",
}
```
> **Note:** It has to be "agent id" then follows the id number whenever querying on a specific agent.

• Response:
```bash
{
    "performance": [
        {
            "id": 1,
            "agent_id": "88",
            "calls_handled": 54,
            "average_call_duration": "03:03",
            "deals": 11,
            "booked": 29,
            "created_at": "2024-09-05T19:54:22.000000Z",
            "updated_at": "2024-09-05T19:54:22.000000Z"
        }
    ]
}
```

#### Query about the targets or achievements of an agent

Api endpoint:
```bash
POST /api/query-bot
```
• Request:
```bash
{
    "query": "give me the the targets for agent id 8",
}
```
> **Note:** It has to be "agent id" then follows the id number whenever querying on a specific agent.

• Response:
```bash
{
    "targets": [
        {
            "id": 17,
            "agent_id": "8",
            "target": 91,
            "achievement": 72,
            "created_at": "2024-09-05T19:55:29.000000Z",
            "updated_at": "2024-09-05T19:55:29.000000Z"
        },
        {
            "id": 18,
            "agent_id": "8",
            "target": 39,
            "achievement": 62,
            "created_at": "2024-09-05T19:55:29.000000Z",
            "updated_at": "2024-09-05T19:55:29.000000Z"
        }
    ]
}
```

### Keywards
The Bot using keyword to perform the queries:
#### Call Logs
• To query for all call logs, The request query parameter must contain "calls" or logs"

Example:
```bash
{
    "query": "give me the the call logs"
}
```
This will retrieve all call logs in the database.
To specify answerd or missed calls:
```bash
{
    "query": "give me the the missed calls"
}
This will retrieve the latest missed calls from the database.
```
### API Endpoints
• Start querying
```bash
POST /api/query-bot
```
• Retrieve all Call Logs
```bash
GET /api/call-logs
```

• Retrieve all Targets and Achievement
```bash
GET /api/targets-achievements
```
• Retrieve all agents performances
```bash
GET /api/agent-performance
```
