<?php

namespace App\Handlers\InstantArticles;

use DOMDocument;
use Illuminate\Database\Eloquent\Model;
use Facebook\InstantArticles\Elements\Time;
use Facebook\InstantArticles\Elements\Image;
use Facebook\InstantArticles\Elements\Header;
use Facebook\InstantArticles\Elements\Footer;
use Facebook\InstantArticles\Elements\Author;
use Facebook\InstantArticles\Elements\Analytics;
use Facebook\InstantArticles\Elements\Slideshow;
use Facebook\InstantArticles\Elements\RelatedItem;
use Facebook\InstantArticles\Elements\InstantArticle;
use Facebook\InstantArticles\Transformer\Transformer;
use Facebook\InstantArticles\Elements\RelatedArticles;
use App\Handlers\InstantArticles\Rules\ImageInsideAnchorRule;

class ItemHandler
{
    /**
     *
     * @var string
     */
    private $charset = '<meta charset="utf-8">';

    /**
     *
     * @var string
     */
    private $rules = '{
        "rules" :
        [
            {
                "class": "TextNodeRule"
            },
            {
                "class": "PassThroughRule",
                "selector" : "html"
            },
            {
                "class": "PassThroughRule",
                "selector" : "blockquote"
            },
            {
                "class": "IgnoreRule",
                "selector" : "meta"
            },
            {
                "class": "PassThroughRule",
                "selector" : "head"
            },
            {
                "class": "PassThroughRule",
                "selector" : "div"
            },
            {
                "class": "PassThroughRule",
                "selector" : "script"
            },
            {
                "class": "LineBreakRule",
                "selector" : "br"
            },
            {
                "class": "PassThroughRule",
                "selector" : "body"
            },
            {
                "class": "ParagraphRule",
                "selector" : "p"
            },
            {
                "class": "H1Rule",
                "selector" : "h1"
            },
            {
                "class": "H2Rule",
                "selector" : "h2,h3,h4,h5,h6"
            },
            {
                "class": "InteractiveRule",
                "selector" : "div.embed-responsive iframe",
                "properties" : {
                    "interactive.url" : {
                        "type" : "string",
                        "selector" : "iframe",
                        "attribute": "src"
                    },
                    "interactive.iframe" : {
                        "type" : "children",
                        "selector" : "iframe",
                        "attribute": "src"
                    },
                    "interactive.width" : {
                        "type" : "int",
                        "selector" : "iframe",
                        "attribute": "width"
                    },
                    "interactive.height" : {
                        "type" : "int",
                        "selector" : "iframe",
                        "attribute": "height"
                    }
                }
            },
            {
                "class": "AnchorRule",
                "selector" : "a",
                "properties": {
                    "anchor.href": {
                        "type": "string",
                        "selector": "a",
                        "attribute": "href"
                    },
                    "anchor.rel": {
                        "type": "string",
                        "selector": "a",
                        "attribute": "rel"
                    }
                }
            },
            {
                "class": "ImageInsideParagraphRule",
                "selector" : "img",
                "properties" : {
                    "image.url" : {
                        "type" : "string",
                        "selector" : "img",
                        "attribute": "src"
                    }
                }
            },
            {
                "class": "\\\\App\\\\Handlers\\\\InstantArticles\\\\Rules\\\\ImageInsideAnchorRule",
                "selector" : "img",
                "properties" : {
                    "image.url" : {
                        "type" : "string",
                        "selector" : "img",
                        "attribute": "src"
                    }
                }
            },
            {
                "class": "ImageRule",
                "selector" : "img",
                "properties" : {
                    "image.url" : {
                        "type" : "string",
                        "selector" : "img",
                        "attribute": "src"
                    }
                }
            },
            {
                "class": "InteractiveInsideParagraphRule",
                "selector" : "iframe",
                "properties" : {
                    "interactive.url" : {
                        "type" : "string",
                        "selector" : "iframe",
                        "attribute": "src"
                    },
                    "interactive.height" : {
                        "type" : "int",
                        "selector" : "iframe",
                        "attribute": "height"
                    },
                    "interactive.width" : {
                        "type" : "int",
                        "selector" : "iframe",
                        "attribute": "width"
                    }
                }
            },
            {
                "class": "InteractiveRule",
                "selector" : "table",
                "properties" : {
                    "interactive.iframe" : {
                        "type" : "element",
                        "selector" : "table"
                    },
                    "interactive.height" : {
                        "type" : "int",
                        "selector" : "table",
                        "attribute": "height"
                    },
                    "interactive.width" : {
                        "type" : "int",
                        "selector" : "iframe",
                        "attribute": "width"
                    }
                }
            },
            {
                "class": "ItalicRule",
                "selector" : "i, em"
            },
            {
                "class": "BoldRule",
                "selector" : "b, strong"
            },
            {
                "class": "BlockquoteRule",
                "selector" : "blockquote p"
            },
            {
                "class": "HeaderTitleRule",
                "selector" : "h1"
            },
            {
                "class": "HeaderSubTitleRule",
                "selector" : "h2"
            },
            {
                "class": "SlideshowImageRule",
                "selector" : "li.c-slide",
                "properties" : {
                    "image.url" : {
                        "type" : "string",
                        "selector" : "img",
                        "attribute": "src"
                    }
                }
            },
            {
                "class": "SlideshowRule",
                "selector" : "ul.js-carousel"
            }
        ]
    }';

    /**
     * instance of Illuminate\Database\Eloquent\Model that needs be InstantArticle
     * @var Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * create new instance of Handler
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     *
     * @param Model $item
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * run all logic
     * @return void
     */
    public function handle()
    {
        $transformer = new Transformer();

        $transformer->loadRules($this->rules);

        // add charset to html
        $html = $this->charset . $this->model->body;

        libxml_use_internal_errors(true);

        $document = new DOMDocument();

        $document->loadHTML($html);

        libxml_use_internal_errors(false);

        $instant_article = $this->createInstantArticle();

        $transformer->transform($instant_article, $document);

        return $instant_article->render('<!doctype html>');
    }

    /**
     *
     * @return InstantArticle
     */
    public function createInstantArticle()
    {
        $instant_article = InstantArticle::create()
            ->withCanonicalUrl($this->model->getViewUrl())
            ->withHeader($this->header())
            ->withFooter($this->footer());

        return $instant_article;
    }

    /**
     *
     * @return Footer
     */
    public function footer()
    {
        return Footer::create()
                ->withCredits('Факультет інформаційних систем, фізики та математики')
                ->withRelatedArticles($this->fetchRelatedArticles());
    }

    /**
     *
     * @return RelatedArticles
     */
    public function fetchRelatedArticles()
    {
        $relatedNews         = $this->model->news;
        $relatedPublications = $this->model->publications;

        $related = RelatedArticles::create();

        if (!$relatedNews->isEmpty()) {
            foreach ($relatedNews as $key => $news) {
                $related->addRelated(RelatedItem::create()->withURL($news->getViewUrl()));
            }
        }

        if (!$relatedPublications->isEmpty()) {
            foreach ($relatedPublications as $key => $publication) {
                $related->addRelated(RelatedItem::create()->withURL($publication->getViewUrl()));
            }
        }

        return $related;
    }

    /**
     *
     * @return Header
     */
    public function header()
    {
        $header = Header::create()
            ->withTitle($this->model->title)
            ->withPublishTime(
                Time::create(Time::PUBLISHED)
                    ->withDatetime(
                        \DateTime::createFromFormat(
                            'j-M-Y G:i:s',
                            $this->model->publish_at->format('j-M-Y G:i:s')
                        )
                    )
            )
            ->withModifyTime(
                Time::create(Time::MODIFIED)
                    ->withDatetime(
                        \DateTime::createFromFormat(
                            'j-M-Y G:i:s',
                            $this->model->updated_at->format('j-M-Y G:i:s')
                        )
                    )
            )->withKicker($this->model->getIAKicker());

        if ($cover = $this->headerImage()) {
            $header->withCover($cover);
        }

        return $header;
    }

    /**
     *
     * @return Image
     */
    public function headerImage()
    {
        $image = Image::create();

        if ($url = $this->model->getIAImage()) {
            return $image->withURL($url);
        }

        return null;
    }

}
