<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/config/Database.php';

$database = new Database();
$db = $database->getConnection();

// Delete message if requested
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM messages WHERE id = :id");
    $stmt->bindParam(':id', $delete_id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}

// Fetch all messages
$query = "SELECT * FROM messages ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - iPortfolio</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Vendor CSS -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4fafd;
            font-family: 'Roboto', sans-serif;
            color: #272829;
        }
        .navbar {
            background-color: #040b14;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .main-content {
            padding: 40px 15px;
        }
        .table {
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead {
            background-color: #149ddd;
            color: #fff;
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 15px;
        }
        .btn-delete {
            color: #df1529;
            background: none;
            border: none;
            padding: 0;
            font-size: 1.2rem;
            cursor: pointer;
            transition: color 0.3s;
        }
        .btn-delete:hover {
            color: #a00b1a;
        }
        .badge-total {
            background-color: #149ddd;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">iPortfolio Admin Console</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-weight: 700; color: #050d18;">Messages Overview</h2>
        <span class="badge badge-total px-3 py-2 rounded-pill">Total: <?php echo count($messages); ?></span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col" width="5%">#</th>
                    <th scope="col" width="15%">Name</th>
                    <th scope="col" width="20%">Email</th>
                    <th scope="col" width="20%">Subject</th>
                    <th scope="col" width="30%">Message</th>
                    <th scope="col" width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $index => $msg): ?>
                        <tr>
                            <th scope="row"><?php echo $index + 1; ?></th>
                            <td><?php echo htmlspecialchars($msg['name']); ?></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>"><?php echo htmlspecialchars($msg['email']); ?></a></td>
                            <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
                            <td class="text-center">
                                <a href="dashboard.php?delete=<?php echo $msg['id']; ?>" class="btn-delete" title="Delete Message" onclick="return confirm('Are you sure you want to delete this message?');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No messages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
