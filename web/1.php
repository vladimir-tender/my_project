<?php
function foo($item)
{
    echo $item . "_!";
}

$data = [
    "A" => ["0", "1", "2"],
    "B" => ["5", "4",
        "3" => ["11", "22"]
    ]
];

array_map('foo', $data);

