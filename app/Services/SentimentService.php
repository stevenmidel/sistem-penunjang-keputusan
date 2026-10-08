<?php

namespace App\Services;

class SentimentService
{
    protected array $positiveWords = [];
    protected array $negativeWords = [];
    protected array $neutralWords  = [];

    public function __construct()
    {
        $kamus = require app_path('Data/kamus_sentimen.php');

        $this->positiveWords = $kamus['positif'] ?? [];
        $this->negativeWords = $kamus['negatif'] ?? [];
        $this->neutralWords  = $kamus['netral']  ?? [];
    }

    /**
     * ===============================
     * HITUNG SKOR SENTIMEN
     * ===============================
     * @param string|null $text
     * @return float 0.0 – 5.0
     */
    public function calculateScore(?string $text): float
    {
        if (!$text) {
            return 0.0;
        }

        $cleanText = $this->normalizeText($text);

        $positiveCount = $this->countMatches($cleanText, $this->positiveWords);
        $negativeCount = $this->countMatches($cleanText, $this->negativeWords);

        return $this->convertToScore($positiveCount, $negativeCount);
    }

    /**
     * ===============================
     * NORMALISASI TEKS
     * ===============================
     */
    protected function normalizeText(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z\s]/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    /**
     * ===============================
     * HITUNG KEMUNCULAN KATA
     * ===============================
     */
    protected function countMatches(string $text, array $keywords): int
    {
        $count = 0;

        foreach ($keywords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/u';
            if (preg_match_all($pattern, $text, $matches)) {
                $count += count($matches[0]);
            }
        }

        return $count;
    }

    /**
     * ===============================
     * KONVERSI KE SKOR 0–5
     * ===============================
     */
    protected function convertToScore(int $positive, int $negative): float
    {
        // Jika tidak ada sentimen sama sekali
        if ($positive === 0 && $negative === 0) {
            return 3.0; // netral default
        }

        $rawScore = $positive - $negative;

        /**
         * Skema konversi:
         * raw >= 4  → 5.0
         * raw 2–3   → 4.5
         * raw 1     → 4.0
         * raw 0     → 3.0
         * raw -1    → 2.5
         * raw -2    → 2.0
         * raw <= -3 → 1.0
         */
        return match (true) {
            $rawScore >= 4   => 5.0,
            $rawScore >= 2   => 4.5,
            $rawScore === 1  => 4.0,
            $rawScore === 0  => 3.0,
            $rawScore === -1 => 2.5,
            $rawScore === -2 => 2.0,
            default          => 1.0,
        };
    }
}
