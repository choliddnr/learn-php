<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <script type="module" src="http://localhost:5173/resources/tailwind/index.ts"></script> -->
    <script type="module" src="/assets/index-B5S487x2.js"></script>
    <link rel="stylesheet" href="/assets/index-BvRfNH8U.css">

    <title>Tag Item</title>

</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4 text-gray-800"><?= htmlspecialchars($tag->title) ?></h1>

        <p class="mb-2 text-gray-600"><strong>Description:</strong> <?= htmlspecialchars($tag->description) ?></p>


        <!-- Action Buttons -->
        <div class="flex justify-between space-x-4">
            <a href="/tag"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded text-center">
                Back
            </a>
            <a href="/tag/<?= $tag->id ?>/update"
                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-center">
                Update
            </a>
            <a href="#" onclick="deleteTag(<?= $tag->id ?>)"
                onclick="return confirm('Are you sure you want to delete this tag?');"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded text-center">
                Remove
            </a>
        </div>
    </div>
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
                            location.replace('/tag'); // Refresh the page to see the updated list
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