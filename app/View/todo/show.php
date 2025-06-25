<?php

function getTimeLeft($deadline)
{
    $now = time();

    if ($deadline <= $now) {
        return "Deadline passed";
    }

    $diff = $deadline - $now;

    $days = floor($diff / (60 * 60 * 24));
    $hours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
    $minutes = floor(($diff % (60 * 60)) / 60);

    $result = [];

    if ($days > 0) {
        $result[] = "$days day" . ($days > 1 ? 's' : '');
    }

    if ($hours > 0) {
        $result[] = "$hours hour" . ($hours > 1 ? 's' : '');
    }

    if ($days === 0 && $minutes > 0) {
        $result[] = "$minutes minute" . ($minutes > 1 ? 's' : '');
    }

    return implode(' ', $result) . ' left';
}

// echo "<pre>";
// print_r($todo);
// echo "</pre>";
?>

<!doctype html>
<html en="lang">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="/assets/index-BvRfNH8U.css">

    <!-- <script type="module" src="http://localhost:5173/resources/tailwind/todo_show.ts"></script> -->

    <script type="module" src="/assets/todo_show-ZI7IqLeI.js"></script>

    <title>Show todo</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4 text-gray-800"><?= htmlspecialchars($todo->title) ?></h1>

        <p class="mb-2 text-gray-600"><strong>Description:</strong> <?= htmlspecialchars($todo->description) ?></p>

        <p class="mb-2 text-gray-600"><strong>Status:</strong>
            <span class="inline-block px-2 py-1 rounded 
                <?= $todo->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ""; ?>
                <?= $todo->status === 'processed' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                <?= htmlspecialchars($todo->status) ?>
            </span>
        </p>
        <p class="mb-6 text-gray-600"><strong>Deadline:</strong> <?= getTimeLeft($todo->deadline) ?></p>
        <?php
        foreach ($todo->tags as $tag) {
            echo '<span class="inline-block bg-sky-200 text-gray-800 px-4 py-2 text-sm rounded-3xl font-bold mr-2 mb-2">' . htmlspecialchars($tag->title) . '</span>';
        }
        ?>
        <div class="flex justify-between space-x-4">
            <a href="/todo"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded text-center">
                Back
            </a>
            <a href="/todo/<?= $todo->id ?>/update"
                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-center">
                Update
            </a>
            <a href="#" onclick="deleteTodo(<?= $todo->id ?>)"
                onclick="return confirm('Are you sure you want to delete this todo?');"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded text-center">
                Remove
            </a>
        </div>
    </div>
    <script>
        function deleteTodo(id) {
            if (confirm("Are you sure you want to delete this todo?")) {
                fetch('/todo/' + id, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log("Todo deleted successfully.");
                            location.replace('/todo'); // Refresh the page to see the updated list
                        } else {
                            console.error("Failed to delete todo.");
                        }
                    })
                    .catch(error => {
                        alert("Error:", error);
                    });

            } else {
                console.log("Delete action canceled.");
            }
        }
    </script>
</body>

</html>