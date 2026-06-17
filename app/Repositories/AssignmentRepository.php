<?php

require_once __DIR__ . '/../Config/Database.php';

class AssignmentRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Create assignment
     */
    public function create(
        int $groupId,
        int $ruleId,
        ?int $parentAssignmentId,
        int $tier,
        int $sortOrder
    ): int {

        $sql = "
            INSERT INTO group_rule_assignments
            (
                group_id,
                rule_id,
                parent_assignment_id,
                tier,
                sort_order
            )
            VALUES
            (
                :group_id,
                :rule_id,
                :parent_assignment_id,
                :tier,
                :sort_order
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':group_id' => $groupId,
            ':rule_id' => $ruleId,
            ':parent_assignment_id' => $parentAssignmentId,
            ':tier' => $tier,
            ':sort_order' => $sortOrder
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Find assignment by id
     */
    public function findAssignment(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM group_rule_assignments
             WHERE id = ?"
        );

        $stmt->execute([$id]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Get all assignments for a group
     */
    public function getByGroup(int $groupId): array
    {
        $sql = "
            SELECT
                a.*,
                r.rule_name,
                r.rule_type
            FROM group_rule_assignments a
            INNER JOIN rules r
                ON r.id = a.rule_id
            WHERE a.group_id = ?
            ORDER BY a.id ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$groupId]);

        return $stmt->fetchAll();
    }

    /**
     * Get direct children
     */
    public function getChildren(
        int $parentAssignmentId
    ): array {

        $sql = "
            SELECT
                a.*,
                r.rule_name,
                r.rule_type
            FROM group_rule_assignments a
            INNER JOIN rules r
                ON r.id = a.rule_id
            WHERE a.parent_assignment_id = ?
            ORDER BY a.sort_order
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$parentAssignmentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count children
     * Used to validate Condition Rules
     */
    public function countChildren(
        int $assignmentId
    ): int {

        $stmt = $this->db->prepare(
            "SELECT COUNT(*)
             FROM group_rule_assignments
             WHERE parent_assignment_id = ?"
        );

        $stmt->execute([$assignmentId]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * Check if same rule already exists
     * under same parent
     */
    public function existsUnderParent(
        int $groupId,
        int $ruleId,
        ?int $parentAssignmentId
    ): bool {

        if ($parentAssignmentId === null) {

            $sql = "
                SELECT COUNT(*)
                FROM group_rule_assignments
                WHERE group_id = ?
                AND rule_id = ?
                AND parent_assignment_id IS NULL
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                $groupId,
                $ruleId
            ]);

        } else {

            $sql = "
                SELECT COUNT(*)
                FROM group_rule_assignments
                WHERE group_id = ?
                AND rule_id = ?
                AND parent_assignment_id = ?
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                $groupId,
                $ruleId,
                $parentAssignmentId
            ]);
        }

        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Delete assignment
     */
    public function delete(
        int $assignmentId
    ): bool {

        $stmt = $this->db->prepare(
            "DELETE
             FROM group_rule_assignments
             WHERE id = ?"
        );

        return $stmt->execute([
            $assignmentId
        ]);
    }

    /**
     * Delete all assignments
     * when rebuilding hierarchy
     */
    // public function deleteByGroup(
    //     int $groupId
    // ): bool {

    //     $stmt = $this->db->prepare(
    //         "DELETE
    //          FROM group_rule_assignments
    //          WHERE group_id = ?"
    //     );

    //     return $stmt->execute([
    //         $groupId
    //     ]);
    // }


    public function deleteWithChildren(
        int $assignmentId
    ): void {

        $children =
            $this->getChildren(
                $assignmentId
            );

        foreach ($children as $child) {

            $this->deleteWithChildren(
                $child['id']
            );
        }

        $stmt =
            $this->db->prepare(
                "DELETE
                FROM group_rule_assignments
                WHERE id = ?"
            );

        $stmt->execute([
            $assignmentId
        ]);
    }


}