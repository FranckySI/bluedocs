const escapeRegex = (value) => value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

document.addEventListener('DOMContentLoaded', () => {
    const content = document.getElementById('document-content');
    const query = new URLSearchParams(window.location.search).get('q');

    if (!content || !query) {
        return;
    }

    const keywords = query.match(/[\p{L}\p{N}]+(?:[-'][\p{L}\p{N}]+)*/gu) ?? [];

    if (keywords.length === 0) {
        return;
    }

    const expression = new RegExp(
        keywords
            .sort((left, right) => right.length - left.length)
            .map(escapeRegex)
            .join('|'),
        'giu',
    );
    const walker = document.createTreeWalker(content, NodeFilter.SHOW_TEXT, {
        acceptNode(node) {
            return node.parentElement?.closest('script, style, textarea, code, mark')
                ? NodeFilter.FILTER_REJECT
                : NodeFilter.FILTER_ACCEPT;
        },
    });
    const textNodes = [];

    while (walker.nextNode()) {
        textNodes.push(walker.currentNode);
    }

    let firstMatch = null;

    for (const textNode of textNodes) {
        const text = textNode.nodeValue;
        const fragment = document.createDocumentFragment();
        let cursor = 0;

        for (const match of text.matchAll(expression)) {
            const start = match.index;
            const end = start + match[0].length;

            fragment.append(document.createTextNode(text.slice(cursor, start)));

            const highlightedText = document.createElement('mark');
            highlightedText.className = 'document-search-highlight';
            highlightedText.textContent = match[0];
            fragment.append(highlightedText);
            firstMatch ??= highlightedText;
            cursor = end;
        }

        if (cursor > 0) {
            fragment.append(document.createTextNode(text.slice(cursor)));
            textNode.replaceWith(fragment);
        }
    }

    firstMatch?.scrollIntoView({
        behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
        block: 'center',
    });
});
