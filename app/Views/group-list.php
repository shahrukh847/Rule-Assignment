<!DOCTYPE html>
<html>
<head>

    <title>Groups</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-4">

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h1>Groups</h1>

    <a href="index.php?action=create-form"  class="btn btn-primary mb-3" >
        Create Group
    </a>

    <table class="table table-bordered">

        <thead>

        <tr>
            <th>ID</th>
            <th>Group</th>
            <th>Actions</th>
        </tr>

        </thead>

        <tbody>

        <?php foreach ($groups as $group): ?>

            <tr>
                <td>
                    <?= $group['id'] ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $group['group_name']
                    ) ?>
                </td>

                <td>
                    <a  class="btn btn-info btn-sm" href="index.php?action=view&id=<?= $group['id'] ?>" >
                        View
                    </a>

                    <a class="btn btn-warning btn-sm"  href="index.php?action=edit&id=<?= $group['id'] ?>" >
                        Edit
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

</body>
</html>