# Entity Relationship Diagram

┌─────────────────────┐
│      rule_groups    │
├─────────────────────┤
│ PK id               │
│ group_name          │
└──────────┬──────────┘
           │ 1
           │
           │ N
┌──────────▼──────────────────┐
│   group_rule_assignments    │
├─────────────────────────────┤
│ PK id                       │
│ FK group_id                 │
│ FK rule_id                  │
│ FK parent_assignment_id     │
│ tier                        │
│ sort_order                  │
└───────┬─────────────┬───────┘
        │             │
        │ N           │ N
        │             │
        │ 1           │ 1
        │             │
┌───────▼───────┐     │
│     rules     │     │
├───────────────┤     │
│ PK id         │     │
│ rule_name     │     │
│ rule_type     │     │
└───────────────┘     │
                      │
                      ▼

            Self Relationship

      parent_assignment_id
                │
                ▼
       group_rule_assignments.id
