<!DOCTYPE html>
<html>
<head>

    <title>Create Group</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-4">

    <div class="card p-4">

        <h2>Create Group</h2>

        <form method="post" action="index.php?action=create" >

            <div class="mb-3">

                <label class="form-label">
                    Group Name
                </label>

                <input type="text"  name="group_name" class="form-control"  required >
            </div>

            <button type="submit" class="btn btn-primary" >
                Save
            </button>

        </form>

    </div>

</div>

</body>
</html>