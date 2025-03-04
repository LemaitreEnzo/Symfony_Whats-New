<?php

namespace App\Service;

use SimpleXMLElement;

class RssFeed
{
    private string $rssUrl = 'https://lamanu.fr/feed/';

    public function getRssItems(): array
    {
        $rssContent = file_get_contents($this->rssUrl);
        $rss = new SimpleXMLElement($rssContent);
        
        $items = [];

        foreach ($rss->channel->item as $item) {
            $description = mb_convert_encoding((string) $item->description, 'UTF-8');
            $content = (string) $item->children('content', true);
            $imageUrl = $this->extractImageUrl($content);
            $items[] = [
                'title' => (string) $item->title,
                'link' => (string) $item->link,
                'date' => (string) $item->pubDate,
                'description' => strip_tags($description),
                'image' => $imageUrl
            ];
        }
        return $items;
    }

    private function extractImageUrl(string $description): ?string
    {
        preg_match('/<img.*?src=["\'](.*?)["\']/', $description, $matches);
        return $matches[1] ?? null;
    }
}
