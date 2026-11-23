<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

/**
 * Sanitizer HTML sederhana berbasis allowlist.
 *
 * Dipakai untuk membersihkan konten artikel blog yang ditulis admin lewat
 * editor WYSIWYG sebelum disimpan dan sebelum ditampilkan ke publik.
 */
class HtmlSanitizer
{
    /**
     * Tag yang diizinkan tampil di artikel.
     *
     * @var array<int, string>
     */
    private const ALLOWED_TAGS = [
        'p', 'br', 'hr', 'strong', 'b', 'em', 'i', 'u', 's', 'strike',
        'h2', 'h3', 'h4', 'blockquote', 'ul', 'ol', 'li', 'a', 'img',
        'code', 'pre', 'div', 'span', 'figure', 'figcaption', 'table',
        'thead', 'tbody', 'tr', 'th', 'td',
    ];

    /**
     * Tag yang dibuang beserta seluruh isinya.
     *
     * @var array<int, string>
     */
    private const DROP_WITH_CONTENT = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'input',
        'button', 'textarea', 'select', 'option', 'link', 'meta', 'svg',
        'math', 'video', 'audio', 'source', 'canvas',
    ];

    public static function clean(?string $html): string
    {
        $html = (string) $html;

        if (trim($html) === '') {
            return '';
        }

        // Buang script/style lebih awal agar isinya tidak bocor sebagai teks.
        $html = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $html) ?? $html;

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        $loaded = $dom->loadHTML(
            '<?xml encoding="UTF-8"?><div id="belidolar-sanitize-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return nl2br(e(strip_tags($html)));
        }

        $root = (new DOMXPath($dom))->query('//*[@id="belidolar-sanitize-root"]')->item(0);

        if (! $root instanceof DOMNode) {
            return '';
        }

        foreach (iterator_to_array($root->childNodes) as $child) {
            self::sanitizeNode($child);
        }

        $output = '';

        foreach ($root->childNodes as $child) {
            $output .= $dom->saveHTML($child);
        }

        return trim($output);
    }

    private static function sanitizeNode(DOMNode $node): void
    {
        if ($node->nodeType === XML_COMMENT_NODE) {
            $node->parentNode?->removeChild($node);

            return;
        }

        if (! $node instanceof DOMElement) {
            return;
        }

        $tag = strtolower($node->tagName);

        if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
            $node->parentNode?->removeChild($node);

            return;
        }

        foreach (iterator_to_array($node->childNodes) as $child) {
            self::sanitizeNode($child);
        }

        if (! in_array($tag, self::ALLOWED_TAGS, true)) {
            self::unwrap($node);

            return;
        }

        self::cleanAttributes($node, $tag);
    }

    private static function unwrap(DOMElement $node): void
    {
        $parent = $node->parentNode;

        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }

    private static function cleanAttributes(DOMElement $node, string $tag): void
    {
        $allowed = match ($tag) {
            'a' => ['href', 'title', 'target', 'rel'],
            'img' => ['src', 'alt', 'title', 'width', 'height'],
            'td', 'th' => ['colspan', 'rowspan'],
            default => [],
        };

        foreach (iterator_to_array($node->attributes) as $attribute) {
            $name = strtolower($attribute->nodeName);

            if (! in_array($name, $allowed, true)) {
                $node->removeAttribute($attribute->nodeName);

                continue;
            }

            $value = trim($attribute->nodeValue ?? '');

            if (in_array($name, ['href', 'src'], true) && ! self::isSafeUrl($value, $name === 'src')) {
                if ($name === 'src') {
                    $node->parentNode?->removeChild($node);

                    return;
                }

                $node->removeAttribute($name);

                continue;
            }

            if ($name === 'target' && ! in_array($value, ['_blank', '_self'], true)) {
                $node->removeAttribute($name);
            }
        }

        if ($tag === 'a') {
            $target = strtolower($node->getAttribute('target'));

            if ($target === '_blank') {
                $node->setAttribute('rel', 'noopener noreferrer');
            }
        }
    }

    private static function isSafeUrl(string $url, bool $image = false): bool
    {
        $url = trim($url);

        if ($url === '') {
            return false;
        }

        // Anchor internal (#section) aman.
        if (str_starts_with($url, '#')) {
            return true;
        }

        // URL relatif (tanpa skema) aman.
        if (! preg_match('#^[a-z][a-z0-9+.-]*:#i', $url)) {
            return true;
        }

        if ($image) {
            return (bool) preg_match('#^https?://#i', $url);
        }

        return (bool) preg_match('#^(https?://|mailto:|tel:)#i', $url);
    }
}
