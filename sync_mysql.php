<?php
require_once '../config/database.php';

date_default_timezone_set('Asia/Jakarta');
$firebase_url = "https://tesds-led-default-rtdb.firebaseio.com/.json";

try {
    $conn = connectDB();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $firebase_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('Error saat mengambil data dari Firebase: ' . curl_error($ch));
    }
    curl_close($ch);

    $data = json_decode($response, true);

    if ($data && isset($data['records'])) {
        $stmt = $conn->prepare("INSERT INTO sensor_data (temperature, led_status, timestamp) VALUES (?, ?, ?)");
        foreach ($data['records'] as $timestamp => $record) {
            if (!isset($record['temperature']) || !isset($record['led_status'])) continue;
            $timestamp_seconds = floor($timestamp / 1000);
            $formatted_timestamp = date('Y-m-d H:i:s', $timestamp_seconds);

            $stmt->bind_param("dss", $record['temperature'], $record['led_status'], $formatted_timestamp);
            $stmt->execute();
        }
        $stmt->close();

        echo json_encode([
            'success' => true,
            'message' => "Sinkronisasi selesai",
            'data' => $data['records']
        ]);
    } else {
        throw new Exception("Data tidak ditemukan atau format salah");
    }

    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
