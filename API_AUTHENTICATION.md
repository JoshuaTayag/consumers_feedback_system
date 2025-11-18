# API Authentication Documentation

## Overview
The API authentication system reuses all the existing authentication functions from your web application, including:
- Same user model and database
- Same role and permission system (Spatie/Laravel-permission)
- Same validation rules
- Same security features

## Authentication Endpoints

### 1. Login
**POST** `/api/login`

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com",
            "email_verified_at": "2024-01-01T00:00:00.000000Z",
            "roles": ["Admin", "User"],
            "permissions": ["view-dashboard", "manage-users"]
        },
        "token": "1|abc123def456...",
        "token_type": "Bearer"
    }
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

### 2. Get User Profile
**GET** `/api/me`

**Headers:**
```
Authorization: Bearer {your-token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "email_verified_at": "2024-01-01T00:00:00.000000Z",
        "roles": ["Admin", "User"],
        "permissions": ["view-dashboard", "manage-users"],
        "employee": {
            "id": 1,
            "employee_number": "EMP001",
            "department": "IT"
        }
    }
}
```

### 3. Logout
**POST** `/api/logout`

**Headers:**
```
Authorization: Bearer {your-token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

### 4. Refresh Token
**POST** `/api/refresh`

**Headers:**
```
Authorization: Bearer {your-token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Token refreshed successfully",
    "data": {
        "token": "2|new123token456...",
        "token_type": "Bearer"
    }
}
```

## Usage Examples

### JavaScript/Fetch
```javascript
// Login
const loginResponse = await fetch('/api/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password123'
    })
});

const loginData = await loginResponse.json();
const token = loginData.data.token;

// Use token for authenticated requests
const userResponse = await fetch('/api/me', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});
```

### cURL
```bash
# Login
curl -X POST /api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'

# Get user profile (replace TOKEN with actual token)
curl -X GET /api/me \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"
```

## Security Features
- **Rate limiting**: Login endpoint is rate limited (5 attempts per minute)
- **Token-based**: Uses Laravel Sanctum for secure API tokens
- **Role/Permission support**: All existing roles and permissions work with API
- **Token revocation**: Tokens can be revoked on logout
- **Token refresh**: Tokens can be refreshed for better security

## Integration with Existing System
The API seamlessly integrates with your existing:
- User accounts and authentication
- Role-based access control (RBAC)
- Employee relationships
- Security policies
- Validation rules

All your existing users can immediately use the API with their current credentials.