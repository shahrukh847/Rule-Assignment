<!DOCTYPE html>
<html>
<head>
    <title>Edit Group</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <style>
        .btn {
            margin-left: 5px;
        }
    </style>
</head>
<body>

<div id="app" class="container mt-4">

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h1 class="mb-4">
        <?= htmlspecialchars($group['group_name']) ?>
    </h1>

    <div class="card mb-4">

        <div class="card-header">
            <strong>Add Rule</strong>
        </div>

        <div class="card-body">

            <form method="post" action="index.php?action=add-rule">

                <input type="hidden" name="group_id" value="<?= $group['id'] ?>">

                <div class="mb-3">

                    <label class="form-label">
                        Rule
                    </label>

                    <select class="form-select"  name="rule_id"  required>
                        <?php foreach ($rules as $rule): ?>

                            <option value="<?= $rule['id'] ?>">
                                <?= htmlspecialchars($rule['rule_name']) ?>
                                (<?= $rule['rule_type'] ?>)
                            </option>

                        <?php endforeach; ?>
                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Parent Rule
                    </label>

                    <select class="form-select" name="parent_assignment_id">

                        <option value="">
                            Root Node
                        </option>

                        <?php foreach ($assignments as $assignment): ?>

                            <?php if ($assignment['rule_type'] === 'CONDITION'): ?>

                                <option value="<?= $assignment['id'] ?>">
                                    <?= htmlspecialchars($assignment['rule_name']) ?>
                                    (Tier <?= $assignment['tier'] ?>)
                                </option>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    </select>

                    <small class="text-muted">
                        Only Condition Rules can be parents.
                    </small>

                </div>

                <button type="submit" class="btn btn-primary">
                    Add Rule
                </button>

                <a href="index.php?action=save&id=<?= $group['id'] ?>" class="btn btn-success">
                    Save Group
                </a>

                <a href="index.php" class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>

    </div>

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Current Assignments
            </strong>

            <input
                type="text"
                class="form-control"
                style="width:250px"
                placeholder="Search Rule..."
                v-model="searchText">

        </div>

        <div class="card-body">

            <tree-node
                v-for="node in filteredTree"
                :key="node.id"
                :node="node">
            </tree-node>

        </div>

    </div>

</div>

<script>

window.initialTree =
<?= json_encode($tree); ?>;

</script>

<script>

const TreeNode = {

    name: 'TreeNode',

    props: ['node'],

    data() {

        return {
            expanded: true
        };
    },

    template: `

        <div>

            <div
                class="border rounded p-2 mb-2 bg-light">

                <span
                    v-if="node.children &&
                        node.children.length"
                    @click="expanded = !expanded"
                    style="cursor:pointer">

                    {{ expanded ? '▼' : '▶' }}

                </span>

                <span v-else>

                    •

                </span>

                <i class="bi bi-diagram-3 ms-2"></i>

                <strong>

                    {{ node.rule_name }}

                </strong>

                <span  class="badge"
                    :class="
                        node.rule_type === 'CONDITION'
                        ? 'bg-warning text-dark'
                        : 'bg-success'
                    ">

                    {{ node.rule_type }}

                </span>

                <span
                    class="badge bg-info text-dark ms-2">

                    Tier {{ node.tier }}

                </span>

                <a
                    class="btn btn-danger btn-sm float-end"
                    :href="'index.php?action=delete-rule&id=' + node.id"
                    onclick="return confirm('Delete this rule?')">

                    Delete

                </a>

            </div>

            <div
                v-if="expanded"
                style="margin-left:40px">

                <TreeNode
                    v-for="child in node.children"
                    :key="child.id"
                    :node="child">
                </TreeNode>

            </div>

        </div>
    `
};

const { createApp } = Vue;

createApp({

    components: {

        TreeNode
    },

    data() {

        return {

            tree: window.initialTree,

            searchText: ''
        };
    },

    computed: {

        filteredTree() {

            if (!this.searchText) {

                return this.tree;
            }

            const search =
                this.searchText.toLowerCase();

            const filterNodes =
                (nodes) => {

                    return nodes
                        .filter(node => {

                            const selfMatch =
                                node.rule_name
                                    .toLowerCase()
                                    .includes(search);

                            const childMatch =
                                node.children &&
                                filterNodes(
                                    node.children
                                ).length;

                            return selfMatch ||
                                   childMatch;
                        })
                        .map(node => ({

                            ...node,

                            children:
                                node.children
                                    ? filterNodes(
                                        node.children
                                      )
                                    : []

                        }));
                };

            return filterNodes(this.tree);
        }
    }

}).mount('#app');

</script>

</body>
</html>