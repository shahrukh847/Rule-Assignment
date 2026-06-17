-- =====================================================
-- DATABASE
-- =====================================================

CREATE DATABASE IF NOT EXISTS rule_assignment_system;
USE rule_assignment_system;

-- =====================================================
-- RULES TABLE
-- Stores master rule definitions
-- =====================================================

CREATE TABLE rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rule_name VARCHAR(100) NOT NULL,
    rule_type ENUM('CONDITION', 'DECISION') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- GROUPS TABLE
-- Stores groups
-- =====================================================

CREATE TABLE rule_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- GROUP RULE ASSIGNMENTS
-- Stores hierarchy within a group
-- =====================================================

CREATE TABLE group_rule_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,

    group_id INT NOT NULL,
    rule_id INT NOT NULL,

    parent_assignment_id INT NULL,

    tier TINYINT NOT NULL,

    sort_order INT NOT NULL DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_assignment_group
        FOREIGN KEY (group_id)
        REFERENCES rule_groups(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_assignment_rule
        FOREIGN KEY (rule_id)
        REFERENCES rules(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_assignment_parent
        FOREIGN KEY (parent_assignment_id)
        REFERENCES group_rule_assignments(id)
        ON DELETE CASCADE
);

-- =====================================================
-- INDEXES
-- =====================================================

CREATE INDEX idx_assignment_group
ON group_rule_assignments(group_id);

CREATE INDEX idx_assignment_rule
ON group_rule_assignments(rule_id);

CREATE INDEX idx_assignment_parent
ON group_rule_assignments(parent_assignment_id);

CREATE INDEX idx_assignment_tier
ON group_rule_assignments(tier);

-- =====================================================
-- SAMPLE RULES
-- =====================================================

INSERT INTO rules (rule_name, rule_type) VALUES
('Condition Rule 1', 'CONDITION'),
('Condition Rule 2', 'CONDITION'),
('Decision Rule 1', 'DECISION'),
('Decision Rule 2', 'DECISION'),
('Decision Rule 3', 'DECISION');

-- =====================================================
-- SAMPLE GROUPS
-- =====================================================

INSERT INTO rule_groups (group_name) VALUES
('Group A'),
('Group B');

-- =====================================================
-- SAMPLE DATA - GROUP A
--
-- Group A
-- ├── Decision Rule 1
-- └── Condition Rule 1
--      ├── Decision Rule 1
--      └── Decision Rule 2
-- =====================================================

INSERT INTO group_rule_assignments
(group_id, rule_id, parent_assignment_id, tier, sort_order)
VALUES
(1, 3, NULL, 1, 1);

INSERT INTO group_rule_assignments
(group_id, rule_id, parent_assignment_id, tier, sort_order)
VALUES
(1, 1, NULL, 1, 2);

INSERT INTO group_rule_assignments
(group_id, rule_id, parent_assignment_id, tier, sort_order)
VALUES
(1, 3, 2, 2, 1);

INSERT INTO group_rule_assignments
(group_id, rule_id, parent_assignment_id, tier, sort_order)
VALUES
(1, 4, 2, 2, 2);

-- =====================================================
-- SAMPLE DATA - GROUP B
--
-- Group B
-- ├── Condition Rule 1
-- │    ├── Decision Rule 1
-- │    └── Condition Rule 2
-- │          └── Decision Rule 2
-- └── Decision Rule 3
-- =====================================================

INSERT INTO group_rule_assignments
(group_id, rule_id, parent_assignment_id, tier, sort_order)
VALUES
(2, 1, NULL, 1, 1);

INSERT INTO group_rule_assignments
(group_id, rule_id, parent_assignment_id, tier, sort_order)
VALUES
(2, 3, 5, 2, 1);

INSERT INTO group_rule_assignments
(group_id, rule_id, parent_assignment_id, tier, sort_order)
VALUES
(2, 2, 5, 2, 2);

INSERT INTO group_rule_assignments
(group_id, rule_id, parent_assignment_id, tier, sort_order)
VALUES
(2, 4, 7, 3, 1);

INSERT INTO group_rule_assignments
(group_id, rule_id, parent_assignment_id, tier, sort_order)
VALUES
(2, 5, NULL, 1, 2);