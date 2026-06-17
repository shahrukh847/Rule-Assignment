<?php

require_once __DIR__ . '/../Services/RuleAssignmentService.php';
require_once __DIR__ . '/../Repositories/GroupRepository.php';
require_once __DIR__ . '/../Repositories/RuleRepository.php';
require_once __DIR__ . '/../Repositories/AssignmentRepository.php';
require_once __DIR__ . '/../Services/TreeBuilder.php';

class GroupController
{
    private GroupRepository $groupRepo;
    private RuleRepository $ruleRepo;
    private AssignmentRepository $assignmentRepo;
    private RuleAssignmentService $assignmentService;

    public function __construct()
    {
        $this->groupRepo = new GroupRepository();
        $this->ruleRepo = new RuleRepository();
        $this->assignmentRepo = new AssignmentRepository();
        $this->assignmentService = new RuleAssignmentService();
    }

    /**
     * List all groups
     */
    public function index(): void
    {
        $groups = $this->groupRepo->getAll();

        require __DIR__ . '/../Views/group-list.php';
    }

    /**
     * Show create group form
     */
    public function createForm(): void
    {
        require __DIR__ . '/../Views/group-form.php';
    }

    /**
     * Create new group
     */
    public function create(): void
    {
        try {

            $groupName = trim($_POST['group_name']);

            if (empty($groupName)) {
                throw new Exception(
                    'Group name is required.'
                );
            }

            $groupId = $this->groupRepo->create(
                $groupName
            );

            header(
                "Location: index.php?action=edit&id={$groupId}"
            );
            exit;

        } catch (Exception $e) {

            echo $e->getMessage();
        }
    }

    /**
     * Show edit page
     */
    public function edit(int $groupId): void
    {
        $group = $this->groupRepo->findById(
            $groupId
        );

        $rules = $this->ruleRepo->getAll();

        $rows =
            $this->assignmentRepo
                ->getByGroup($groupId);

        $treeBuilder =
            new TreeBuilder();

        $treeBuilder = new TreeBuilder();

        $treeAssignments = $treeBuilder->buildTree($rows);

        $assignments = $rows; // for dropdown

        $tree = $treeAssignments; // for tree display

        require __DIR__ . '/../Views/group-edit.php';
    }

    /**
     * Add rule to hierarchy
     */
    public function addRule(): void
    {
        try {

            $groupId =
                (int)$_POST['group_id'];

            $ruleId =
                (int)$_POST['rule_id'];

            $parentAssignmentId =
                !empty($_POST['parent_assignment_id'])
                    ? (int)$_POST['parent_assignment_id']
                    : null;

            $this->assignmentService
                ->addRule(
                    $groupId,
                    $ruleId,
                    $parentAssignmentId,
                );

            header(
                "Location: index.php?action=edit&id={$groupId}"
            );
            exit;

        } 
            catch (Exception $e) {

            $_SESSION['error'] =
                $e->getMessage();

            header(
                "Location: index.php?action=edit&id={$groupId}"
            );

            exit;
        }
    }

    /**
     * View hierarchy
     */
    public function view(int $groupId): void
    {
        $group =
            $this->groupRepo
                 ->findById($groupId);

        $rows =
            $this->assignmentRepo
                ->getByGroup($groupId);

        $treeBuilder =
            new TreeBuilder();

        $assignments =
            $treeBuilder->buildTree($rows);

        require __DIR__ . '/../Views/group-view.php';
    }

    /**
     * Final validation before save
     */
    public function save(int $groupId): void
    {
        try {

            $this->assignmentService
                ->validateConditionRules(
                    $groupId
                );

            $_SESSION['success'] =
                'Group saved successfully.';

        } catch (Exception $e) {

            $_SESSION['error'] =
                $e->getMessage();
        }

        header(
            "Location: index.php?action=edit&id={$groupId}"
        );

        exit;
    }

    public function deleteRule(): void
    {
        try {

            $assignmentId =
                (int)$_GET['id'];

            $this->assignmentService
                ->removeRule(
                    $assignmentId
                );

            header(
                'Location: ' .
                $_SERVER['HTTP_REFERER']
            );

            exit;

        } catch (Exception $e) {

            echo $e->getMessage();
        }
    }
}