<?php
namespace App\Service;


use GuzzleHttp\Client;
class NewsAPIService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        // Accessing the environment variable inside the constructor
        $this->apiKey = $_ENV['NEWS_API_KEY'];

        $this->client = new Client([
            'base_uri' => 'https://newsapi.org/v2/',
        ]);
    }

    public function getAssociationNews(): array
{
    try {
        $response = $this->client->get('everything', [
            'query' => [
                'q' => 'donation OR food OR clothing OR education OR children OR poverty OR volunteering OR charity OR association',
                'apiKey' => $this->apiKey,
                'pageSize' => 10, // Limit the number of results
                'language' => 'en', // Language preference
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        $articles = [];
        if (isset($data['articles'])) {
            foreach ($data['articles'] as $article) {
                $title = $article['title'];
                $description = isset($article['description']) ? $article['description'] : null;
                $imageUrl = isset($article['urlToImage']) ? $article['urlToImage'] : null;
                $category = $this->inferCategory($title, $description);
                $commentsCount = rand(0, 100);
                $publishedAt = isset($article['publishedAt']) ? $article['publishedAt'] : null;
                $author = isset($article['author']) ? $this->formatAuthorName($article['author']) : null;

                $articles[] = [
                    'title' => $title,
                    'author' => $author,
                    'description' => $description,
                    'imageUrl' => $imageUrl,
                    'commentsCount' => $commentsCount,
                    'publishedAt' => $publishedAt,
                    'category' => $category,
                ];
            }
        }
        
        return $articles;
    } catch (RequestException $e) {
        // Handle request exceptions (e.g., network errors, API errors)
        // Log or report the error for further investigation
        error_log('News API request failed: ' . $e->getMessage());
        return [];
    } catch (\Throwable $e) {
        // Handle other types of exceptions
        error_log('Unexpected error: ' . $e->getMessage());
        return [];
    }
}
private function inferCategory(string $title, ?string $description): ?string
    {
       
        if (stripos($title, 'donat') !== false || stripos($description, 'donat') !== false) {
            return 'Donation';
        }
        if (stripos($title, 'health') !== false || stripos($description, 'health') !== false) {
            return 'Health';
        }
        if (stripos($title, 'poverty') !== false || stripos($description, 'poverty') !== false) {
            return 'Poverty';
        }
        if (stripos($title, 'charity') !== false || stripos($description, 'charity') !== false) {
            return 'Charity';
        } elseif (stripos($title, 'food') !== false || stripos($description, 'food') !== false) {
            return 'Food';
        } elseif (stripos($title, 'clothing') !== false || stripos($description, 'clothing') !== false) {
            return 'Clothing';
        }
        
        return null;
    }
    private function formatAuthorName(string $author): string
{
    // Extract the author name from the URL
    $authorParts = explode('/', $author);
    $authorName = end($authorParts);

    // Replace '-' with spaces and capitalize the first letter of each word
    $formattedAuthorName = ucwords(str_replace('-', ' ', $authorName));

    return $formattedAuthorName;
}
}
