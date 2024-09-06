
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
```
This will retrieve the latest missed calls from the database.
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
## Overview

The Query Bot API is a Laravel-based backend service designed to handle various queries related to call logs, agent performance, and targets/achievements. It provides a structured way to process user input and return relevant information.

## Architecture

### Controller

The main controller for handling queries is `QueryBotController`. It contains methods that respond to user queries by calling specific handler methods based on the keywords detected in the request.

### Routes

The API provides the following routes:

```php
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/query-bot', [QueryBotController::class, 'handleQuery']);
    Route::get('/query', [QueryBotController::class, 'handleQuery']);
    Route::get('/call-logs', [QueryBotController::class, 'getCallLogs']);
    Route::get('/agent-performance', [QueryBotController::class, 'getAgentPerformance']);
    Route::get('/targets-achievements', [QueryBotController::class, 'getTargetsAchievements']);
});
```
### Query Handling Logic
The handleQuery method processes incoming requests by matching the input query against predefined keywords. Based on the matched keyword, it delegates the call to the corresponding handler method.

### Example of Query Handlers
Call Logs: Handled by handleCallLogs()
Agent Performance: Handled by handleAgentPerformance()
Targets and Achievements: Handled by handleTargetsAchievements()
Greetings: Handled by handleGreeting()
Extending Query Handling Functionality
To extend or modify the query handling functionality:

#### Add Keywords:
Update the $handlers array in the handleQuery method. For example, to add a new query type, add a new entry to $handlers array:
```php
'new keyword' => 'handleNewQueryType',
```
#### Create a New Handler:
Implement a new method in the QueryBotController that follows the existing pattern. For example:
```php
public function handleNewQueryType($query)
{
    // Handle the new query type
}
```

### Error Handling
The API provides error responses for invalid queries or when no data is found. For example, if an invalid query is received, it returns:
```json
{"error": "Invalid query"}
```

## Conclusion
The Query Bot API is designed to be modular and easily extendable. By following the outlined steps, you can enhance its capabilities to accommodate new types of queries and responses.

### Notes:
- This README provides a clear overview of the project, its structure, and how to extend its functionality.
- Feel free to customize the content as needed to better fit your project's specifics and any additional details you want to include.