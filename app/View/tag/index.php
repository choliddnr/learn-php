<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <script type="module" src="http://localhost:5173/resources/tailwind/index.ts"></script> -->
    <script type="module" src="/assets/index-B5S487x2.js"></script>
    <link rel="stylesheet" href="/assets/index-BvRfNH8U.css">

    <title>Tag list</title>
</head>

<body>
    <header>
        <!-- <h1>Welcome Tags Index</h1> -->
    </header>
    <main class=" max-w-8/10 justify-center mx-auto my-6">
        <div class="flex gap-2">


            <a href="/tag/create"
                class="flex-1 bg-sky-500 hover:bg-sky-600 text-white font-medium py-2 px-4 rounded text-center">
                Create new tag
            </a>
            <a href="/"
                class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded text-center">
                View profile
            </a>
            <a href="/todo"
                class="flex-1 bg-blue-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded text-center">
                View Todos
            </a>
            <a href="/logout"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded text-center">
                Logout
            </a>
        </div>
        <div class="overflow-x-auto mt-5">
            <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php
                    foreach ($tags as $tag) {
                        echo "<tr>";
                        echo "<td class='px-4 py-2 text-sm text-gray-800'>" . $tag->title . "</td>";
                        echo "<td class='px-4 py-2 text-sm text-gray-800'>" . $tag->description . "</td>";
                        echo '
                                <td class="px-4 py-2 flex items-center justify-center gap-x-2">

                                    <a href="/tag/' . $tag->id . '"
                                        class="flex bg-sky-400 hover:bg-sky-500 text-white font-light py-1 px-2 rounded text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                     <a href="/tag/' . $tag->id . '/update"
                                        class="flex bg-gray-400 hover:bg-gray-500 text-white font-light py-1 px-2 rounded text-center">
                                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
</svg>

                                    </a>
                                     <a href="#" onclick="deleteTag(' . $tag->id . ')"
                                        class="flex bg-red-400 hover:bg-red-500 text-white font-light py-1 px-2 rounded text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                         <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>

                                    </a>

                                        </td>
                                        
                                        ';

                        echo "</tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        function deleteTag(id) {
            if (confirm("Are you sure you want to delete this tag?")) {
                fetch('/tag/' + id, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log("Tag deleted successfully.");
                            location.reload(); // Refresh the page to see the updated list
                        } else {
                            console.error("Failed to delete tag.");
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