<?php
$apiKey = "3b17863269484ef593f04b3ec583cc0e";

// One focused topic about mental thinking, cognition, brain, psychology, etc.
$topic = "Mental Thinking";

// Use a broad query with keywords related to mental thinking, cognition, brain, psychology, intelligence, reasoning
$query = "mental OR cognition OR brain OR psychology OR intelligence OR thinking OR reasoning OR mindfulness OR neuroplasticity OR cognitive";

// Fetch articles function
function fetchArticles($query, $apiKey) {
    $url = "https://newsapi.org/v2/everything?" . http_build_query([
        'q' => $query,
        'language' => 'en',
        'sortBy' => 'relevancy',
        'pageSize' => 8,
        'apiKey' => $apiKey
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: SmartMind NewsFetcher/1.0'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response);
    if (isset($data->status) && $data->status === "ok") {
        return $data->articles;
    }
    return [];
}

// Fetch articles for the mental thinking topic
$articles = fetchArticles($query, $apiKey);
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SmartMind News - Mental Thinking & Brain</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold mb-8 text-center">🧠 News on Mental Thinking & Brain</h1>

        <?php if (!empty($articles)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($articles as $article): ?>
                    <div class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition">
                        <?php if (!empty($article->urlToImage)): ?>
                            <img src="<?= htmlspecialchars($article->urlToImage) ?>" alt="News Image" class="w-full h-40 object-cover rounded mb-4" loading="lazy" />
                        <?php endif; ?>
                        <h2 class="text-lg font-semibold mb-2"><?= htmlspecialchars($article->title) ?></h2>
                        <p class="text-xs text-gray-500 mb-2"><?= date("F j, Y", strtotime($article->publishedAt)) ?></p>
                        <p class="text-sm mb-3"><?= htmlspecialchars(substr($article->description ?? 'No description available.', 0, 120)) . "..." ?></p>
                        <a href="<?= htmlspecialchars($article->url) ?>" target="_blank" rel="noopener noreferrer" class="text-purple-600 hover:underline font-medium">Read more</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-red-500 text-lg mt-10">No recent news found about mental thinking. Please check back later!</p>
        <?php endif; ?>
    </div>
</body>
</html>