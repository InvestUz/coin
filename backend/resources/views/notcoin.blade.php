<!-- resources/views/notcoin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotCoin Game</title>
    <style>
        /* Add your clean CSS for the NotCoin game */
    </style>
</head>
<body>
    <div class="points">Points: <span id="points">0</span></div>
    <div id="click-coin">
        <i class="fas fa-coins"></i>
    </div>

    <script>
        const chatId = "{{ auth()->user()->telegram_user_id ?? 1 }}";

        async function fetchPoints() {
            const response = await fetch('/api/telegram/get-points?chat_id=' + chatId);
            const data = await response.json();
            document.getElementById('points').textContent = data.points;
        }

        document.getElementById('click-coin').addEventListener('click', async () => {
            const response = await fetch('/api/telegram/click-coin', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ chat_id: chatId }),
            });
            const data = await response.json();
            document.getElementById('points').textContent = data.total_points;
        });

        fetchPoints();
    </script>
</body>
</html>
