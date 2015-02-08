<?php

namespace ComTSo\ForumBundle\Twig;

use ComTSo\ForumBundle\Entity\Photo;
use ComTSo\ForumBundle\Entity\Routable;
use ComTSo\ForumBundle\Lib\Utils;
use ComTSo\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;

class ForumExtension extends Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'comtso_forum';
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('file_size', [$this, 'fileSizeFormat']),
            new \Twig_SimpleFilter('highlight', [$this, 'getHighlightedText'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('path', [$this, 'getObjectPath'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('shorten', [$this, 'shorten']),
            new \Twig_SimpleFilter('htmlDate', [$this, 'getHtmlDate'], ['is_safe' => ['html']] ),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('random_quote', [$this, 'getRandomQuote']),
            new \Twig_SimpleFunction('image_size_attrs', [$this, 'getImageSizeAttrs'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('get_brand_name', [$this, 'getBrandName'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('get_owa_base_url', [$this, 'getOwaBaseUrl'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('get_owa_site_id', [$this, 'getOwaSiteId'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('user_theme', [$this, 'getUserTheme']),
        ];
    }

    public function getRandomQuote()
    {
        return $this->getDoctrine()->getRepository('ComTSoForumBundle:Quote')->findRandom();
    }

    public function getImageSizeAttrs(Photo $photo, $filter)
    {
        $config = $this->container->get('liip_imagine.filter.configuration')->get($filter);
        $width = $photo->getWidth();
        $height = $photo->getHeight();
        if (isset($config['filters']['thumbnail'])) {
            $width = $config['filters']['thumbnail']['size'][0];
            $height = $config['filters']['thumbnail']['size'][1];
            if ($config['filters']['thumbnail']['mode'] == 'inset') {
                if ($photo->getWidth() > $photo->getHeight()) {
                    $height = floor($width / $photo->getWidth() * $photo->getHeight());
                }
                if ($photo->getWidth() < $photo->getHeight()) {
                    $width = floor($height / $photo->getHeight() * $photo->getWidth());
                }
            }
        }

        return "width=\"{$width}\" height=\"{$height}\"";
    }

    /**
     *
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    public function fileSizeFormat($size, $decimals = 1)
    {
        return Utils::filesizeFormat($size, $decimals);
    }

    public function getHighlightedText($text, $terms, $skipNonMatching = false)
    {
        if ($skipNonMatching) {
            $result = $this->skipNonMatching($text, $terms);
        } else {
            $result = $text;
        }
        foreach ($terms as $term) {
            $result = $this->highlightText($result, $term);
        }

        return $result;
    }

    public function getBrandName()
    {
        return $this->container->getParameter('brand.name');
    }

    public function getOwaBaseUrl()
    {
        return $this->container->getParameter('owa.baseUrl');
    }

    public function getOwaSiteId()
    {
        return $this->container->getParameter('owa.siteId');
    }

    protected function skipNonMatching($text, $terms)
    {
        $lines = preg_split( '/\r\n?|\n|<br ?\/?>/', $text);
        $result = '';
        $first = true;
        $found = false;
        foreach ($lines as $line) {
            $parsed = Utils::asciiFormat($line);
            if (!$parsed) {
                continue;
            }
            $found = false;
            foreach ($terms as $term) {
                if (stripos($parsed, $term) !== false) {
                    $result .= $line;
                    $found = true;
                    $first = false;
                    break;
                }
            }
            if (!$found && $first) {
                $first = false;
                $result .= '<p>…</p>';
            }
        }
        if (!$found) {
            $result .= '<p>…</p>';
        }

        return $result;
    }

    protected function highlightText($text, $term)
    {
        $result = '';
        $l = mb_strlen($term);
        $parsed = Utils::asciiFormat(str_replace(['…', '«', '»'], ['.', '"', '"'], $text));
        while (($pos = mb_stripos($parsed, $term)) !== false) {
            $result .= mb_substr($text, 0, $pos) . '<span class="highlight">' . mb_substr($text, $pos, $l) . '</span>';
            $text = mb_substr($text, $pos + $l);
            $parsed = mb_substr($parsed, $pos + $l);
        }
        $result .= $text;

        return $result;
    }

    public function getObjectPath(Routable $entity, $action = 'show', $parameters = [], $absolute = false)
    {
        $class = ClassUtils::getRealClass(get_class($entity));
        $namespace = explode('\\', $class);
        $shortName = strtolower(array_pop($namespace));
        $route = "comtso_{$shortName}_{$action}";
        $parameters = array_merge($entity->getRoutingParameters(), $parameters);

        return $this->container->get('router')->generate($route, $parameters, $absolute);
    }

    public function shorten($string, $len = 40)
    {
        return Utils::shorten($string, $len);
    }

    public function getHtmlDate(\DateTime $date, $format = 'd/m/Y')
    {
        $html = "<time datetime=\"{$date->format(\DateTime::W3C)}\" title=\"Le {$date->format('d/m/Y à H:i:s')}\">";
        $html .= $date->format($format);
        $html .= "</time>";

        return $html;
    }

    public function getUserTheme()
    {
        $user = $this->getUser();
        if ($user instanceof User && $theme = $user->getConfigValue('bootstrap_theme')) {
            return $theme;
        }
        return $this->container->getParameter('default_bootstrap_theme');
    }

    /**
     * @return null|User
     * @see TokenInterface::getUser()
     */
    public function getUser()
    {
        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }
}
