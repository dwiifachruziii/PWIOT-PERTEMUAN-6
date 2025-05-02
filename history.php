<?php
require_once '../config/database.php';
$conn = connectDB();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Data Sensor</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/all.min.css" rel="stylesheet" />
  <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Riwayat Data Sensor</h2>
      <a href="../index.html" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Waktu</th>
            <th>Suhu</th>
            <th>Status LED</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC";
          $result = $conn->query($sql);
          $no = 1;
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $statusClass = $row['led_status'] === 'ON' ? 'status-on' : 'status-off';
              echo "<tr>
                      <td>{$no}</td>
                      <td>{$row['timestamp']}</td>
                      <td>{$row['temperature']}°C</td>
                      <td><span class='status-badge {$statusClass}'>{$row['led_status']}</span></td>
                    </tr>";
              $no++;
            }
          } else {
            echo "<tr><td colspan='4' class='text-center'>Tidak ada data</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>
