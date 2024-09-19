<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotCoin Game</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to top, #fba007, #ffc630);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            user-select: none;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px;
            text-align: center;
            background: rgba(31, 31, 31, 0.8);
            backdrop-filter: blur(10px);
            z-index: 1000;
        }

        .button {
            background: #fad258;
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
        }

        .button:hover {
            background: #f9c035;
        }

        .points {
            margin-top: 100px;
            font-size: 3rem;
            font-weight: bold;
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }

        .click-icon {
            margin-top: 50px;
            font-size: 5rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .click-icon:active {
            transform: scale(0.9);
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .points {
                font-size: 2rem;
            }

            .click-icon {
                font-size: 4rem;
            }

            .button {
                padding: 10px 20px;
                font-size: 1.2rem;
            }
        }

        /* Loading Spinner */
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            display: none;
            margin-left: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Animation for Click Icon */
        .animate-click {
            animation: bounce 0.3s;
        }

        @keyframes bounce {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Join the NotCoin Squad!</h1>
        <a href="https://t.me/SingleDevelopers" class="button">
            Join Squad <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="content">
        <div class="points">Points: <span id="points">0</span></div>
        <div class="click-icon" id="click-coin">
            <i class="fas fa-coins"></i>
            <div class="spinner" id="spinner"></div>
        </div>
    </div>

    <div class="footer">
        <img src="{{ asset('images/trophy.png') }}" width="24" height="24" alt="Trophy">
        <a href="https://github.com/Malith-Rukshan" target="_blank" style="color: white;">Gold</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        const chatId = "{{ auth()->user()->telegram_user_id ?? 1 }}";

        async function fetchPoints() {
            try {
                const response = await fetch(`/api/telegram/get-points?chat_id=${chatId}`);
                const data = await response.json();
                if (data && data.points !== undefined) {
                    document.getElementById('points').textContent = data.points;
                }
            } catch (error) {
                console.error("Error fetching points:", error);
            }
        }
        document.getElementById('click-coin').addEventListener('click', async () => {
            const chatId = "{{ auth()->user()->telegram_user_id ?? 1 }}";

            const response = await fetch('/api/telegram/click-coin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    chat_id: chatId
                }),
            });

            const data = await response.json();
            document.getElementById('points').textContent = data.total_points;
        });


        fetchPoints();
        setInterval(fetchPoints, 60000); // Optional: Fetch points every 60 seconds
    </script>
</body>

</html>
