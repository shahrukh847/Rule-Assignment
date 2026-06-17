<!DOCTYPE html>
<html>
<head>

    <title>View Group</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        .tree-node {
            border-left: 3px solid #dee2e6;
            padding-left: 15px;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .rule-card {
            padding: 10px;
            border-radius: 8px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
    </style>

</head>
<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            <i class="bi bi-diagram-3"></i>
            <?= $group['group_name'] ?>
        </h2>

        <a href="index.php" class="btn btn-secondary">
            Back
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">

            <strong>
                Rule Hierarchy
            </strong>

        </div>

        <div class="card-body">

            <?php

            function renderTree(
                array $nodes,
                int $level = 0
            )
            {
                foreach ($nodes as $node) {

                    ?>

                    <div class="tree-node" style="margin-left: <?= $level * 30 ?>px;" >

                        <div class="rule-card">

                            <i class="bi bi-diagram-3"></i>

                            <strong>
                                <?= htmlspecialchars(
                                    $node['rule_name']
                                ) ?>
                            </strong>

                            <?php  if (  $node['rule_type'] === 'CONDITION' ): ?>

                                <span class="badge bg-warning text-dark ms-2" >
                                    CONDITION
                                </span>

                            <?php else: ?>

                                <span  class="badge bg-success ms-2">
                                    DECISION
                                </span>

                            <?php endif; ?>

                            <span class="badge bg-info text-dark ms-2">
                                Tier <?= $node['tier'] ?>
                            </span>

                        </div>

                        <?php
                        if (  !empty( $node['children'] )) 
                        {
                            renderTree(
                                $node['children'],
                                $level + 1
                            );
                        }
                        ?>

                    </div>

                    <?php
                }
            }

            renderTree($assignments);

            ?>

        </div>
    </div>
</div>

</body>
</html>
