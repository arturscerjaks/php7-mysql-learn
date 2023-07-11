<?php

namespace Framework;

/**
 * Class that holds methods related to text-formatting in a code-injection-safe way
 */

class Markdown
{

    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * Converts `$this->string` into HTML
     */

    public function toHtml()
    {
        // remove any HTML characters and convert to UTF-8
        $text = htmlspecialchars($this->string, ENT_QUOTES, 'UTF-8');

        // strong (bold)
        $text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);

        // emphasis (italic)
        $text = preg_replace('/_([^_]+)_/', '<em>$1<em>', $text);
        $text = preg_replace('/\*([^\*]+)\*/', '<em>$1<em>', $text);
        
        // Convert Windows (\r\n) to Unix (\n)
        $text = str_replace("\r\n", "\n", $text);
        // Convert Macintosh (\r) to Unix (\n)
        $text = str_replace("\r", "\n", $text);

        // paragraphs
        $text = '<p>' . preg_replace('/\n\n/', '</p><p>', $text) . '</p>';
        
        // line breaks
        $text = str_replace("\n", '<br>', $text);

        // hyperlink
        $text = preg_replace('/\[([^\]]+)]\(([-a-z0-9._~:\/?#@!$&\'()*+,;=%]+)\)/i', '<a href="$2">$1</a>', $text);

        return $text;
    }
}
