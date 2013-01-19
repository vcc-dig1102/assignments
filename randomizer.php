#!/usr/bin/env php
<?php
/**
 * Pick a random pair from a list of students stored in a CSV file provided on
 * the command line, because that's what Atlas can give me... C'mon, Atlas!
 */

function usage()
{
    echo '
    Provide a CSV file that contains at least "First Name" and "Last Name"
    fields with a header row, and "randomizer" will spit out a randomly
    assigned set of pairs (or trios) from that roster.';

    exit;
}

($argc - 1) or usage();

file_exists($argv[1]) and $file = fopen($argv[1], 'r') or exit(1);

$fields = array_map(function($field){
    return strtolower(str_replace(' ', '_', $field));
}, fgetcsv($file));

while ( $data = fgetcsv($file) )
{
    $record = (object) array_combine($fields, $data);
    $names[] = "{$record->first_name} {$record->last_name}";
}

shuffle($names);

$one; $two;

echo 'Randomly selected pairs:';

while ( $one = current($names) and $two = next($names) )
{
    echo "\n\n\t* $one\n\t* $two";
    next($names);
}

if ( !$two ) echo "\n\t* $one";

