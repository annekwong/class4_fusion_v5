<?php

class XpaginatorHelper extends AppHelper
{

    var $helpers = array('Html', 'Ajax');
    var $__defaultModel = null;
    var $options = array();

    function params($model = null)
    {
        if (empty($model))
        {
            $model = $this->defaultModel();
        }
        if (!isset($this->params['paging']) || empty($this->params['paging'][$model]))
        {
            return null;
        }
        return $this->params['paging'][$model];
    }

    function options($options = array())
    {
        if (is_string($options))
        {
            $options = array('update' => $options);
        }

        if (!empty($options['paging']))
        {
            if (!isset($this->params['paging']))
            {
                $this->params['paging'] = array();
            }
            $this->params['paging'] = array_merge($this->params['paging'], $options['paging']);
            unset($options['paging']);
        }
        $model = $this->defaultModel();

        if (!empty($options[$model]))
        {
            if (!isset($this->params['paging'][$model]))
            {
                $this->params['paging'][$model] = array();
            }
            $this->params['paging'][$model] = array_merge($this->params['paging'][$model], $options[$model]);
            unset($options[$model]);
        }
        $this->options = array_filter(array_merge($this->options, $options));
    }

    function current($model = null)
    {
        $params = $this->params($model);

        if (isset($params['page']))
        {
            return $params['page'];
        }
        return 1;
    }

    function sortKey($model = null, $options = array())
    {
        if (empty($options))
        {
            $params = $this->params($model);
            $options = array_merge($params['defaults'], $params['options']);
        }

        if (isset($options['sort']) && !empty($options['sort']))
        {
            if (preg_match('/(?:\w+\.)?(\w+)/', $options['sort'], $result) && isset($result[1]))
            {
                if ($result[0] == $this->defaultModel())
                {
                    return $result[1];
                }
            }
            return $options['sort'];
        }
        elseif (isset($options['order']) && is_array($options['order']))
        {
            return key($options['order']);
        }
        elseif (isset($options['order']) && is_string($options['order']))
        {
            if (preg_match('/(?:\w+\.)?(\w+)/', $options['order'], $result) && isset($result[1]))
            {
                return $result[1];
            }
            return $options['order'];
        }
        return null;
    }

    function sortDir($model = null, $options = array())
    {
        $dir = null;

        if (empty($options))
        {
            $params = $this->params($model);
            $options = array_merge($params['defaults'], $params['options']);
        }

        if (isset($options['direction']))
        {
            $dir = strtolower($options['direction']);
        }
        elseif (isset($options['order']) && is_array($options['order']))
        {
            $dir = strtolower(current($options['order']));
        }

        if ($dir == 'desc')
        {
            return 'desc';
        }
        return 'asc';
    }

    function prev($title = '<< Previous', $options = array(), $disabledTitle = null, $disabledOptions = array())
    {
        return $this->__pagingLink('Prev', $title, array('id' => 'prev_page', 'class' => 'page-prev'), $options, $disabledTitle, $disabledOptions);
    }

    function next($title = 'Next >>', $options = array(), $disabledTitle = null, $disabledOptions = array())
    {
        return $this->__pagingLink('Next', $title, array('id' => 'next_page', 'class' => 'page-next'), $options, $disabledTitle, $disabledOptions);
    }

    function sort($title, $key = null, $options = array())
    {
        $options = array_merge(array('url' => array(), 'model' => null), $options);
        $url = $options['url'];
        unset($options['url']);

        if (empty($key))
        {
            $key = $title;
            $title = __(Inflector::humanize(preg_replace('/_id$/', '', $title)), true);
        }
        $dir = 'asc';
        $sortKey = $this->sortKey($options['model']);
        $isSorted = ($sortKey === $key || $sortKey === $this->defaultModel() . '.' . $key);

        if ($isSorted && $this->sortDir($options['model']) === 'asc')
        {
            $dir = 'desc';
        }

        if (is_array($title) && array_key_exists($dir, $title))
        {
            $title = $title[$dir];
        }

        $url = array_merge(array('sort' => $key, 'direction' => $dir), $url, array('order' => null));
        return $this->link($title, $url, $options);
    }

    function link($title, $url = array(), $options = array())
    {
        $options = array_merge(array('model' => null, 'escape' => true), $options);
        $model = $options['model'];
        unset($options['model']);

        if (!empty($this->options))
        {
            $options = array_merge($this->options, $options);
        }
        if (isset($options['url']))
        {
            $url = array_merge((array) $options['url'], (array) $url);
            unset($options['url']);
        }
        $url = $this->url($url, true, $model);

        $obj = isset($options['update']) ? 'Ajax' : 'Html';
        $url = array_merge(array('page' => $this->current($model)), $url);
        $url = array_merge(Set::filter($url, true), array_intersect_key($url, array('plugin' => true)));
        return $this->{$obj}->link($title, $url, $options);
    }

    /**
     * Merges passed URL options with current pagination state to generate a pagination URL.
     *
     * @param array $options Pagination/URL options array
     * @param boolean $asArray Return the url as an array, or a URI string 
     * @param string $model Which model to paginate on
     * @return mixed By default, returns a full pagination URL string for use in non-standard contexts (i.e. JavaScript)
     */
    function url($options = array(), $asArray = false, $model = null)
    {
        $paging = $this->params($model);
        $url = array_merge(array_filter(Set::diff(array_merge($paging['defaults'], $paging['options']), $paging['defaults'])), $options);

        if (isset($url['order']))
        {
            $sort = $direction = null;
            if (is_array($url['order']))
            {
                list($sort, $direction) = array($this->sortKey($model, $url), current($url['order']));
            }
            unset($url['order']);
            $url = array_merge($url, compact('sort', 'direction'));
        }

        if ($asArray)
        {
            return $url;
        }
        return parent::url($url);
    }

    /**
     * Protected method for generating prev/next links
     *
     */
    function __pagingLink($which, $title = null, $url = null, $options = array(), $disabledTitle = null, $disabledOptions = array())
    {
        $check = 'has' . $which;
        $_defaults = array('url' => array(), 'step' => 1, 'escape' => true, 'model' => null, 'tag' => 'li');
        $options = array_merge($_defaults, (array) $options);
        $paging = $this->params($options['model']);
        if (!$this->{$check}($options['model']) && (!empty($disabledTitle) || !empty($disabledOptions)))
        {
            if (!empty($disabledTitle) && $disabledTitle !== true)
            {
                $title = $disabledTitle;
            }
            $options = array_merge($_defaults, (array) $disabledOptions);
        }
        elseif (!$this->{$check}($options['model']))
        {
            $url['?'] = isset($this->params['getUrl']) ? $this->params['getUrl'] : '';
            return $this->Html->tag($options['tag'], htmlspecialchars_decode($this->link($title, $url, array('id' => 'prev_page', 'class' => 'page-prev'))), $options, $options['escape']);
        }

        foreach (array_keys($_defaults) as $key)
        {
            ${$key} = $options[$key];
            unset($options[$key]);
        }
        $url = array_merge(array('page' => $paging['page'] + ($which == 'Prev' ? $step * -1 : $step)), $url);

        if ($this->{$check}($model))
        {
            return $this->Html->tag($tag, $this->link($title, array('page' => $paging['page'] + ($which == 'Prev' ? $step * -1 : $step), '?' => isset($this->params['getUrl']) ? $this->params['getUrl'] : ''), array('id' => 'last_page', 'class' => 'page-last'), $options));
        }
        else
        {
            return $this->Html->tag($tag, $this->link($title, array('page' => $paging['page'] + ($which == 'Prev' ? $step * -1 : $step), '?' => isset($this->params['getUrl']) ? $this->params['getUrl'] : ''), array('id' => 'last_page', 'class' => 'page-last'), $options));
            //return $this->Html->tag($tag, $title, $url, $options, $escape);
        }
    }

    /**
     * Returns true if the given result set is not at the first page
     *
     * @param string $model Optional model name. Uses the default if none is specified.
     * @return boolean True if the result set is not at the first page.
     */
    function hasPrev($model = null)
    {
        return $this->__hasPage($model, 'prev');
    }

    /**
     * Returns true if the given result set is not at the last page
     *
     * @param string $model Optional model name.  Uses the default if none is specified.
     * @return boolean True if the result set is not at the last page.
     */
    function hasNext($model = null)
    {
        return $this->__hasPage($model, 'next');
    }

    /**
     * Returns true if the given result set has the page number given by $page
     *
     * @param string $model Optional model name.  Uses the default if none is specified.
     * @param int $page The page number - if not set defaults to 1.
     * @return boolean True if the given result set has the specified page number.
     */
    function hasPage($model = null, $page = 1)
    {
        if (is_numeric($model))
        {
            $page = $model;
            $model = null;
        }
        $paging = $this->params($model);
        return $page <= $paging['pageCount'];
    }

    /**
     * Protected method
     *
     */
    function __hasPage($model, $page)
    {
        $params = $this->params($model);
        if (!empty($params))
        {
            if ($params["{$page}Page"] == true)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets the default model of the paged sets
     *
     * @return string Model name or null if the pagination isn't initialized.
     */
    function defaultModel()
    {
        if ($this->__defaultModel != null)
        {
            return $this->__defaultModel;
        }
        if (empty($this->params['paging']))
        {
            return null;
        }
        list($this->__defaultModel) = array_keys($this->params['paging']);
        return $this->__defaultModel;
    }

    /**
     * Returns a counter string for the paged result set
     *
     * Options
     *
     * - `model` The model to use, defaults to PaginatorHelper::defaultModel();
     * - `format` The format string you want to use, defaults to 'pages' Which generates output like '1 of 5'
     *    set to 'range' to generate output like '1 - 3 of 13'.  Can also be set to a custom string, containing 
     *    the following placeholders `%page%`, `%pages%`, `%current%`, `%count%`, `%start%`, `%end%` and any
     *    custom content you would like.
     * - `separator` The separator string to use, default to ' of '
     *
     * @param mixed $options Options for the counter string. See #options for list of keys.
     * @return string Counter string.
     */
    function counter($options = array())
    {
        if (is_string($options))
        {
            $options = array('format' => $options);
        }

        $options = array_merge(
                array(
            'model' => $this->defaultModel(),
            'format' => 'pages',
            'separator' => __(' of ', true)
                ), $options);

        $paging = $this->params($options['model']);
        if ($paging['pageCount'] == 0)
        {
            $paging['pageCount'] = 1;
        }
        $start = 0;
        if ($paging['count'] >= 1)
        {
            $start = (($paging['page'] - 1) * $paging['options']['limit']) + 1;
        }
        $end = $start + $paging['options']['limit'] - 1;
        if ($paging['count'] < $end)
        {
            $end = $paging['count'];
        }

        switch ($options['format'])
        {
            case 'range':
                if (!is_array($options['separator']))
                {
                    $options['separator'] = array(' - ', $options['separator']);
                }
                $out = $start . $options['separator'][0] . $end . $options['separator'][1] . $paging['count'];
                break;
            case 'pages':
                $out = $paging['page'] . $options['separator'] . $paging['pageCount'];
                break;
            default:
                $replace = array(
                    '%page%' => $paging['page'],
                    '%pages%' => $paging['pageCount'],
                    '%current%' => $paging['current'],
                    '%count%' => $paging['count'],
                    '%start%' => $start,
                    '%end%' => $end
                );
                $out = str_replace(array_keys($replace), array_values($replace), $options['format']);
                break;
        }
        return $this->output($out);
    }

    /**
     * Returns a set of numbers for the paged result set
     * uses a modulus to decide how many numbers to show on each side of the current page (default: 8)
     *
     * Options
     *
     * - `before` Content to be inserted before the numbers
     * - `after` Content to be inserted after the numbers
     * - `model` Model to create numbers for, defaults to PaginatorHelper::defaultModel()
     * - `modulus` how many numbers to include on either side of the current page, defaults to 8.
     * - `separator` Separator content defaults to ' | '
     * - `tag` The tag to wrap links in, defaults to 'li'
     * - `first` Whether you want first links generated, set to an integer to define the number of 'first' 
     *    links to generate
     * - `last` Whether you want last links generated, set to an integer to define the number of 'last' 
     *    links to generate
     *
     * @param mixed $options Options for the numbers, (before, after, model, modulus, separator)
     * @return string numbers string.
     */
    function numbers($options = array(), $url = array())
    {
        if ($options === true)
        {
            $options = array(
                'before' => ' | ', 'after' => ' | ',
                'first' => 'first', 'last' => 'last',
            );
        }



        if ($url === true && isset($this->params['getUrl']))
        {
            $url = Array('?' => $this->params['getUrl']);
        }
        $options = array_merge(
                array(
            'tag' => 'li',
            'before' => null, 'after' => null,
            'model' => $this->defaultModel(),
            'modulus' => '9', 'separator' => '  ',
            'first' => null, 'last' => null,
                ), (array) $options);
        $params = array_merge(array('page' => 1), (array) $this->params($options['model']));
        unset($options['model']);
        if ($params['pageCount'] < 1)
        {
            return false;
        }

        extract($options);
        unset($options['tag'], $options['before'], $options['after'], $options['model'], $options['modulus'], $options['separator'], $options['first'], $options['last']);

        $out = '';

        if ($modulus && $params['pageCount'] > $modulus)
        {
            $half = intval($modulus / 2);
            $end = $params['page'] + $half;

            if ($end > $params['pageCount'])
            {
                $end = $params['pageCount'];
            }
            $start = $params['page'] - ($modulus - ($end - $params['page']));
            if ($start <= 1)
            {
                $start = 1;
                $end = $params['page'] + ($modulus - $params['page']) + 1;
            }

            if ($first && $start > 1)
            {
                $offset = ($start <= (int) $first) ? $start - 1 : $first;
                if ($offset < $start - 1)
                {
                    $out .= $this->first($offset, array('tag' => $tag, 'separator' => $separator));
                }
                else
                {
                    $out .= $this->first($offset, array('tag' => $tag, 'after' => $separator, 'separator' => $separator));
                }
            }

            $out .= $before;

            for ($i = $start; $i < $params['page']; $i++)
            {
                $out .= $this->Html->tag($tag, $this->link($i, array_merge(array('page' => $i, '?' => $this->params['getUrl']), $url), $options)) . $separator;
            }

            $out .= $this->Html->tag($tag, $this->link($params['page']), array('class' => 'page active'));
            if ($i != $params['pageCount'])
            {
                $out .= $separator;
            }

            $start = $params['page'] + 1;
            for ($i = $start; $i < $end; $i++)
            {
                $out .= $this->Html->tag($tag, $this->link($i, array_merge(array('page' => $i, '?' => $this->params['getUrl']), $url), $options)) . $separator;
            }

            if ($end != $params['page'])
            {   
                $out .= $this->Html->tag($tag, $this->link($i, array_merge(array('page' => $end, '?' => $this->params['getUrl']), $url), $options));
            }

            $out .= $after;

            if ($last && $end < $params['pageCount'])
            {
                $offset = ($params['pageCount'] < $end + (int) $last) ? $params['pageCount'] - $end : $last;
                if ($offset <= $last && $params['pageCount'] - $end > $offset)
                {
                    $out .= $this->last($offset, array('tag' => $tag, 'separator' => $separator));
                }
                else
                {
                    $out .= $this->last($offset, array('tag' => $tag, 'before' => $separator, 'separator' => $separator));
                }
            }
        }
        else
        {
            if ($url === true)
            {
                $url = array();
            }
            $out .= $before;
            for ($i = 1; $i <= $params['pageCount']; $i++)
            {
                if ($i == $params['page'])
                {
                    //$out .= $this->Html->tag($tag, $this->link($i, array(), array('class' => 'page active')));
                    //$out .= $this->Html->tag($tag, $this->link($i, array_merge(array('page' => $i))), array('class' => 'page active'));
                    $out .= $this->Html->tag($tag, $this->link($i, array_merge(array('page' => $i, '?' => isset($this->params['getUrl']) ? $this->params['getUrl'] : ''))), array('class' => 'page active'));
                }
                else
                {
                    $out .= $this->Html->tag($tag, $this->link($i, array_merge(array('page' => $i, '?' => isset($this->params['getUrl']) ? $this->params['getUrl'] : ''), $url), array('class' => 'page'), $options));
                }
                if ($i != $params['pageCount'])
                {
                    $out .= $separator;
                }
            }

            $out .= $after;
        }
        return $this->output($out);
    }

    /**
     * Returns a first or set of numbers for the first pages
     *
     * Options:
     *
     * - `tag` The tag wrapping tag you want to use, defaults to 'li'
     * - `before` Content to insert before the link/tag
     * - `model` The model to use defaults to PaginatorHelper::defaultModel()
     * - `separator` Content between the generated links, defaults to ' | '
     *
     * @param mixed $first if string use as label for the link, if numeric print page numbers
     * @param mixed $options
     * @return string numbers string.
     */
    function first($first = '<< first', $options = array())
    {
        $options = array_merge(
                array(
            'tag' => 'li',
            'after' => null,
            'model' => $this->defaultModel(),
            'separator' => ' | ',
                ), (array) $options);

        $params = array_merge(array('page' => 1), (array) $this->params($options['model']));
        unset($options['model']);

        if ($params['pageCount'] < 1)
        {
            return false;
        }
        extract($options);
        unset($options['tag'], $options['after'], $options['model'], $options['separator']);

        $out = '';

        if (is_int($first) && $params['page'] > $first)
        {
            if ($after === null)
            {
                $after = '...';
            }
            for ($i = 1; $i <= $first; $i++)
            {
                $out .= $this->Html->tag($tag, $this->link($i, array('page' => $i, '?' => $this->params['getUrl']), array('id' => 'first_page', 'class' => 'page-first'), $options));
                if ($i != $first)
                {
                    $out .= $separator;
                }
            }
            $out .= $after;
        }
        elseif ($params['page'] > 1)
        {
            $out = $this->Html->tag($tag, $this->link($first, array('page' => 1, '?' => isset($this->params['getUrl']) ? $this->params['getUrl'] : ''), array('id' => 'first_page', 'class' => 'page-first'), $options)) . $after;
        }
        else
        {
            $out = $this->Html->tag($tag, $this->link($first, array('page' => 1, '?' => isset($this->params['getUrl']) ? $this->params['getUrl'] : ''), array('id' => 'first_page', 'class' => 'page-first'), $options)) . $after;
            //$out = $this->Html->tag($tag, $this->link($first, array('page' => 1), array('id' => 'first_page', 'class' => 'page-first'), $options)) . $after;
        }
        return $out;
    }

    /**
     * Returns a last or set of numbers for the last pages
     *
     * Options:
     *
     * - `tag` The tag wrapping tag you want to use, defaults to 'li'
     * - `before` Content to insert before the link/tag
     * - `model` The model to use defaults to PaginatorHelper::defaultModel()
     * - `separator` Content between the generated links, defaults to ' | '
     *
     * @param mixed $last if string use as label for the link, if numeric print page numbers
     * @param mixed $options Array of options
     * @return string numbers string.
     */
    function last($last = 'last >>', $options = array())
    {
        $options = array_merge(
                array(
            'tag' => 'li',
            'before' => null,
            'model' => $this->defaultModel(),
            'separator' => ' | '
                ), (array) $options);

        $params = array_merge(array('page' => 1), (array) $this->params($options['model']));
        unset($options['model']);

        if ($params['pageCount'] < 1)
        {
            return false;
        }

        extract($options);
        unset($options['tag'], $options['before'], $options['model'], $options['separator']);

        $out = '';
        $lower = $params['pageCount'] - $last + 1;
        if (is_int($last) && $params['page'] < $lower)
        {
            if ($before === null)
            {
                $before = '...';
            }
            for ($i = $lower; $i <= $params['pageCount']; $i++)
            {
                $out .= $this->Html->tag($tag, $this->link($i, array('page' => $i, '?' => $this->params['getUrl']), array('id' => 'last_page', 'class' => 'page-last'), $options));
                if ($i != $params['pageCount'])
                {
                    $out .= $separator;
                }
            }
            $out = $before . $out;
        }
        elseif ($params['page'] < $params['pageCount'])
        {
            $out = $before . $this->Html->tag($tag, $this->link($last, array('page' => $params['pageCount'], '?' => isset($this->params['getUrl']) ? $this->params['getUrl'] : ''), array('id' => 'last_page', 'class' => 'page-last'), $options));
        }
        else
        {
            $out = $before . $this->Html->tag($tag, $this->link($last, array('page' => $params['pageCount']), array('id' => 'last_page', 'class' => 'page-last'), $options));
        }
        return $out;
    }

    function limit($options = Array())
    {
        $options = array_merge(Array('model' => $this->defaultModel()));

        $params = array_merge((array) $this->params($options['model']));
        return $params['options']['limit'];
    }

    function xurl($options = Array())
    {
        $options = array_merge(Array('add' => Array(), 'del' => Array()), $options);
        if (isset($this->params['plugin']))
        {
            $plugin = $this->params['plugin'];
        }
        else
        {
            $plugin = '';
        }
        if (isset($this->params['pass']))
        {
            $pass = $this->params['pass'];
        }
        else
        {
            $pass = '';
        }
        $controller = $this->params['controller'];
        $action = $this->params['action'];
        $named = $this->params['named'];
        foreach ($options['del'] as $key)
        {
            if (isset($named[$key]))
            {
                unset($named[$key]);
            }
        }
        $urls = array_merge(Array($plugin), Array($controller), Array($action), $named, $options['add'], $pass);
        return $this->webroot . join($urls, '/');
    }

    function xpageCount($options = Array())
    {
        $options = array_merge(Array('model' => $this->defaultModel()), $options);
        return $this->params['paging'][$options['model']]['pageCount'];
    }

    /* 	function limitUrl($limit,$options=Array())
      {
      $options=array_merge(Array('model'=>$this->defaultModel()));
      $params = array_merge((array)$this->params($options['model']));
      $this->Html->tag($tag, $this->link($last, array('page' => $params['pageCount']), $options));
      }
      function pagesUrl($pages,$options=Array())
      {

      } */
}

?>