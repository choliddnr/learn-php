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
?>



<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Todo list</title>
</head>

<body>
    <header>
        <!-- <h1>Welcome Todos Index</h1> -->
    </header>
    <main class=" max-w-8/10 justify-center mx-auto my-6">

        <!-- <h1>Params: <?= $task ?? "NO Params" ?></h1> -->
        <a href="/todo/create"
            class="flex-1 bg-sky-500 hover:bg-sky-600 text-white font-medium py-2 px-4 rounded text-center">
            Create new Task
        </a>
        <div class="overflow-x-auto mt-5">
            <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Deadline</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php
                    foreach ($todos as $todo) {
                        echo "<tr>";
                        echo "<td class='px-4 py-2 text-sm text-gray-800'>" . $todo['title'] . "</td>";
                        echo "<td class='px-4 py-2 text-sm text-gray-800'>" . $todo['description'] . "</td>";
                        echo "<td class='px-4 py-2 text-sm text-gray-800'>" . getTimeLeft($todo['deadline']) . "</td>";
                        echo "<td class='px-4 py-2 text-sm text-gray-800'>" . $todo['status'] . "</td>";
                        echo '<td class="px-4 py-2 flex items-center justify-center gap-x-2">
                          <a href="/todo/' . $todo['id'] . '" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded text-center"> <span aria-hidden="true">Show</span></a>
                          <a href="/todo/' . $todo['id'] . '/update" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded text-center"> <span aria-hidden="true">Update</span></a>
                          <a href="#" onclick="deleteTodo(' . $todo['id'] . ')" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded text-center"> <span aria-hidden="true">Delete</span></a>
                        </td>';

                        // echo '<td><div class="flex justify-between space-x-4 max-w-8">
                        // <a href="/todo/' . $todo['id'] . '"
                        // class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded text-center">
                        // Back
                        //             </a>
                        //             <a href="/todo/' . $todo['id'] . '/update"
                        //             class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-center">
                        //                 Update
                        //                 </a>
                        //             <a href="#" onclick="deleteTodo(' . $todo['id'] . ')"
                        //                 onclick="return confirm(`Are you sure you want to delete this todo?`);"
                        //                 class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded text-center">
                        //                 Remove
                        //                 </a>
                        //                 </div></td>';
                    
                        echo "</tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </main>
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
                            location.reload(); // Refresh the page to see the updated list
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