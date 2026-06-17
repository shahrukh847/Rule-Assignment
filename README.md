# Rule Assignment System

## Overview

This project implements a Rule Assignment System where predefined rules can be assigned to groups in a hierarchical structure.

Rules are organized in parent-child relationships with a maximum depth of three tiers.

The application allows users to:

* Create groups
* Assign rules to groups
* View rule hierarchies
* Edit rule assignments
* Remove assignments
* Validate hierarchy rules before saving

---

## Technology Stack

### Backend

* PHP 8+
* MySQL
* PDO
* Object-Oriented Programming (OOP)

### Frontend

* Bootstrap 5
* Vue.js 3

### Testing

* PHPUnit

---

## Project Structure

```text
config/
controllers/
repositories/
services/
views/
tests/
sql/

index.php
composer.json
phpunit.xml
README.md
```

---

## Database Setup

### Step 1

Create a MySQL database:

```sql
CREATE DATABASE rule_assignment_system;
```

### Step 2

Import the SQL script:

```text
sql/schema.sql
```

This script creates:

* rules
* rule_groups
* group_rule_assignments

### Step 3

Update database configuration:

```php
config/Database.php
```

Example:

```php
$host = 'localhost';
$dbname = 'rule_assignment_system';
$username = 'root';
$password = '';
```

---

## Running the Application

Place the project inside your web server directory.

Example:

```text
C:\wamp64\www\ruleAssignment
```

Open:

```text
http://localhost/ruleAssignment
```

---

## Business Rules

### Rule Types

#### Condition Rule

* Can have child rules
* Must have at least one child rule

#### Decision Rule

* Cannot have child rules

---

## Hierarchy Constraints

### Maximum Tier Depth

Only 3 levels are allowed.

Example:

```text
Tier 1
 └─ Tier 2
     └─ Tier 3
```

Tier 4 is not allowed.

### Duplicate Prevention

The same rule cannot be assigned more than once under the same parent.

Valid:

```text
Condition Rule
├─ Decision Rule 1
├─ Decision Rule 2
```

Invalid:

```text
Condition Rule
├─ Decision Rule 1
├─ Decision Rule 1
```

### Condition Rule Validation

Every Condition Rule must have at least one child.

---

## Database Design

### rules

Stores available rules.

| Column    | Description          |
| --------- | -------------------- |
| id        | Primary Key          |
| rule_name | Rule Name            |
| rule_type | CONDITION / DECISION |

### rule_groups

Stores groups.

| Column     | Description |
| ---------- | ----------- |
| id         | Primary Key |
| group_name | Group Name  |

### group_rule_assignments

Stores hierarchical assignments.

| Column               | Description       |
| -------------------- | ----------------- |
| id                   | Primary Key       |
| group_id             | Group Reference   |
| rule_id              | Rule Reference    |
| parent_assignment_id | Parent Assignment |
| tier                 | Hierarchy Level   |
| sort_order           | Display Order     |

---

## Design Approach

The application follows a layered architecture.

### Controllers

Handle HTTP requests and application flow.

### Repositories

Responsible for database access.

Examples:

* RuleRepository
* GroupRepository
* AssignmentRepository

### Services

Contain business logic and validation rules.

Example:

* RuleAssignmentService

### Views

Responsible for rendering HTML and Vue.js components.

---

## Security

### SQL Injection Prevention

All database operations use prepared statements.

Example:

```php
$stmt->execute([$id]);
```

### XSS Prevention

Output is escaped using:

```php
htmlspecialchars()
```

Example:

```php
<?= htmlspecialchars($group['group_name']) ?>
```

---

## Unit Tests

The project includes PHPUnit tests for service validation logic.

Run tests:

```bash
vendor\bin\phpunit.bat
```

Expected output:

```text
OK (4 tests, 4 assertions)
```

---

## Author

Shahrookh Shaikh
Sr. PHP Developer

```
```
