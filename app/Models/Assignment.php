<?php

class Assignment
{
    public int $id;
    public int $groupId;
    public int $ruleId;
    public ?int $parentAssignmentId;
    public int $tier;
    public int $sortOrder;
}