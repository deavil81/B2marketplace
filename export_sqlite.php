<?php

$db = new PDO('sqlite:database/database.sqlite');

// Open the output file
$file = fopen('sqlite_data.sql', 'w');

// Get all tables
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);

foreach ($tables as $table) {
    // Dump table structure
    $create = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$table}'")->fetchColumn();
    fwrite($file, "{$create};\n");

    // Dump table data
    $rows = $db->query("SELECT * FROM {$table}");
    while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
        $columns = implode(', ', array_keys($row));
        $values = implode(', ', array_map(fn($val) => $db->quote($val), array_values($row)));
        fwrite($file, "INSERT INTO {$table} ({$columns}) VALUES ({$values});\n");
    }
}

fclose($file);

echo "SQLite data exported to sqlite_data.sql\n";
