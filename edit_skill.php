<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "webuser", "password123", "mysite");

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// 編集するスキルのIDを取得
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 既存データを取得
    $sql = "SELECT * FROM skills WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $skill = $result->fetch_assoc();
    $stmt->close();
}

// フォーム送信時の更新処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $skill_name = $_POST['skill_name'];
    $level = $_POST['level'];
    $years = $_POST['years'];
    
    $sql = "UPDATE skills SET skill_name = ?, level = ?, years = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $skill_name, $level, $years, $id);
    
    if ($stmt->execute()) {
        header("Location: skills.php");
        exit();
    } else {
        $error = "更新エラー: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>スキル編集</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>スキルを編集</h1>
    
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    
    <?php if (isset($skill)): ?>
    <form method="POST" action="" style="background-color: white; padding: 20px; border-radius: 5px;">
        <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
        
        <label>スキル名:</label><br>
        <input type="text" name="skill_name" value="<?php echo htmlspecialchars($skill['skill_name']); ?>" required style="width: 100%; padding: 8px; margin: 5px 0 15px 0;"><br>
        
        <label>レベル:</label><br>
        <select name="level" style="width: 100%; padding: 8px; margin: 5px 0 15px 0;">
            <option <?php if($skill['level']=='初級') echo 'selected'; ?>>初級</option>
            <option <?php if($skill['level']=='中級') echo 'selected'; ?>>中級</option>
            <option <?php if($skill['level']=='上級') echo 'selected'; ?>>上級</option>
        </select><br>
        
        <label>経験年数:</label><br>
        <input type="number" name="years" value="<?php echo $skill['years']; ?>" min="0" required style="width: 100%; padding: 8px; margin: 5px 0 15px 0;"><br>
        
        <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">更新</button>
    </form>
    <?php else: ?>
    <p>編集するスキルが見つかりません</p>
    <?php endif; ?>
    
    <br>
    <a href="skills.php">スキル一覧に戻る</a>
</body>
</html>