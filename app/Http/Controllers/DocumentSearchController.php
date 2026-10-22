<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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

        if ($query !== '') {
            if ($keywords->isNotEmpty()) {
                $documents = Document::query()
                    ->where(function (Builder $documentQuery) use ($keywords): void {
                        foreach ($keywords as $keyword) {
                            $variants = collect([
                                $keyword,
                                mb_strtoupper($keyword),
                                mb_convert_case($keyword, MB_CASE_TITLE, 'UTF-8'),
                            ])->unique();

                            $documentQuery->where(function (Builder $keywordQuery) use ($variants): void {
                                foreach ($variants as $variant) {
                                    $keywordQuery->orWhere(function (Builder $variantQuery) use ($variant): void {
                                        $variantQuery
                                            ->where('title', 'like', "%{$variant}%")
                                            ->orWhere('content', 'like', "%{$variant}%");
                                    });
                                }
                            });
                        }
                    })
                    ->orderBy('title')
                    ->orderBy('id')
                    ->paginate(15)
                    ->withQueryString();

                $documents->getCollection()->transform(function (Document $document) use ($keywords): Document {
                    $document->setAttribute('search_snippet', $this->makeSnippet($document, $keywords));

                    return $document;
                });

                $resultsCount = $documents->total();
            }

            Log::info('Document search performed.', [
                'query_length' => mb_strlen($query),
                'keyword_count' => $keywords->count(),
                'results_count' => $resultsCount,
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
    private function makeSnippet(Document $document, Collection $keywords): string
    {
        $content = html_entity_decode(strip_tags($document->content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $content = preg_replace('/\s+/u', ' ', $content) ?? $content;
        $matchPosition = null;

        foreach ($keywords as $keyword) {
            $position = mb_stripos($content, $keyword);

            if ($position !== false && ($matchPosition === null || $position < $matchPosition)) {
                $matchPosition = $position;
            }
        }

        $start = max(0, ($matchPosition ?? 0) - 70);
        $snippet = mb_substr($content, $start, 220);

        return ($start > 0 ? '…' : '').$snippet.(mb_strlen($content) > $start + 220 ? '…' : '');
    }
}
