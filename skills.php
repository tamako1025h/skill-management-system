<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 削除処理
if (isset($_GET['delete_id'])) {
    $conn = new mysqli("db", "webuser", "password123", "mysite");
    
    if ($conn->connect_error) {
        die("接続失敗: " . $conn->connect_error);
    }
    
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM skills WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $message = "削除しました";
    } else {
        $message = "削除エラー: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>スキル一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>保有スキル一覧（データベースから取得）</h1>
    
    <?php if (isset($message)) echo "<p style='color: green;'>$message</p>"; ?>
    
    <?php
    // データベース接続
    $conn = new mysqli("db", "webuser", "password123", "mysite");
    
    // 接続確認
    if ($conn->connect_error) {
        die("接続失敗: " . $conn->connect_error);
    }
    
    // データ取得
    $sql = "SELECT * FROM skills ORDER BY years DESC";
    $result = $conn->query($sql);
    
    // テーブル表示
    echo "<table border='1' style='background-color: white; width: 100%;'>";
    echo "<tr><th>ID</th><th>スキル名</th><th>レベル</th><th>経験年数</th><th>操作</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["skill_name"] . "</td>";
        echo "<td>" . $row["level"] . "</td>";
        echo "<td>" . $row["years"] . "年</td>";
        echo "<td>
        <a href='edit_skill.php?id=" . $row["id"] . "'>編集</a> |
        <a href='?delete_id=" . $row["id"] . "' onclick='return confirm(\"本当に削除しますか?\");' style='color: red;'>削除</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    $conn->close();
    ?>
    
    <br>
    <a href="add_skill.php">スキルを追加</a> | 
    <a href="index.html">トップに戻る</a>
</body>
</html>