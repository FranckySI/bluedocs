<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DocumentSearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = trim($validated['q'] ?? '');
        $matchCount = preg_match_all('/[\p{L}\p{N}]+(?:[-\'][\p{L}\p{N}]+)*/u', $query, $matches);
        $keywords = collect($matchCount === false ? [] : $matches[0])
            ->map(fn (string $keyword): string => mb_strtolower($keyword))
            ->unique()
            ->values();
        $documents = collect();
        $resultsCount = 0;
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $queryFingerprint = hash_hmac('sha256', $query, (string) config('app.key'));

        if ($query !== '') {
            if ($keywords->isNotEmpty()) {
                $pageOffset = ($currentPage - 1) * $perPage;

                try {
                    Document::query()
                        ->select(['id', 'title', 'slug', 'content', 'updated_at'])
                        ->orderBy('title')
                        ->orderBy('id')
                        ->chunk(100, function (Collection $batch) use ($keywords, $pageOffset, $perPage, &$documents, &$resultsCount): void {
                            foreach ($batch as $document) {
                                $visibleContent = $this->visibleText($document->content);

                                if (! $this->matchesKeywords($document, $visibleContent, $keywords)) {
                                    continue;
                                }

                                if ($resultsCount >= $pageOffset && $resultsCount < $pageOffset + $perPage) {
                                    $document->setAttribute('search_snippet', $this->makeSnippet($visibleContent, $keywords));
                                    $documents->push($document);
                                }

                                $resultsCount++;
                            }
                        });
                } catch (QueryException $exception) {
                    Log::warning('Document search failed.', [
                        'query_fingerprint' => $queryFingerprint,
                        'keyword_count' => $keywords->count(),
                        'actor_id' => Auth::id(),
                        'exception' => $exception,
                    ]);

                    throw $exception;
                }

                $documents = (new LengthAwarePaginator(
                    $documents,
                    $resultsCount,
                    $perPage,
                    $currentPage,
                    [
                        'path' => LengthAwarePaginator::resolveCurrentPath(),
                        'query' => $request->query(),
                    ],
                ))->withQueryString();
            }

            Log::info('Document search performed.', [
                'query_fingerprint' => $queryFingerprint,
                'query_length' => mb_strlen($query),
                'keyword_count' => $keywords->count(),
                'results_count' => $resultsCount,
                'page' => $currentPage,
                'actor_id' => Auth::id(),
            ]);
        }

        return view('docs.search', [
            'documents' => $documents,
            'query' => $query,
        ]);
    }

    /**
     * @param  Collection<int, string>  $keywords
     */
    private function matchesKeywords(Document $document, string $visibleContent, Collection $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (! $this->containsWholeWord($document->title, $keyword)
                && ! $this->containsWholeWord($visibleContent, $keyword)) {
                return false;
            }
        }

        return true;
    }

    private function containsWholeWord(string $text, string $keyword): bool
    {
        return preg_match($this->keywordPattern($keyword), $text) === 1;
    }

    private function keywordPattern(string $keyword): string
    {
        return "/(?<![\\p{L}\\p{N}'-])".preg_quote($keyword, '/')."(?![\\p{L}\\p{N}'-])/iu";
    }

    private function visibleText(string $html): string
    {
        $html = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1\s*>/isu', ' ', $html) ?? $html;
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return preg_replace('/[\p{Z}\s]+/u', ' ', $text) ?? $text;
    }

    /**
     * @param  Collection<int, string>  $keywords
     */
    private function makeSnippet(string $content, Collection $keywords): string
    {
        $matchPosition = null;

        foreach ($keywords as $keyword) {
            $matches = [];
            $matched = preg_match($this->keywordPattern($keyword), $content, $matches, PREG_OFFSET_CAPTURE);

            if ($matched === 1) {
                $position = mb_strlen(substr($content, 0, $matches[0][1]), 'UTF-8');

                if ($matchPosition === null || $position < $matchPosition) {
                    $matchPosition = $position;
                }
            }
        }

        if ($matchPosition === null) {
            return 'Correspondance dans le titre.';
        }

        $start = max(0, $matchPosition - 70);
        $snippet = mb_substr($content, $start, 220);

        return ($start > 0 ? '…' : '').$snippet.(mb_strlen($content) > $start + 220 ? '…' : '');
    }
}
