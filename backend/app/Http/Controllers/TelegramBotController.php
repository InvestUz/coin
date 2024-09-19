<?php

// app/Http/Controllers/TelegramBotController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use App\Models\Point;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }
    

    // Handle webhook updates from Telegram
    public function handleWebhook(Request $request)
    {
        $update = $this->telegram->getWebhookUpdates();
        if ($update->getMessage()) {
            $chatId = $update->getMessage()->getChat()->getId();
            return $this->clickCoinApi($chatId);
        }
        return response()->json(['status' => 'Update not handled'], 200);
    }

    // Handle "click coin" functionality
    <?php

    // app/Http/Controllers/TelegramBotController.php
    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Telegram\Bot\Api;
    use App\Models\Point;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Log;
    
    class TelegramBotController extends Controller
    {
        protected $telegram;
    
        public function __construct()
        {
            $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        }
        
    
        // Handle webhook updates from Telegram
        public function handleWebhook(Request $request)
        {
            $update = $this->telegram->getWebhookUpdates();
            if ($update->getMessage()) {
                $chatId = $update->getMessage()->getChat()->getId();
                return $this->clickCoinApi($chatId);
            }
            return response()->json(['status' => 'Update not handled'], 200);
        }
    
        // Handle "click coin" functionality
        public function clickCoinApi($chatId)
        {
            if (!$chatId) {
                return response()->json(['error' => 'Chat ID is required'], 400);
            }
    
            // Retrieve points from cache or database
            $cacheKey = "user_points_{$chatId}";
            $points = Cache::get($cacheKey);
    
            if ($points === null) {
                $userPoints = Point::firstOrCreate(['telegram_user_id' => $chatId]);
                $points = $userPoints->points;
                Cache::put($cacheKey, $points, 600); // Cache for 10 minutes
            }
    
            $newPoints = rand(1, 10); // Random points on each click
            $updatedPoints = $points + $newPoints;
    
            // Update database
            Point::updateOrCreate(
                ['telegram_user_id' => $chatId],
                ['points' => $updatedPoints]
            );
    
            // Update cache
            Cache::put($cacheKey, $updatedPoints, 600);
    
            return response()->json(['points' => $newPoints, 'total_points' => $updatedPoints], 200);
        }
    
        // Return user points
        public function getPoints(Request $request)
        {
            $chatId = $request->input('chat_id');
    
            if (!$chatId) {
                return response()->json(['error' => 'Chat ID is required'], 400);
            }
    
            $cacheKey = "user_points_{$chatId}";
            $points = Cache::get($cacheKey);
    
            if ($points === null) {
                $userPoints = Point::where('telegram_user_id', $chatId)->first();
                if ($userPoints) {
                    $points = $userPoints->points;
                    Cache::put($cacheKey, $points, 600);
                } else {
                    return response()->json(['points' => 0], 200);
                }
            }
    
            return response()->json(['points' => $points], 200);
        }
        public function clickCoinApi(Request $request)
        {
            // Your logic here
            $chatId = $request->input('chat_id');
            // Example response
            return response()->json(['total_points' => 100]);
        }
    }
    

    // Return user points
    public function getPoints(Request $request)
    {
        $chatId = $request->input('chat_id');

        if (!$chatId) {
            return response()->json(['error' => 'Chat ID is required'], 400);
        }

        $cacheKey = "user_points_{$chatId}";
        $points = Cache::get($cacheKey);

        if ($points === null) {
            $userPoints = Point::where('telegram_user_id', $chatId)->first();
            if ($userPoints) {
                $points = $userPoints->points;
                Cache::put($cacheKey, $points, 600);
            } else {
                return response()->json(['points' => 0], 200);
            }
        }

        return response()->json(['points' => $points], 200);
    }
    
}
