<?php

require_once __DIR__ . '/../Repositories/GroupRepository.php';
class GroupService
{
    private GroupRepository $groupRepo;

    public function __construct( ?GroupRepository $groupRepo = null)
    {
        $this->groupRepo =
            $groupRepo ?? new GroupRepository();
    }

    public function createGroup(string $groupName ): int {

        $existing =
            $this->groupRepo
                 ->findByName($groupName);

        if ($existing) {
            throw new Exception(
                'Group name already exists.'
            );
        }

        return $this->groupRepo
                    ->create($groupName);
    }
}