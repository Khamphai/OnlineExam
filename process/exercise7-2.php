<?php
include_once 'connect_db.php';
$start = @$_GET['start'];
if (isset($_GET['next']))
    $start += 20;
if (isset($_GET['prev']))
    $start -= 20;
$category_id = @$_GET['category'];
$name = @$_GET['name'];
$where = " WHERE "
        .($category_id > 0 ? " c.id=$category_id" : "")
        .($name != "" ? " p.name LIKE '%" . mysqli_real_escape_string($connect_db, $name) . "%'" : "");

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exercise 7-2</title>
    <style>
        .form-group {
            margin-bottom: 5px;
        }

        .title {
            text-align: right;
            display: inline-block;
            width: 80px;
            padding: 10px;
        }

        .values {
            display: inline-block;
            padding: 10px;
        }

        .customTB {
            border-collapse: collapse;
            align-content: center;
            width: 100%;
        }

        .customTB th, td {
            border: 1px solid black;
        }

        .customTB th, td {
            padding: 10px 30px;
        }
        .customTB th {
            background: #555555;
            color: #E5E5E5;
        }
        .customTB tr:hover {
            background: darkgrey;
            color: white;
        }

        .next_prev {
            border: white;
            text-decoration: underline;
            background: white;
            font-weight: bolder;
        }
    </style>
</head>
<body>
<div style="margin: 50px;">
    <form method="GET">
        <div class="form-group">
            <div class="title">Category:</div>
            <div class="values">
                <select name="category" onchange="submit();">
                    <option>All Categories</option>
                    <?php
                    $sql = "select id, name from category order by name asc;";
                    $result = mysqli_query($connect_db, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <option value="<?= $row['id']; ?>" <?php if ($category_id === $row['id']) echo "selected"; ?>><?= $row['name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="title">Name:</div>
            <div class="values">
                <label>
                    <input type="text" name="name" size="30" value="<?= htmlspecialchars($name) ?>"/>
                </label>
            </div>
            <input type="submit" name="search" value="Search">
        </div>

        <table class="customTB">
            <tr>
                <th>No.</th>
                <th>Product Name</th>
                <th>Category Name</th>
                <th>Price</th>
            </tr>
            <?php

            $sql = "select p.name, c.name as cat_name, p.price from product p join category c on p.category_id = c.id $where order by p.id limit $start, 20";
            echo $sql;

            $result = mysqli_query($connect_db, $sql);
            $no = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td align="center"><?= @++$no ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['cat_name']) ?></td>
                    <td><?= number_format($row['price']) ?></td>
                </tr>
                <?php
            }
            ?>
        </table>

        <br/>
        <table style="width: 100%">
            <tr>
                <td style="border: none;">
                    <input class="next_prev" type="submit" name="prev" value="Previous Page">
                </td>
                <td style="border: none; text-align: right;">
                    <input class="next_prev" type="submit" name="next" value="Next Page">
                </td>
            </tr>
        </table>
        <input type="hidden" name="start"  value="<?= $start; ?>">
    </form>
</div>
</body>
</html>