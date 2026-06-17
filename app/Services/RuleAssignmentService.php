<?php

require_once __DIR__ . '/../Repositories/AssignmentRepository.php';
require_once __DIR__ . '/../Repositories/RuleRepository.php';

class RuleAssignmentService
{
    private AssignmentRepository $assignmentRepo;
    private RuleRepository $ruleRepo;

    public function __construct( ?AssignmentRepository $assignmentRepo = null, ?RuleRepository $ruleRepo = null )
    {
        $this->assignmentRepo =
            $assignmentRepo ?? new AssignmentRepository();

        $this->ruleRepo =
            $ruleRepo ?? new RuleRepository();
    }

    /**
     * Main method called by controller
     */
    public function addRule(
        int $groupId,
        int $ruleId,
        ?int $parentAssignmentId,
        int $sortOrder = 1
    ): int {

        $tier = 1;

        if ($parentAssignmentId !== null) {

            $parentAssignment =
                $this->assignmentRepo
                     ->findAssignment($parentAssignmentId);

            if (!$parentAssignment) {
                throw new Exception(
                    'Parent assignment not found.'
                );
            }

            $tier =
                $parentAssignment['tier'] + 1;

            $this->validateParentRule(
                $parentAssignmentId
            );
        }

        $this->validateTier($tier);

        $this->validateDuplicate(
            $groupId,
            $ruleId,
            $parentAssignmentId
        );

        return $this->assignmentRepo->create(
            $groupId,
            $ruleId,
            $parentAssignmentId,
            $tier,
            $sortOrder
        );
    }

    /**
     * Maximum 3 tiers
     */
    private function validateTier(
        int $tier
    ): void {

        if ($tier > 3) {

            throw new Exception(
                'Maximum hierarchy depth is 3 tiers.'
            );
        }
    }

    /**
     * Decision Rule cannot have children
     */
    private function validateParentRule(
        int $parentAssignmentId
    ): void {

        $parentAssignment =
            $this->assignmentRepo
                 ->findAssignment(
                     $parentAssignmentId
                 );

        if (!$parentAssignment) {
            throw new Exception(
                'Parent assignment not found.'
            );
        }

        $parentRule =
            $this->ruleRepo
                 ->findById(
                     $parentAssignment['rule_id']
                 );

        if (!$parentRule) {
            throw new Exception(
                'Parent rule not found.'
            );
        }

        if (
            $parentRule['rule_type']
            === 'DECISION'
        ) {

            throw new Exception(
                'Decision Rule cannot have child rules.'
            );
        }
    }

    /**
     * Prevent duplicate rule
     * under same parent
     */
    private function validateDuplicate(
        int $groupId,
        int $ruleId,
        ?int $parentAssignmentId
    ): void {

        $exists =
            $this->assignmentRepo
                 ->existsUnderParent(
                     $groupId,
                     $ruleId,
                     $parentAssignmentId
                 );

        if ($exists) {

            throw new Exception(
                'Rule already exists under this parent.'
            );
        }
    }

    /**
     * Validate all Condition Rules
     * before final save/view
     */
    public function validateConditionRules(
        int $groupId
    ): void {

        $assignments =
            $this->assignmentRepo
                 ->getByGroup($groupId);

        foreach ($assignments as $assignment) {

            if (
                $assignment['rule_type']
                === 'CONDITION'
            ) {

                $childCount =
                    $this->assignmentRepo
                         ->countChildren(
                             $assignment['id']
                         );

                if ($childCount === 0) {

                    throw new Exception(
                        'Condition Rule "' .
                        $assignment['rule_name'] .
                        '" must have at least one child.'
                    );
                }
            }
        }
    }

    /**
     * Remove node
     */
    public function removeRule(
        int $assignmentId
    ): void {

        $this->assignmentRepo
            ->deleteWithChildren(
                $assignmentId
            );
    }

    /**
     * Save group hierarchy
     */
    public function saveGroup(
        int $groupId
    ): bool {

        $this->validateConditionRules(
            $groupId
        );

        return true;
    }
}