<?php

namespace Atlantis\Helpers;


class RegexMatcher
{

    public static function matchPatternFunc($subject)
    {

        if (!empty($subject)) {

            preg_match_all('/\[patternfunc\](.*?)\[\/patternfunc\]/', $subject, $aMatchesFunc); // match function

            return $aMatchesFunc;

        } else {

            //throw exception
        }

    }

    public static function removePatternFunc($pattern, $replacement, $subject)
    {

        if (!empty($subject) && !empty($replacement)) {

            return preg_replace('/\[patternfunc\b[^>]*\]' . $pattern . '\[\/patternfunc\]/i', $replacement, $subject);

        } else {

            return preg_replace('/\[patternfunc\b[^>]*\](.*?)\[\/patternfunc\]/i', '', $subject);
        }

    }

    public static function matchPatternName($subject)
    {

        if (!empty($subject)) {

            preg_match_all('/\[patternname\](.*?)\[\/patternname\]/i', $subject, $aMatchesName); // match function

            return $aMatchesName;

        } else {

            //throw exception
        }

    }

    public static function removePatternName($pattern, $replacement, $subject)
    {

        if (!empty($subject) && !empty($replacement)) {

            return preg_replace('/\[patternname\b[^>]*\]' . $pattern . '\[\/patternname\]/i', $replacement, $subject);

        } else {

            return preg_replace('/\[patternname\](.*?)\[\/patternname\]/i', '', $subject);

        }

    }

    public static function matchPatternId($subject)
    {

        if (!empty($subject)) {

            preg_match_all('/\[patternid\](.*?)\[\/patternid\]/i', $subject, $aMatchesId); // match function

            return $aMatchesId;

        } else {

            //throw exception
        }

    }

    public static function removePatternId($pattern, $replacement, $subject)
    {

        if (!empty($subject) && !empty($replacement)) {

            return preg_replace('/\[patternid\b[^>]*\]' . $pattern . '\[\/patternid\]/i', $replacement, $subject);

        } else {

            return preg_replace('/\[patternid\](.*?)\[\/patternid\]/i', '', $subject);
        }

    }


    public static function noMobile($subject, $status = false)
    {

        if (!empty($subject) && !$status) {
            return preg_replace('/\[nomobile\](.*?)\[\/nomobile\]/i', '', $subject);
        } elseif (!empty($subject) && $status) {
            return preg_replace('/\[nomobile\](.*?)\[\/nomobile\]/i', '${1}', $subject);

        }

    }


}