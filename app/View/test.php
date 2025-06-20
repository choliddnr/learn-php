<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Centered Card Carousel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .carousel-container {
            position: relative;
            overflow: hidden;
        }

        .carousel-track {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 1rem 0;
        }

        .carousel-track::-webkit-scrollbar {
            display: none;
        }

        .carousel-card {
            flex: 0 0 auto;
            width: 300px;
            height: 800px;
            margin: 0 10px;
        }

        .carousel-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            pointer-events: none;
            z-index: 1000;
        }

        .carousel-controls button {
            pointer-events: all;
        }
    </style>
</head>

<body>

    <div class="container-fluid py-5">
        <div class="carousel-container position-relative w-100">
            <div class="carousel-controls">
                <button class="btn btn-secondary" id="prevBtn">&lt;</button>
                <button class="btn btn-secondary" id="nextBtn">&gt;</button>
            </div>

            <div class="carousel-track" id="carouselTrack">
                <!-- Add as many cards as you like -->
                <div class="card carousel-card">
                    <div class="card-body">Card 1</div>
                </div>
                <div class="card carousel-card">
                    <div class="card-body">Card 2</div>
                </div>
                <div class="card carousel-card">
                    <div class="card-body">Card 3</div>
                </div>
                <div class="card carousel-card">
                    <div class="card-body">Card 4</div>
                </div>
                <div class="card carousel-card">
                    <div class="card-body">Card 5</div>
                </div>
                <div class="card carousel-card">
                    <div class="card-body">Card 6</div>
                </div>
                <div class="card carousel-card">
                    <div class="card-body">Card 7</div>
                </div>
                <div class="card carousel-card">
                    <div class="card-body">Card 8</div>
                </div>
                <div class="card carousel-card">
                    <div class="card-body">Card 9</div>
                </div>
                <div class="card carousel-card">
                    <div class="card-body">Card 10</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const track = document.getElementById('carouselTrack');
        const cards = track.querySelectorAll('.carousel-card');
        let currentIndex = 0;

        function centerCard(index) {
            const card = cards[index];
            const cardRect = card.getBoundingClientRect();
            const trackRect = track.getBoundingClientRect();
            const scrollLeft = track.scrollLeft;

            const offset = (card.offsetLeft + card.offsetWidth / 2) - (track.offsetWidth / 2);
            track.scrollTo({
                left: offset,
                behavior: 'smooth'
            });

            console.log(index);

        }

        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                centerCard(currentIndex);
            }
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (currentIndex < cards.length - 1) {
                currentIndex++;
                centerCard(currentIndex);
            }
        });

        // Center first card on load
        window.addEventListener('load', () => {
            centerCard(currentIndex);
        });
    </script>

</body>

</html>