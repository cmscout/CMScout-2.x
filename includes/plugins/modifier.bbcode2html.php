<?php
/*
 * Smarty plugin
 * ------------------------------------------------------------
 * Type:       modifier
 * Name:       bbcode2html
 * Purpose:    Converts BBCode style tags to HTML
 * ------------------------------------------------------------
 */
require_once 'stringparser_bbcode.class.php';
    
// Unify line breaks of different operating systems
function convertlinebreaks ($text) 
{
    return preg_replace ("/\015\012|\015|\012/", "\n", $text);
}

// Remove everything but the newline charachter
function bbcode_stripcontents ($text) 
{
    return preg_replace ("/[^\n]/", '', $text);
}

function is_valid_url($url)
{
    return true;
}

//Handle URL tags
function do_bbcode_url ($action, $attributes, $content, $params, &$node_object) 
{
    // 1) the code is being valided
    if ($action == 'validate') 
    {
            // the code is specified as follows: [url]http://.../[/url]
            if (!isset ($attributes['default'])) 
            {
                // is this a valid URL?
                return is_valid_url ($content);
            }
            // the code is specified as follows: [url=http://.../]Text[/url]
            // is this a valid URL?
            return is_valid_url ($attributes['default']);
    }
    // 2) the code is being output
    else 
    {
        // the code was specified as follows: [url]http://.../[/url]
        if (!isset ($attributes['default'])) 
        {
            return '<a href="'.htmlspecialchars ($content).'">'.htmlspecialchars ($content).'</a>';
        }
        // the code was specified as follows: [url=http://.../]Text[/url]
        return '<a href="'.htmlspecialchars ($attributes['default']).'">'.$content.'</a>';
    }
}

//Handle quote tags
function do_bbcode_quote ($action, $attributes, $content, $params, &$node_object) {
    // 1) the code is being valided
    if ($action == 'validate') 
    {
        return true;
    }
    // 2) the code is being output
    else 
    {
        // the code was specified as follows: [quote]Text[/quote]
        if (!isset ($attributes['default'])) 
        {
            return '<div><span style="font-weight:bold">Quote:</span><div class="bb-quote">' . $content. '</div></div>';
        }
        // the code was specified as follows: [quote=name]Text[/quote]
        return '<div><span style="font-weight:bold">Quoting ' . $attributes['default'] . ':</span><div class="bb-quote">' . $content . '</div></div>';
    }
}
//Handle color tags
function do_bbcode_color ($action, $attributes, $content, $params, &$node_object) {
    // 1) the code is being valided
    if ($action == 'validate') 
    {
        return true;
    }
    // 2) the code is being output
    else 
    {
        // the code was specified as follows: [color]Text[/color]
        if (!isset ($attributes['default'])) 
        {
            return '<span style="color:black">' . $content. '</span>';
        }
        // the code was specified as follows: [color=colorname or code]Text[/color]
        return '<span style="color:' . $attributes['default'] . '">' . $content . '</span>';
    }
}

//Handle size tags
function do_bbcode_size ($action, $attributes, $content, $params, &$node_object) {
    // 1) the code is being valided
    if ($action == 'validate') 
    {
        return true;
    }
    // 2) the code is being output
    {
        // the code was specified as follows: [size]Text[/size]
        if (!isset ($attributes['default'])) 
        {
            return '<span style="font-size:12px">' . $content. '</span>';
        }
        // the code was specified as follows: [size=size of text]Text[/size]
        return '<span style="font-size:' . $attributes['default'] . 'px">' . $content . '</span>';
    }
}

// Function to include images
function do_bbcode_img ($action, $attributes, $content, $params, $node_object) 
{
    if ($action == 'validate')
    {
        return true;
    }
    return '<img src="'.htmlspecialchars($content).'" alt="">';
}

// Function for code
function do_bbcode_code ($action, $attributes, $content, $params, $node_object) 
{
    if ($action == 'validate')
    {
        return true;
    }
    return '<div><span style="font-weight:bold">Code:</span><pre class="bb-code" style="margin:0px; padding:6px; border:1px inset; width:640px; height:498px; overflow:auto"><div dir="ltr" style="text-align:left;">'.$content.'</pre></div>';
}
 
//Handle quote tags
function do_bbcode_list ($action, $attributes, $content, $params, &$node_object) {
    // 1) the code is being valided
    if ($action == 'validate') 
    {
        return true;
    }
    // 2) the code is being output
    else 
    {
        if ($attributes['default'] == "") 
        {
            return '<ul class="bb-list-unordered">' . $content. '</ul>';
        }
        elseif($attributes['default'] == 'u')
        {
            return '<ul class="bb-list-unordered">' . $content. '</ul>';
        }
        elseif($attributes['default'] == 'o')
        {
            return '<ol class="bb-list-ordered">' . $content. '</ol>';
        }
        elseif($attributes['default'] == '1')
        {
            return '<ol class="bb-list-ordered-d">' . $content. '</ol>';
        }
        elseif($attributes['default'] == 'i')
        {
            return '<ol class="bb-list-ordered-lr">' . $content. '</ol>';
        }
        elseif($attributes['default'] == 'I')
        {
            return '<ol class="bb-list-ordered-ur">' . $content. '</ol>';
        }
        elseif($attributes['default'] == 'a')
        {
            return '<ol class="bb-list-ordered-la">' . $content. '</ol>';
        }
        elseif($attributes['default'] == 'A')
        {
            return '<ol class="bb-list-ordered-ua">' . $content. '</ol>';
        }    
    }
}

function smarty_modifier_bbcode2html($message) 
{
    $bbcode = new StringParser_BBCode ();
    $bbcode->addFilter (STRINGPARSER_FILTER_PRE, 'convertlinebreaks');
    
    //$bbcode->addParser (array ('block', 'inline', 'link', 'listitem'), 'htmlspecialchars');
    $bbcode->addParser (array ('block', 'inline', 'link', 'listitem'), 'nl2br');
    $bbcode->addParser ('list', 'bbcode_stripcontents');
    
    $bbcode->addCode ('b', 'simple_replace', null, array ('start_tag' => '<span style="font-weight:bold">', 'end_tag' => '</span>'),
                      'inline', array ('listitem', 'block', 'inline', 'link', 'quote', 'color', 'size'), array ());
    $bbcode->addCode ('i', 'simple_replace', null, array ('start_tag' => '<span style="font-style:italic">', 'end_tag' => '</span>'),
                      'inline', array ('listitem', 'block', 'inline', 'link', 'quote', 'color', 'size'), array ());
    $bbcode->addCode ('u', 'simple_replace', null, array ('start_tag' => '<span style="text-decoration:underline">', 'end_tag' => '</span>'),
                      'inline', array ('listitem', 'block', 'inline', 'link', 'quote', 'color', 'size'), array ());
    $bbcode->addCode ('url', 'usecontent?', 'do_bbcode_url', array ('usecontent_param' => 'default'),
                      'link', array ('listitem', 'block', 'inline', 'quote', 'color', 'size'), array ('link'));
    $bbcode->addCode ('quote', 'callback_replace', 'do_bbcode_quote', array ('usecontent_param' => 'default'),
                      'quote', array ('listitem', 'block', 'inline', 'quote'), array ('link'));
    $bbcode->addCode ('code', 'usecontent', 'do_bbcode_code', array (),
                      'code', array ('listitem', 'block', 'inline', 'quote', 'code'), array ('link'));
    $bbcode->addCode ('color', 'callback_replace', 'do_bbcode_color', array ('usecontent_param' => 'default'),
                      'color', array ('listitem', 'block', 'inline', 'quote', 'size'), array ('link'));
    $bbcode->addCode ('size', 'callback_replace', 'do_bbcode_size', array ('usecontent_param' => 'default'),
                      'size', array ('listitem', 'block', 'inline', 'quote', 'color'), array ('link'));
    $bbcode->addCode ('link', 'callback_replace_single', 'do_bbcode_url', array (),
                      'link', array ('listitem', 'block', 'inline', 'quote', 'color', 'size'), array ('link'));
    $bbcode->addCode ('img', 'usecontent', 'do_bbcode_img', array (),
                      'image', array ('listitem', 'block', 'inline', 'link', 'quote', 'color', 'size'), array ());
    $bbcode->addCode ('list', 'callback_replace', 'do_bbcode_list', array ('usecontent_param' => 'default'),
                      'list', array ('block', 'listitem', 'quote', 'color', 'size'), array ());
    $bbcode->addCode ('*', 'simple_replace', null, array ('start_tag' => '<li>', 'end_tag' => '</li>'),
                      'listitem', array ('list'), array ());
    $bbcode->setCodeFlag ('*', 'closetag', BBCODE_CLOSETAG_OPTIONAL);
    $bbcode->setCodeFlag ('*', 'paragraphs', true);
    $bbcode->setCodeFlag ('list', 'paragraph_type', BBCODE_PARAGRAPH_BLOCK_ELEMENT);
    $bbcode->setCodeFlag ('list', 'opentag.before.newline', BBCODE_NEWLINE_DROP);
    $bbcode->setCodeFlag ('list', 'closetag.before.newline', BBCODE_NEWLINE_DROP);
    $bbcode->setRootParagraphHandling (false);

    return $bbcode->parse($message);
}
?>