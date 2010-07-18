<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula_View
 * @subpackage Template_Plugins
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Zikula_View outputfilter to add page variables and additional header global into page header
 *
 * By default this output filter places page variable output immediately prior to the closing
 * head tag (</head>). The output can, optionally, be placed anywhere in the template by adding
 * the HTML comment <!-- pagevars --> to the page template. Note that this must always be in
 * the header for the output to function correctly.
 *
 * @param string $source  Output source.
 * @param Zikula_View $view Reference to Zikula_View instance.
 *
 * @return string
 */
function smarty_outputfilter_pagevars($source, $view)
{
    $return = '';

    $themeinfo = ThemeUtil::getInfo(ThemeUtil::getIDFromName(UserUtil::getTheme()));
    $cssjscombine = ModUtil::getVar('Theme', 'cssjscombine', false);
    // get list of stylesheets and scripts from JCSSUtil
    $jcss = JCSSUtil::prepareJCSS($cssjscombine,$view->cache_dir);

    if (is_array($jcss['stylesheets']) && !empty($jcss['stylesheets'])) {
        foreach ($jcss['stylesheets'] as $stylesheet) {
            if (empty($stylesheet))
                continue;
                // check if the stylesheets is in the additional_header array
            if ($themeinfo['xhtml']) {
                $return .= '<link rel="stylesheet" href="' . DataUtil::formatForDisplay($stylesheet) . '" type="text/css" />' . "\n";
            } else {
                $return .= '<link rel="stylesheet" href="' . DataUtil::formatForDisplay($stylesheet) . '" type="text/css">' . "\n";
            }
        }
    }

    // get inline js config and print it just before any script tag
    $jsConfig = JCSSUtil::getJSConfig();
    if(!empty($jsConfig)) {
        $return .= $jsConfig;
    }

    if (is_array($jcss['javascripts']) && !empty($jcss['javascripts'])) {
        foreach ($jcss['javascripts'] as $j => $javascript) {
            if (empty($javascript)) {
                unset($javascripts[$j]);
                continue;
            }
            // check if the javascript is in the additional_header array
            $return .= '<script type="text/javascript" src="' . DataUtil::formatForDisplay($javascript) . '"></script>' . "\n";
        }
    }

    $rawtext = PageUtil::getVar('rawtext');
    if (is_array($rawtext) && !empty($rawtext)) {
        $return .= implode("\n", $rawtext) . "\n";
    }

    // implode the remaining additional header global to a string
    if (isset($additional_header) && count($additional_header) > 0) {
        $return .= @implode("\n", $additional_header) . "\n";
    }

    // if we've got some page vars to add the header wrap the output in
    // suitable identifiying comments when in development mode
    $return = trim($return);
    if (!empty($return) && System::getVar('development') != 0) {
        $return = "<!-- zikula pagevars -->\n" . $return . "\n<!-- /zikula pagevars -->";
    }

    // get any body page vars
    $bodyvars = PageUtil::getVar('body');
    if (!empty($bodyvars)) {
        $bodyattribs = '<body ' . @implode(' ', $bodyvars) . '>';
        $source = str_replace('<body>', $bodyattribs, $source);
    }

    // get any footer page vars
    $footervars = PageUtil::getVar('footer');
    if (!empty($footervars)) {
        $footersource = @implode("\n", $footervars) . "\n</body>";
        $source = str_replace('</body>', $footersource, $source);
    }

    // replace the string in the template source
    if (stristr($source, '<!-- pagevars -->')) {
        $source = str_replace('<!-- pagevars -->', $return, $source);
    } else {
        $source = str_replace('</head>', $return . "\n</head>", $source);
    }

    // return the modified source
    return $source;
}
