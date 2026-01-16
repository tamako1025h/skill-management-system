<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// フォームが送信された時の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("db", "webuser", "password123", "mysite");
    
    if ($conn->connect_error) {
        die("接続失敗: " . $conn->connect_error);
    }
    
    $skill_name = $_POST['skill_name'];
    $level = $_POST['level'];
    $years = $_POST['years'];
    
    $sql = "INSERT INTO skills (skill_name, level, years) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $skill_name, $level, $years);
    
    if ($stmt->execute()) {
        $message = "スキルを追加しました!";
    } else {
        $message = "エラー: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>スキル追加</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>新しいスキルを追加</h1>
    
    <?php if (isset($message)) echo "<p style='color: green;'>$message</p>"; ?>
    
    <form method="POST" action="" style="background-color: white; padding: 20px; border-radius: 5px;">
        <label>スキル名:</label><br>
        <input type="text" name="skill_name" required style="width: 100%; padding: 8px; margin: 5px 0 15px 0;"><br>
        
        <label>レベル:</label><br>
        <select name="level" style="width: 100%; padding: 8px; margin: 5px 0 15px 0;">
            <option>初級</option>
            <option>中級</option>
            <option>上級</option>
        </select><br>
        
        <label>経験年数:</label><br>
        <input type="number" name="years" min="0" required style="width: 100%; padding: 8px; margin: 5px 0 15px 0;"><br>
        
        <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">追加</button>
    </form>
    
    <br>
    <a href="skills.php">スキル一覧に戻る</a> | 
    <a href="index.html">トップに戻る</a>
</body>
</html>