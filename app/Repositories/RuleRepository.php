<?php

require_once dirname(__DIR__) . '/config/Database.php';

class RuleRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM rules ORDER BY rule_name"
        );

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM rules WHERE id = ?"
        );

        $stmt->execute([$id]);
        $rule = $stmt->fetch();

        return $rule ?: null;
    }
}