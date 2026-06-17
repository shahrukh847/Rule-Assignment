<?php

class GroupRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM rule_groups ORDER BY id DESC"
        );

        return $stmt->fetchAll();
    }

    public function create(string $groupName): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO rule_groups(group_name)
             VALUES(?)"
        );

        $stmt->execute([$groupName]);

        return (int)$this->db->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM rule_groups WHERE id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch();
    }
}