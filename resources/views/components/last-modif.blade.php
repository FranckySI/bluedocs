@props(['document'])

<style>
    .bd-modification-sentence {
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 13.5px;
        color: #5b6b83;
        background: #f7f9fc;
        padding: 8px 16px;
        border-radius: 6px;
        border: 1px solid #eef1f6;
    }

    .bd-badge-inline {
        font-weight: 600;
        font-size: 11px;
    }
    
    .bd-text-blue { color: var(--bd-blue); }
    .bd-text-red { color: var(--bd-red); }
    .bd-bold { font-weight: 600; color: #1e293b; }
</style>

@if($document)
    @php
        // Ce qui change ici : On cherche d'abord le nom via la relation 'editor' (User), 
        // sinon on prend le texte 'user_name', sinon 'Anonyme'
        $userName = $document->editor->name ?? ($document->user_name ?? 'Anonyme');
        
        // Extraction rapide des initiales
        $words = explode(' ', $userName);
        $initials = '';
        foreach ($words as $w) {
            $initials .= mb_substr($w, 0, 1);
        }
        $initials = mb_strtoupper(mb_substr($initials, 0, 2));

        // Détection de l'action
        $isNew = $document->created_at == $document->updated_at;
    @endphp

    <div class="bd-modification-sentence">
        <!-- Badge d'action -->
        @if ($isNew)
            <span class="bd-badge-inline bd-text-blue">[Ajout]</span>
        @else
            <span class="bd-badge-inline bd-text-red">[Modification]</span>
        @endif

        Dernière activité par 

        <!-- Avatar + Nom de l'utilisateur -->
        <span class="bd-bold">{{ $userName }}</span>

        le 

        <!-- Date de modification -->
        <span class="bd-bold">{{ $document->updated_at ? $document->updated_at->format('d/m/Y à H:i') : 'N/A' }}</span>
    </div>
@endif
