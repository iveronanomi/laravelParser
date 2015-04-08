<?php
/**
 * @author Eremin Ivan
 * @email coding.ebola@gmail.com
 */
namespace App\Api\Parser;

use App\Api\TwitterAPI;

class TwitterParser extends Parser implements ParserInterface
{
    public function extractScreenName($url)
    {
        preg_match("|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|", $url, $matches);
        return !empty($matches[3]) ? $matches[3] : '';
    }

    public function parse()
    {
        $bird = TwitterAPI::getCodeBird();
        $bird->setToken(TwitterAPI::ACCESS_TOKEN, TwitterAPI::ACCESS_SECRET);

        $screenName = $this->extractScreenName($this->source->uri) ? : $this->source->uri;

        $twits = $bird->statuses_userTimeline([
            'screen_name'       => $screenName,
            'exclude_replies'   => 'true',
            'include_rts'       => 'false',
            'count'             => (int) $this->source->requestLimit,
        ]);

        $result = [];
        $keywords = array_flip(explode(';', $this->source->keywords));

        /** @var \StdClass $twit */
        foreach ($twits as $twit) {
            if ($twit instanceof \StdClass && $this->test($twit, $keywords)) {
               $result[] = $this->normalize($twit);
            }
        }
        return $result;
    }

    /**
     * @param \StdClass $item
     * @param array     $keywords
     * @return bool
     */
    public function test($item, $keywords)
    {
        if (!property_exists($item, 'text')) {
            return false;
        }
        if ($text = $item->text) {
            foreach (preg_split("/\s/i", $text) as $keyword) {
                if (isset($keywords[$keyword])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $item
     * @return array
     */
    public function normalize($item)
    {
        return [
            'id'            => (string) $item->id_str,
            'title'         => '',
            'description'   => '',
            'text'          => (string) $item->text,
            'link'          => (string) $item->id_str,
            'created_at'    => (string) $item->created_at,
            'user'          => [
                'id'    => (string) $item->user->id,
                'name'  => (string) $item->user->name
            ],
        ];
    }
}