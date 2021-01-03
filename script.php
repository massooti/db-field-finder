<?php

/**
 * do find & replace for a specific value of field in all the databse tables dynamicly.
 * @author Masoud
 * @link  https://github.com/massouti/db_find_replace.git
 */

$pdo = new PDO('mysql:host=db_host;dbname=db_name', 'db_user', 'db_password');

//Our SQL statement, which will select a list of tables from the current MySQL database.
$sql = "SHOW TABLES";

//////Prepare our SQL statement,
$t_statement = $pdo->prepare($sql);

////Execute the statement.
$t_statement->execute();

////Fetch the tables from our statement.
$tables = $t_statement->fetchAll(PDO::FETCH_NUM);

foreach ($tables as $table) {

    $sql = "SHOW COLUMNS FROM $table[0]";

    $c_statement = $pdo->prepare($sql);
    $c_statement->execute();

    $raw_column_data = $c_statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($raw_column_data as $key => $coloum) {
        $field = $coloum['Field'];
        $command = "UPDATE  `$table[0]` SET  `$field` =
                REPLACE($field, 'old_strings', 'new_strings')
                WHERE  $field LIKE 'old_strings%';";
        $f_statement = $pdo->prepare($command);
        $f_statement->execute();
    }
}
?>