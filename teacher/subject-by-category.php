<?php
require_once "../process/connector.php";
$category_id = $_POST["category_id"];
$result = mysqli_query($link,"SELECT SUB_ID, TITLE FROM TB_SUBJECTS WHERE CAT_ID = $category_id");
?>
<option value="">Select Subject</option>
<?php
while($row = mysqli_fetch_assoc($result)) {
?>
    <option value="<?= $row['SUB_ID']; ?>">
        <?= $row['TITLE']; ?>
    </option>
<?php
}
?>
