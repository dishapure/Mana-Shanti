<!DOCTYPE html>
<html>
<head>
    <title>All Appointments</title>
    <script>
      // Push Notification Setup (Client Side)
      function showNotification(msg) {
        if (Notification.permission === "granted") {
          new Notification(msg);
        } else if (Notification.permission !== "denied") {
          Notification.requestPermission().then(permission => {
            if (permission === "granted") {
              new Notification(msg);
            }
          });
        }
      }
    </script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            background: #f4f4f9;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #6a1b9a;
            color: white;
        }
        h1 {
            color: #4a148c;
            margin-bottom: 20px;
        }
        form.action-form {
            display: inline;
        }
        .btn {
            padding: 6px 14px;
            margin: 0 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            font-weight: bold;
            transition: background 0.3s;
        }
        .accept {
            background-color: #43a047;
        }
        .accept:hover {
            background-color: #388e3c;
        }
        .reject {
            background-color: #e53935;
        }
        .reject:hover {
            background-color: #c62828;
        }
        .back-home {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #6a1b9a;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .back-home:hover {
            background-color: #4a148c;
        }
    </style>
</head>
<body>

<a href="index.html" class="back-home">← Back to Home</a>

<h1>All Appointments</h1>
<?php
$file = "appointments.txt";
if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (count($lines) > 0) {
        echo "<table>
                <tr>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Birthplace</th>
                    <th>Referred By</th>
                    <th>Profession</th>
                    <th>Location</th>
                    <th>Property Type</th>
                    <th>Phone</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>";
        foreach ($lines as $index => $line) {
            $cols = explode('|', $line);
            $status = '';

            if (strpos(end($cols), 'status:') === 0) {
                $status = strtoupper(str_replace('status:', '', array_pop($cols)));
            }

            echo "<tr>";
            $expectedCols = 9;
            for ($i = 0; $i < $expectedCols; $i++) {
                $val = isset($cols[$i]) ? htmlspecialchars($cols[$i]) : '';
                echo "<td>$val</td>";
            }

            echo "<td>" . ($status ?: 'Pending') . "</td>";

            if (!$status) {
                echo "<td>
                        <form class='action-form' method='POST' action='update_status.php' onsubmit=\"showNotification('Appointment Accepted. Notification sent to user.');\">
                            <input type='hidden' name='line_index' value='{$index}'>
                            <input type='hidden' name='action' value='accept'>
                            <button class='btn accept' type='submit'>Accept</button>
                        </form>
                        <form class='action-form' method='POST' action='update_status.php'>
                            <input type='hidden' name='line_index' value='{$index}'>
                            <input type='hidden' name='action' value='reject'>
                            <button class='btn reject' type='submit'>Reject</button>
                        </form>
                      </td>";
            } else {
                echo "<td>—</td>";
            }

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No appointments found.</p>";
    }
} else {
    echo "<p>No appointments file found.</p>";
}
?>
</body>
</html>
