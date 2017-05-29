<?php
namespace WpShortcodes;

class Shortcodes
{
    private $content;
    private $tax;

    public function __construct()
    {
        $this->content = new \WpContent\Content;
        $this->tax = new \WpTaxonomy\Taxonomy;
    }

    public function addShortcodes()
    {
        // new up all shortcodes
        add_shortcode('elr-author', function () {
            return $this->content->authorBox();
        });

        add_shortcode('elr-categories', function ($atts, $content = null) {
            extract(shortcode_atts([
                'num' => 'all',
                'by_count' => false,
                'hierarchical' => true,
                'count' => false
            ], $atts));

            $attrs = [
                'num' => $num,
                'by_count' => $by_count,
                'hierarchical' => $hierarchical
            ];

            $cat_args = $this->tax->createCatArgs($attrs);

            return '<div class="elr-categories">' .
                $this->content->title($content) .
                $this->tax->createCategoryList($cat_args) .
                '</div>';
        });

        add_shortcode('elr-recent-posts', function ($atts, $content = null) {
            extract(shortcode_atts([
                'num' => 5,
                'post_type' => 'post'
            ], $atts));

            return '<div class="elr-recent-posts">' .
                $this->content->title($content) .
                $this->content->recentPostList($post_type, $num) .
                '</div>';
        });

        add_shortcode('elr-related-posts', function ($atts, $content = null) {
            extract(shortcode_atts([
                'tax' => 'category',
                'num' => 5
            ], $atts));

            return '<div class="elr-related-posts">' .
                $this->content->title($content) .
                $this->content->relatedPostList($tax, $num) .
                '</div>';
        });

        add_shortcode('elr-email', function ($atts, $content = null) {
            if ($content) {
                return $this->content->email($content);
            }
        });

        add_shortcode('elr-phone', function ($atts, $content = null) {
            return $this->content->phone($content);
        });

        add_shortcode('elr-video', function ($atts) {
            extract(shortcode_atts([
                'src' => '',
                'width' => 641,
                'height' => 360
            ], $atts));

            if (!$src) {
                return '<p>No Video Source Provided</p>';
            }

            return $this->content->video($src, $width, $height);
        });
    }
}
