<?php
session_start();

require_once '../app/Controllers/GroupController.php';

$controller = new GroupController();

$action =
    $_GET['action'] ?? 'index';

switch ($action) {

    case 'create-form':
        $controller->createForm();
        break;

    case 'create':
        $controller->create();
        break;

    case 'edit':
        $controller->edit(
            (int)$_GET['id']
        );
        break;

    case 'view':
        $controller->view(
            (int)$_GET['id']
        );
        break;

    case 'add-rule':
        $controller->addRule();
        break;
    
    case 'delete-rule':
        $controller->deleteRule();
        break;

    case 'save':
        $controller->save(
            (int)$_GET['id']
        );
        break;

    default:
        $controller->index();
}