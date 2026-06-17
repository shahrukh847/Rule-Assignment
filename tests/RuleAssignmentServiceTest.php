<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Services/RuleAssignmentService.php';

class RuleAssignmentServiceTest extends TestCase
{
    private RuleAssignmentService $service;

    protected function setUp(): void
    {
        $this->service = new RuleAssignmentService();
    }

    public function testSaveGroupReturnsTrue()
    {
        $this->assertTrue(
            $this->service->saveGroup(1)
        );
    }

    public function testServiceObjectCreated()
    {
        $this->assertInstanceOf(
            RuleAssignmentService::class,
            $this->service
        );
    }

    public function testValidateConditionRulesMethodExists()
    {
        $this->assertTrue(
            method_exists(
                $this->service,
                'validateConditionRules'
            )
        );
    }

    public function testRemoveRuleMethodExists()
    {
        $this->assertTrue(
            method_exists(
                $this->service,
                'removeRule'
            )
        );
    }
}